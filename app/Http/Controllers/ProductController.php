<?php

namespace App\Http\Controllers;

use App\Category;
use App\Product;
use App\ProductDetail;
use App\ProductImage;
use App\Shop;
use App\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ProductController extends Controller
{
    public function all()
    {
       
        $products = DB::table('products')
            ->join('shops', 'shops.id', '=', 'products.shop_id')
            ->join('users', 'users.id', '=', 'shops.user_id')
            ->where('users.id', '=', auth()->user()->id)
            ->select('products.id')
            ->get();
        return view('shop.product.index', compact('products'));
    }

    public function addProduct()
    {
        $brands=Brand::all();
        $categories = Category::all();
        return view('shop.product.create', compact('categories','brands'));
    }

    public function storeProduct(Request $request)
    {

        $this->validate($request, [
            'name' => ['required', 'string'],
            'company_name' => ['required', 'string'],
            'category_id' => ['required'],
            'shop_id' => ['required'],
            'price' => ['required'],
            'quantity' => ['required'],
            'description' => ['required', 'string'],
            'image' => 'required',
            'image.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'color' => ['string', 'max:255'],
            'size' => ['string', 'max:255'],
            'model' => ['string', 'max:255'],
            'brand' => ['string', 'max:255'],
        ]);
        $product = Product::create([
            'name' => $request['name'],
            'company_name' => $request['company_name'],
            'category_id' => $request['category_id'],
            'quantity' => $request['quantity'],
            'price' => $request['price'],
            'description' => $request['description'],
            'shop_id' => $request['shop_id'],
            'brand_id'=>$request->brand,

        ]);
        $productDetails = ProductDetail::create([
            'color' => $request['color'],
            'size' => $request['size'],
            'model' => $request['model'],
            'product_id' => $product->id,

        ]);
        if ($request->hasFile('image')) {
            $shop = Shop::find($request['shop_id']);
            $i = 0;
            foreach ($request->file('image') as $file) {
                $extention = $file->getClientOriginalExtension();
                $filename = time()+$i . '.' . $extention;
                $path = '/shop/' . $shop->name . '/product/' . $filename;
                $file->move(public_path() . "/shop/" . $shop->name . "/product/", $filename);

                ProductImage::create([
                    'image' => $path,
                    'product_id' => $product->id,

                ]);
                $i++;
            }
        }
        return redirect()->route('product.all')->with('success', 'Product added successfully!');
    }

    public function editProduct(Product $product)
    {
        $categories = Category::all();
        return view('shop.product.edit', compact('product', 'categories'));
    }

    public function deleteProduct(Product $product)
    {
        if (isset($product->productImages) && !empty($product->productImages)) {

            foreach ($product->productImages as $image) {

                $image_path = public_path().$image->image;
                //dd($image_path);
                if (File::exists($image_path)) {
                    File::delete($image_path);
                    //unlink($image_path);
                }
            }
        }
        $product->delete();

        return redirect()->route('product.all')->with('success','Product Deleted Sucessfully');
    }

    public function removeImageProduct(ProductImage $productImage)
    {
        $image_path = public_path().$productImage->image;
        //dd($image_path);
        if (File::exists($image_path)) {
            File::delete($image_path);
            //unlink($image_path);
        }
        $productImage->delete();
        return redirect()->back()->with('success', 'Image Deleted Successfully');
    }

    public function updateProduct(Request $request, Product $product)
    {
        $this->validate($request,[
            'name' => ['required', 'string', ],
            'company_name' => ['required', 'string' ],
            'category_id' => ['required'],
            'price' => ['required'],
            'quantity' => ['required'],
            'description' => ['required', 'string'],
            'color' => ['string','max:255'],
            'size' => ['string','max:255'],
            'model' => ['string','max:255',],
        ]);

        $product->update([
            'name' => $request['name'],
            'company_name' => $request['company_name'],
            'category_id' => $request['category_id'],
            'quantity' => $request['quantity'],
            'price' => $request['price'],
            'description' => $request['description'],
            'shop_id' => $request['shop_id'],

        ]);
        $product->productDetail->update([
            'color' => $request['color'],
            'size' => $request['size'],
            'model' => $request['model'],
            'product_id' =>$product->id,

        ]);

        if($request->hasFile('image')){
            $shop = Shop::find($request['shop_id']);
            $i = 0;
            foreach($request->file('image') as $file){
                $extention=$file->getClientOriginalExtension();
                $filename=time()+$i .'.'.$extention;
                $path = '/shop/' . $shop->name . '/product/' . $filename;
                $file->move(public_path() . "/shop/" . $shop->name . "/product/", $filename);


                ProductImage::create([
                    'image' =>$path,
                    'product_id' =>$product->id,

                ]);
                $i++;
            }
        }

        return redirect()->route('product.all')->with('success','Product Update successfully!');
    }
    public function productDetails(Product $product){
        $categories=Category::all();
        $brands=Brand::orderBy('created_at', 'desc')->take(6)->get();
      return view('layouts.productDetials',compact('product','products','product_images','categories','brands','product_detials','categories'));
    }
    public function shop(){
        $products=Product::all();
        $categories=Category::all();
        $brands=Brand::orderBy('created_at', 'desc')->take(15)->get();
      return view('layouts.shop',compact('product','products','product_images','categories','brands','product_detials','categories'));
    }
    public function categorywiseProduct(Category $category){
        $categories=Category::all();
        $brands=Brand::all();
      return view('layouts.categorywiseProduct',compact('category','categories','brands'));
    }
}
