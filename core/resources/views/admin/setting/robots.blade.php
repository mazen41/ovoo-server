@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <x-admin.ui.card>
                <x-admin.ui.card.header>
                    <h4 class="card-title">@lang('Insert Robots txt')</h4>
                </x-admin.ui.card.header>
                <x-admin.ui.card.body>
                    <form method="post">
                        @csrf
                        <div class="form-group">
                            <textarea class="form-control" rows="10" name="robots">{{ $fileContent }}</textarea>
                        </div>
                        <x-admin.ui.btn.submit />
                    </form>
                </x-admin.ui.card.body>
            </x-admin.ui.card>
        </div>
    </div>
@endsection
