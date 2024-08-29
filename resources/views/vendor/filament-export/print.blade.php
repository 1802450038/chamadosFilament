<!DOCTYPE html>
<html lang="{{ App::getLocale() }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $fileName ?? date()->format('d') }}</title>
    <style>
        table {
            background: white;
            color: black;
            width: 100%;
            border-collapse: collapse;
            border-spacing: 0;
            font-family: sans-serif;
        }

        td,
        th {
            border-color: #ededed;
            border-style: solid;
            border-width: 1px;
            font-size: 13px;
            line-height: 2;
            overflow: hidden;
            padding-left: 6px;
            word-break: normal;
        }

        th {
            font-weight: normal;
        }

        table {
            page-break-after: auto
        }

        tr {
            page-break-inside: avoid;
            page-break-after: auto
        }

        td {
            page-break-inside: avoid;
            page-break-after: auto
        }
    </style>
</head>

<body>

    @foreach ($rows as $row)
        @foreach ($columns as $column)
            <b>{{ $column->getLabel() }}</b>
            <br>
            {!! $row[$column->getName()] !!}
            <br>
            <hr>
        @endforeach
        Concluido
        <br>
        [ ] - Sim   [ ] - Não
        <br>
        <br>
        Ass:
        <b><hr></b>
    @endforeach

</body>


</html>
