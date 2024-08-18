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
</head>

<body>

    <h2>Amounts</h2>

    <form action="{{ route('amounts.store') }}" method="POST">
        @csrf
        <input type="number" name="amount" placeholder="Amount" required>
        <select name="from" id="from">
            <!-- From Currency -->
            <option value="" disabled selected="selected">Convert from </option>
            @if(!empty($currencies))
            @foreach ($currencies as $currency)
            <option value="{{ $currency['id'] }}">{{ $currency['name'] }}</option>
            @endforeach
            @endif
        </select>
        <select name="to" id="from">
            <!-- From Currency -->
            <option value="" disabled selected="selected">Convert to </option>
            @if(!empty($currencies))
            @foreach ($currencies as $currency)
            <option value="{{ $currency['id'] }}">{{ $currency['name'] }}</option>
            @endforeach
            @endif
        </select>
        <button type="submit">Add</button>
    </form>
    <br>
    <table>
        <tr>
            <th>Amount</th>
            <th>To</th>
            <th>Actions</th>

        </tr>
        <tr>
            @if(!empty($amounts))
            @foreach($amounts as $amount)
        <tr>
            <td>{{ $amount['amount']." ".$amount['from']['name']}}</td>
            <td>{{ $amount['result']." ".$amount['to']['name'] }}</td>
            <td>
                <form action="{{ route('amounts.destroy', $amount['id']) }}" method="post" style="display: inline-block;">
                    @csrf
                    @method('DELETE')
                    <button type="submit">Del</button>
                </form>
                <button>
                    <a href="amounts/update/{{ $amount['id'] }}">
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
            <a href="/exchange_rates">
                Exchange Rates
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