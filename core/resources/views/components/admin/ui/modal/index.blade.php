<div {{ $attributes->merge(['class' => 'modal modal-lg  fade', 'tabindex' => '-1', 'aria-hidden' => 'true']) }}>
    <div class="modal-dialog modal-dialog-centered ">
        <div class="modal-content">
            {{ $slot }}
        </div>
    </div>
</div>
