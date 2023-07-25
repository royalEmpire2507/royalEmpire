<table>
    <thead>
        <tr>
            @foreach ($headers as $head)
                <th>{{ $head }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach ($documents as $doc)
            <tr>
                @foreach ($headers as $head)
                    <td>
                        @if (isset($doc[$head]))
                            @php
                                $value = $doc[$head];

                                // Resto del código
                                if ($head == 'wolkvox_fecha_creacion' || $head == 'wolkvox_fecha_modificacion' || $head == 'created_at') {
                                    if(gettype($value) == 'string'){
                                        $value = $value;
                                    }else{
                                        $value = $value->toDateTime()->format('Y-m-d H:i:s');
                                    }
                                } elseif ($head == '_id') {
                                    $value = (string)$value;
                                } elseif (gettype($value) == 'array' || gettype($value) == 'object') {
                                    $value = (array)$value;
                                    if(isset($value['value'])){
                                        $value = gettype($value['value']) !== 'array' && gettype($value['value']) !== 'object' ? $value['value']:'';
                                    }else{
                                        $value = '';
                                    }
                                } else {
                                    //$value = gettype($value);
                                    $value = strval($value);
                                }
                            @endphp
                            @if (is_string($value))
                                {{ htmlspecialchars($value) }}
                            @endif
                        @endif
                    </td>
                @endforeach
            </tr>
        @endforeach
    </tbody>
</table>
