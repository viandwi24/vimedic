<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Imports\MedicineImport;
use App\Models\Medicine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class MedicineController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax())
        {
            $medicines = Medicine::query();
            return DataTables::of($medicines)
                ->addColumn('action', function (Medicine $medicine) {
                    $medicine_json = "onclick='vm.editModal(" .
                        json_encode($medicine).
                        ")'";
                    $action = "!confirm('Delete this item?') ? event.preventDefault() : console.log(1)";
                    return '
                        <div class="text-center">
                            <button '.$medicine_json.' type="button" class="btn btn-sm btn-warning">
                                <i class="fa fa-edit"></i>
                            </button>
                            <form method="post" action="'. route('admin.medicine.destroy', [$medicine->id]) .'" style="display:inline;">'.csrf_field().method_field('delete').'<button onclick="'.$action.'" class="btn btn-sm btn-danger btn-delete"><i class="fa fa-trash"></i></button>
                        </dviv>
                    ';
                })
                ->make(true);
        }

        return view('pages.admin.medicine');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|min:3',
            'type' => 'required|string',
            'price' => 'required|integer',
            'stock' => 'required|integer',
        ]);

        $store = Medicine::create($request->only('name', 'type', 'price', 'stock'));

        return redirect()->route('admin.medicine.index')->with('alert', ['type' => 'success', 'text' => 'Save data successfully.']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Medicine $medicine)
    {
        $request->validate([
            'name' => 'required|string|min:3',
            'type' => 'required|string',
            'price' => 'required|integer',
            'stock' => 'required|integer',
        ]);

        $update = $medicine->update($request->only('name', 'type', 'price', 'stock'));

        return redirect()->route('admin.medicine.index')->with('alert', ['type' => 'success', 'text' => 'Update data successfully.']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Medicine $medicine)
    {
        $medicine->delete();
        return redirect()->route('admin.medicine.index')->with('alert', ['type' => 'success', 'text' => 'Delete data successfully.']);
    }



    public function import(Request $request)
    {
        $request->validate([ //csv,xls,xlsx
            'file' => 'required|file'
        ]);

        $file = $request->file('file');

        DB::transaction(function () use ($file) {
            Excel::import(new MedicineImport, $file);
        });

        return redirect()->route('admin.medicine.index')->with('alert', ['type' => 'success', 'text' => 'Import data successfully.']);
    }
}
