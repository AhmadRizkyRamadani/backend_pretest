@extends("layouts.master")
@section("css")
<style>
    #totalBiayaSpan{
        font-size: x-large;
    }
</style>
@endsection
@section("content")
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
            <h1 class="m-0">Detail Invoice</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="/invoice">Invoice</a></li>
                <li class="breadcrumb-item active">Detail Invoice</li>
            </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<div class="container">
    <div class="content">
        <div class="container-fluid">
        <div class="card card-primary card-outline">
                <div class="card-header">
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <label for="">Transaction Code</label>
                            <p><span>{{$invoice->kode_transaksi}}</span></p>
                        </div>
                        <div class="col-md-4">
                            <label for="">User</label>
                            <p><span>{{$invoice->user->name}}</span></p>
                        </div>
                        <div class="col-md-4">
                            <label for="">Status</label>
                            <p><span class="badge badge-{{$invoice->status == 'pending' ? 'warning':($invoice->status == 'proses' ? 'info':($invoice->status == 'batal' ? 'danger':'success'))}} custom-badge">{{strtoupper($invoice->status)}}</span></p>
                        </div>
                        <div class="col-md-4">
                            <label for="">Total Amount</label>
                            <p><span>Rp. {{number_format($invoice->total_biaya, 0, ',', '.')}}</span></p>
                        </div>
                        <div class="col-md-4">
                            <label for="">Payment Date</label>
                            <p><span>{{$invoice->tanggal_bayar}}</span></p>
                        </div>
                        <div class="col-md-4">
                            <label for="">Payment Code</label>
                            <p><span>{{$invoice->kode_pembayaran}}</span></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card card-primary card-outline">
                <div class="card-header">
                </div>
                <div class="card-body">
                    <table class="table table-bordered" id="produkListsTable">
                        <thead>
                            <tr>
                                <th class="text-center">Product Code</th>
                                <th class="text-center">Product Name</th>
                                <th class="text-center">Price</th>
                                <th class="text-center">Quantity</th>
                                <th class="text-center">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($detail_invoice as $di)
                            <tr>
                                <td class="text-center">{{$di->kode_produk}}</td>
                                <td class="text-center">{{$di->produk->nama_produk}}</td>
                                <td class="text-center">{{number_format($di->harga, 0, ',', '.')}}</td>
                                <td class="text-center">{{number_format($di->jumlah, 0, ',', '.')}}</td>
                                <td class="text-center">{{number_format($di->total, 0, ',', '.')}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection