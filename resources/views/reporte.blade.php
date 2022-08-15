<h1>REPORTE</h1>

<table>
    <thead>
        <tr>
            <th>
                Animal
            </th>
            <th>
                Tipo
            </th>
        </tr>
    </thead>
    <tbody>
        @foreach ($datos as $item)
            <tr>
                <td>
                    {{$item->nombre}}
                </td>
                <td>
                    {{$item->tipo_id}}
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

