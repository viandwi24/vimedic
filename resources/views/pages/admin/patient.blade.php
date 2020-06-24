@extends('layouts.admin', ['title' => "Patients"])

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fa fa-users mr-2"></i>
                            Patient List
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
                                <th>Name</th>
                                <th>Identity Number</th>
                                <th>Date of Birth</th>
                                <th>Address</th>
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
                <h5 class="modal-title" id="modalLabel" v-if="action == 'create'">Add New Patient</h5>
                <h5 class="modal-title" id="modalLabel" v-if="action == 'edit'">Edit Patient</h5>
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
                        <label>Name</label>
                        <input type="text" name="name" class="form-control" v-model="patient.name">
                    </div>
                    <div class="form-group">
                        <label>Identity Number</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Number</span>
                            </div>
                            <input type="number" min="0" name="identity_number" class="form-control" v-model="patient.identity_number">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Date of Birth</label>
                        <div class="input-group date" id="reservationdate" data-target-input="nearest">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Date</span>
                            </div>
                            <input type="text" name="birth" class="form-control dateofbirth datetimepicker-input" v-model="patient.birth" data-inputmask-alias="datetime" data-inputmask-inputformat="dd/mm/yyyy" data-mask>
                            <div class="input-group-append" data-target="#reservationdate" data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Address</label>
                        <textarea name="address" class="form-control" v-model="patient.address"></textarea>
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
@stop

@push('js')
    <script>
        const vm = new Vue({
            el: '#app',
            data: {
                action: 'create',
                patient: {}
            },
            methods: {
                addModal() {
                    this.action = 'create';
                    this.patient = {
                        name: 'Example',
                        identity_number: null,
                        birth: "01/01/2020",
                        address: ""
                    }
                    $('.modal#modal').modal('show');
                },
                editModal(patient) {
                    this.action = 'edit';
                    this.patient = patient
                    $('.modal#modal').modal('show');
                },
                create() {
                    $('.modal#modal form').attr('action', "{{ route('admin.patient.store') }}").submit();
                },
                update() {
                    let id = this.patient.id;
                    $('.modal#modal form').attr('action', "{{ route('admin.patient.index') }}/" + id).submit();
                },
            }
        });

        $('#table').DataTable({
            ajax: "{{ route('admin.patient.index') }}",
            processing: true,
            order: [[0, 'asc']],
            columnDefs: [ { orderable: false, targets: [5] }, ],
            columns: [
                { render: (data, type, row, meta) => meta.row + meta.settings._iDisplayStart + 1 },
                { data: 'name' },
                { data: 'identity_number' },
                { data: 'birth' },
                { data: 'address' },
                { data: 'action' },
            ]
        });

        $(document).ready(() => {
            $('.dateofbirth').inputmask('dd/mm/yyyy', { 'placeholder': 'dd/mm/yyyy' });
            $('#reservationdate').datetimepicker({
                format: 'DD/MM/YYYY'
            });
        });
    </script>    
@endpush

@push('css-lib')
    <link rel="stylesheet" href="{{ asset('assets/plugins/daterangepicker/daterangepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
@endpush

@push('js-lib')
    <script src="{{ asset('assets/plugins/inputmask/min/jquery.inputmask.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/daterangepicker/daterangepicker.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
@endpush