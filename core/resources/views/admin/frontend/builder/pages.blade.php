@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-12">
            <x-admin.ui.card>
                <x-admin.ui.card.body :paddingZero="true">
                    <x-admin.ui.table.layout :renderTableFilter=false>
                        <x-admin.ui.table>
                            <x-admin.ui.table.header>
                                <tr>
                                    <th>@lang('Name')</th>
                                    <th>@lang('Slug')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </x-admin.ui.table.header>
                            <x-admin.ui.table.body>
                                @forelse($pData as $k => $data)
                                    <tr>
                                        <td>{{ __($data->name) }}</td>
                                        <td>{{ __($data->slug) }}</td>
                                        <td>
                                            <div class="d-flex justify-content-end gap-2 flex-wrap">
                                                <a href="{{ route('admin.frontend.manage.pages.seo', $data->id) }}"
                                                    class="btn  btn-outline--info"><i class="la la-cog"></i>
                                                    @lang('SEO Setting')</a>
                                                <a href="{{ route('admin.frontend.manage.section', $data->id) }}"
                                                    class="btn  btn-outline--primary"><i class="la la-pencil"></i>
                                                    @lang('Edit')</a>
                                                @if ($data->is_default == Status::NO)
                                                    <button class="btn  btn-outline--danger confirmationBtn"
                                                        data-action="{{ route('admin.frontend.manage.pages.delete', $data->id) }}"
                                                        data-question="@lang('Are you sure to remove this page?')">
                                                        <i class="las la-trash"></i> @lang('Delete')
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <x-admin.ui.table.empty_message />
                                @endforelse
                            </x-admin.ui.table.body>
                        </x-admin.ui.table>
                    </x-admin.ui.table.layout>
                </x-admin.ui.card.body>
            </x-admin.ui.card>
        </div>
    </div>

    <x-admin.ui.modal id="addModal">
        <x-admin.ui.modal.header>
            <h1 class="modal-title">@lang('Add New Page')</h1>
            <button type="button" class="btn-close close" data-bs-dismiss="modal" aria-label="Close">
                <i class="las la-times"></i>
            </button>
        </x-admin.ui.modal.header>
        <x-admin.ui.modal.body>
            <form action="{{ route('admin.frontend.manage.pages.save') }}" method="POST">
                @csrf
                <div class="form-group">
                    <div class="d-flex justify-content-between">
                        <label> @lang('Page Name')</label>
                        <a href="javascript:void(0)" class="buildSlug"><i class="las la-link"></i>
                            @lang('Make Slug')</a>
                    </div>
                    <input type="text" class="form-control" name="name" value="{{ old('name') }}" required>
                </div>
                <div class="form-group">
                    <div class="d-flex justify-content-between">
                        <label> @lang('Slug')</label>
                        <div class="slug-verification d-none"></div>
                    </div>
                    <input type="text" class="form-control" name="slug" value="{{ old('slug') }}" required>
                </div>
                <div class="form-group">
                    <x-admin.ui.btn.modal />
                </div>
            </form>
        </x-admin.ui.modal.body>
    </x-admin.ui.modal>

    <x-confirmation-modal />
@endsection

@push('breadcrumb-plugins')
    <button type="button" class="btn  btn--primary addBtn">
        <i class="las la-plus"></i> @lang('Add New')
    </button>
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";

            $('.addBtn').on('click', function() {
                var modal = $('#addModal');
                modal.find('input[name=id]').val($(this).data('id'))
                modal.modal('show');
            });

            $('.buildSlug').on('click', function() {
                let closestForm = $(this).closest('form');
                let title = closestForm.find('[name=name]').val();
                closestForm.find('[name=slug]').val(title);
                closestForm.find('[name=slug]').trigger('input');
            });

            $('[name=slug]').on('input', function() {
                let closestForm = $(this).closest('form');
                closestForm.find('[type=submit]').addClass('disabled')
                let slug = $(this).val();
                slug = slug.toLowerCase().replace(/ /g, '-').replace(/[^\w-]+/g, '');
                $(this).val(slug);
                if (slug) {
                    $('.slug-verification').removeClass('d-none');
                    $('.slug-verification').html(`
                        <small class="text--info"><i class="las la-spinner la-spin"></i> @lang('Checking')</small>
                    `);
                    $.get("{{ route('admin.frontend.manage.pages.check.slug') }}", {
                        slug: slug
                    }, function(response) {
                        if (!response.exists) {
                            $('.slug-verification').html(`
                                <small class="text--success"><i class="las la-check"></i> @lang('Available')</small>
                            `);
                            closestForm.find('[type=submit]').removeClass('disabled')
                        }
                        if (response.exists) {
                            $('.slug-verification').html(`
                                <small class="text--danger"><i class="las la-times"></i> @lang('Slug already exists')</small>
                            `);
                        }
                    });
                } else {
                    $('.slug-verification').addClass('d-none');
                }
            })
        })(jQuery);
    </script>
@endpush
