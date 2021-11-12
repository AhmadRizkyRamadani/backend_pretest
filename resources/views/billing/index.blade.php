@extends("layouts.master")
@section("content")
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
            <h1 class="m-0">Bill</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Bill</a></li>
                <li class="breadcrumb-item active">Bill Lists</li>
            </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</section>
<section class="content">
    <div class="container-fluid">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <!-- <h3 class="card-title mt-2">List Klien</h3> -->
                <div class="float-right">
                    <a href="/bill/create" class="btn btn-primary" >Add Bill</a>
                </div>
            </div>
            <div class="card-body table-responsive">
                <table class="table table-responsive-bordered" id="productTable">
                    <thead>
                        <tr>
                            <th class="text-center">Bill Code</th>
                            <th class="text-center">User</th>
                            <th class="text-center">Total</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bills as $b)
                        <tr>
                            <td class="text-center">{{$b->kode_billing}}</td>
                            <td class="text-center">{{$b->user->name}}</td>
                            <td class="text-center">{{number_format($b->total_biaya, 0, ',', '.')}}</td>
                            <td class="text-center"><span class="badge badge-{{$b->status == 'pending'?'warning':($b->status == 'cancel'?'danger':'success')}}">{{ucfirst($b->status)}}</span></td>
                            <td class="text-center">
                                <ul class="table-controls">
                                    <!-- <li><a href="/bill/{{$b->kode_bill}}/delete" class="delete-data" data-toggle="tooltip" data-placement="top" title="Delete"><i class="fa fa-trash" style="color: #ed263b;"></i></a></li> -->
                                    <!-- <li><a href="/bill/{{$b->kode_bill}}/edit" class="btn-edit" data="{{$b}}" data-toggle="modal" data-target="#modal-default" data-placement="top" title="Edit"><i class="fa fa-edit"></i></a></li> -->
                                    <li><a href="/bill/{{$b->kode_billing}}/detail" data-toggle="tooltip" data-placement="top" title="Detail"><i class="fa fa-eye btn-default"></i></a></li>
                                    <li><a href="/bill/{{$b->kode_billing}}/download_pdf" data-toggle="tooltip" data-placement="top" title="Download PDF"><i class="fa fa-download btn-default"></i></a></li>
                                </ul>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
@endsection
@section("script")
<script>

    $(".delete-data").click(function(e){
        if(!confirm("Are you sure to delete this data?")){
            e.preventDefault();
        }
    });

    @if(session("sukses"))
        toastr.success('{{session("sukses")}}');
    @elseif(session("gagal"))
        toastr.error('{{session("gagal")}}');
    @endif
</script>
@endsection