@extends('admin.layouts.app')
@section('panel')
    <div class="row responsive-row justify-content-start">
        @foreach (getPageSections(true) as $k => $secs)
            <div class="col-lg-3 col-md-4 col-sm-6 section-col">
                <a class="card  shadow-none h-100 section-card" href="{{ route('admin.frontend.sections', $k) }}">
                    <div class="card-body  text-center">
                        <span class="mb-2 section-icon">
                            <i class="{{ $secs['icon'] }}"></i>
                        </span>
                        <h6 class="mb-2 section-name">
                            {{ __($secs['name']) }}
                        </h6>
                        <p>{{ __($secs['description']) }}</p>
                    </div>
                </a>
            </div>
        @endforeach
    </div>
@endsection

@push('breadcrumb-plugins')
    <div class="input-group">
        <span class="input-group-text bg--white border-0">
            <i class="las la-search"></i>
        </span>
        <input class="form-control bg--white highLightSearchInput me-0 ms-0 border-0 ps-0" type="search" placeholder="@lang('Search section')..."
            data-parent="section-col" data-search="section-name">
    </div>
@endpush


@push('style')
    <style>
        .section-icon {
            font-size: 2.188rem;
        }
        .section-card{
            transition: .3s;
            border: 1px solid hsl(var(--white))
        }
        [data-theme=dark] .section-card{
            border: 1px solid hsl(var(--border-color))
        }
        .section-card:hover, .section-card:focus-visible{
            border: 1px solid hsl(var(--primary))
        }
    </style>
@endpush
