@extends("layouts.master")
@section("css")
<style>
    .btn{
        width: 100px;
    }
</style>
@endsection
@section("content")
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
            <h1 class="m-0">Pay Bill</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Bill</a></li>
                <li class="breadcrumb-item active">Pay Bill</li>
            </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</section>
<section class="content">
    <form action="/bill/{{$bill->kode_billing}}/pay" method="POST" id="form1">
        {{csrf_field()}}
        <div class="container-fluid">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title mt-2">Payment Method</h3>
                    <!-- <div class="float-right">
                        <a href="/bill/create" class="btn btn-primary" >Add Bill</a>
                    </div> -->
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <span>Total Amount : <h1>{{number_format($bill->total_biaya, 0, ',', '.')}}</h1></span>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="icheck-primary d-inline mb-4">
                                    <input type="radio" id="cash" checked="" value="cash" name="metode_pembayaran">
                                    <label for="cash">Cash
                                    </label>
                                </div>
                                <div class="ml-4 icheck-primary d-inline">
                                    <input type="radio" id="myWalet" value="mywalet" name="metode_pembayaran">
                                    <label for="myWalet">My Wallet
                                    </label>
                                </div>
                            </div>
                            <div class="form-group" id="cashForm">
                                <div class="row">
                                    <div class="col">
                                        <input type="text" name="nominal_pembayaran" class="form-control nominal-pembayaran" placeholder="Payment Amount">
                                    </div>
                                    <div class="col">
                                        <input type="text" name="nominal_kembalian" class="form-control nominal-kembalian" placeholder="Return Amount" value="0" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group" id="waletForm" style="display: none;">
                                <span>Current Saldo : <b>{{number_format($user->saldo, 0, ',', '.')}}</b></span>
                                <div class="error-feedback" style="color:red; display: none;">Saldo not enough</div>
                            </div>
                            <div class="form-group float-right">
                                <a href="/bill" class="btn btn-default">Back</a>
                                <button type="submit" class="btn btn-primary" id="submit">Pay</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</section>
@endsection
@section("script")
<script>

    let dontsubmit = true;
    let msg = "Payment Amount can't be empty";

    $(".delete-data").click(function(e){
        if(!confirm("Are you sure to delete this data?")){
            e.preventDefault();
        }
    });

    $("#cash").click(function(){
        $("#cashForm").show();
        $("#waletForm").hide();
        finalCounting();
    });

    $("#myWalet").click(function(){
        $("#cashForm").hide();
        $("#waletForm").show();
        finalCounting();
    });

    $(".nominal-pembayaran").keyup(function(){
        let nomminalPembayaran = Number($(this).val().split(".").join(""));
        let saldo = Number("{{$user->saldo}}");
        let selectedPayment = $("input[type=radio]:checked").val();
        
        this.value = formatRupiah(this.value);

        if(selectedPayment == "cash"){
            if(nomminalPembayaran >= Number("{{$bill->total_biaya}}")){
                $("#submit").attr('disabled', false);
                dontsubmit = false;
            }else{
                $("#submit").attr('disabled', true);
                dontsubmit = true;
                msg = "Payment amount not enough."
            }
        }else{
            if(saldo >= Number("{{$bill->total_biaya}}")){
                $(".error-feedback").css("display", "none");
                $("#submit").attr("disabled", false);
            }else{
                kembalian.val(0);
                $(".error-feedback").css("display", "block");
                $("#submit").attr("disabled", true);
            }
        }

        finalCounting();
    });

    function finalCounting(){
        let kembalian = $(".nominal-kembalian");
        let nomminalPembayaran = Number($(".nominal-pembayaran").val().split(".").join(""));
        let saldo = Number("{{$user->saldo}}");
        let selectedPayment = $("input[type=radio]:checked").val();

        if(selectedPayment == "cash"){
            if(nomminalPembayaran >= Number("{{$bill->total_biaya}}")){
                kembalian.val(formatRupiah(String(nomminalPembayaran - Number("{{$bill->total_biaya}}"))));
            }else{
                kembalian.val(0);
            }
        }else{
            if(saldo >= Number("{{$bill->total_biaya}}")){
                kembalian.val(formatRupiah(String(saldo - Number("{{$bill->total_biaya}}"))));
                $(".error-feedback").css("display", "none");
            }else{
                kembalian.val(0);
                $(".error-feedback").css("display", "block");
                $("#submit").attr("disabled", true);
            }
        }
    }

    $(document).ready(function(){

        $("#form1").validate({
            rules: {
                "nominal_pembayaran" : {
                    required : ($(".nominal-pembayaran").val() == ""),
                },
            },
            messages: {
                "nominal_pembayaran" : {
                    required : msg,
                },
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