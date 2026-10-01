 @if (isParentUser())
     @php
         $agents = \App\Models\User::active()->where('parent_id', $user->id)->where('is_deleted', Status::NO)->get();
     @endphp

     <button class="dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
         <i class="las la-user chat-actions__icon me-1"></i>
         <span class="name"><span>@lang('Assign Agent')</span> </span>
     </button>
     <ul class="dropdown-menu dropdown-menu-end chatbot-dropdown__menu">
         @forelse ($agents as $agent)
             <li>
                 <a href="{{ route('user.inbox.conversation.assign', ['conversationId' => $conversation->id, 'agentId' => $agent->id, 'channel' => ($channel ?? request('channel'))]) }}"
                     class="dropdown-item d-flex justify-content-between flex-wrap align-items-center">
                     <span>
                         {{ $agent->fullname }} <strong>({{ $agent->email }})</strong>
                     </span>

                     @if ($conversation->agent_id == $agent->id)
                         <i class="fa fa-check-double text--success"></i>
                     @endif
                 </a>
             </li>
             @empty
             <li class="dropdown-item d-flex justify-content-between flex-wrap align-items-center">
                 <span>@lang('No Agent Found')</span>
             </li>
         @endforelse
     </ul>
 @endif
