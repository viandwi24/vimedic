@extends('layouts.admin', ['title' => "Recipes"])

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fa fa-users mr-2"></i>
                            Recipe List
                        </h3>

                        @if (auth()->user()->role != "employee")
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
                        @endif
                    </div>
                    <div class="card-body">
                        <table id="table" class="table table-bordered table-hover">
                            <thead>
                                <th width="6%">#</th>
                                <th>Doctor</th>
                                <th>Code</th>
                                <th>Total Price</th>
                                <th>Status</th>
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
                <h5 class="modal-title" id="modalLabel" v-if="action == 'create'">Add New Recipe</h5>
                <h5 class="modal-title" id="modalLabel" v-if="action == 'edit'">Edit Recipe</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="" method="POST">
                    @csrf
                    <input type="hidden" name="carts">
                    <div v-if="action == 'edit'">
                        @method('PUT')
                        <div class="form-group">
                            <label>Status</label>
                            <select name="status" class="form-control" v-model="recipe.status">
                                <option value="not_yet_taken">Not Yet Taken</option>
                                <option value="already_taken">Already Taken</option>
                            </select>
                        </div>
                    </div>
                    @if (auth()->user()->role != "employee")
                        <div class="form-group">
                            <label>Doctor</label>
                            <select
                            name="doctor_id"
                            class="select2 form-control"
                            id="selectDoctor">
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Note</label>
                            <textarea name="note" class="form-control" v-model="recipe.note"></textarea>
                        </div>
                        <hr>
                        <h4>Carts</h4>
                        <div class="row mb-2">
                            <div class="col-10">
                                <select class="select2 form-control" id="selectMedicine"></select>
                            </div>
                            <div class="col-2">
                                <button type="button" @click.prevent="addCart" class="btn btn-success btn-block">
                                    <i class="fa fa-plus"></i>
                                </button>
                            </div>
                        </div>
                        <table class="table table-bordered table-hover">
                            <thead>
                                <th>Name</th>
                                <th>Stock</th>
                                <th>Total</th>
                            </thead>
                            <tbody>
                                <tr v-for="(item, i) in carts" :key="i">
                                    <td>@{{ item.name }}</td>
                                    <td>
                                        x <input type="number" class="form-control form-control-sm" min="1" style="width: 50px;display: inline-block;" v-model="item.stock" :max="item.max">
                                        <button class="btn btn-sm btn-danger" @click.prevent="delCart(i)">
                                            <i class="fa fa-times"></i>
                                        </button>
                                    </td>
                                    <td>
                                        Rp@{{ item.price*item.stock }}
                                    </td>
                                </tr>
                                <tr v-if="carts.length == 0" class="text-center">
                                    <td colspan="3">
                                        <b>No item found.</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-right">
                                        <b>Total : Rp @{{ totalPriceCart }}</b>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    @endif
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
                recipe: {},
                doctors: @JSON($doctors->array()),
                medicines: @JSON($medicines->array()),
                medicinesOriginal: @JSON($medicines->original()),
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
                    $('#selectMedicine').select2({ data: this.medicines });

                    @if(auth()->check() && auth()->user()->role == "doctor")
                        $('#selectDoctor').attr('disabled', true);
                    @endif
                },
                addModal() {
                    this.action = 'create';
                    this.recipe = {
                        doctor: null,
                        note: null,
                    }
                    this.loadSelect2();

                    @if(auth()->check() && auth()->user()->role == "doctor")
                        $('#selectDoctor').val({{ auth()->user()->id }}).trigger('change').trigger('click');
                    @endif

                    $('.modal#modal').modal('show');
                },
                editModal(recipe) {
                    this.action = 'edit';
                    this.recipe = recipe;
                    this.recipe.doctor = this.recipe.doctor_id;
                    this.loadSelect2();
                    this.carts = this.recipe.medicines;
                    $('#selectDoctor').val(this.recipe.doctor).trigger('change').trigger('click');
                    $('.modal#modal').modal('show');
                },
                create() {
                    $('.modal#modal form input[name=carts]').val( JSON.stringify(this.carts) );
                    $('.modal#modal form').attr('action', "{{ route('admin.recipe.store') }}").submit();
                },
                update() {
                    let id = this.recipe.id;
                    $('.modal#modal form input[name=carts]').val( JSON.stringify(this.carts) );
                    $('.modal#modal form').attr('action', "{{ route('admin.recipe.index') }}/" + id).submit();
                },
                
                addCart() {
                    let id = $('#selectMedicine').val();
                    let s = this.carts.find(e => e.id === id);
                    if (typeof s !== 'undefined') return s.stock++;
                    let medicine = this.medicinesOriginal.find(e => e.id === parseInt(id));
                    let name = medicine.name;
                    let price = medicine.price;
                    let max = medicine.stock;
                    console.log(medicine)
                    this.carts.push({ id, name, price, max, stock: 1 });
                },
                delCart(index) {
                    this.carts.splice(index, 1);
                }
            }
        });

        $('#table').DataTable({
            ajax: "{{ route('admin.recipe.index') }}",
            processing: true,
            serverSide: true,
            order: [[0, 'asc']],
            columnDefs: [ { orderable: false, targets: [3] }, ],
            columns: [
                { render: (data, type, row, meta) => meta.row + meta.settings._iDisplayStart + 1 },
                { data: 'doctor.name' },
                { data: 'code' },
                { data: 'total_price' },
                { data: 'status' },
                { data: 'action' },
            ]
        });
    </script>    
@endpush

@push('css-lib')
    <link rel="stylesheet" href="{{ asset('assets/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
@endpush

@push('js-lib')
    <script src="{{ asset('assets/plugins/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
@endpush