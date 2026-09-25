@extends('admin.layouts.app')
@section('panel')
    <div class="row responsive-row">
        <div class="col-md-12">
            <div class="alert alert--info d-flex" role="alert">
                <div class="alert__icon">
                    <i class="las la-info"></i>
                </div>
                <div class="alert__content">
                    <p>
                        <span>@lang('From this page, you can add/update CSS for the user interface. Changing content on this page required programming knowledge.')</span>
                        <span class="text--danger d-inline">@lang('Please do not change/edit/add anything without having proper knowledge of it. The website may misbehave due to any mistake you have made.')</span>
                    </p>
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <x-admin.ui.card>
                <x-admin.ui.card.header>
                    <h4 class="card-title">@lang('Write Custom CSS')</h4>
                </x-admin.ui.card.header>
                <x-admin.ui.card.body>
                    <form method="post">
                        @csrf
                        <div class="form-group custom-css">
                            <textarea class="form-control customCss" rows="10" name="css">{{ $fileContent }}</textarea>
                        </div>
                        <x-admin.ui.btn.submit />
                    </form>
                </x-admin.ui.card.body>
            </x-admin.ui.card>
        </div>
    </div>
@endsection
@push('style')
    <style>
        .CodeMirror {
            border-top: 1px solid #eee;
            border-bottom: 1px solid #eee;
            line-height: 1.3;
            height: 500px;
        }

        .CodeMirror-linenumbers {
            padding: 0 8px;
        }

        .custom-css p,
        .custom-css li,
        .custom-css span {
            color: white;
        }

        .dashboard__area {
            max-width: calc(100% - var(--sidebar));
        }

        @media screen and (max-width: 1199px) {
            .dashboard__area {
                max-width: 100%;
            }
        }
    </style>
@endpush
@push('style-lib')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/codemirror.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/css/monokai.min.css') }}">
@endpush
@push('script-lib')
    <script src="{{ asset('assets/admin/js/codemirror.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/css.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/sublime.min.js') }}"></script>
@endpush
@push('script')
    <script>
        "use strict";
        var editor = CodeMirror.fromTextArea(document.getElementsByClassName("customCss")[0], {
            lineNumbers: true,
            mode: "text/css",
            theme: "monokai",
            keyMap: "sublime",
            autoCloseBrackets: true,
            matchBrackets: true,
            showCursorWhenSelecting: true,
            matchBrackets: true
        });
    </script>
@endpush
