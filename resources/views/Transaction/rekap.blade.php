<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Rekap Detail Transaksi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
</head>

<body>
    <div class="container mt-3">
        <h2>Rekap Detail Transaksi</h2>
        <a href="{{ route('transactions.create') }}" class="btn btn-success btn-sm">Tambah</a>
        <a href="{{ route('transactions.index') }}" class="btn btn-primary btn-sm">Rekap</a>
        <a href="{{ route('transactions.rekap') }}" class="btn btn-primary btn-sm">Rekap Detail</a>
        <div class="table-responsive mt-3">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Tanggal</th>
                        <th>ID Item</th>
                        <th>Nama Item</th>
                        <th>Deskripsi</th>
                        <th>Harga (Rp)</th>
                        <th>Qty</th>
                        <th>Subtotal (Rp)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($details as $detail)
                        <tr>
                            <td>{{ $detail->invoice_number }}</td>
                            <td>{{ $detail->transaction_date }}</td>
                            <td>{{ $detail->item_id }}</td>
                            <td>{{ $detail->item_name }}</td>
                            <td>{{ $detail->item_description ?? '-' }}</td>
                            <td>Rp {{ number_format($detail->price, 0, ',', '.') }}</td>
                            <td>{{ $detail->quantity }}</td>
                            <td>Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted">Belum ada detail transaksi.</td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="8">
                            <div class="d-flex justify-content-end align-items-center gap-4">
                                <div class="d-flex align-items-center gap-1">
                                    <span class="fw-bold">Qty:</span>
                                    <span class="fw-bold text-primary fs-5">{{ $details->sum('quantity') }}</span>
                                </div>
                                <div class="d-flex align-items-center gap-1">
                                    <span class="fw-bold">Grand Total (Rp):</span>
                                    <span class="fw-bold text-primary fs-5">
                                        {{ number_format($details->sum('subtotal'), 0, ',', '.') }}
                                    </span>
                                </div>
                            </div>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</body>

</html>
