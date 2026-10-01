<?php

namespace Tests\Feature\Flow;

use App\Constants\Status;

class HarnessSmokeTest extends FlowTestCase
{
    public function test_a_keyword_flow_with_one_text_node_replies(): void
    {
        $this->seedExistingContact();

        $this->makeFlow(
            ['a' => ['type' => Status::NODE_TYPE_TEXT, 'text' => 'Hello from the flow']],
            [['from' => 'trigger', 'to' => 'a']],
            ['keyword' => 'start']
        );

        $response = $this->receiveText('start');

        $response->assertOk();
        $this->assertSame(['text:Hello from the flow'], $this->sentMessages());
    }
}
