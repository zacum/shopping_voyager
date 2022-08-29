<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;

use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::all();
        $newproduct=Product::orderBy('created_at', 'desc')->take(4)->get();

        // dd(Cart::content());

        return view('Theme2.main.content.cart-v2')->with([
            'allCategories'=> $categories,
            'newproduct'=>$newproduct,


        ]);
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
        $requestid=str_replace(",","",$request->price);
        $duplicate=Cart::search(function ($cartItem,$rowId)use($request){
            return $cartItem->id===$request->id;
        });
        //if specific item is exist
        if ($duplicate->count() > 0){
            Cart::update($duplicate->first()->rowId, $duplicate->first()->qty +1); // Will update the quantity
            return redirect()->route('cart.index')->with('sucess message','Item is Added Cart');
        }else{
            Cart::add($request->id, $request->name, 1, $requestid)->associate('App\Models\Product');
            return redirect()->route('cart.index')->with('sucess message', 'Item Add to Cart');
        }

    }

    public function empty()
    {
        Cart::destroy();
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
    public function update(Request $request, $id)
    {
        //dd($id);
        //return $request->all();
        Cart::update($id, $request->qty);
        //return response()->json(['success'=>true]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Cart::remove($id);
        return back()->with('sucess_message','item has been removed');
    }
}
