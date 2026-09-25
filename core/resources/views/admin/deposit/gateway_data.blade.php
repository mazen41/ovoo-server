@foreach ($details as $k => $val)
    <div class="mb-3">
        @if (is_object($val) || is_array($val))
            <span class="fs-13 text-muted mb-1">{{ keyToTitle($k) }}</span>
            <hr>
            <div class="ms-3">
                @include('admin.deposit.gateway_data', ['details' => $val])
            </div>
        @else
            <span class="fs-13 text-muted mb-1">{{ @keyToTitle($k) }}</span>
            <p>{{ @$val }}</p>
        @endif
    </div>
@endforeach
