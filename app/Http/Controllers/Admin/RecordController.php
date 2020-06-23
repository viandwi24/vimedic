<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\Recipe;
use App\Models\Record;
use App\Services\Select2;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class RecordController extends Controller
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
            $records = Record::with('doctor', 'patient', 'recipe')->get();
            return DataTables::of($records)
                ->addColumn('action', function (Record $record) {
                    $record_json = "onclick='vm.editModal(" .
                        json_encode($record).
                        ")'";
                    $record_action_qrcode = "onclick='vm.printQrcode(" .
                        json_encode($record).
                        ")'";
                    return '
                        <div class="text-center">
                            <button '.$record_action_qrcode.' type="button" class="btn btn-sm btn-primary">
                                <i class="fa fa-qrcode"></i>
                            </button>
                            <button '.$record_json.' type="button" class="btn btn-sm btn-warning">
                                <i class="fa fa-edit"></i>
                            </button>
                            <form method="post" action="'. route('admin.record.destroy', [$record->id]) .'" style="display:inline;">'.csrf_field().method_field('delete').'<button class="btn btn-sm btn-danger btn-delete"><i class="fa fa-trash"></i></button>
                        </dviv>
                    ';
                })
                ->make();
        }

        // get patient data anc convert to Select2 pattern
        $doctors = User::select('id', 'name')->where('role', 'doctor')->get();
        $doctors = (new Select2)->data($doctors)->pattern(['id', 'name']);
        $patients = Patient::select('id', 'name')->get();
        $patients = (new Select2)->data($patients)->pattern(['id', 'name']);
        $recipes = Recipe::select('id', 'code')->get();
        $recipes = (new Select2)->data($recipes)->pattern(['id', 'code']);

        // return view
        return view('pages.admin.record', compact('doctors', 'patients', 'recipes'));
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
            'patient_id' => 'required|integer|min:1|exists:patients,id',
            'recipe_id' => 'required|integer|min:1|exists:recipes,id',
            'checkup' => 'required|string|min:1',
            'diagnosis' => 'required|string|min:1',
            'action' => 'required|string|min:1',
            'cost' => 'required|integer|min:1',
        ]);

        $data = $request->only(
            'patient_id', 'checkup', 'recipe_id',
            'diagnosis', 'action', 'cost'
        );
        $data['code'] = Str::random(10) . Carbon::now()->timestamp;

        if (auth()->check() && auth()->user()->role == "doctor")
        {
            $data['doctor_id'] = auth()->user()->id;
        } else {
            $request->validate(['doctor_id' => 'required|integer|min:1|exists:users,id']);
            $data['doctor_id'] = $request->doctor_id;
        }

        $store = Record::create($data);

        return redirect()->route('admin.record.index')->with('alert', ['type' => 'success', 'text' => 'Save data successfully.']);
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
    public function update(Request $request, Record $record)
    {
        $request->validate([
            'patient_id' => 'required|integer|min:1|exists:patients,id',
            'recipe_id' => 'required|integer|min:1|exists:recipes,id',
            'checkup' => 'required|string|min:1',
            'diagnosis' => 'required|string|min:1',
            'action' => 'required|string|min:1',
            'cost' => 'required|integer|min:1',
        ]);

        $data = $request->only(
            'patient_id', 'doctor_id', 'checkup', 'recipe_id',
            'diagnosis', 'action', 'cost'
        );

        if (auth()->check() && auth()->user()->role == "doctor")
        {
            $data['doctor_id'] = auth()->user()->id;
        } else {
            $request->validate(['doctor_id' => 'required|integer|min:1|exists:users,id']);
            $data['doctor_id'] = $request->doctor_id;
        }

        $update = $record->update($data);

        return redirect()->route('admin.record.index')->with('alert', ['type' => 'success', 'text' => 'Update data successfully.']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Record $record)
    {
        $record->delete();
        return redirect()->route('admin.record.index')->with('alert', ['type' => 'success', 'text' => 'Delete data successfully.']);
    }
}
