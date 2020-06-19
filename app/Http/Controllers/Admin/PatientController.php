<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PatientController extends Controller
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
            $patients = Patient::query();
            return DataTables::eloquent($patients)
                ->addColumn('action', function (Patient $patient) {
                    $patient_json = "onclick='vm.editModal(" .
                        json_encode($patient).
                        ")'";
                    return '
                        <div class="text-center">
                            <button '.$patient_json.' type="button" class="btn btn-sm btn-warning">
                                <i class="fa fa-edit"></i>
                            </button>
                            <form method="post" action="'. route('admin.patient.destroy', [$patient->id]) .'" style="display:inline;">'.csrf_field().method_field('delete').'<button class="btn btn-sm btn-danger btn-delete"><i class="fa fa-trash"></i></button>
                        </dviv>
                    ';
                })
                ->make();
        }

        return view('pages.admin.patient');
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
            'identity_number' => 'required|integer|min:6',
            'birth' => 'required|date|date_format:d/m/Y',
            'address' => 'required|string|min:6'
        ]);
        
        $store = Patient::create($request->only('name', 'identity_number', 'birth', 'address'));

        return redirect()->route('admin.patient.index')->with('alert', ['type' => 'success', 'text' => 'Save data successfully.']);
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
    public function update(Request $request, Patient $patient)
    {
        $request->validate([
            'name' => 'required|string|min:3',
            'identity_number' => 'required|integer|min:6',
            'birth' => 'required|date_format:d/m/Y',
            'address' => 'required|string|min:6'
        ]);

        $update = $patient->update($request->only('name', 'identity_number', 'birth', 'address'));

        return redirect()->route('admin.patient.index')->with('alert', ['type' => 'success', 'text' => 'Update data successfully.']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Patient $patient)
    {
        $patient->delete();
        return redirect()->route('admin.patient.index')->with('alert', ['type' => 'success', 'text' => 'Delete data successfully.']);
    }
}
