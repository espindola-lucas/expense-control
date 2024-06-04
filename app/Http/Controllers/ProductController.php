<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function index(){
        $products = Product::all();
        $user = Auth::user();
        $totalPrice = Product::sum('price');
        return view('dashboard', [
            'products' => $products,
            'user' => $user->name,
            'totalPrice' => $totalPrice
        ]);
    }

    public function create(){
        return view('products.create-product');
    }
    
    public function store(Request $request){
        if($request->isMethod('post')){
            $request->validate([
                'productName' => 'required|string|max:255',
                'price' => 'required|numeric|min:0',
            ]);
            
            Product::create([
                'name' => $request->input('productName'),
                'price' => $request->input('price'),
                'user_id' => Auth::user()->id
            ]);
    
            return redirect()->route('dashboard')->with('success', 'Producto agregado exitosamente.');
        }
        return view('products.create-product');
    }

    public function edit(Product $product){
        return view('products.edit-product', [
            'product' => $product
        ]);
    }

    public function update(Request $request, Product $product)
    {
        $input = $request->all();
        $product -> update($input);
        return redirect('dashboard');
    }

    public function destroy(Product $product){
        $product->delete();
        return redirect()->route('dashboard');
    }
}
