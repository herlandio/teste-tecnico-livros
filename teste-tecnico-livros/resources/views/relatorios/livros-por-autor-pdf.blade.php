
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">

    <title>Relatório de Livros por Autor</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
            color: #333;
        }

        h1 {
            font-size: 18px;
            margin-bottom: 5px;
        }

        .subtitle {
            color: #666;
            margin-bottom: 20px;
        }

        .autor {
            margin-top: 20px;
            margin-bottom: 8px;
            padding: 8px;
            background-color: #eeeeee;
            font-size: 12px;
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        th, td {
            border: 1px solid #cccccc;
            padding: 6px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .valor {
            text-align: right;
        }

        .footer {
            margin-top: 20px;
            font-size: 9px;
            color: #666;
        }
    </style>
</head>
<body>

    <h1>Relatório de Livros por Autor</h1>

    <div class="subtitle">
        Relação de livros, autores e assuntos cadastrados.
    </div>

    @forelse ($autores as $livros)
        @php
            $primeiro = $livros->first();
        @endphp

        <div class="autor">
            Autor: {{ $primeiro->autor_nome }}
        </div>

        <table>
            <thead>
                <tr>
                    <th>Livro</th>
                    <th>Editora</th>
                    <th>Edição</th>
                    <th>Ano</th>
                    <th>Valor</th>
                    <th>Assuntos</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($livros as $registro)
                    <tr>
                        <td>
                            {{ $registro->livro_titulo ?? 'Sem livros cadastrados' }}
                        </td>

                        <td>{{ $registro->editora ?? '-' }}</td>
                        <td>{{ $registro->edicao ?? '-' }}</td>
                        <td>{{ $registro->ano_publicacao ?? '-' }}</td>

                        <td class="valor">
                            @if ($registro->valor !== null)
                                R$ {{ number_format((float) $registro->valor, 2, ',', '.') }}
                            @else
                                -
                            @endif
                        </td>

                        <td>{{ $registro->assuntos ?: '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @empty
        <p>Nenhum autor cadastrado.</p>
    @endforelse

    <div class="footer">
        Relatório gerado em {{ date('d/m/Y H:i') }}.
    </div>

</body>
</html>
