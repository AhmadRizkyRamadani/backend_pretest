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
            <h1 class="m-0">Billing Detail</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Bill</a></li>
                <li class="breadcrumb-item active">Billing Detail</li>
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
                    <table class="table table-bordered" id="produkListsTable">
                        <thead>
                            <tr>
                                <th class="text-center">Invoice Code</th>
                                <th class="text-center">User</th>
                                <th class="text-center">Total</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($invoices as $i)
                            <tr>
                                <td class="text-center" style="vertical-align: middle;">{{$i->kode_transaksi}}</td>
                                <td class="text-center" style="vertical-align: middle;">{{$i->user->name}}</td>
                                <td class="text-center" style="vertical-align: middle;">{{number_format($i->total_biaya, 0, ',', '.')}}</td>
                                <td class="text-center" style="vertical-align: middle;"><span class="badge badge-{{$i->status == 'pending'?'warning':($i->status == 'cancel'?'danger':'success')}}">{{ucfirst($i->status)}}</span></td>
                                <td class="text-center">
                                    <ul class="table-controls">
                                        <li><a href="/invoice/{{$i->kode_transaksi}}/detail" data-toggle="tooltip" data-placement="top" title="Detail"><i class="fa fa-eye btn-default"></i></a></li>
                                    </ul>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    <div class="row">
                        <div class="col-6">
                            <span style="font-size: 20px">Total : </span> <span id="totalBiayaSpan">{{number_format($bill->total_biaya, 0, ',', '.')}}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection