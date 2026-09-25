@php
    $user = auth()->user();
    $mobileNumber = $user->hasAgentPermission('view contact mobile')
        ? $conversation->contact->mobileNumber
        : showMobileNumber($conversation->contact->mobileNumber);
    $firstName = $user->hasAgentPermission('view contact name') ? $conversation->contact->firstname : '***';
    $lastName = $user->hasAgentPermission('view contact name') ? $conversation->contact->lastname : '***';
@endphp
<div class="body-right__top-btn">
    <span class="close-icon-two">
        <i class="fas fa-times"></i>
    </span>
    {{-- to do --}}

    {{-- <x-permission_check permission="edit contact">
        <a target="_blank" href="{{ route('user.contact.edit', @$conversation->contact->id) }}"
            class="btn--gray d-flex align-items-center gap-1">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="lucide lucide-square-pen-icon lucide-square-pen">
                <path d="M12 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                <path
                    d="M18.375 2.625a1 1 0 0 1 3 3l-9.013 9.014a2 2 0 0 1-.853.505l-2.873.84a.5.5 0 0 1-.62-.62l.84-2.873a2 2 0 0 1 .506-.852z" />
            </svg>
            @lang('Edit')
        </a>
    </x-permission_check>
    @if ($conversation->contact->is_blocked)
        <x-permission_check permission="unblock contact">
            <button type="button" class="text--success confirmationBtn"
                data-action="{{ route('user.contact.unblock', $conversation->contact->id) }}?status=unblock"
                data-question="@if (@$conversation->contact?->blockedBy->is_agent) @lang('This contact was blocked by ') {{ @$conversation->contact?->blockedBy?->username }}. @endif @lang('Are you sure to unblock this contact?')">
                <i class="las la-check-circle"></i>
                @lang('Unblock')
            </button>
        </x-permission_check>
    @else
        <x-permission_check permission="block contact">
            <button type="button" class="btn--gray confirmationBtn d-flex align-items-center"
                data-action="{{ route('user.contact.block', $conversation->contact->id) }}?status=block"
                data-question="@lang('Are you sure to block this contact?')">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="lucide lucide-ban-icon lucide-ban">
                    <path d="M4.929 4.929 19.07 19.071" />
                    <circle cx="12" cy="12" r="10" />
                </svg>
                @lang('Block')
            </button>
        </x-permission_check>
    @endif --}}
</div>
<div class="profile-details">
    <div class="profile-details__top">
        <div class="profile-thumb">
            <img src="{{ $conversation->contact->image_src }}" alt="image">
        </div>
        <p class="profile-name mb-0">{{ __(@$conversation->contact->fullName) }}</p>
        <p class="text fs-14">
            @if ($user->hasAgentPermission('view contact mobile'))
                <a href="tel:{{ @$conversation->contact->mobileNumber }}"
                    class="link">+{{ @$conversation->contact->mobileNumber }}</a>
            @else
                <span class="link">+{{ @$mobileNumber }}</span>
            @endif
        </p>
    </div>
    <div class="profile-details__tab">
        <ul class="nav nav-pills custom--tab tab-two" id="pills-tabtwo" role="tablist">
            <li class="nav-item">
                <button class="nav-link active" id="pills-details-tab" data-bs-toggle="pill"
                    data-bs-target="#pills-details" type="button" role="tab" aria-controls="pills-details"
                    aria-selected="true">@lang('Details')</button>
            </li>
            <li class="nav-item">
                <button class="nav-link" id="pills-not-tab" data-bs-toggle="pill" data-bs-target="#pills-not"
                    type="button" role="tab" aria-controls="pills-not"
                    aria-selected="false">@lang('Note')</button>
            </li>
        </ul>
        <div class="tab-content" id="pills-tabContenttwo">
            <div class="tab-pane fade show active" id="pills-details" role="tabpanel"
                aria-labelledby="pills-details-tab" tabindex="0">
                <div class="details-content">

                    <p class="details-content__text d-flex gap-1 flex-wrap justify-content-between">
                        <span class="title">@lang('First Name') : </span>
                        <span>{{ __(@$firstName) }}</span>
                    </p>
                    <p class="details-content__text d-flex gap-1 flex-wrap justify-content-between">
                        <span class="title">@lang('Last Name') : </span>
                        <span>{{ __(@$lastName) }}</span>
                    </p>
                    <p class="details-content__text d-flex gap-1 flex-wrap justify-content-between">
                        <span class="title">@lang('Mobile') : </span>
                        <span>{{ @$mobileNumber }}</span>
                    </p>
                    <p class="details-content__text d-flex gap-1 flex-wrap justify-content-between">
                        <span class="title">@lang('Crated At') : </span>
                        <span>{{ showDateTime(@$conversation->contact->created_at, 'd M Y') }}</span>
                    </p>
                    <p class="details-content__text d-flex gap-1 flex-wrap justify-content-between">
                        <span class="title">@lang('Last Modified') : </span>
                        <span>{{ showDateTime(@$conversation->contact->updated_at, 'd M Y') }}</span>
                    </p>
                    @foreach ($conversation->contact->details ?? [] as $key => $value)
                        @if (!empty($value))
                            <p class="details-content__text">
                                <span class="title"> {{ __(ucfirst($key)) }}</span>
                                <span>{{ __($value) }}</span>
                            </p>
                        @endif
                    @endforeach
                    <div class="details-content__tag">
                        <p class="tag-title"> @lang('Tags'): </p>
                        <ul class="tag-list justify-content-start">
                            @foreach ($conversation->contact->tags as $tag)
                                <li>
                                    <a target="_blank"
                                        href="{{ route('user.contact.list') }}?tag_id={{ $tag->id }}"
                                        class="tag-list__link">{{ __(@$tag->name) }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                   
                </div>
            </div>
            <div class="tab-pane fade" id="pills-not" role="tabpanel" aria-labelledby="pills-not-tab" tabindex="0">
                <div class="note-wrapper">
                    <form class="note-wrapper__form">
                        @csrf
                        <input type="hidden" name="conversation_id" value="{{ $conversation->id }}">
                        <label for="note" class="form--label">@lang('Add Note')</label>
                        <textarea id="note" class="form--control" name="note" placeholder="@lang('Write a note...')"></textarea>
                        <div class="note-wrapper__btn">
                            <button class="btn btn--base btn-shadow">@lang('Add')</button>
                        </div>
                    </form>
                    <div class="note-wrapper__output">
                        @foreach ($conversation->notes as $note)
                            <div class="output">
                                <div>
                                    <p class="text"> {{ __(@$note->note) }}</p>
                                    <span class="date"> {{ showDateTime(@$note->created_at, 'd M Y') }}</span>
                                </div>
                                <span class="icon deleteNote" data-id="{{ $note->id }}">
                                    <i class="fa-regular fa-trash-can"></i>
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
