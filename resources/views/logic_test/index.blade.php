@extends("layouts.master")
@section("content")
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
            <h1 class="m-0">Tonase Pretest</h1>
            </div>
            <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Logic Test</a></li>
            </ol>
            </div>
        </div>
    </div>
</section>
<section class="content">
    <div class="container-fluid">
        <div class="card card-primary collapsed-card">
            <div class="card-header">
                <h3 class="card-title">Case</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i></button>
                </div>
            </div>
            <div class="card-body" style="display: none;">
                <p>Sebuah kapal memiliki bagian lambung Kanan, Kiri, dan Tengah. Setiap kontainer yang akan dimuat ke atas kapal memiliki nomer kontainer dengan 7 (tujuh) numeric. Petugas menaruh posisi kontainer di atas kapal dengan kriteria tertentu, sebagai berikut:</p>
                <table class="table table-responsive-bordered">
                    <thead>
                        <tr>
                            <th class="text-center">Posisi</th>
                            <th class="text-center">Ketentuan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Tengah</td>
                            <td>
                                <ul style="list-style-type: lower-latin;">
                                    <li>Id Bilangan Prima</li>
                                    <li>Tidak mengandung angka 0</li>
                                    <li>Apabila 3 digit awal dihapus, maka tetap menjadi bilangan prima</li>
                                </ul>
                            </td>
                        </tr>
                        <tr>
                            <td>Kanan</td>
                            <td>
                                <ul style="list-style-type: lower-latin;">
                                    <li>Id Bilangan Prima</li>
                                    <li>Tidak mengandung angka 0</li>
                                    <li>Apabila 3 (tiga) digit awal dihapus, 3 digit paling akhir merupakan bilangan yang sama</li>
                                </ul>
                            </td>
                        </tr>
                        <tr>
                            <td>Kiri</td>
                            <td>
                                <ul style="list-style-type: lower-latin;">
                                    <li>Id Bilangan Prima</li>
                                    <li>Tidak mengandung angka 0</li>
                                    <li>Apabila 3 (tiga) digit awal dihapus, 2 digit terakhir menjadi bilangan prima yang berurutan angkanya</li>
                                </ul>
                            </td>
                        </tr>
                        <tr>
                            <td>Reject</td>
                            <td>
                                <ul style="list-style-type: lower-latin;">
                                    <li>Selain Bilangan Prima</li>
                                    <li>Mengandung Angka 0</li>
                                </ul>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">Logic Test</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col col-md-3">
                        <div class="form-group">
                            <label for="user_input">Input Number</label>
                            <input type="number" class="form-control user-input" id="userInput">
                        </div>
                    </div>
                    <div class="col col-md-3" style="margin-top: 32px;">
                        <div class="form-group">
                            <button type="button" class="btn btn-primary" id="submit">Submit</button>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="result">Result</label>
                    <input type="text" class="form-control input-transparent" id="result" readonly>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@section("script")
<script>
    $("#submit").click(function(){
        $.ajax({
            url: "/logic_test",
            type: "get",
            dataType: "json",
            data: {
                "user_input" : $("#userInput").val()
            },
            success: function(res){
                console.log(res);
                $("#result").val(res.data);
            },
            error: function(err){
                console.log(err);
            }
        })
    })
</script>
@endsection