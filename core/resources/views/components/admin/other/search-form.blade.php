@props([
    'placeholder' => 'Search...',
    'btn' => 'btn--primary',
    'dateSearch' => 'no',
    'keySearch' => 'yes',
])

<form class="d-flex flex-wrap gap-2 no-submit-loader">
    @if ($keySearch == 'yes')
        <x-admin.other.search-key-field placeholder="{{ $placeholder }}" btn="{{ $btn }}" />
    @endif
    @if ($dateSearch == 'yes')
        <x-admin.other.search-date-field />
    @endif
</form>
