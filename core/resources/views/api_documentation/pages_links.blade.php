@php
    $externalAPiBaseURL = route('home') . '/extern-api';
@endphp
{{-- Contact start --}}
@include('api_documentation.contact.list')
@include('api_documentation.contact.store')
@include('api_documentation.contact.update')
@include('api_documentation.contact.delete')
{{-- Contact end --}}


{{-- Inbox Start --}}
{{-- @include('api_documentation.inbox.overview') --}}
@include('api_documentation.inbox.conversation_list')
@include('api_documentation.inbox.change_conversation_status')
@include('api_documentation.inbox.conversation_details')
@include('api_documentation.inbox.send_message')
@include('api_documentation.inbox.send_template_message')
@include('api_documentation.inbox.template_list')
{{-- Inbox End --}}
