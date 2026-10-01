@props(['placeholder' => 'Search...'])

<div class="input-group w-auto flex-fill">
    <input type="search" name="search" class="form-control bg--white" placeholder="{{ __($placeholder) }}" value="{{ request()->search }}">
    <button class="input-group-text" type="submit"><i class="la la-search"></i></button>
</div>
