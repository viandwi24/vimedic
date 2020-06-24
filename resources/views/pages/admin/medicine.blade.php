@extends('layouts.admin', ['title' => "Medicines"])

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fa fa-pills mr-2"></i>
                            Medicine List
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
                                    <a href="#" @click.prevent="openImport" class="dropdown-item">
                                        <i class="fas fa-file-excel mr-2"></i> Import Excel
                                    </a>
                                    <form id="import" action="{{ route('admin.medicine.import') }}" method="POST" style="display: none;" enctype="multipart/form-data">
                                        @csrf
                                        <input type="file" name="file" @change="importExcel" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
                                    </form>
                                </div>
                            </div>
                        </span>
                    </div>
                    <div class="card-body">
                        <table id="table" class="table table-bordered table-hover">
                            <thead>
                                <th width="6%">#</th>
                                <th>Name</th>
                                <th>Type</th>
                                <th>Price</th>
                                <th>Stock</th>
                                <th class="text-center">...</th>
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
                <h5 class="modal-title" id="modalLabel" v-if="action == 'create'">Add New Medicine</h5>
                <h5 class="modal-title" id="modalLabel" v-if="action == 'edit'">Edit Medicine</h5>
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
                        <input placeholder="Medicine Name..." type="text" name="name" class="form-control" v-model="medicine.name">
                    </div>
                    <div class="form-group">
                        <label>Type</label>
                        <select name="type" class="form-control" v-model="medicine.type">
                            <option v-for="(item, i) in type" :value="item.key">@{{ item.text }}</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Price</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Rp</span>
                            </div>
                            <input type="number" min="1" name="price" class="form-control" v-model="medicine.price">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Stock</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Count</span>
                            </div>
                            <input type="number" min="1" name="stock" class="form-control" v-model="medicine.stock">
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
@stop

@push('js')
    <script>
        const vm = new Vue({
            el: '#app',
            data: {
                action: 'create',
                type: [
                    { key: 'capsules', text: 'Capsules' },
                    { key: 'tablet', text: 'Tablet' },
                    { key: 'liquid', text: 'Liquid' },
                    { key: 'drops', text: 'Drops' },
                    { key: 'injections', text: 'Injections' },
                ],
                medicine: {}
            },
            methods: {
                addModal() {
                    this.action = 'create';
                    this.medicine = {
                        name: '',
                        price: 1000,
                        type: 'capsules',
                        stock: 1,
                    }
                    $('.modal#modal').modal('show');
                },
                editModal(medicine) {
                    this.action = 'edit';
                    this.medicine = medicine
                    $('.modal#modal').modal('show');
                },
                create() {
                    $('.modal#modal form').attr('action', "{{ route('admin.medicine.store') }}").submit();
                },
                update() {
                    let id = this.medicine.id;
                    $('.modal#modal form').attr('action', "{{ route('admin.medicine.index') }}/" + id).submit();
                },
                openImport() {
                    $('form#import input').trigger('click');
                },
                importExcel(event) {
                    if(!event.target.files.length) return 
                    $('form#import').submit();
                }
            }
        });
        $('#table').DataTable({
            ajax: "{{ route('admin.medicine.index') }}",
            processing: true,
            serverSide: true,
            order: [[0, 'asc']],
            columnDefs: [ { orderable: false, targets: [5] }, ],
            columns: [
                { render: (data, type, row, meta) => meta.row + meta.settings._iDisplayStart + 1 },
                { data: 'name' },
                { data: 'type' },
                { data: 'price' },
                { data: 'stock' },
                { data: 'action' },
            ]
        });
    </script>    
@endpush

@push('css-lib')
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
@endpush

@push('js-lib')
    <script src="{{ asset('assets/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
@endpush