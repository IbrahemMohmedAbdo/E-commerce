<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Product;
use App\Models\Category;
use App\Models\Categorie;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Requests\CreateProductRequest;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Product $product)
    {
        //
        $products=Product::all();


        return view('dashboard.Products.index',compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $categories = Category::pluck('name', 'id');
        return view('dashboard.Products.create',compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

                $product = new Product;
                $product->name = $request->input('name');
                $product->description = $request['description'];
                $product->price = $request->input('price');

        // To store image for product..
                if ($request->hasFile('image')) {
                    $image = $request->file('image');
                    $filename = time() . '.' . $image->getClientOriginalExtension();
                    $path =$request->file('image')->storeAs('images', $filename, 'public');
                    $product->image ='/storage/'.$path;
                }

                    // To store product id with category id in pivot table...
                        $category = Category::find($request->input('category_id'));

                        $category->products()->save($product);


                 return redirect()->route('products.index')->with('msg', 'Product created successfully.');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        //
        return view('dashboard.products.show',compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        //
        $categories = Category::pluck('name', 'id');

        return view('dashboard.products.edit', compact('product','categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,Product $product )
    {


                          //      $requestData  = $request->all();


                                if ($request->hasFile('image')) {
                                    $image = $request->file('image');
                                    $filename = time() . '.' . $image->getClientOriginalExtension();
                                    $path =$request->file('image')->storeAs('images', $filename, 'public');
                                    $product->image ='/storage/'.$path;
                                }
                                $product->update([
                                    'name' => $request->input('name'),
                                    'description' => $request->input('description'),
                                    'price'=>$request->input('price'),
                                ]);

                                $category = Category::find($request->input('category_id'));
                                $product->categories()->sync($category);





         return redirect()->route('products.index')->with('msg', 'Product Updated successfully.');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        //
        $product->delete();

        return redirect()->route('products.index')->with('msg', 'Product deleted successfully.');
    }
}
