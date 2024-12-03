<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\ProductTag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends Controller
{

    public function index()
    {
        $products = Product::with('categories', 'tags', 'suppliers')->get();
        return response()->json($products, Response::HTTP_OK);
    }

    public function show($id)
    {
        $product = Product::with('categories', 'tags', 'suppliers')->find($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], Response::HTTP_NOT_FOUND);
        }

        return response()->json($product, Response::HTTP_OK);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'image' => 'nullable|url',
            'category_ids' => 'required|array',
            'tag' => 'nullable|array',
            'supplier_ids' => 'nullable|array',
            'profit_margin_type' => 'nullable|in:percentage,amount',
            'profit_margin_value' => 'nullable|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), Response::HTTP_BAD_REQUEST);
        }

        // Handle image upload
        $imagePath = null;
        if ($request->has('image')) {
            $imagePath = $request->image;
        }


        $product = Product::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'image' => $imagePath,
            'profit_margin_type' => $request->profit_margin_type,
            'profit_margin_value' => $request->profit_margin_value,
            'final_price' => $this->calculateFinalPrice($request),
        ]);

        $product->categories()->attach($request->category_ids);

        if ($request->has('tag')) {
            foreach ($request->tag as $tag) {
                ProductTag::create([
                    'product_id' => $product->id,
                    'tag' => $tag,
                ]);
            }
        }

        if ($request->has('supplier_names') && $request->has('supplier_contacts')) {
            $supplierNames = $request->supplier_names;
            $supplierContacts = $request->supplier_contacts;

            foreach ($supplierNames as $index => $name) {
                $contact = $supplierContacts[$index] ?? null;
                $supplier = Supplier::create([
                    'name' => $name,
                    'contact_info' => $contact,
                ]);

                // Attach supplier to the product
                $product->suppliers()->attach($supplier->id);
            }
        }

        // return response()->json($product, Response::HTTP_CREATED);
        return redirect()->route('products.list')->with('success', 'Product created successfully!');

    }

    public function update(Request $request, $id)
    {
        $product = Product::find($id);

        if (!$product) {
            // return response()->json(['message' => 'Product not found'], Response::HTTP_NOT_FOUND);
            return redirect()->route('products.list')->with('error', 'Product not found');

        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'image' => 'nullable|url',
            'category_ids' => 'required|array',
            'tag' => 'nullable|array',
            'supplier_ids' => 'nullable|array',
            'profit_margin_type' => 'nullable|in:percentage,amount',
            'profit_margin_value' => 'nullable|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), Response::HTTP_BAD_REQUEST);
        }

        $imagePath = null;
        if ($request->has('image')) {
            $imagePath = $request->image;
        }

        $product->update([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'profit_margin_type' => $request->profit_margin_type,
            'profit_margin_value' => $request->profit_margin_value,
            'final_price' => $this->calculateFinalPrice($request),
        ]);

        $product->categories()->sync($request->category_ids);

        // Handle suppliers (remove and add new ones)
        if ($request->has('existing_supplier_ids')) {
            $product->suppliers()->sync($request->existing_supplier_ids);
        }

        // Add new suppliers
        if ($request->has('new_suppliers') && count($request->new_suppliers) > 0) {
            foreach ($request->new_suppliers as $index => $supplierName) {
                $supplier = Supplier::create([
                    'name' => $supplierName,
                    'contact_info' => $request->new_supplier_contact[$index],
                ]);

                $product->suppliers()->attach($supplier->id);
            }
        }

        if ($request->has('tag')) {
            ProductTag::where('product_id', $product->id)->delete();
            foreach ($request->tag as $tag) {
                ProductTag::create([
                    'product_id' => $product->id,
                    'tag' => $tag,
                ]);
            }
        }

        if ($request->has('supplier_ids')) {
            $product->suppliers()->sync($request->supplier_ids);
        }

        // return response()->json($product, Response::HTTP_OK);

        return redirect()->route('products.list')->with('success', 'Product updated successfully');

    }

    public function destroy($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], Response::HTTP_NOT_FOUND);
        }

        // Detach related categories (or other relations like suppliers, tags)
        $product->categories()->detach();
        $product->suppliers()->detach();
        $product->tags()->delete();

        if ($product->image) {
            Storage::delete('public/' . $product->image);
        }

        // Delete product
        $product->delete();

        // return response()->json(['message' => 'Product deleted successfully'], status: Response::HTTP_OK);
        return redirect()->route('products.list')->with('success', 'Product deleted successfully');

    }



    private function calculateFinalPrice($request)
    {
        if ($request->profit_margin_type === 'percentage') {
            return $request->price + ($request->price * $request->profit_margin_value / 100);
        } elseif ($request->profit_margin_type === 'amount') {
            return $request->price + $request->profit_margin_value;
        }

        return $request->price;
    }




    public function list()
    {
        // Fetch products from the database
        $products = Product::all();
        $isAdmin = auth()->user()->hasRole('admin');
        return view('products.list', compact('products', 'isAdmin'));
    }


    public function details($id)
    {
        $product = Product::findOrFail($id);
        return view('products.details', compact('product'));
    }

    public function create()
    {
        $categories = Category::all();
        $suppliers = Supplier::all();
        return view('products.create', compact('categories', 'suppliers'));
    }

    public function edit($id)
    {
        $product = Product::with('categories', 'tags', 'suppliers')->find($id);

        if (!$product) {
            return redirect()->route('products.list')->with('error', 'Product not found');
        }

        $categories = Category::all(); // Fetch all categories
        $suppliers = Supplier::all(); // Fetch all suppliers

        return view('products.edit', compact('product', 'categories', 'suppliers'));
    }

}
