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
    <title>Exchange Rates</title>
</head>

<body>
    <h1>Exchange Rates</h1>

    <form action="{{ route('exchange_rates.store') }}" method="POST">
        @csrf
        <select name="from" id="from">
            <!-- From Currency -->
            <option value="" disabled selected="selected">Convert from </option>
            @if(!empty($currencies))
            @foreach ($currencies as $currency)
            <option value="{{ $currency['id'] }}">{{ $currency['name'] }}</option>
            @endforeach
            @endif
        </select>
        <select name="to" id="to">
            <!-- From Currency -->
            <option value="" disabled selected="selected">Convert to </option>
            @if(!empty($currencies))
            @foreach ($currencies as $currency)
            <option value="{{ $currency['id'] }}">{{ $currency['name'] }}</option>
            @endforeach
            @endif
        </select>

        <input type="number" name="rate" step="0.01" placeholder="Rate" required>
        <button type="submit">Add</button>
    </form>
    <br>
    <table>
        <tr>
            <th>From</th>
            <th>To</th>
            <th>Rate</th>
            <th>Actions</th>

        </tr>
        <tr>
            @if(!empty($rates))
            @foreach($rates as $rate)
        <tr>
            <td>{{$rate['from']['name']}}</td>
            <td>{{$rate['to']['name']}}</td>
            <td>{{$rate['rate']}}</td>
            <td>
                <form action="{{ route('exchange_rates.destroy', $rate['id']) }}" method="post" style="display:inline-block">
                    @csrf
                    @method('DELETE')
                    <button type="submit">Del</button>
                </form>
                <button>
                    <a href="exchange_rates/update/{{$rate['id']}}">
                        Update
                    </a>
                </button>
            </td>
        </tr>
        @endforeach
        @endif
    </table>

    <ul>
        <li>
            <a href="/amounts">
                Amounts
            </a>
        </li>
        <li>
            <a href="/currencies">
                Currencies
            </a>
        </li>
    </ul>
</body>

</html>