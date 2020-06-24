<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
        <title>Medic Record</title>
    </head>
    <body>
        <div class="container pt-4 pb-4">
            <div class="row justify-content-center">
                <div class="col-lg-8 col-sm-12">

                    <div>
                        <div class="text-center">
                            <div id="qrcode" style="padding: 1rem;background: #fff;display: inline-block;width: auto;"></div>
                        </div>
                    </div>

                    <div class="card shadow mb-4">
                        <div class="card-header">
                            <h5 class="mt-0 header-title">
                                Information
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="p-2">
                                        <form class="form-horizontal" role="form">
                                            <div class="form-group row">
                                                <label class="col-sm-2  col-form-label" for="simpleinput">Patient</label>
                                                <div class="col-sm-10">
                                                    <input name="name" value="{{ $record->patient->name }}" type="text" class="form-control" disabled>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-sm-2  col-form-label" for="simpleinput">Doctor</label>
                                                <div class="col-sm-10">
                                                    <input name="name" value="{{ $record->doctor->name }}" type="text" class="form-control" disabled>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-sm-2  col-form-label" for="simpleinput">Record Code</label>
                                                <div class="col-sm-10">
                                                    <input name="name" value="{{ $record->code }}" type="text" class="form-control" disabled>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="card shadow mb-4">
                        <div class="card-header">
                            <h5 class="mt-0 header-title">
                                Medic Record
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="p-2">
                                        <form class="form-horizontal" role="form">
                                            <div class="form-group row">
                                                <label class="col-sm-2  col-form-label" for="simpleinput">Check Date</label>
                                                <div class="col-sm-10">
                                                    <input name="name" value="{{ $record->check_date }}" type="text" class="form-control" disabled>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-sm-2  col-form-label" for="simpleinput">Checkup</label>
                                                <div class="col-sm-10">
                                                    <input name="name" value="{{ $record->checkup }}" type="text" class="form-control" disabled>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-sm-2  col-form-label" for="simpleinput">Diagnosis</label>
                                                <div class="col-sm-10">
                                                    <input name="name" value="{{ $record->diagnosis }}" type="text" class="form-control" disabled>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-sm-2  col-form-label" for="simpleinput">Action</label>
                                                <div class="col-sm-10">
                                                    <input name="name" value="{{ $record->action }}" type="text" class="form-control" disabled>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-sm-2  col-form-label" for="simpleinput">Cost</label>
                                                <div class="col-sm-10">
                                                    <input name="name" value="Rp {{ number_format($record->cost,2,',','.') }}" type="text" class="form-control" disabled>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="card shadow mb-4">
                        <div class="card-header">
                            <h5 class="mt-0 header-title" style="display: inline-block;">
                                <i class="mdi mdi-cart"></i>
                                Recipe
                            </h5>
                        </div>
                        <div class="card-body card-responsive">
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label>Note :</label>
                                        <textarea readonly class="form-control">{{ $record->recipe->note }}</textarea>
                                    </div>
                                    <div class="form-group">
                                        <label>Doctor :</label>
                                        <input type="text" value="{{ $record->recipe->doctor->name }}" class="form-control" readonly>
                                    </div>
                                    <table id="table" class="table table-bordered table-bordered dt-responsive nowrap">
                                        <thead>
                                            <th>#</th>
                                            <th>Medicine</th>
                                            <th>Stock</th>
                                            <th>Total</th>
                                        </thead>
                                        <tbody>
                                            @php $i = 1; @endphp
                                            @foreach ($record->recipe->medicines as $item)
                                                <tr>
                                                    <td>{{ $i++ }}</td>
                                                    <td>{{ $item->name }}</td>
                                                    <td>{{ $item->pivot->stock }}</td>
                                                    <td>Rp {{ $item->pivot->stock*$item->pivot->price }}</td>
                                                </tr>                                    
                                            @endforeach
                                            <tr>
                                                <th colspan="3" class="text-right pr-4">Total :</th>
                                                <th>Rp {{ $record->recipe->total_price }}</th>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
        <script src="https://cdn.rawgit.com/davidshimjs/qrcodejs/gh-pages/qrcode.min.js"></script>
        <script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
        <script>
            let qrcode = new QRCode("qrcode", {
                    text: "{{ route('record.link', [$record->code]) }}",
                    width: 128,
                    height: 128,
                    colorDark : "#000000",
                    colorLight : "#ffffff",
                    correctLevel : QRCode.CorrectLevel.H
                });
            function generateQr() {
                qrcode.makeCode($('textarea').val());
            }
            function saveQr() {
                html2canvas(document.querySelector("#qrcode")).then(canvas => {
                    $('#qrcode').css('padding-right', '1.5rem');
                    setTimeout(() => {
                        let link = document.createElement('a');
                        link.download = 'qrcode_transaction_{{ $record->code }}.png';
                        link.href = canvas.toDataURL("image/png").replace(/^data:image\/[^;]/, 'data:application/octet-stream');
                        link.click();
                        $('#qrcode').css('padding-right', '1rem');
                    }, 500);
                });
            }
        </script>
    </body>
</html>