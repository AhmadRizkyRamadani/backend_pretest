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
            <h1 class="m-0">Create Invoice</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Invoice</a></li>
                <li class="breadcrumb-item active">Create Invoice</li>
            </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<form action="/invoice/store" method="POST" id="form1">
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
                                <th class="text-center">Product Code</th>
                                <th class="text-center">Product Name</th>
                                <th class="text-center">Price</th>
                                <th class="text-center">Quantity</th>
                                <th class="text-center">Subtotal</th>
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
                                <a href="/invoice" class="btn btn-danger">Cancel</a>
                                <button type="submit" class="btn btn-primary" value="save" id="save">Save</button>
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
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Product Lists</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-responsive-bordered" id="produkTable">
                    <thead>
                        <tr>
                            <th class="text-center">Product Code</th>
                            <th class="text-center">Product Name</th>
                            <th class="text-center">Price</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $p)
                        <tr>
                            <td class="text-center">{{$p->kode_produk}}</td>
                            <td class="text-center">{{$p->nama_produk}}</td>
                            <td class="text-center">{{number_format($p->harga, 0, ',', '.')}}</td>
                            <td class="text-center" style="text-align: center; vertical-align: middle;">
                                <ul class="table-controls">
                                    <li><a href="#" class="add-product" data="{{$p}}" id-produk="{{$p->kode_produk}}"><i class="fa fa-plus"></i></a></li>
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
    // window.onbeforeunload = function(){
    //     return confirm("Data yang telah anda isi akan hilang. Lanjutkan?");
    // }

    let rowCount = 1;

    $("#hargaSatuan").keyup(function(){
        this.value = formatRupiah(this.value);
        counting();
    });

    $("#jumlah").keyup(function(){
        this.value = formatRupiah(this.value);
        counting();
    });

    $("#save").click(function(){
        if($("#produkListsTable > tbody > tr").length < 1){
            alert("Add minimal 1 product to save this data");
            return false;
        }
    })

    let id_row = 1;

    $(".add-product").click(function(){
        let data = JSON.parse(this.getAttribute("data"));
        let row =   '<tr>'+
                    '<td class="text-center"><input type="text" value="'+data.kode_produk+'" name="kode_produk[]" id-row="'+id_row+'" class="form-control input-transparent" readonly></td>'+
                    '<td class="text-center"><input type="text" value="'+data.nama_produk+'" id-row="'+id_row+'" class="form-control input-transparent" readonly></td>'+
                    '<td class="text-center"><input type="text" value="'+formatRupiah(String(data.harga))+'" name="harga[]" id-row="'+id_row+'" class="form-control input-transparent harga" readonly></td>'+
                    '<td class="text-center"><input type="number" value="1" name="jumlah[]" id-row="'+id_row+'" class="form-control jumlah"></td>'+
                    '<td class="text-center"><input type="text" value="'+formatRupiah(String(data.harga))+'" name="subtotal[]" id-row="'+id_row+'" class="form-control input-transparent subtotal" readonly></td>'+
                    '<td class="text-center"><ul class="table-controls">'+
                        '<li><a href="#" id-row="'+id_row+'" id-produk="'+data.kode_produk+'" class="delete-data" data-toggle="tooltip" data-placement="top" title="Delete"><i class="fa fa-trash" style="color: #ed263b;"></i></a></li>'+
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
            $(".add-product[id-produk="+id_produk+"]").css("display", "inline-block");
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