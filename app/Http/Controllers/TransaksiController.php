<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;


class TransaksiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $barang = Barang::with('transaksi')->get();
        // return $barang;
        $transaksi = Transaksi::orderBy('id', 'desc')->get();
        return view('transaksi', compact('barang', 'transaksi'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreTransaksiRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $barang = Barang::where('id', $request->barang_id)
                    ->with('transaksi')
                    ->first();

        $stok = $barang->stok - $barang->transaksi->sum('jumlah_beli');

        $validator = Validator::make($request->all(), [
            'jumlah_beli' => 'numeric|min:1|max:' . $stok,
        ]);

        if($validator->fails()){
            return back()->with('errors', $validator->messages()->all()[0])->withInput();
        }

        $transaksi = new Transaksi();
        $transaksi->barang_id = $request->barang_id;
        $transaksi->jumlah_beli = $request->jumlah_beli;
        $transaksi->total_harga = $barang->harga_jual * $transaksi->jumlah_beli;
        $transaksi->save();
        Alert::success('Berhasil', 'Membeli Barang ' . $transaksi->barang->nama);
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Transaksi  $transaksi
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Transaksi  $transaksi
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateTransaksiRequest  $request
     * @param  \App\Models\Transaksi  $transaksi
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Transaksi  $transaksi
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
