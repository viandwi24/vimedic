@extends('layouts.admin', ['title' => "Records"])

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fa fa-users mr-2"></i>
                            Record List
                        </h3>

                        <span class="card-right-button">
                            <div class="dropdown">
                                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fa fa-cog"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                                    <a href="#" @click.prevent="addModal" class="dropdown-item">
                                        <i class="fa fa-plus mr-2"></i> New
                                    </a>
                                </div>
                            </div>
                        </span>
                    </div>
                    <div class="card-body">
                        <table id="table" class="table table-bordered table-hover">
                            <thead>
                                <th width="6%">#</th>
                                <th>Patient</th>
                                <th>Doctor</th>
                                <th>Record Code</th>
                                <th>Recipe Code</th>
                                <th>Check Date</th>
                                <th class="text-center" width="15%">...</th>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel" v-if="action == 'create'">Add New Record</h5>
                <h5 class="modal-title" id="modalLabel" v-if="action == 'edit'">Edit Record</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="" method="POST">
                    @csrf
                    <div v-if="action == 'edit'">
                        @method('PUT')
                    </div>
                    <div class="form-group">
                        <label>Doctor</label>
                        <select
                          name="doctor_id"
                          class="select2 form-control"
                          id="selectDoctor">
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Patient</label>
                        <select
                          name="patient_id"
                          class="select2 form-control"
                          id="selectPatient">
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Recipe</label>
                        <select
                          name="recipe_id"
                          class="select2 form-control"
                          id="selectRecipe">
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Checkup</label>
                        <input type="text" name="checkup" class="form-control" v-model="record.checkup" placeholder="Ex: check the stomach...">
                    </div>
                    <div class="form-group">
                        <label>Diagnosis</label>
                        <input type="text" name="diagnosis" class="form-control" v-model="record.diagnosis" placeholder="Ex: ulcer, maag...">
                    </div>
                    <div class="form-group">
                        <label>Action</label>
                        <input type="text" name="action" class="form-control" v-model="record.action" placeholder="Ex : taking medication...">
                    </div>
                    <div class="form-group">
                        <label>Cost</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Rp</span>
                            </div>
                            <input type="number" min="0" name="cost" class="form-control" v-model="record.cost">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Check Date</label>
                        <div class="input-group date" id="reservationdate" data-target-input="nearest">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Date</span>
                            </div>
                            <input type="text" name="check_date" class="form-control checkdate datetimepicker-input" data-inputmask-alias="datetime" data-inputmask-inputformat="dd/mm/yyyy" data-mask>
                            <div class="input-group-append" data-target="#reservationdate" data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" data-dismiss="modal" v-if="action == 'create'" @click.prevent="create">Create</button>
                <button type="button" class="btn btn-primary" data-dismiss="modal" v-if="action == 'edit'" @click.prevent="update">Save</button>
            </div>
        </div>
        </div>
    </div>
    <div id="qrcode" style="padding: 1rem;background: #fff;display: none;width: auto;"></div>
@stop

@push('js')
    <script>
        const vm = new Vue({
            el: '#app',
            data: {
                action: 'create',
                record: {},
                doctors: @JSON($doctors->array()),
                carts: [],
            },
            computed: {
                totalPriceCart: function() {
                    let total = 0;
                    this.carts.forEach(e => total += e.stock*e.price);
                    return total;
                }
            },
            methods: {
                loadSelect2() {
                    $('#selectDoctor').select2({ data: this.doctors });
                    // $('#selectPatient').select2({ data: this.patients });
                    // $('#selectRecipe').select2({ data: this.recipes });
                    $('#selectPatient').select2({
                        ajax: {
                            url: "{{ route('admin.patient.index') }}",
                            dataType: 'json',
                            delay: 250,
                            data: function (params) {
                                var query = { q: params.term };
                                return query;
                            },
                            processResults: function (data) {
                                return {
                                    results: data.data.map(e => { return {id: e.id, text: e.id + ' - ' + e.name} })
                                };
                            }
                        },
                        placeholder: 'Search for a patient with name',
                        minimumInputLength: 3,
                    });
                    $('#selectRecipe').select2({
                        ajax: {
                            url: "{{ route('admin.recipe.index') }}",
                            dataType: 'json',
                            delay: 250,
                            data: function (params) {
                                var query = { q: params.term };
                                return query;
                            },
                            processResults: function (data) {
                                return {
                                    results: data.data.map(e => { return {id: e.id, text: e.id + ' - ' + e.code} })
                                };
                            }
                        },
                        placeholder: 'Search for a recipe with code',
                        minimumInputLength: 3,
                    });

                    @if(auth()->check() && auth()->user()->role == "doctor")
                        $('#selectDoctor').attr('disabled', true);
                    @endif
                },
                addModal() {
                    this.action = 'create';
                    this.record = {
                        doctor_id: null,
                        patient_id: null,
                        recipe_id: null,
                        checkup: "",
                        diagnosis: "",
                        action: "",
                        cost: 1000,
                    }
                    $('.modal#modal form input[name=check_date]').val("01/01/2020");
                    this.loadSelect2();

                    @if(auth()->check() && auth()->user()->role == "doctor")
                        $('#selectDoctor').val({{ auth()->user()->id }}).trigger('change').trigger('click');
                    @endif

                    $('.modal#modal').modal('show');
                },
                editModal(record) {
                    this.action = 'edit';
                    this.record = record;
                    $('.modal#modal form input[name=check_date]').val(record.check_date);
                    this.loadSelect2();
                    this.carts = this.record.medicines;
                    $('#selectDoctor').val(this.record.doctor_id).trigger('change').trigger('click');
                    $('#selectPatient').append(`<option value="${record.patient.id}" selected="selected">${record.patient.id}&nbsp;-&nbsp;${record.patient.name}</option>`).trigger('change');
                    $('#selectRecipe').append(`<option value="${record.recipe.id}" selected="selected">${record.recipe.id}&nbsp;-&nbsp;${record.recipe.code}</option>`).trigger('change');
                    $('.modal#modal').modal('show');
                },
                create() {
                    $('.modal#modal form').attr('action', "{{ route('admin.record.store') }}").submit();
                },
                update() {
                    let id = this.record.id;
                    $('.modal#modal form').attr('action', "{{ route('admin.record.index') }}/" + id).submit();
                },
                printQrcode(record) {
                    let qrcode = new QRCode("qrcode", {
                        text: "{{ route('record.link', ['']) }}/" + record.code,
                        width: 128,
                        height: 128,
                        colorDark : "#000000",
                        colorLight : "#ffffff",
                        correctLevel : QRCode.CorrectLevel.H
                    });
                    var html = `
                    <div>
                        <table border="1" width="100%">
                            <tr>
                                <td style="padding: 2rem;text-align: center;vertical-align: middle;">
                                    <img id="qrcode" style="display: inline-block;">
                                </td>
                                <td style="padding: 2rem;">
                                    <table>
                                        <tr style="text-align: left;">
                                            <th>Doctor</th>
                                            <td>:</td>
                                            <td>${record.doctor.name}</td>
                                        </tr>
                                        <tr style="text-align: left;">
                                            <th>Patient</th>
                                            <td>:</td>
                                            <td>${record.patient.name}</td>
                                        </tr>
                                        <tr style="text-align: left;">
                                            <th>Record Code</th>
                                            <td>:</td>
                                            <td>${record.code}</td>
                                        </tr>
                                        <tr style="text-align: left;">
                                            <th>Recipe Code</th>
                                            <td>:</td>
                                            <td>${record.recipe.code}</td>
                                        </tr>
                                        <tr style="text-align: left;">
                                            <th>Check Date</th>
                                            <td>:</td>
                                            <td>${record.check_date}</td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </div>
                    `;
                    setTimeout(() => {
                        let w = window.open();
                        $(w.document.body).html(html);
                        $(w.document.body).find("#qrcode").attr('src', $(qrcode._el).find("img").attr('src'));
                        w.print();
                    }, 1000);
                }
            }
        });

        $('#table').DataTable({
            ajax: "{{ route('admin.record.index') }}",
            processing: true,
            serverSide: true,
            order: [[0, 'asc']],
            columnDefs: [ { orderable: false, targets: [3] }, ],
            columns: [
                { render: (data, type, row, meta) => meta.row + meta.settings._iDisplayStart + 1 },
                { data: 'patient.name' },
                { data: 'doctor.name' },
                { data: 'code' },
                { data: 'recipe.code' },
                { data: 'check_date' },
                { data: 'action' },
            ]
        });

        $(document).ready(() => {
            $('.checkdate').inputmask('dd/mm/yyyy', { 'placeholder': 'dd/mm/yyyy' });
            $('#reservationdate').datetimepicker({
                format: 'DD/MM/YYYY'
            });
        });
    </script>
@endpush

@push('css-lib')
    <link rel="stylesheet" href="{{ asset('assets/plugins/daterangepicker/daterangepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
@endpush

@push('js-lib')
    <script src="{{ asset('assets/plugins/inputmask/min/jquery.inputmask.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/daterangepicker/daterangepicker.js') }}"></script>
    <script src="{{ asset('assets/plugins/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="https://cdn.rawgit.com/davidshimjs/qrcodejs/gh-pages/qrcode.min.js"></script>
@endpush