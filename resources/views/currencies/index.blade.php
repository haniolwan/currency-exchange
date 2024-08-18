<!DOCTYPE html>
<html>

<head>
    <style>
        table {
            font-family: arial, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        td,
        th {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }

        tr:nth-child(even) {
            background-color: #dddddd;
        }
    </style>
    <title>Currencies</title>
</head>

<body>
    <h1>Currency</h1>

    <form action="{{ route('currencies.store') }}" method="POST">
        @csrf
        <input type="text" name="name" placeholder="Currency name" required>
        <button type="submit">Add</button>
    </form>
    <br>
    <table>
        <tr>
            <th>Currency</th>
            <th>Actions</th>
        </tr>
        @if(!empty($currencies))
        @foreach($currencies as $currency)
        <tr>
            <td>{{$currency['name']}}</td>
            <td>
                <form action="{{ route('currencies.destroy', $currency['id']) }}" method="post" style="display: inline-block;">
                    @csrf
                    @method('DELETE')
                    <button type="submit">Del</button>
                </form>
                <button>
                    <a href="currencies/update/{{$currency['id']}}">
                        Update
                    </a>
                </button>
            </td>
        </tr>
        @endforeach
        @endif
        <tr>

    </table>

    <ul>
        <li>
            <a href="/exchange_rates">
                Exchange Rates
            </a>
        </li>
        <li>
            <a href="/amounts">
                Amounts
            </a>
        </li>
    </ul>
</body>

</html>