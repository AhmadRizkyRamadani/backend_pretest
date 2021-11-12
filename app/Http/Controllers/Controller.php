<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Facade\Ignition\QueryRecorder\Query;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function index(){
        return view("login.index");
    }

    public function set_session(Request $request){
        session()->put("user_id", $request->user_id);
        session()->put("name", $request->name);

        return redirect("/dashboard");
    }

    public function logout(){
        session()->forget("user_id");
        session()->forget("name");
        return redirect("/");
    }

    public function product(){
        $product = Product::all();
        return view("product.index", ["product" => $product]);
    }


    public function product_store(Request $request){
        try{
            $query = Product::create([
                "kode_produk" => $request->kode_produk,
                "nama_produk" => $request->nama_produk,
                "harga" => str_replace(".", "", $request->harga),
                "status" => 1
            ]);
        }catch(QueryException $e){
            return redirect("/product")->with("gagal", $e->errorInfo[2]);
        }

        if(!$query){
            return redirect("/product")->with("gagal", "Failed to save data.");
        }
        return redirect("/product")->with("sukses", "Data saved successfully");
    }

    public function product_delete(Request $request, $id){
        try{
            $query = Product::where("kode_produk", $id)->delete();
        }catch(QueryException $e){
            return redirect("/product")->with("gagal", $e->errorInfo[2]);
        }

        if(!$query){
            return redirect("/product")->with("gagal", "Failed to delete data.");
        }
        return redirect("/product")->with("sukses", "Data deleted successfully");
    }

    public function product_update(Request $request, $id){
        try{
            $query = Product::where("kode_produk", $id)->update([
                "kode_produk" => $request->kode_produk,
                "nama_produk" => $request->nama_produk,
                "harga" => str_replace(".", "", $request->harga)
            ]);
        }catch(QueryException $e){
            return redirect("/product")->with("gagal", $e->errorInfo[2]);
        }

        if(!$query){
            return redirect("/product")->with("gagal", "Failed to save data.");
        }
        return redirect("/product")->with("sukses", "Data saved successfully");
    }
}
