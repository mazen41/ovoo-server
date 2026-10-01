@props(['text' => 'Submit'])

<div class="text-end">
    <button {{ $attributes->merge(['type' => 'submit', 'class' => 'btn btn--primary btn-large']) }}>
        <span class="me-1"><i class="fa-regular fa-paper-plane"></i></span>
        {{ __($text) }}
    </button>
</div>
