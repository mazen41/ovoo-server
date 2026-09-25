@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <section class="py-100">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 ">
                    <div class="policy-content">
                        @php echo $cookie?->data_values?->description @endphp
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('style')
    <style>
        .policy-content p,.policy-content li {
            color: hsl(var(--body-color)/0.7);
        }
    </style>
@endpush
