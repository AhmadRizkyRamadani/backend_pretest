<?php

namespace App\Http\Controllers;

use App\Models\DetailInvoice;
use App\Models\Invoice;
use App\Models\Product;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function index(){
        $invoices = Invoice::all();
        return view("invoice.index", ['invoices' => $invoices]);
    }

    public function create(){
        $products = Product::limit(50)->get();
        return view("invoice.create", ["products" => $products]);
    }

    public function store(Request $request){
        $t = microtime(true);
        $micro = sprintf("%06d",($t - floor($t)) * 1000000);
        date_default_timezone_set("Asia/Jakarta");

        $data_to_insert = [];
        $kode_transaksi = "inv_".date("ymdhis".$micro, $t);
        foreach($request->kode_produk as $x => $kode_produk){
            // array_push($data_to_insert, );
            DetailInvoice::create(["kode_transaksi" => $kode_transaksi, "kode_produk" => $kode_produk, "harga" => str_replace(".", "", $request->harga[$x]), "jumlah" => str_replace(".", "", $request->jumlah[$x]), "total" => str_replace(".", "", $request->subtotal[$x])]);
        }
        try{
            Invoice::create([
                "kode_transaksi" => $kode_transaksi,
                "user_id" => $request->session()->get("user_id"),
                "total_biaya" => $request->total_biaya,
                "status" => "pending",
                "kode_pembayaran" => ""
            ]);
        }catch(QueryException $e){
            return redirect("/invoice/create")->with("gagal", $e->errorInfo[2]);
        }

        return redirect("/invoice")->with("sukses", "Data saved successfully");
    }

    public function detail(Request $request, $id){
        $invoice = Invoice::where("kode_transaksi", $id)->first();
        $detail_invoice = DetailInvoice::where("kode_transaksi", $id)->get();

        return view("invoice.detail", ["invoice" => $invoice, "detail_invoice" => $detail_invoice]);
    }
}
