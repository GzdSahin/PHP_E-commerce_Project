<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\ShippingInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientController extends Controller
{
    public function CategoryPage($id){
          $cate=Category::findOrFail($id);
          $pro=Product::where('product_category_id',$id)->latest()->get();
        return view('user_template.category',compact('cate','pro'));
    }



    public function SingleProduct($id){
         $proc=Product::findOrFail($id);
         $subcat_id=Product::where('id',$id)->value('product_subcategory_id');
        $related_products=Product::where('product_subcategory_id',$subcat_id)->latest()->get();
        return view('user_template.product',compact('proc' ,'related_products'));
    }

    public function AddToCart(){
          $userid=Auth::id();
        $cart_items=Cart::where('user_id',$userid)->get();
        return view('user_template.addtocart' , compact('cart_items'));
    }


    public function RemoveItem($id){

        Cart::findOrFail($id)->delete();

        return redirect()->route('addtocart')->with('message','Ürün sepetten kaldırıldı!');

    }


    public function GetShippingAdress(){

        return view('user_template.shippingadress');

    }


   public function AddShippingAdress(Request $request){
    ShippingInfo::insert([
        'user_id'=>Auth::id(),
        'phone_number'=>$request->phone_number,
        'city_name'=>$request->city_name,
        'postal_code'=>$request->postal_code


    ]);

    return redirect()->route('checkout');
       
   }




    public function AddProductToCart(Request $request){
               
        $product_price=$request->price;
        $quantity=$request->quantity;
        $price= $product_price*$quantity;
         Cart::insert([
             'product_id'=>$request->product_id,
             'user_id'=>Auth::id(),
             'quantity'=>$quantity,
             'price'=>$price

         ]);


         return redirect()->route('addtocart')->with('message','ürün sepete eklendi');

      
    }

    public function Checkout(){
        $userid=Auth::id();
        $cart_items=Cart::where('user_id',$userid)->get();
        $shipping_adress=ShippingInfo::where('user_id',$userid)->first();

        return view('user_template.checkout' , compact('cart_items' , 'shipping_adress'));
    }
    public function UserProfile(){

        return view('user_template.userprofile');
    }


    public function PlaceOrder(){

        $userid=Auth::id();
        $shipping_adress=ShippingInfo::where('user_id',$userid)->first();
        $cart_items=Cart::where('user_id',$userid)->get();


       foreach($cart_items as $item){

          Order::insert([

            'userid'=>$userid,
            'shipping_phonenumber'=>$shipping_adress->phone_number,
            'shipping_city'=>$shipping_adress->city_name,
            'shipping_postalcode'=>$shipping_adress->postal_code,
            'product_id'=>$item->product_id,
            'quantity'=>$item->quantity,
            'total_price'=>$item->price
            

          ]);

            $id=$item->id;
          Cart::findOrFail($id)->delete();

       }


       ShippingInfo::where('user_id',$userid)->first()->delete();
 
       return redirect()->route('pendingorders')->with('message' ,'Siparişiniz Alındı!');

    }





    public function PendingOrders(){
        $pending_orders=Order::where('durum','pending')->latest()->get();

        return view('user_template.pendingorders',compact('pending_orders'));
    }

    public function History(){

        return view('user_template.history');
    }


}
