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
        <select name="to">
            <!-- To Currency -->
            <option value="" disabled selected>Select To Curr</option>
            @foreach ($currencies as $currency => $rate)
            <option value="{{ $currency }}"
                @if ($currency==old('To', $currency))
                selected="selected"
                @endif>{{ $currency }}</option>
            @endforeach
        </select>
        <button type="submit">Add</button>
    </form>
    <br>
    <table>
        <tr>
            <th>Amount</th>
            <th>To</th>
            <th>Result</th>
            <th>Actions</th>

        </tr>
        <tr>
            @foreach($amounts as $amount)
        <tr>
            <td>{{ $amount['amount'] }}</td>
            <td>{{ $amount['to'] }}</td>
            <td>{{ $amount['result'] }}</td>
            <td>
                <button>Del</button>
                <button>Update</button>
            </td>
        </tr>
        @endforeach
    </table>
</body>

</html>