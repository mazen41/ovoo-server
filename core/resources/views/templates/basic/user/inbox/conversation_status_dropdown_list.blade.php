 <button class="dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
     <i class="las la-comments chat-actions__icon me-1"></i>
     <span class="name"><span>@lang('Conversation Status')</span> </span>
 </button>

 <ul class="dropdown-menu dropdown-menu-end chatbot-dropdown__menu">
     <li>
         <button type="button" class="dropdown-item d-flex justify-content-between flex-wrap align-items-center"
             data-value="{{ Status::UNREAD_CONVERSATION }}">
             <span>@lang('Unread')</span>
             @if ($conversation->status == Status::UNREAD_CONVERSATION || $conversation->unseenMessages->count() > 0)
                 <i class="fa fa-check-double text--success"></i>
             @endif
         </button>
     </li>
     <li>
         <button type="button" class="dropdown-item d-flex justify-content-between flex-wrap align-items-center"
             data-value="{{ Status::PENDING_CONVERSATION }}">
             <span>@lang('Pending') </span>
             @if ($conversation->status == Status::PENDING_CONVERSATION)
                 <i class="fa fa-check-double text--success"></i>
             @endif
         </button>

     </li>
     <li>
         <button type="button" class="dropdown-item d-flex justify-content-between flex-wrap align-items-center"
             data-value="{{ Status::IMPORTANT_CONVERSATION }}">
             <span>@lang('Important') </span>
             @if ($conversation->status == Status::IMPORTANT_CONVERSATION)
                 <i class="fa fa-check-double text--success"></i>
             @endif
         </button>
     </li>
     <li>
         <button type="button" class="dropdown-item d-flex justify-content-between flex-wrap align-items-center"
             data-value="{{ Status::DONE_CONVERSATION }}">
             <span>@lang('Done') </span>
             @if ($conversation->status == Status::DONE_CONVERSATION)
                 <i class="fa fa-check-double text--success"></i>
             @endif
         </button>
     </li>
     <li>
         <button type="button" class="dropdown-item d-flex justify-content-between flex-wrap align-items-center"
             data-value="{{ Status::UNREAD_CONVERSATION }}">
             <span>@lang('No Status') </span>
             @if (!$conversation->status)
                 <i class="fa fa-check-double text--success"></i>
             @endif
         </button>
     </li>
 </ul>
