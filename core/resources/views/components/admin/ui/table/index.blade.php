@props(['tableClass' => 'table  table--responsive--lg'])
<div class="table-body">
    <table {{ $attributes->merge(['class' => $tableClass]) }}>
        {{ $slot }}
    </table>
</div>
