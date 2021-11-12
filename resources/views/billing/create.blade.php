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
            <h1 class="m-0">Create Bill</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Bill</a></li>
                <li class="breadcrumb-item active">Create Bill</li>
            </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<form action="/bill/store" method="POST" id="form1">
    {{csrf_field()}}
<div class="container">
    <div class="content">
        <div class="container-fluid">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <!-- <h3 class="card-title mt-2">Add Product</h3> -->
                    <div class="float-right">
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-default">Add Product</button>
                    </div>
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
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    <div class="row">
                        <div class="col-6">
                            <input type="hidden" name="total_biaya" value="0" id="totalBiaya">
                            <span style="font-size: 20px">Total : </span> <span id="totalBiayaSpan">{{number_format(0, 0, ',', '.')}}</span>
                        </div>
                        <div class="col-6">
                            <div class="float-right">
                                <a href="/bill" type="submit" class="btn btn-danger">Cancel</a>
                                <button type="submit" name="submit" class="btn btn-warning" value="pay_later">Pay Later</button>
                                <button type="submit" name="submit"class="btn btn-primary" value="pay_now" id="save">Pay Now</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</form>

<div class="modal fade" id="modal-default">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Invoice Lists</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-responsive-bordered" id="produkTable" style="overflow-x: auto;">
                    <thead>
                        <tr>
                            <th class="text-center">Invoice Code</th>
                            <th class="text-center">User</th>
                            <th class="text-center">Total Amount</th>
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
                            <td class="text-center" style="text-align: center; vertical-align: middle;">
                                <ul class="table-controls">
                                    <li><a href="#" class="add-invoice" data="{{$i}}" user-name="{{$i->user->name}}" id-produk="{{$i->kode_transaksi}}"><i class="fa fa-plus"></i></a></li>
                                </ul>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="modal-footer float-right">
                <button type="button" class="btn btn-default" data-dismiss="modal" id="closeBtn">Close</button>
                <!-- <button type="button" class="btn btn-primary" id="submit">Simpan</button> -->
            </div>
        </div>
    </div>
</div>
@endsection
@section("script")
<script>

    let rowCount = 1;

    $("#save").click(function(){
        if($("#produkListsTable > tbody > tr").length < 1){
            alert("Add minimal 1 invoice to save this data");
            return false;
        }
    })

    let id_row = 1;

    $(".add-invoice").click(function(){
        let data = JSON.parse(this.getAttribute("data"));
        let user_name = this.getAttribute("user-name");
        let badge = "warning";

        if(data.status == "cancel"){
            badge = "danger";
        }else if(data.status == "paid"){
            badge = "success";
        }
        let row =   '<tr>'+
                    '<td class="text-center" style="vertical-align: middle;"><input type="text" value="'+data.kode_transaksi+'" name="kode_transaksi[]" id-row="'+id_row+'" class="form-control input-transparent" readonly></td>'+
                    '<td class="text-center" style="vertical-align: middle;"><span>'+user_name+'</span></td>'+
                    '<td class="text-center" style="vertical-align: middle;"><input type="text" value="'+formatRupiah(String(data.total_biaya))+'" name="subtotal[]" id-row="'+id_row+'" class="form-control input-transparent subtotal" readonly></td>'+
                    '<td class="text-center" style="vertical-align: middle;"><span class="badge badge-'+badge+'">'+data.status.toUpperCase()+'</span></td>'+
                    '<td class="text-center" style="vertical-align: middle;"><ul class="table-controls">'+
                        '<li><a href="#" id-row="'+id_row+'" id-produk="'+data.kode_transaksi+'" class="delete-data" data-toggle="tooltip" data-placement="top" title="Delete"><i class="fa fa-trash" style="color: #ed263b;"></i></a></li>'+
                    '</ul></td>'+
                    '</tr>';
        $("#produkListsTable > tbody:last-child").prepend(row);
        $(this).css("display", "none");
        id_row++;
        runFunction();
        finalCounting();
    });

    function counting(){
        let jumlah = $(this).val().split(".").join("");
        let hargaSatuan = $("#hargaSatuan").val().split(".").join("");
        let subtotal = Number(jumlah) * Number(hargaSatuan);
        $("#subtotal").val(formatRupiah(String(subtotal)));
    }

    function finalCounting(){
        let total_biaya = 0;
        if($("#produkListsTable > tbody > tr").length > 0){
            let total_biaya = $(".subtotal").map((_, el) => el.value.split(".").join("")).get();
            // console.log(total_biaya.reduce(sum));
            total_biaya = total_biaya.reduce(sum);
            $("#totalBiaya").val(total_biaya);
            $("#totalBiayaSpan").html(formatRupiah(String(total_biaya)));
        }else{
            $("#totalBiaya").val("0");
            $("#totalBiayaSpan").html("0");
        }
    }

    function sum(total, num){
        console.log("Total : "+total);
        return Number(total.toString().split(".").join("")) + Number(num.toString().split(".").join(""));
    }

    function runFunction(){
        $(".jumlah").keyup(function(){
            let id_row = $(this).attr("id-row");
            let jumlah = $(this).val().split(".").join("");
            let hargaSatuan = $(".harga[id-row="+id_row+"]").val().split(".").join("");
            let subtotal = Number(jumlah) * Number(hargaSatuan);
            $(".subtotal[id-row="+id_row+"]").val(formatRupiah(String(subtotal)));
            finalCounting();
        });

        $(".delete-data").click(function(){
            let id_produk = this.getAttribute("id-produk");
            $(this).closest("tr").remove();
            $(".add-invoice[id-produk="+id_produk+"]").css("display", "inline-block");
            finalCounting();
        });
    }

    $(document).ready(function(){
        $("#form1").validate({
            rules: {
                "tgl_service" : "required",
                "pelapor" : "required",
                "kepala_petugas" : "required",
            },
            messages: {
                "tgl_service" : "Tanggal harus dipilih",
                "pelapor" : "Pelapor harus diisi",
                "kepala_petugas" : "Kepala Petugas harus dipilih",
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
                form.submit();
            }
        });
    })

    @if(session("sukses"))
        toastr.success('{{session("sukses")}}');
    @elseif(session("gagal"))
        toastr.error('{{session("gagal")}}');
    @endif
</script>
@endsection