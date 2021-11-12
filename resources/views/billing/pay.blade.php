@extends("layouts.master")
@section("content")
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
            <h1 class="m-0">Pay Bill</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Pay Bill</a></li>
                <!-- <li class="breadcrumb-item active">Bill Lists</li> -->
            </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</section>
<section class="content">
    <div class="container-fluid">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title mt-2">Input Billing ID</h3>
                <!-- <div class="float-right">
                    <a href="/bill/create" class="btn btn-primary" >Add Bill</a>
                </div> -->
            </div>
            <div class="card-body table-responsive">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="billingID">Billing ID</label>
                        <input type="text" class="form-control" name="billing_id" id="billingID" placeholder>
                    </div>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary btn-block" id="submit">Check Bill</button>
                </div>
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

    $("#submit").click(function(){
        window.location = "/bill/"+$("#billingID").val()+"/payment";
    });

    @if(session("sukses"))
        toastr.success('{{session("sukses")}}');
    @elseif(session("gagal"))
        toastr.error('{{session("gagal")}}');
    @endif
</script>
@endsection