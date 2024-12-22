<?php

namespace App\Http\Controllers;

use App\Models\Product;  // Import Product model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
class ProductController extends Controller
{
    // Display a listing of the products
    public function index()
    {
        $products = Product::all(); // Fetch all products
        return response()->json($products);
    }

    // Show the form for creating a new product (if needed)
    public function create()
    {
        // Typically, you'd return a view here
        // Example: return view('products.create');
    }

    // Store a newly created product in storage
    public function store(Request $request)
    {
        // Validate request data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
           
        ]);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('product_images', 'public');  // Store image
          
        }
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
                'message' => 'Validation failed.',
            ], 422);
        }
 $validated = $request->validate([
        'name' => 'required|string|max:255',
        'price' => 'required|numeric',
       
    ]);
    $validated['image'] =$imagePath;
        // Create and save the product
        $product = Product::create($validated);

        // Return the created product with a 201 status code
        return response()->json($product, 201);
    }

    // Display the specified product
    public function show($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        return response()->json($product);
    }

    // Show the form for editing the specified product (if needed)
    public function edit($id)
    {
        // Typically, you'd return a view here
        // Example: return view('products.edit', compact('product'));
    }

    // Update the specified product in storage
    public function update(Request $request, $id)
    {
        // Validate request data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
          
        ]);

        // Find the product
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        // Update the product with validated data
        $product->update($validated);

        // Return the updated product
        return response()->json($product);
    }

    // Remove the specified product from storage
    public function destroy($id)
    {
        // Find the product
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        // Delete the product
        $product->delete();

        // Return a success message
        return response()->json(['message' => 'Product deleted successfully']);
    }
}
