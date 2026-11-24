<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = DB::table('transaction')->get();

        return view('Transaction.index', ['transactions' => $transactions]);
    }

    public function rekap()
    {
        $details = DB::table('transaction')
            ->join('transaction_details', 'transaction.id', '=', 'transaction_details.transaction_id')
            ->join('items', 'transaction_details.item_id', '=', 'items.id')
            ->select(
                'transaction.invoice_number',
                'transaction.transaction_date',
                'items.id as item_id',
                'items.name as item_name',
                'items.description as item_description',
                'transaction_details.price',
                'transaction_details.quantity',
                'transaction_details.subtotal'
            )
            ->orderBy('transaction.transaction_date')
            ->orderBy('transaction.invoice_number')
            ->get();

        return view('Transaction.rekap', ['details' => $details]);
    }

    public function create()
    {
        $items = DB::table('items')->get();

        return view('Transaction.create', ['items' => $items]);
    }

    public function store(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'tanggal' => 'required|date',
            'customer' => 'nullable|string|max:255',
            'keterangan' => 'nullable|string|max:255',
            'items' => 'required|array|min:1',
            'items.*.id' => 'required|exists:items,id', 
            'items.*.qty' => 'required|numeric|min:1',
            'items.*.price' => 'required|numeric|min:0',
        ]);

        // 2. Jalankan Database Transaction
        try {
            DB::beginTransaction();

            // Hitung Total di sisi server
            $total = 0;
            foreach ($request->items as $item) {
                $total += $item['qty'] * $item['price'];
            }

            // Simpan Data Header Transaksi dan Ambil ID yang Baru Dibuat
            $invoiceNumber = 'TRX-' . date('YmdHis');
            $transactionId = DB::table('transaction')->insertGetId([
                'invoice_number' => $invoiceNumber,
                'transaction_date' => $request->tanggal,
                'customer' => $request->customer,
                'description' => $request->keterangan,
                'total_amount' => $total,
                'discount' => $request->discount ?? 0,
                'grand_total' => $total - ($total * ($request->discount ?? 0) / 100),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Simpan Detail Transaksi dan Update Stok Barang
            foreach ($request->items as $item) {
                DB::table('transaction_details')->insert([
                    'transaction_id' => $transactionId,
                    'item_id' => $item['id'],
                    'quantity' => $item['qty'],
                    'price' => $item['price'],
                    'subtotal' => $item['qty'] * $item['price'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Pengurangan stok barang di database
                DB::table('items')
                    ->where('id', $item['id'])
                    ->decrement('stock', $item['qty']);
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Transaksi berhasil disimpan!',
                'data' => [
                    'id' => $transactionId,
                    'invoice_number' => $invoiceNumber,
                    'total_amount' => $total,
                    'discount' => $request->discount ?? 0,
                    'grand_total' => $total - ($total * ($request->discount ?? 0) / 100),
                ],
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function show($id)
    {
        // 1. Ambil data header transaksi
        $transaction = DB::table('transaction')
            ->where('id', $id)
            ->first();

        if (! $transaction) {
            abort(404, 'Transaksi tidak ditemukan');
        }

        // 2. Ambil data detail transaksi join dengan tabel items untuk mengambil nama barang
        $details = DB::table('transaction_details')
            ->join('items', 'transaction_details.item_id', '=', 'items.id')
            ->select(
                'transaction_details.*',
                'items.id as item_id',
                'items.name as item_name',
                'items.description as item_description'
            )
            ->where('transaction_details.transaction_id', $id)
            ->get();

        // 3. Return view
        return view('Transaction.show', compact('transaction', 'details'));
    }
}