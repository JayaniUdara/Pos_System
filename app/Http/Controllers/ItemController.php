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
        return view('items');
    }

    public function getItems(Request $request)
    {
        if ($request->ajax()) {
            $data = Item::select('*');

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('image', function($row) {
                    return $row->image
                        ? '<img src="'.asset('storage/'.$row->image).'" width="50" height="50" class="img-thumbnail">'
                        : 'No Image';
                })
                ->addColumn('mfd_date', function($row) {
                    return $row->mfd_date ? $row->mfd_date->format('Y-m-d') : 'N/A';
                })
                ->addColumn('exp_date', function($row) {
                    return $row->exp_date->format('Y-m-d');
                })
                ->addColumn('cost_price', function($row) {
                    return number_format($row->cost_price, 2);
                })
                ->addColumn('selling_price', function($row) {
                    return number_format($row->selling_price, 2);
                })
                ->rawColumns(['image'])
                ->make(true);
        }
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'item_code' => 'required|unique:items|max:255',
                'item_name' => 'required|max:255',
                'mfd_date' => 'nullable|date',
                'exp_date' => 'required|date|after_or_equal:'.date('Y-m-d'),
                'company_name' => 'nullable|max:255',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'quantity' => 'required|integer|min:0',
                'cost_price' => 'required|numeric|min:0',
                'selling_price' => 'required|numeric|min:0|gte:cost_price',
            ], [
                'exp_date.after_or_equal' => 'The expiration date must be today or later.',
                'selling_price.gte' => 'The selling price must be greater than or equal to the cost price.',
                'item_code.unique' => 'This item code already exists.',
            ]);

            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('items', 'public');
            }

            $item = Item::create([
                'item_code' => $validatedData['item_code'],
                'item_name' => $validatedData['item_name'],
                'mfd_date' => $validatedData['mfd_date'],
                'exp_date' => $validatedData['exp_date'],
                'company_name' => $validatedData['company_name'],
                'image' => $imagePath,
                'quantity' => $validatedData['quantity'],
                'cost_price' => $validatedData['cost_price'],
                'selling_price' => $validatedData['selling_price'],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Item added successfully',
                'data' => $item
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->validator->errors()
            ], 422);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage(),
            ], 500);
        }
    }
}
