{{--
    Left-hand slot of the action bar. The empty div keeps the primary button pinned right on the
    steps that have nowhere to go back to.
--}}
@if (!empty($back))
    <a class="btn btn--ghost" href="{{ $back }}">
        <svg viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
            <path fill-rule="evenodd" d="M12.7 4.3a1 1 0 0 1 0 1.4L8.4 10l4.3 4.3a1 1 0 0 1-1.4 1.4l-5-5a1 1 0 0 1 0-1.4l5-5a1 1 0 0 1 1.4 0Z" clip-rule="evenodd"/>
        </svg>
        Back
    </a>
@else
    <div></div>
@endif
