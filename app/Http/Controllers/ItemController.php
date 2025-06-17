<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class ItemController extends Controller
{
    public function index()
    {
        $items = Item::latest()->get(); 

        return view('items', compact('items'));
    }

    public function getItems(Request $request)
    {
        if ($request->ajax()) {
            $data = Item::select('*');
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('image', function($row) {
                    if ($row->image) {
                        return '<img src="'.asset('storage/'.$row->image).'" width="50" height="50" class="rounded">';
                    }
                    return 'No Image';
                })
                ->rawColumns(['image'])
                ->make(true);
        }
    }

    public function store(Request $request)
{
    $validated = $request->validate([
        'item_code'     => 'required|string|max:255|unique:items,item_code',
        'item_name'     => 'required|string|max:255',
        'mfd_date'      => 'nullable|date',
        'exp_date'      => 'required|date|after_or_equal:today',
        'company_name'  => 'nullable|string|max:255',
        'image'         => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'quantity'      => 'required|integer|min:0',
        'cost_price'    => 'required|numeric|min:0',
        'selling_price' => 'required|numeric|min:0',
    ]);
   
    // Handle image upload
    if ($request->hasFile('image')) {
        $imagePath = $request->file('image')->store('uploads/items', 'public');
        $validated['image'] = $imagePath;
    }

    // Create item
    Item::create($validated);

    return redirect()->back()->with('success', 'Item created successfully.');
}
   

    
}