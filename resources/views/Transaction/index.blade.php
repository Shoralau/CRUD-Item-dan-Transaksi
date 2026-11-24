<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
</head>

<body>
    <div class="container mt-3">
        <h2>Transactions</h2>
        <a href="{{ route('transactions.create') }}" class="btn btn-success btn-sm">Tambah</a>
        <a href="{{ route('transactions.index') }}" class="btn btn-primary btn-sm">Rekap</a>
        <a href="{{ route('transactions.rekap') }}" class="btn btn-primary btn-sm">Rekap Detail</a>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Aksi</th>
                    <th>ID</th>
                    <th>Kode</th>
                    <th>Customer</th>
                    <th>Tanggal</th>
                    <th>Total</th>
                    <th>Discount</th>
                    <th>Grand Total</th>
                </tr>
            </thead>
            <tbody id="TransactionTableBody">
                @foreach ($transactions as $transaction)
                    <tr>
                        <td>
                            <a href="{{ route('transactions.show', $transaction->id) }}" class="btn btn-warning btn-sm">View</a>
                        </td>
                        <td>{{ $transaction->id }}</td>
                        <td>{{ $transaction->invoice_number }}</td>
                        <td>{{ $transaction->customer }}</td>
                        <td>{{ $transaction->transaction_date }}</td>
                        <td>Rp {{ $transaction->total_amount }}</td>
                        <td>{{ $transaction->discount }}%</td>
                        <td>Rp {{ $transaction->grand_total }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="7" class="text-end fw-bold">Grand Total (Rp):</th>
                    <th class="fw-bold text-primary fs-5">
                        Rp {{ number_format($transactions->sum('grand_total'), 0, ',', '.') }}
                    </th>
                </tr>
            </tfoot>
        </table>
    </div>
</body>

</html>
