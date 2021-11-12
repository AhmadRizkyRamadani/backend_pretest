@extends("layouts.master")
@section("content")
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
            <h1 class="m-0">Invoice</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Invoice</a></li>
                <li class="breadcrumb-item active">Invoice Lists</li>
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
                    <a href="/invoice/create" class="btn btn-primary" >Add Invoice</a>
                </div>
            </div>
            <div class="card-body table-responsive">
                <table class="table table-responsive-bordered" id="productTable">
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
                            <td class="text-center">{{$i->kode_transaksi}}</td>
                            <td class="text-center">{{$i->user->name}}</td>
                            <td class="text-center">{{number_format($i->total_biaya, 0, ',', '.')}}</td>
                            <td class="text-center"><span class="badge badge-{{$i->status == 'pending'?'warning':($i->status == 'cancel'?'danger':'success')}}">{{ucfirst($i->status)}}</span></td>
                            <td class="text-center">
                                <ul class="table-controls">
                                    <!-- <li><a href="/invoice/{{$i->kode_transaksi}}/delete" class="delete-data" data-toggle="tooltip" data-placement="top" title="Delete"><i class="fa fa-trash" style="color: #ed263b;"></i></a></li> -->
                                    <li><a href="/invoice/{{$i->kode_transaksi}}/edit" class="btn-edit" data="{{$i}}" data-toggle="modal" data-target="#modal-default" data-placement="top" title="Edit"><i class="fa fa-edit"></i></a></li>
                                    <li><a href="/invoice/{{$i->kode_transaksi}}/detail" data-toggle="tooltip" data-placement="top" title="Detail"><i class="fa fa-eye btn-default"></i></a></li>
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

<div class="modal fade" id="modal-default">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="/invoice/store" method="POST" id="form1">
                {{csrf_field()}}
                <input type="hidden" class="form-control" name="kode_klien" value='klien'>
                <div class="modal-header">
                    <h4 class="modal-title">Add Product</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label id="my-label">Product Code</label>
                        <input type="text" class="form-control" name="kode_produk">
                    </div>
                    <div class="form-group">
                        <label id="my-label">Product Name</label>
                        <input type="text" class="form-control" name="nama_produk">
                    </div>
                    <div class="form-group">
                        <label id="my-label">Price</label>
                        <input type="text" class="form-control" name="harga">
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="submit">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@section("script")
<script>
    $(document).ready(function(){
        $("#form1").validate({
            rules: {
                "kode_produk" : "required",
                "nama_produk" : "required",
                "harga" : "required",
            },
            messages: {
                "kode_produk" : "Product Code can't be empty",
                "nama_produk" : "Produk Name can't be empty",
                "harga" : "Price can't be empty",
            },
            errorElement: "div",
            errorPlacement: function ( error, element ) {
                // Add the `help-block` class to the error element
                error.addClass( "invalid-feedback" );

                if ( element.prop( "type" ) === "checkbox" ) {
                    $('#error-checkbox').append(error);
                } else
                if ( element.prop( "type" ) === "radio" ) {
                    $('#errorPembayaran').append(error);
                } else
                if ( element.hasClass("js-states")) {
                    error.insertAfter( element.next() );
                } else {
                    error.insertAfter( element );
                }

            },
            highlight: function ( element, errorClass, validClass ) {
                $( element ).parents( ".col-sm-5" ).addClass( "has-error" ).removeClass( "has-success" );
            },
            unhighlight: function (element, errorClass, validClass) {
                $( element ).parents( ".col-sm-5" ).addClass( "has-success" ).removeClass( "has-error" );
            },
            submitHandler: function(form) {
                if(confirm("Are you sure to save this data?")){
                    form.submit();
                }
            }
        });
    });

    $("input[name=harga").keyup(function(){
        this.value = formatRupiah(this.value);
    });

    $("#addBtn").click(function(){
        $("#form1").attr("action", $(this).attr("data-url"));
        $("input[name=kode_produk").val("");
        $("input[name=nama_produk").val("");
        $("input[name=harga").val("");
    });

    $(".btn-edit").click(function(){
        let data = JSON.parse(this.getAttribute("data"));
        console.log(data);
        $("#form1").attr("action", this.getAttribute("data-url"));
        $("input[name=kode_produk").attr("readonly", true);
        $("input[name=kode_produk").val(data.kode_produk);
        $("input[name=nama_produk").val(data.nama_produk);
        $("input[name=harga").val(data.harga);
    })

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