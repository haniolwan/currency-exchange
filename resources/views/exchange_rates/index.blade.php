<!DOCTYPE html>
<html>

<head>
    <title>Exchange Rates</title>
</head>

<body>
    <h1>Exchange Rates</h1>
   
    <form action="{{ route('exchange_rates.store') }}" method="POST">
        @csrf
        <input type="text" name="currency" placeholder="Currency" required>
        <input type="number" name="rate" step="0.01" placeholder="Rate" required>
        <button type="submit">Add</button>
    </form>

    <ul>
        @foreach ($currencies as $currency => $rate)
        <li>{{ $currency }}: {{ $rate }}</li>
        @endforeach
    </ul>
</body>

</html>