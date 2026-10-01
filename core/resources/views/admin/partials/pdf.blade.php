<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
@php
    $perColumnWidth = 1000 / count($data['headers']) . 'px';
@endphp
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="title" Content="{{ gs()->siteName(__($data['pageTitle'])) }}">
    <link rel="shortcut icon" href="{{ siteFavicon() }}" type="image/x-icon">
    <title>{{ gs()->siteName(__($data['pageTitle'])) }}</title>
    <style>
        .pdf-table {
            border-collapse: collapse;
            max-width: 100%;
            width: 100%;
            text-align: center;
        }

        .pdf-table td,
        .pdf-table th {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
            vertical-align: middle;
        }

        .pdf-table tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .pdf-table tr:hover {
            background-color: #ddd;
        }

        .pdf-table th {
            padding-top: 10px;
            padding-bottom: 10px;
            background-color: #04AA6D;
            color: white;
        }

        .pdf-table td {
            max-width: {{ $perColumnWidth }};
            word-wrap: break-word;
            overflow-wrap: break-word;
            white-space: normal;
            text-align: center;
        }

        @page {
            size: {{ $data['printPageSize'] }};
        }
    </style>
    </style>
</head>

<body>
    <table class="pdf-table">
        <thead>
            <tr>
                @foreach ($data['headers'] as $header)
                    <th>{{ $header }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach ($data['body'] as $body)
                <tr>
                    @foreach ($body as $item)
                        <td>{{ $item }}</td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
