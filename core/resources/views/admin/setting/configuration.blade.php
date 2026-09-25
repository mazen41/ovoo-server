@extends('admin.layouts.app')
@section('panel')
    <div class="row responsive-row">
        @foreach ($configurations as $k => $configuration)
            <div class="col-xxl-3 col-lg-4 col-sm-6 config-col">
                <div class="system-configure">
                    <div class="system-configure__header d-flex justify-content-between align-items-center">
                        <div class="system-configure__title d-flex align-items-center gap-2">
                            <div class="icon"><i class=" {{ @$configuration->icon }}"></i></div>
                            <h6 class="mb-0 config-name">{{ __(ucwords(@$configuration->title)) }}</h6>
                        </div>
                        <div class="form-check form-switch form--switch pl-0 form-switch-success">
                            <input class="form-check-input configuration-switch" type="checkbox" role="switch"
                                id="{{ $k }}" data-key="{{ $k }}" @checked(gs($k))
                                data-configuration='@json($configuration)'>
                        </div>
                    </div>
                    <div class="system-configure__content">
                        <p class="desc">
                            @if (gs($k))
                                {{ __(@$configuration->description_disabled) }}
                            @else
                                {{ __(@$configuration->description_enabled) }}
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection

@push('breadcrumb-plugins')
    <div class="input-group">
        <span class="input-group-text bg--white border-0">
            <i class="las la-search"></i>
        </span>
        <input class="form-control bg--white highLightSearchInput border-0 ps-0" type="search"
            placeholder="@lang('Search configuration')..." data-parent="config-col" data-search="config-name">
    </div>
@endpush

@push('script')
    <script>
        "use strict";
        (function($) {
            $(".configuration-switch").on('change', function(e) {

                const url = "{{ route('admin.setting.system.configuration.update', ':key') }}";
                const key = $(this).data('key');
                const configuration = $(this).data('configuration');
                const $this = $(this);
                const isChecked = $this.is(':checked');
                $.ajax({
                    type: "get",
                    url: url.replace(":key", key),
                    data: "data",
                    success: function(resp) {
                        if (resp.success) {
                            if (resp.new_status) {
                                notify('success', `${configuration.title} enabled successfully`);
                                $this.closest(".system-configure").find('.desc').text(configuration
                                    .description_disabled);
                            } else {
                                notify('success', `${configuration.title} disabled successfully`);
                                $this.closest(".system-configure").find('.desc').text(configuration
                                    .description_enabled);
                            }
                        } else {
                            notify('error', resp.message);
                            $this.attr('checked', !isChecked)
                        }
                    },
                    error: function(resp) {
                        $this.attr('checked', !isChecked)
                    }
                });
            });

        })(jQuery);
    </script>
@endpush
