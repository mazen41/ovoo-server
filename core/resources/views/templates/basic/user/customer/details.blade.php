<div class="body-right__top-btn">
    <span class="close-icon">
        <i class="fas fa-times"></i>
    </span>
    <a href="{{ route('user.contact.edit', @$conversation->contact->id) }}">
        <span class="icon"><i class="las la-pen"></i></span>
        @lang('Edit')
    </a>
</div>
<div class="profile-details">
    <div class="profile-details__top">
        <div class="profile-thumb">
            <img src="{{ $conversation->contact->imageSrc }}" alt="image">
        </div>
        <p class="profile-name">{{ __(@$conversation->contact->fullName) }}</p>
        <p class="text">
            <a href="tel:" class="link">+{{ @$conversation->contact->mobileNumber }}</a>
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
                    @foreach ($conversation->contact->details ?? [] as $key => $value)
                        @if (!empty($value))
                            <p class="details-content__text">
                                <span class="title">{{ __(ucfirst($key)) }} :</span> {{ __($value) }}
                            </p>
                        @endif
                    @endforeach
                    <p class="details-content__text"> <span class="title">@lang('Create Date') :
                        </span>{{ showDateTime(@$conversation->contact->created_at, 'd M Y') }}
                    </p>
                    <p class="details-content__text"> <span class="title"> @lang('Modified Date'):</span>
                        {{ showDateTime(@$conversation->contact->updated_at, 'd M Y') }}</p>
                    <div class="details-content__tag">
                        <p class="tag-title"> @lang('Tags'): </p>
                        <ul class="tag-list">
                            @foreach ($conversation->contact->tags as $tag)
                                <li>
                                    <a href="javascript:void(0)" class="tag-list__link">{{ __(@$tag->name) }} </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                   
                </div>
            </div>
            <div class="tab-pane fade" id="pills-not" role="tabpanel" aria-labelledby="pills-not-tab" tabindex="0">
                <div class="note-wrapper">
                    
                    <div class="note-wrapper__output">
                        @foreach ($conversation->notes as $note)
                            <div class="output">
                                <div>
                                    <p class="text"> {{ __(@$note->note) }}</p>
                                    <span class="date"> {{ showDateTime(@$note->created_at, 'd M Y') }}</span>
                                </div>
                                <span class="icon deleteNote" data-id="{{ $note->id }}"> <i
                                        class="fas fa-trash"></i> </span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    (function($) {
        'use strict';
        var $noteForm = $('.note-wrapper__form');
        var route = "{{ route('user.contact.note.store') }}";

        $noteForm.on('submit', function(e) {
            e.preventDefault();

            var formData = new FormData(this);
            $.ajax({
                url: route,
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function(data) {
                    if (data.success) {
                        $noteForm[0].reset();
                        notify('success', data.message);
                        $('.note-wrapper__output').prepend(data.html);
                    } else {
                        notify('error', data.message);
                    }
                }
            });
        });

        $('.note-wrapper__output').on('click', '.deleteNote', function(e) {
            e.preventDefault();

            var $this = $(this);
            var noteId = $this.data('id');
            var route = "{{ route('user.contact.note.delete', ':id') }}".replace(':id', noteId);

            $.post(route, {
                _token: "{{ csrf_token() }}"
            }, function(data) {
                if (data.status == 'success') {
                    $this.closest('.output').remove();
                }
                notify(data.status, data.message);
            });
        });
    })(jQuery);
</script>
