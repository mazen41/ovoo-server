@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <section class="py-100 policy-content">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    @php echo $policy->data_values->details @endphp
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
