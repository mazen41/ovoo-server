@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="dashboard-container">
        <div class="container-top">
            <div class="container-top__left">
                <h5 class="container-top__title">{{ __(@$pageTitle) }}</h5>
                <p class="container-top__desc">@lang('Organize and manage your customer with effortless ease.')</p>
            </div>
            <x-permission_check permission="add customer">
                <div class="container-top__right">
                    <div class="btn--group">
                        <a href="{{ route('user.customer.create') }}" class="btn btn--base btn-shadow">
                            <i class="las la-plus"></i> @lang('Add New')
                        </a>
                    </div>
                </div>
            </x-permission_check>
        </div>
        <div class="dashboard-container__body">
            <div class="body-top">
                <div class="body-top__left">
                    <form class="search-form">
                        <input type="search" class="form--control" name="search" placeholder="@lang('Search here...')"
                            value="{{ request()->search }}" autocomplete="off">
                        <span class="search-form__icon"> <i class="fa-solid fa-magnifying-glass"></i>
                        </span>
                    </form>
                </div>
                <div class="body-top__right">
                    <span class="text"> @lang('Filter by') :</span>
                    <form class="select-group filter-form">
                        <select class="form-select form--control select2" name="tag_id">
                            <option selected value="">@lang('Customer Tag')</option>
                            @foreach ($contactTags as $tag)
                                <option value="{{ $tag->id }}" @selected(request()->tag_id == $tag->id)>
                                    {{ __(@$tag->name) }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                </div>
            </div>
            <div class="dashboard-table">
                <table class="table table--responsive--xl">
                    <thead>
                        <tr>
                            <th>@lang('Name')</th>
                            <th>@lang('Mobile')</th>
                            <th>@lang('Tags')</th>
                            <th>@lang('Action')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($contacts as $contact)
                            <tr>
                                <td>
                                    <div
                                        class="d-flex align-items-center gap-2 flex-wrap justify-content-end justify-content-md-start">
                                        <div class="table-thumb d-none d-lg-block">
                                            <img src="{{ $contact->image_src }}" alt="">
                                        </div>
                                        {{ __(@$contact->fullName) }}
                                    </div>
                                </td>
                                <td>+{{ @$contact->mobileNumber }}</td>
                                <td>
                                    <ul class="tag-list">
                                        @forelse ($contact->tags->take(3) as $tag)
                                            <li>
                                                <a href="{{ appendQuery('tag_id', $tag->id) }}"
                                                    class="tag-list__link">{{ __(@$tag->name) }}</a>
                                            </li>
                                        @empty
                                            <li>
                                                <span class="text-muted">@lang('N/A')</span>
                                            </li>
                                        @endforelse
                                        @if ($contact->tags->count() > 3)
                                            <li>
                                                <button type="button" data-tags="{{ $contact->tags }}"
                                                    class="more_tags_btn text--base">@lang('See More...')
                                                </button>
                                            </li>
                                        @endif
                                    </ul>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <x-permission_check permission="edit customer">
                                            <a href="{{ route('user.customer.edit', $contact->id) }}"
                                                class="action-btn text--base" data-bs-toggle="tooltip"
                                                data-bs-title="@lang('Edit')">
                                                <i class="fas fa-pen"></i>
                                            </a>
                                        </x-permission_check>
                                        <x-permission_check permission="delete customer">
                                            <button type="button" class="action-btn confirmationBtn text--danger"
                                                data-bs-toggle="tooltip" data-question="@lang('Are you sure to remove this customer?')"
                                                data-action="{{ route('user.customer.delete', @$contact->id) }}"
                                                data-bs-title="@lang('Delete')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </x-permission_check>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            @include('Template::partials.empty_message')
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ paginateLinks(@$contacts) }}
        </div>
    </div>

    <div class="modal fade custom--modal tags-modal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Contact Tags')</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <ul class="tag-list contact-tags">
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <x-confirmation-modal isFrontend="true" />
@endsection

@push('style-lib')
    <link rel="stylesheet" href="{{ asset('assets/global/css/select2.min.css') }}">
@endpush
@push('script-lib')
    <script src="{{ asset('assets/global/js/select2.min.js') }}"></script>
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";
            $('.more_tags_btn').on('click', function() {
                const contactTags = $(this).data('tags');
                $('.contact-tags').html('');
                contactTags.forEach(tag => {
                    $('.contact-tags').append(`
                    <li>
                        <a href="?tag_id=${tag.id}" class="tag-list__link">${tag.name}</a>
                    </li>`);
                });
                $('.tags-modal').modal('show');
            });

        })(jQuery);
    </script>
@endpush
