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
    <h1>Update Currency</h1>

    <form action="{{ route('currencies.update', $currency['id']) }}" method="POST">
        @csrf
        @method('PUT')
        <input type="text" name="name" placeholder="Currency name" value="{{$currency['name']}}" required>
        <button type="submit">Update</button>
    </form>
    <br>

</body>

</html>