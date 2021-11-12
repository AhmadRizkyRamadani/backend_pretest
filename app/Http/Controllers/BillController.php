<?php

namespace App\Http\Controllers;

use App\Exports\InvoiceExport;
use App\Models\Billing;
use App\Models\DetailBilling;
use App\Models\DetailInvoice;
use App\Models\Invoice;
use App\Models\MutasiPengguna;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

use PDF;

class BillController extends Controller
{
    public function index(){
        $bills = Billing::all();
        return view("billing.index", ['bills' => $bills]);
    }

    public function create(){
        $invoices = Invoice::limit(50)->doesnthave("detail_billing")->get();
        return view("billing.create", ["invoices" => $invoices]);
    }

    public function store(Request $request){
        $t = microtime(true);
        $micro = sprintf("%06d",($t - floor($t)) * 1000000);
        date_default_timezone_set("Asia/Jakarta");

        $data_to_insert = [];
        $kode_billing = "bill_".date("ymdhis".$micro, $t);
        $submit_type = $request->input("submit");

        foreach($request->kode_transaksi as $x => $kode_transaksi){
            // array_push($data_to_insert, );
            DetailBilling::create(["kode_billing" => $kode_billing,"kode_transaksi" => $kode_transaksi]);
            Invoice::where("kode_transaksi", $kode_transaksi)->update([
                "kode_pembayaran" => $kode_billing
            ]);
        }
        try{
            Billing::create([
                "kode_billing" => $kode_billing,
                "user_id" => $request->session()->get("user_id"),
                "total_biaya" => $request->total_biaya,
                "status" => "pending",
                "kode_pembayaran" => ""
            ]);
        }catch(QueryException $e){
            return redirect("/bill/create")->with("gagal", $e->errorInfo[2]);
        }

        if($submit_type != "pay_later"){
            return redirect("/bill/$kode_billing/payment")->with("sukses", "Data saved successfully");
        }

        return redirect("/bill")->with("sukses", "Data saved successfully");
    }

    public function detail(Request $request, $id){
        $bill = Billing::where("kode_billing", $id)->first();
        $invoices = Invoice::has("detail_billing")->get();

        return view("billing.detail", ["bill" => $bill, "invoices" => $invoices]);
    }

    public function payment($id, Request $request){
        try{
            $bill = Billing::where("kode_billing", $id)->first();
            $user = User::where("user_id", $request->session()->get("user_id"))->first();
        }catch(QueryException $e){
            return redirect("/bill/pay")->with("gagal", $e->errorInfo[2]);
        }

        if($bill->status == "paid"){
            return redirect("/bill/pay")->with("gagal", "Billing has been paid");
        }

        return view("billing.payment", ["bill" => $bill, "user" => $user]);
    }

    public function pay(Request $request, $id){
        $billing = Billing::where('kode_billing', $id)->first();
        $nominal_pembayaran = str_replace(".", "", $request->nominal_pembayaran);
        $nominal_kembalian = str_replace(".", "", $request->nominal_kembalian);

        if($request->metode_pembayaran != "cash"){
            $nominal_pembayaran = $billing->total_biaya;
            $nominal_kembalian = 0;
            User::where("user_id", $request->session()->get("user_id"))->decrement("saldo", $nominal_pembayaran);
            MutasiPengguna::create([
                "kode_mutasi" => $id,
                "user_sender" => $request->session()->get("user_id"),
                "user_receiver" => "-",
                "nominal" => $nominal_pembayaran,
                "type" => "purchase",
                "in_out" => "out",
                "keterangan" => "Billing Payment",
                "status" => "success"
            ]);
        }
        try{
            Billing::where('kode_billing', $id)->update([
                "tanggal_bayar" => date("Y-m-d"),
                "metode_pembayaran" => $request->metode_pembayaran,
                "nominal_pembayaran" => $nominal_pembayaran,
                "nominal_kembalian" => $nominal_kembalian,
                "status" => "paid"
            ]);
    
            Invoice::where("kode_pembayaran", $id)->update([
                "status" => "paid",
                "metode_pembayaran" => $request->metode_pembayaran,
                "nominal_pembayaran" => $nominal_pembayaran,
                "nominal_kembalian" => $nominal_kembalian,
                "tanggal_bayar" => date("Y-m-d")
            ]);
        }catch(QueryException $e){
            return redirect("/bill/$id/payment")->with("gagal", $e->errorInfo[2]);
        }

        return redirect("/bill")->with("sukses", "Payment Success");
    }

    public function pay_index(Request $request){
       
        return view("billing.pay");
    }

    public function download_pdf($id){
        $billing = Billing::where('kode_billing', $id)->first();
        $detail_billing = Invoice::where("kode_pembayaran", $id)->get();

        $pdf = PDF::loadView("billing.view_pdf", ["billing" => $billing, "detail_billing" => $detail_billing]);

        return $pdf->download("$id.pdf");
        // return view("billing.view_pdf", ["billing" => $billing, "detail_billing" => $detail_billing]);
    }
}
