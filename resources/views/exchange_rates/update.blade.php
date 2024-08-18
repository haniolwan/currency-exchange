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
    <h1>Update Exchange Rates</h1>

    <form action="{{ route('exchange_rates.update', $ex_rate['id']) }}" method="POST">
        @csrf
        @method('PUT')
        <select name="from" id="from">
            <option value="" disabled>Select currency</option>
            @foreach ($currencies as $currency)
            <option value="{{ $currency['id'] }}"
                @if ($currency['id']==$ex_rate['from']['id']) selected @endif>
                {{ $currency['name'] }}
            </option>
            @endforeach
        </select>
        <select name="to" id="to">
            <option value="" disabled>Select currency</option>
            @foreach ($currencies as $currency)
            <option value="{{ $currency['id'] }}"
                @if ($currency['id']==$ex_rate['to']['id']) selected @endif>
                {{ $currency['name'] }}
            </option>
            @endforeach
        </select>
        <input type="number" name="rate" step="0.01" placeholder="Rate" value="{{$ex_rate['rate']}}" required>
        <button type="submit">Update</button>
    </form>
    <br>

</body>

</html>