<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Medicine;
use App\Models\Patient;
use App\Models\Recipe;
use App\Services\Select2;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class RecipeController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:admin,doctor,employee')->only('index', 'update');
        $this->middleware('role:admin,doctor')->except('index', 'update');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax())
        {
            $recipes = Recipe::with('doctor')->get();
            return DataTables::of($recipes)
                ->addColumn('action', function (Recipe $recipe) {
                    $recipe_arr = $recipe->toArray();
                    $medicines = [];
                    foreach($recipe->medicines as $item)
                    {
                        $medicines[] = [
                            'id' => $item->id,
                            'name' => $item->name,
                            'max' => $item->stock,
                            'price' => $item->pivot->price,
                            'stock' => $item->pivot->stock,
                        ];
                    }
                    $recipe_arr['medicines'] = $medicines;
                    $recipe_json = "onclick='vm.editModal(" .
                        json_encode($recipe_arr).
                        ")'";
                    
                    
                    $action = "!confirm('Delete this item?') ? event.preventDefault() : console.log(1)";
                    $delete_el = (auth()->user()->role != "employee") ? '<form method="post" action="'. route('admin.recipe.destroy', [$recipe->id]) .'" style="display:inline;">'.csrf_field().method_field('delete').'<button onclick="'.$action.'" class="btn btn-sm btn-danger btn-delete"><i class="fa fa-trash"></i></button>' : '';

                    return '
                        <div class="text-center">
                            <button '.$recipe_json.' type="button" class="btn btn-sm btn-warning">
                                <i class="fa fa-edit"></i>
                            </button>
                            ' .
                            $delete_el .
                            '
                        </dviv>
                    ';
                })
                ->make();
        }

        // get patient data anc convert to Select2 pattern
        $doctors = User::select('id', 'name')->where('role', 'doctor')->get();
        $doctors = (new Select2)->data($doctors)->pattern(['id', 'name']);
        $medicines = Medicine::select('id', 'name', 'stock', 'price')->get();
        $medicines = (new Select2)->data($medicines)->pattern(['id', 'name']);

        // return view
        return view('pages.admin.recipe', compact('doctors', 'medicines'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
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
            'carts' => 'required|json',
            'note' => 'required|nullable|string',
        ]);

        if (auth()->check() && auth()->user()->role == "doctor")
        {
            $doctor_id = auth()->user()->id;
        } else {
            $request->validate(['doctor_id' => 'required|integer|exists:users,id']);
            $doctor_id = $request->doctor_id;
        }

        $carts = json_decode($request->carts);

        $carts_collection = new Collection($carts);

        // 
        $medicine_cart = Medicine::whereIn("id", $carts_collection->pluck('id'))->get();
        $cart_transaction = [];
        foreach($medicine_cart as $medicine) {
            $cart = array_search($medicine->id, array_column($carts, 'id') );
            if ($cart !== false) {
                $cart_transaction[$medicine->id] = [
                    'medicine_id' => $medicine->id,
                    'stock' => $carts[$cart]->stock,
                    'price' => $medicine->price
                ];
            }
        }

        $total_price = 0;
        foreach ($cart_transaction as $item) { $total_price += ($item['price']*$item['stock']); }
        
        DB::transaction(function () use ($total_price, $cart_transaction, $request, $doctor_id) {

            // random code unique
            $code = Str::random(10) . Carbon::now()->timestamp;
            while (Recipe::where('code', $code)->get()->count() > 0) {
                $code = Str::random(10) . Carbon::now()->timestamp;
            }

            $transaction = Recipe::create([
                "code" => $code,
                "doctor_id" => $doctor_id,
                "total_price" => $total_price,
                "note" => $request->note,
            ]);
            $transaction->medicines()->sync($cart_transaction);
            foreach($cart_transaction as $cart) {
                $medicine = Medicine::findOrFail($cart['medicine_id']);
                if ($medicine->stock != 0) $medicine->decrement('stock', $cart['stock']);
            }
        });

        return redirect()->route('admin.recipe.index')->with('alert', ['type' => 'success', 'text' => 'Save data successfully.']);
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
    public function update(Request $request, Recipe $recipe)
    {
        $request->validate([
            'carts' => 'required|json',
            'note' => 'required|nullable|string',
            'status' => 'required|in:not_yet_taken,already_taken',
        ]);


        if (auth()->check() && auth()->user()->role == "doctor")
        {
            $doctor_id = auth()->user()->id;
        } else {
            $request->validate(['doctor_id' => 'required|integer|exists:users,id']);
            $doctor_id = $request->doctor_id;
        }

        $carts = json_decode($request->carts);
        $carts_collection = new Collection($carts);

        // 
        $medicine_cart = Medicine::whereIn("id", $carts_collection->pluck('id'))->get();
        $cart_transaction = [];
        foreach($medicine_cart as $medicine) {
            $cart = array_search($medicine->id, array_column($carts, 'id') );
            if ($cart !== false) {
                $cart_transaction[$medicine->id] = [
                    'medicine_id' => $medicine->id,
                    'stock' => $carts[$cart]->stock,
                    'price' => $medicine->price
                ];
            }
        }

        $total_price = 0;
        foreach ($cart_transaction as $item) { $total_price += ($item['price']*$item['stock']); }
        
        DB::transaction(function () use ($total_price, $cart_transaction, $request, $recipe, $doctor_id) {
            $update = $recipe->update([
                "doctor_id" => $doctor_id,
                "total_price" => $total_price,
                "note" => $request->note,
                "status" => $request->status,
            ]);;
            $recipe->medicines()->sync($cart_transaction);
        });

        return redirect()->route('admin.recipe.index')->with('alert', ['type' => 'success', 'text' => 'Update data successfully.']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Recipe $recipe)
    {
        $recipe->delete();
        return redirect()->route('admin.recipe.index')->with('alert', ['type' => 'success', 'text' => 'Delete data successfully.']);
    }
    
}
