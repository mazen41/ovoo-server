@props([
    'renderTableFilter' => true,
    'renderExportButton' => true,
    'renderFilterOption' => true,
    'filterBoxLocation' => null,
    'searchPlaceholder' => 'Search here',
])

<div class="table-layout">
    @if ($renderTableFilter)
        <x-admin.ui.table.filter :renderExportButton=$renderExportButton :renderFilterOption=$renderFilterOption
            :searchPlaceholder=$searchPlaceholder :filterBoxLocation=$filterBoxLocation />
    @endif
    {{ $slot }}
</div>
