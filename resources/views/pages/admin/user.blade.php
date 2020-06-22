@extends('layouts.admin', ['title' => "Users"])

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fa fa-user mr-2"></i>
                            User List
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
                                    <a href="#" class="dropdown-item">
                                        <i class="fas fa-file-excel mr-2"></i> Import Excel
                                    </a>
                                </div>
                            </div>
                        </span>
                    </div>
                    <div class="card-body">
                        <table id="table" class="table table-bordered table-hover">
                            <thead>
                                <th width="6%">#</th>
                                <th>Role</th>
                                <th>Username</th>
                                <th>Name</th>
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
                <h5 class="modal-title" id="modalLabel" v-if="action == 'create'">Add New User</h5>
                <h5 class="modal-title" id="modalLabel" v-if="action == 'edit'">Edit User</h5>
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
                        <input type="text" name="name" class="form-control" v-model="user.name">
                    </div>
                    <div class="form-group">
                        <label>Role</label>
                        <select name="role" class="form-control" v-model="user.role">
                            <option v-for="(item, i) in role" :value="item.key">@{{ item.text }}</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Username</label>
                        <input type="text" name="username" class="form-control" v-model="user.username">
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <input type="text" name="password" class="form-control" v-model="user.password">
                        <span class="text-muted" v-if="action == 'edit'">* Type password when you want to change.</span>
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
                role: [
                    { key: 'admin', text: 'Admin' },
                    { key: 'employee', text: 'Employee' },
                    { key: 'doctor', text: 'Doctor' },
                ],
                user: {}
            },
            methods: {
                addModal() {
                    this.action = 'create';
                    this.user = {
                        name: 'Example',
                        username: '',
                        role: 'admin',
                        password: ''
                    }
                    $('.modal#modal').modal('show');
                },
                editModal(user) {
                    this.action = 'edit';
                    this.user = user
                    $('.modal#modal').modal('show');
                },
                create() {
                    $('.modal#modal form').attr('action', "{{ route('admin.user.store') }}").submit();
                },
                update() {
                    let id = this.user.id;
                    $('.modal#modal form').attr('action', "{{ route('admin.user.index') }}/" + id).submit();
                },
            }
        });
        $('#table').DataTable({
            ajax: "{{ route('admin.user.index') }}",
            processing: true,
            order: [[0, 'asc']],
            columnDefs: [ { orderable: false, targets: [3] }, ],
            columns: [
                { render: (data, type, row, meta) => meta.row + meta.settings._iDisplayStart + 1 },
                { data: 'role' },
                { data: 'username' },
                { data: 'name' },
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