@extends('admin.layouts.app')
@section('panel')
    <div class="row gy-4">
        @foreach ($templates as $temp)
            <div class="col-xl-4 col-md-6">
                <x-admin.ui.card>
                    <x-admin.ui.card.header class="d-flex justify-content-between gap-2 py-2 align-items-center">
                        <h4 class="card-title">{{ __(keyToTitle($temp['name'])) }}</h4>
                        @if (gs('active_template') == $temp['name'])
                            <button type="submit" name="name" value="{{ $temp['name'] }}" class="btn btn--info ">
                                @lang('SELECTED')
                            </button>
                        @else
                            <form method="post">
                                @csrf
                                <button type="submit" name="name" value="{{ $temp['name'] }}" class="btn btn--success">
                                    @lang('SELECT')
                                </button>
                            </form>
                        @endif
                    </x-admin.ui.card.header>
                    <x-admin.ui.card.body>
                        <img src="{{ $temp['image'] }}" alt="Template" class="w-100 rounded">
                    </x-admin.ui.card.body>
                </x-admin.ui.card>
            </div>
        @endforeach
        @if ($extraTemplates)
            @foreach ($extraTemplates as $temp)
                <div class="col-lg-3">
                    <x-admin.ui.card>
                        <x-admin.ui.card.header>
                            <h4 class="card-title"> {{ __(keyToTitle($temp['name'])) }}</h4>
                        </x-admin.ui.card.header>
                        <x-admin.ui.card.body>
                            <img src="{{ $temp['image'] }}" alt="Template" class="w-100">
                            <a href="{{ $temp['url'] }}" target="_blank"
                                class="btn btn--primary mt-3 ">@lang('Get This')</a>
                        </x-admin.ui.card.body>
                    </x-admin.ui.card>
                </div>
            @endforeach
        @endif
    </div>
@endsection
