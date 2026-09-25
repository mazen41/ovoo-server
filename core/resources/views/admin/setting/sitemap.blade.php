@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <x-admin.ui.card>
                <x-admin.ui.card.header>
                    <h4 class="card-title">@lang('Insert Sitemap XML')</h4>
                </x-admin.ui.card.header>
                <x-admin.ui.card.body>
                    <form method="post">
                        @csrf
                        <div class="form-group custom-css">
                            <textarea class="form-control sitemapEditor" rows="10" name="sitemap">{{ $fileContent }}</textarea>
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

        [data-theme=dark] .CodeMirror {
            border-color: hsl(var(--border-color)) !important;
        }
    </style>
@endpush
@push('style-lib')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/codemirror.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/css/monokai.min.css') }}">
@endpush
@push('script-lib')
    <script src="{{ asset('assets/admin/js/codemirror.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/xml.js') }}"></script>
    <script src="{{ asset('assets/admin/js/sublime.min.js') }}"></script>
@endpush
@push('script')
    <script>
        "use strict";
        var editor = CodeMirror.fromTextArea(document.getElementsByClassName("sitemapEditor")[0], {
            lineNumbers: true,
            mode: "text/xml",
            theme: "monokai",
            keyMap: "sublime",
            showCursorWhenSelecting: true,
        });
    </script>
@endpush
