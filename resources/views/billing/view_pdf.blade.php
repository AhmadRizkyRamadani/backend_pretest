<!DOCTYPE html>
<head>
    <title>Invoice</title>
    <style>
        table, td, th {  
            border: 1px solid #ddd;
            text-align: left;
            }

            table {
            border-collapse: collapse;
            width: 100%;
            }

            th, td {
            padding: 15px;
            }
    </style>
</head>
<body>
    <table>
        <div class="invoice p-3 mb-3">
            <!-- title row -->
            <div class="row">
            <div class="col-12">
                <h4>
                <i class="fas fa-sticky-note"></i> BE Pretest | Invoice Report
                <span style="float:right"><small class="float-right">Date: {{date("d-M-Y H:i:s")}}</small></span>
                </h4>
            </div>
            <!-- /.col -->
            </div>
            <!-- info row -->
            <div class="row invoice-info">
            <div class="col-sm-4 invoice-col">
                <div>
                    <label for=""><strong>Billing Code : </strong></label>
                    <strong><span>{{$billing->kode_billing}}</span></strong>
                    <span style="float:right"><b>Payment Date:</b> {{date_format(date_create($billing->tanggal_bayar), "d-m-Y")}}<br></span>
                </div>
            </div>
            <!-- /.col -->
            <div class="col-sm-4 invoice-col">
                <div>
                    <label for=""><strong>User : </strong></label>
                    <span>{{$billing->user->name}}</span>
                </div>
                <div>
                    <b>Payment Status:</b> {{strtoupper($billing->status)}}<br>
                </div>
            </div>
            <!-- /.col -->
            <div class="col-sm-4 invoice-col">
                <div>
                    <b>Invoice Total : </b> {{count($detail_billing)}}
                </div>
                <div>
                    <b>Payment Method:</b> {{strtoupper($billing->metode_pembayaran)}}
                </div>
            </div>
            <!-- /.col -->
            </div>
            <!-- /.row -->

            <!-- Table row -->
            <div class="row">
            <div class="col-12 table-responsive">
                
            <center><h4>Invoice Lists</h4></center>
                <table class="table table-bordered">
                <thead>
                <tr>
                    <th class="text-center" style="vertical-align: middle;" rowspan="2">Invoice Code</th>
                    <th class="text-center" colspan="5">Detail Invoice</th>
                </tr>
                <tr>
                    <th class="text-center">Product Code</th>
                    <th class="text-center">Product Name</th>
                    <th class="text-center">Quantity</th>
                    <th class="text-center">Price</th>
                    <th class="text-center">Subtotal</th>
                </tr>
                </thead>
                <tbody>
                    @foreach($detail_billing as $db)
                    <tr>
                        <td class="text-center" style="vertical-align:middle;" rowspan="{{count($db->detail_invoice)}}">{{$db->kode_transaksi}}</th>
                        @foreach($db->detail_invoice as $di)
                            <td class="text-center">{{$di->kode_produk}}</td>
                            <td class="text-center">{{$di->produk->nama_produk}}</td>
                            <td class="text-center">{{number_format($di->jumlah, 0, ',', '.')}}</td>
                            <td class="text-center">{{number_format($di->harga, 0, ',', '.')}}</td>
                            <td class="text-center">{{number_format($di->total, 0, ',', '.')}}</td>
                    </tr>
                    @endforeach
                    @endforeach
                </tbody>
                </table>
            </div>
            <!-- /.col -->
            </div>
            <!-- /.row -->

            <div class="row">
            <!-- accepted payments column -->
            <div class="col-6">
            </div>
            <!-- /.col -->
            <div class="col-6">
                <!-- <p class="lead">Amount Due 2/22/2014</p> -->
                <div class="table-responsive">
                <table class="table" style="text-align:right">
                    <tbody><tr>
                    <th style=" text-align: right;" colspan="6">Subtotal</th>
                    <td style="text-align:right">{{number_format($billing->total_biaya, 0, ',', '.')}}</td>
                    </tr>
                    <tr>
                    <th style=" text-align: right;" colspan="6">Payment Amount</th>
                    <td style="text-align:right">{{number_format($billing->nominal_pembayaran, 0, ',', '.')}}</td>
                    </tr>
                    <tr>
                    <th style="text-align: right;" colspan="6">Change</th>
                    <td style="text-align:right">{{number_format($billing->nominal_kembalian, 0, ',', '.')}}</td>
                    </tr>
                </tbody></table>
                </div>
            </div>
            <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>
    </table>
</body>