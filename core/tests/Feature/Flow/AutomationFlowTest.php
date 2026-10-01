<?php

namespace Tests\Feature\Flow;

use App\Constants\Status;
use App\Models\ContactFlowState;
use Illuminate\Support\Facades\Http;

/**
 * End to end proof of the automation flow engine.
 *
 * Every test drives the real /webhook endpoint with a real Meta payload and asserts on
 * what the application tried to send back to the Cloud API, so nothing here can pass
 * because of a stub: the controller, AutomationLib and WhatsAppLib all really run.
 */
class AutomationFlowTest extends FlowTestCase
{
    // ------------------------------------------------------------------ entry point

    public function test_the_flow_starts_at_the_node_wired_to_the_trigger(): void
    {
        $this->seedExistingContact();

        // Saved with "second" first, which is what happens whenever the user drops the
        // later step onto the canvas before the earlier one.
        $this->makeFlow(
            [
                'second' => ['type' => Status::NODE_TYPE_TEXT, 'text' => 'Second'],
                'first'  => ['type' => Status::NODE_TYPE_TEXT, 'text' => 'First'],
            ],
            [
                ['from' => 'trigger', 'to' => 'first'],
                ['from' => 'first', 'to' => 'second'],
            ],
            ['keyword' => 'start']
        );

        $this->receiveText('start')->assertOk();

        $this->assertSame(['text:First', 'text:Second'], $this->sentMessages());
    }

    public function test_only_the_branch_wired_to_the_trigger_runs(): void
    {
        $this->seedExistingContact();

        // An orphan node left on the canvas must never be sent.
        $this->makeFlow(
            [
                'orphan' => ['type' => Status::NODE_TYPE_TEXT, 'text' => 'Orphan'],
                'wired'  => ['type' => Status::NODE_TYPE_TEXT, 'text' => 'Wired'],
            ],
            [['from' => 'trigger', 'to' => 'wired']],
            ['keyword' => 'start']
        );

        $this->receiveText('start')->assertOk();

        $this->assertSame(['text:Wired'], $this->sentMessages());
    }

    public function test_a_cycle_in_the_flow_does_not_send_endlessly(): void
    {
        $this->seedExistingContact();

        $this->makeFlow(
            [
                'a' => ['type' => Status::NODE_TYPE_TEXT, 'text' => 'A'],
                'b' => ['type' => Status::NODE_TYPE_TEXT, 'text' => 'B'],
            ],
            [
                ['from' => 'trigger', 'to' => 'a'],
                ['from' => 'a', 'to' => 'b'],
                ['from' => 'b', 'to' => 'a'],   // the loop
            ],
            ['keyword' => 'start']
        );

        $this->receiveText('start')->assertOk();

        $this->assertSame(['text:A', 'text:B'], $this->sentMessages());
    }

    // ------------------------------------------------------------------ button nodes

    public function test_a_button_node_is_sent_as_an_interactive_button_message(): void
    {
        $this->seedExistingContact();

        $this->makeFlow(
            ['ask' => [
                'type'    => Status::NODE_TYPE_BUTTON,
                'body'    => 'Do you want a demo?',
                'footer'  => 'Sales team',
                'buttons' => ['Yes please', 'No thanks'],
            ]],
            [['from' => 'trigger', 'to' => 'ask']],
            ['keyword' => 'start']
        );

        $this->receiveText('start')->assertOk();

        $payloads = $this->sentPayloads();
        $this->assertCount(1, $payloads);

        $interactive = $payloads[0]['interactive'];
        $this->assertSame('button', $interactive['type']);
        $this->assertSame('Do you want a demo?', $interactive['body']['text']);
        $this->assertSame('Sales team', $interactive['footer']['text']);
        $this->assertSame(
            ['Yes please', 'No thanks'],
            array_column(array_column($interactive['action']['buttons'], 'reply'), 'title')
        );
    }

    public function test_a_button_node_pauses_the_flow_and_records_a_waiting_state(): void
    {
        $this->seedExistingContact();

        $this->makeFlow(
            [
                'ask'   => ['type' => Status::NODE_TYPE_BUTTON, 'body' => 'Pick', 'buttons' => ['Yes', 'No']],
                'after' => ['type' => Status::NODE_TYPE_TEXT, 'text' => 'Should not be sent yet'],
            ],
            [
                ['from' => 'trigger', 'to' => 'ask'],
                ['from' => 'ask', 'to' => 'after', 'button' => 0],
            ],
            ['keyword' => 'start']
        );

        $this->receiveText('start')->assertOk();

        $this->assertSame(['button:Pick'], $this->sentMessages());

        $state = $this->flowState();
        $this->assertNotNull($state, 'The flow should have recorded where it paused.');
        $this->assertSame(Status::FLOW_STATE_WAITING, (int) $state->status);
    }

    public function test_clicking_a_button_follows_that_buttons_branch(): void
    {
        $this->seedExistingContact();

        $this->makeFlow(
            [
                'ask' => ['type' => Status::NODE_TYPE_BUTTON, 'body' => 'Demo?', 'buttons' => ['Yes', 'No']],
                'yes' => ['type' => Status::NODE_TYPE_TEXT, 'text' => 'Booking you in'],
                'no'  => ['type' => Status::NODE_TYPE_TEXT, 'text' => 'No problem'],
            ],
            [
                ['from' => 'trigger', 'to' => 'ask'],
                ['from' => 'ask', 'to' => 'yes', 'button' => 0],
                ['from' => 'ask', 'to' => 'no', 'button' => 1],
            ],
            ['keyword' => 'start']
        );

        $this->receiveText('start')->assertOk();

        // Meta echoes back exactly the id and title we sent, so replay them.
        $buttons = $this->sentPayloads()[0]['interactive']['action']['buttons'];
        $this->receiveButtonReply($buttons[1]['reply']['id'], $buttons[1]['reply']['title'])->assertOk();

        $this->assertSame(['button:Demo?', 'text:No problem'], $this->sentMessages());
    }

    public function test_a_button_branch_is_followed_even_when_two_buttons_share_a_label(): void
    {
        $this->seedExistingContact();

        // Duplicated labels are legal on WhatsApp and the builder does not stop the user
        // typing them, so the branch has to be resolved by position, not by text.
        $this->makeFlow(
            [
                'ask'   => ['type' => Status::NODE_TYPE_BUTTON, 'body' => 'Pick', 'buttons' => ['More info', 'More info']],
                'left'  => ['type' => Status::NODE_TYPE_TEXT, 'text' => 'Left branch'],
                'right' => ['type' => Status::NODE_TYPE_TEXT, 'text' => 'Right branch'],
            ],
            [
                ['from' => 'trigger', 'to' => 'ask'],
                ['from' => 'ask', 'to' => 'left', 'button' => 0],
                ['from' => 'ask', 'to' => 'right', 'button' => 1],
            ],
            ['keyword' => 'start']
        );

        $this->receiveText('start')->assertOk();

        $buttons = $this->sentPayloads()[0]['interactive']['action']['buttons'];
        $this->assertNotSame(
            $buttons[0]['reply']['id'],
            $buttons[1]['reply']['id'],
            'Two buttons must never be sent with the same reply id.'
        );

        $this->receiveButtonReply($buttons[1]['reply']['id'], $buttons[1]['reply']['title'])->assertOk();

        $this->assertSame(['button:Pick', 'text:Right branch'], $this->sentMessages());
    }

    public function test_a_legacy_button_reply_carrying_the_label_as_its_id_still_works(): void
    {
        $this->seedExistingContact();

        // Buttons sent by an older release used the label as the reply id. Those messages
        // are still sitting in customers' chats, so their replies must keep routing.
        $this->makeFlow(
            [
                'ask' => ['type' => Status::NODE_TYPE_BUTTON, 'body' => 'Demo?', 'buttons' => ['Yes', 'No']],
                'yes' => ['type' => Status::NODE_TYPE_TEXT, 'text' => 'Booking you in'],
            ],
            [
                ['from' => 'trigger', 'to' => 'ask'],
                ['from' => 'ask', 'to' => 'yes', 'button' => 0],
            ],
            ['keyword' => 'start']
        );

        $this->receiveText('start')->assertOk();
        $this->receiveButtonReply('Yes', 'Yes')->assertOk();

        $this->assertSame(['button:Demo?', 'text:Booking you in'], $this->sentMessages());
    }

    public function test_a_template_quick_reply_button_advances_the_flow(): void
    {
        $this->seedExistingContact();

        // Meta delivers a quick reply on a *template* as type "button", not "interactive".
        $this->makeFlow(
            [
                'ask' => ['type' => Status::NODE_TYPE_BUTTON, 'body' => 'Demo?', 'buttons' => ['Yes', 'No']],
                'yes' => ['type' => Status::NODE_TYPE_TEXT, 'text' => 'Booking you in'],
            ],
            [
                ['from' => 'trigger', 'to' => 'ask'],
                ['from' => 'ask', 'to' => 'yes', 'button' => 0],
            ],
            ['keyword' => 'start']
        );

        $this->receiveText('start')->assertOk();
        $this->receiveTemplateButton('Yes')->assertOk();

        $this->assertSame(['button:Demo?', 'text:Booking you in'], $this->sentMessages());
    }

    public function test_an_unconnected_button_ends_the_flow_without_error(): void
    {
        $this->seedExistingContact();

        $this->makeFlow(
            [
                'ask' => ['type' => Status::NODE_TYPE_BUTTON, 'body' => 'Demo?', 'buttons' => ['Yes', 'No']],
                'yes' => ['type' => Status::NODE_TYPE_TEXT, 'text' => 'Booking you in'],
            ],
            [
                ['from' => 'trigger', 'to' => 'ask'],
                ['from' => 'ask', 'to' => 'yes', 'button' => 0],
            ],
            ['keyword' => 'start']
        );

        $this->receiveText('start')->assertOk();

        $buttons = $this->sentPayloads()[0]['interactive']['action']['buttons'];
        $this->receiveButtonReply($buttons[1]['reply']['id'], $buttons[1]['reply']['title'])->assertOk();

        $this->assertSame(['button:Demo?'], $this->sentMessages());
    }

    public function test_the_flow_pauses_at_a_button_even_when_a_sibling_branch_exists(): void
    {
        $this->seedExistingContact();

        // The user was asked a question, so the bot must stop talking and wait for the
        // answer instead of running the sibling edge on top of it.
        $this->makeFlow(
            [
                'intro'   => ['type' => Status::NODE_TYPE_TEXT, 'text' => 'Intro'],
                'ask'     => ['type' => Status::NODE_TYPE_BUTTON, 'body' => 'Pick', 'buttons' => ['Yes', 'No']],
                'sibling' => ['type' => Status::NODE_TYPE_TEXT, 'text' => 'Sibling'],
            ],
            [
                ['from' => 'trigger', 'to' => 'intro'],
                ['from' => 'intro', 'to' => 'ask'],
                ['from' => 'intro', 'to' => 'sibling'],
            ],
            ['keyword' => 'start']
        );

        $this->receiveText('start')->assertOk();

        $this->assertSame(['text:Intro', 'button:Pick'], $this->sentMessages());

        $state = $this->flowState();
        $this->assertNotNull($state);
        $this->assertSame(
            Status::FLOW_STATE_WAITING,
            (int) $state->status,
            'The waiting state must not be overwritten by a later node.'
        );
    }

    // ------------------------------------------------------------------ list nodes

    public function test_a_list_node_pauses_and_a_selection_resumes_the_flow(): void
    {
        $this->seedExistingContact();
        $list = $this->makeInteractiveList();

        $this->makeFlow(
            [
                'menu'  => ['type' => Status::NODE_TYPE_LIST, 'interactive_list_id' => $list->id],
                'after' => ['type' => Status::NODE_TYPE_TEXT, 'text' => 'Thanks for choosing'],
            ],
            [
                ['from' => 'trigger', 'to' => 'menu'],
                ['from' => 'menu', 'to' => 'after'],
            ],
            ['keyword' => 'start']
        );

        $this->receiveText('start')->assertOk();

        $this->assertSame(['list:Please choose'], $this->sentMessages());
        $this->assertSame(Status::FLOW_STATE_WAITING, (int) $this->flowState()->status);

        $this->receiveListReply('row_1', 'Row two')->assertOk();

        $this->assertSame(['list:Please choose', 'text:Thanks for choosing'], $this->sentMessages());
    }

    // ------------------------------------------------------------------ template nodes

    public function test_a_template_node_sends_the_selected_template_with_its_variables(): void
    {
        $this->seedExistingContact();
        $template = $this->makeTemplate('order_update');

        $this->makeFlow(
            ['t' => [
                'type'        => Status::NODE_TYPE_TEMPLATE,
                'template_id' => $template->id,
                // saved by the builder as an object keyed by the placeholder
                'body_params' => json_encode(['{{1}}' => 'John', '{{2}}' => 'ORD-9']),
            ]],
            [['from' => 'trigger', 'to' => 't']],
            ['keyword' => 'start']
        );

        $this->receiveText('start')->assertOk();

        $payloads = $this->sentPayloads();
        $this->assertCount(1, $payloads, 'The template node should have sent exactly one message.');
        $this->assertSame('template', $payloads[0]['type']);
        $this->assertSame('order_update', $payloads[0]['template']['name']);

        $body = null;
        foreach ($payloads[0]['template']['components'] as $component) {
            if ($component['type'] === 'body') {
                $body = $component;
            }
        }

        $this->assertNotNull($body);
        $this->assertSame(['John', 'ORD-9'], array_column($body['parameters'], 'text'));
    }

    public function test_a_template_node_is_skipped_when_its_template_is_gone(): void
    {
        $this->seedExistingContact();

        $this->makeFlow(
            [
                't'     => ['type' => Status::NODE_TYPE_TEMPLATE, 'template_id' => 999999],
                'after' => ['type' => Status::NODE_TYPE_TEXT, 'text' => 'Still running'],
            ],
            [
                ['from' => 'trigger', 'to' => 't'],
                ['from' => 't', 'to' => 'after'],
            ],
            ['keyword' => 'start']
        );

        $this->receiveText('start')->assertOk();

        $this->assertSame(['text:Still running'], $this->sentMessages());
    }

    // ------------------------------------------------------------------ resilience

    public function test_a_rejected_send_does_not_stop_the_rest_of_the_flow(): void
    {
        $this->seedExistingContact();

        Http::fake([
            '*/messages*' => Http::sequence()
                ->push(['messaging_product' => 'whatsapp', 'messages' => [['id' => 'wamid.1']]], 200)
                ->push(['error' => ['message' => 'Rate limit hit']], 400)
                ->push(['messaging_product' => 'whatsapp', 'messages' => [['id' => 'wamid.3']]], 200),
            '*' => Http::response([], 200),
        ]);

        $this->makeFlow(
            [
                'a' => ['type' => Status::NODE_TYPE_TEXT, 'text' => 'A'],
                'b' => ['type' => Status::NODE_TYPE_TEXT, 'text' => 'B'],
                'c' => ['type' => Status::NODE_TYPE_TEXT, 'text' => 'C'],
            ],
            [
                ['from' => 'trigger', 'to' => 'a'],
                ['from' => 'a', 'to' => 'b'],
                ['from' => 'b', 'to' => 'c'],
            ],
            ['keyword' => 'start']
        );

        // Meta retries a webhook that does not answer 200, which would replay the whole flow.
        $this->receiveText('start')->assertOk();

        $this->assertSame(
            ['text:A', 'text:B', 'text:C'],
            $this->sentMessages(),
            'One rejected message must not abort the remaining steps.'
        );
    }

    public function test_a_media_node_with_no_media_is_skipped_rather_than_sending_an_empty_text(): void
    {
        $this->seedExistingContact();

        $this->makeFlow(
            [
                'img'   => ['type' => Status::NODE_TYPE_IMAGE],
                'after' => ['type' => Status::NODE_TYPE_TEXT, 'text' => 'After the image'],
            ],
            [
                ['from' => 'trigger', 'to' => 'img'],
                ['from' => 'img', 'to' => 'after'],
            ],
            ['keyword' => 'start']
        );

        $this->receiveText('start')->assertOk();

        $this->assertSame(['text:After the image'], $this->sentMessages());
    }

    // ------------------------------------------------------------------ trigger routing

    public function test_a_keyword_flow_triggers_for_a_brand_new_contact(): void
    {
        // No seedExistingContact(): this is the contact's very first message.
        $this->makeFlow(
            ['a' => ['type' => Status::NODE_TYPE_TEXT, 'text' => 'Keyword reply']],
            [['from' => 'trigger', 'to' => 'a']],
            ['keyword' => 'start']
        );

        $this->receiveText('start')->assertOk();

        $this->assertSame(['text:Keyword reply'], $this->sentMessages());
    }

    public function test_keyword_matching_ignores_case_and_surrounding_space(): void
    {
        $this->seedExistingContact();

        $this->makeFlow(
            ['a' => ['type' => Status::NODE_TYPE_TEXT, 'text' => 'Matched']],
            [['from' => 'trigger', 'to' => 'a']],
            ['keyword' => 'Start']
        );

        $this->receiveText('  START  ')->assertOk();

        $this->assertSame(['text:Matched'], $this->sentMessages());
    }

    public function test_a_new_message_flow_runs_even_when_a_welcome_message_is_enabled(): void
    {
        $this->enableWelcomeMessage('Welcome to our shop');

        $this->makeFlow(
            ['a' => ['type' => Status::NODE_TYPE_TEXT, 'text' => 'Flow greeting']],
            [['from' => 'trigger', 'to' => 'a']],
            ['trigger_type' => Status::FLOW_TRIGGER_NEW_MESSAGE, 'keyword' => null]
        );

        $this->receiveText('hello')->assertOk();

        $this->assertSame(['text:Flow greeting'], $this->sentMessages());
    }

    public function test_a_disabled_flow_never_runs(): void
    {
        $this->seedExistingContact();

        $this->makeFlow(
            ['a' => ['type' => Status::NODE_TYPE_TEXT, 'text' => 'Should not send']],
            [['from' => 'trigger', 'to' => 'a']],
            ['keyword' => 'start', 'status' => Status::DISABLE]
        );

        $this->receiveText('start')->assertOk();

        $this->assertSame([], $this->sentMessages());
    }

    public function test_starting_a_different_flow_clears_the_previous_state(): void
    {
        $conversation = $this->seedExistingContact();

        $this->makeFlow(
            ['ask' => ['type' => Status::NODE_TYPE_BUTTON, 'body' => 'Pick', 'buttons' => ['Yes', 'No']]],
            [['from' => 'trigger', 'to' => 'ask']],
            ['keyword' => 'one']
        );

        $second = $this->makeFlow(
            ['b' => ['type' => Status::NODE_TYPE_TEXT, 'text' => 'Second flow']],
            [['from' => 'trigger', 'to' => 'b']],
            ['keyword' => 'two']
        );

        $this->receiveText('one')->assertOk();
        $this->receiveText('two')->assertOk();

        $this->assertSame(['button:Pick', 'text:Second flow'], $this->sentMessages());

        $states = ContactFlowState::where('conversation_id', $conversation->id)->get();
        $this->assertCount(1, $states, 'The abandoned flow state should have been cleared.');
        $this->assertSame($second->id, (int) $states->first()->flow_id);
    }

    public function test_the_flow_state_records_the_owning_user(): void
    {
        $this->seedExistingContact();

        $this->makeFlow(
            ['a' => ['type' => Status::NODE_TYPE_TEXT, 'text' => 'Hello']],
            [['from' => 'trigger', 'to' => 'a']],
            ['keyword' => 'start']
        );

        $this->receiveText('start')->assertOk();

        $state = $this->flowState();
        $this->assertNotNull($state);
        $this->assertSame($this->user->id, (int) $state->user_id);
    }

    public function test_a_completed_flow_can_be_restarted_by_its_keyword(): void
    {
        $this->seedExistingContact();

        $this->makeFlow(
            ['a' => ['type' => Status::NODE_TYPE_TEXT, 'text' => 'Hello']],
            [['from' => 'trigger', 'to' => 'a']],
            ['keyword' => 'start']
        );

        $this->receiveText('start')->assertOk();
        $this->receiveText('start')->assertOk();

        $this->assertSame(['text:Hello', 'text:Hello'], $this->sentMessages());
    }

    // ------------------------------------------------------------------ failure paths

    public function test_a_disabled_flow_stops_answering_a_contact_who_was_mid_flow(): void
    {
        $this->seedExistingContact();

        $flow = $this->makeFlow(
            [
                'ask' => ['type' => Status::NODE_TYPE_BUTTON, 'body' => 'Pick', 'buttons' => ['Yes', 'No']],
                'yes' => ['type' => Status::NODE_TYPE_TEXT, 'text' => 'You said yes'],
            ],
            [
                ['from' => 'trigger', 'to' => 'ask'],
                ['from' => 'ask', 'to' => 'yes', 'button' => 0],
            ],
            ['keyword' => 'start']
        );

        $this->receiveText('start')->assertOk();
        $this->assertSame(Status::FLOW_STATE_WAITING, (int) $this->flowState()->status);

        // The user switches the flow off while the contact is still looking at the buttons.
        $flow->status = Status::DISABLE;
        $flow->save();

        $this->receiveButtonReply('btn_0', 'Yes')->assertOk();

        $this->assertSame(
            ['button:Pick'],
            $this->sentMessages(),
            'A flow the user disabled must not keep answering contacts who were part way through it.'
        );
    }

    public function test_a_question_that_could_not_be_sent_does_not_strand_the_contact(): void
    {
        $this->seedExistingContact();

        // A button node saved with no buttons: the Cloud API would reject it, so
        // WhatsAppLib refuses to send it at all.
        $this->makeFlow(
            [
                'intro' => ['type' => Status::NODE_TYPE_TEXT, 'text' => 'Intro'],
                'ask'   => ['type' => Status::NODE_TYPE_BUTTON, 'body' => 'Pick', 'buttons' => []],
            ],
            [
                ['from' => 'trigger', 'to' => 'intro'],
                ['from' => 'intro', 'to' => 'ask'],
            ],
            ['keyword' => 'start']
        );

        $this->receiveText('start')->assertOk();

        $this->assertSame(['text:Intro'], $this->sentMessages());

        $state = $this->flowState();
        $this->assertTrue(
            $state === null || (int) $state->status !== Status::FLOW_STATE_WAITING,
            'A question the contact never received must not leave the flow waiting for an answer.'
        );

        // And because nothing is waiting, the keyword still starts the flow again
        // instead of the contact being stuck forever.
        $this->receiveText('start')->assertOk();

        $this->assertSame(['text:Intro', 'text:Intro'], $this->sentMessages());
    }

    public function test_a_broken_branch_releases_the_contact_instead_of_pinning_them_to_an_answered_question(): void
    {
        $this->seedExistingContact();

        // The branch behind "Yes" cannot be delivered - this is what a deleted template or
        // a button node saved with no buttons looks like to the engine.
        $this->makeFlow(
            [
                'ask'    => ['type' => Status::NODE_TYPE_BUTTON, 'body' => 'Pick', 'buttons' => ['Yes', 'No']],
                'broken' => ['type' => Status::NODE_TYPE_BUTTON, 'body' => 'Second', 'buttons' => []],
            ],
            [
                ['from' => 'trigger', 'to' => 'ask'],
                ['from' => 'ask', 'to' => 'broken', 'button' => 0],
            ],
            ['keyword' => 'start']
        );

        $this->receiveText('start')->assertOk();
        $this->assertSame(Status::FLOW_STATE_WAITING, (int) $this->flowState()->status);

        $this->receiveButtonReply('btn_0', 'Yes')->assertOk();

        $this->assertSame(['button:Pick'], $this->sentMessages());

        $state = $this->flowState();
        $this->assertSame(
            Status::FLOW_STATE_SENT,
            (int) $state->status,
            'A branch that delivered nothing must stop the flow waiting for an answer it already has.'
        );

        // What that buys the contact: the flow's own keyword still works for them. While
        // the state stayed on WAITING, every message was read as an answer to the question
        // they had already tapped, so the keyword was dead and they could never get out.
        $this->receiveText('start')->assertOk();

        $this->assertSame(
            ['button:Pick', 'button:Pick'],
            $this->sentMessages(),
            'The contact must be able to start the flow again.'
        );
    }
}
