@extends($activeTemplate . 'layouts.app')
@section('app-content')
    <section class="maintenance-page flex-column justify-content-center banner-bg">
        <div class="container">
            <div class="row justify-content-center align-items-center">
                <div class="col-lg-7 text-center">
                    <img class="img-fluid mx-auto mb-3"
                        src="{{ getImage(getFilePath('maintenance') . '/' . @$maintenance->data_values->image, getFileSize('maintenance')) }}"
                        alt="image">
                    <div class="maintenance-page__content">@php echo $maintenance->data_values->description @endphp</div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('style')
    <style>
        header,
        nav,
        .footer-area {
            display: none !important;
        }

        .breadcrumb {
            display: none;
        }

        .maintenance-page {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding-top: 0;
        }

        .maintenance-page img {
            max-width: 400px;
        }
        @media (max-width: 575px) {
            .maintenance-page img {
                max-width: 350px;
            }
        }
        @media (max-width: 424px) {
            .maintenance-page img {
                max-width: 250px;
            }
        }

        .maintenance-page .maintenance-page__content font span {
            font-size: 32px !important;
        }

        .maintenance-page .maintenance-page__content p font,
        .maintenance-page .maintenance-page__content font span {
            color: hsl(var(--body-color)) !important;
        }
    </style>
@endpush
