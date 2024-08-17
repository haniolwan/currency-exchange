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
        <li>
            <form action="{{ route('exchange_rates.destroy', $currency) }}" method="post">
                @csrf
                @method('DELETE')
                <input type="hidden" name="currency" value="{{$currency}}">
                <button type="submit">Delete</button>
            </form>
            <form action="{{ route('exchange_rates.update', $currency) }}" method="POST" style="display:inline;">
                @csrf
                @method('PUT')
                <input type="hidden" name="currency" value="{{$currency}}">
                {{ $currency }}: <input type="text" name="rate" value="{{ $rate }}">
                <button type="submit">Update</button>
            </form>
        </li>
        @endforeach
    </ul>
</body>

</html>