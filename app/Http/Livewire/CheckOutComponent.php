<?php

namespace App\Http\Livewire;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Shipping;
use App\Models\Transaction;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Cartalyst\Stripe\Laravel\Facades\Stripe;
class CheckOutComponent extends Component
{
    //تفعيل الشحن الي عنوان أخر 
    public $is_shipping_different;
    //خواص عنوان الدفع
    public $firstname;
    public $lastname;
    public $email;
    public $mobile;
    public $line1;
    public $line2;
    public $city;
    public $province;
    public $country;
    public $zipcode;
    // خواص عنوان الشحن
    public $s_firstname;
    public $s_lastname;
    public $s_email;
    public $s_mobile;
    public $s_line1;
    public $s_line2;
    public $s_city;
    public $s_province;
    public $s_country;
    public $s_zipcode;
    public $paymentmode;
    public $thankyou;
    public $card_no;
    public $exp_month;
    public $exp_year;
    public $cvc;
    //دالة لعرض رسائل الخطأ
    public function updated($fields){

        $this->validateOnly($fields,[
            'firstname'=>'required',
            'lastname'=>'required',
            'email'=>'required|email',
            'mobile'=>'required|numeric',
            'line1'=>'required',
            'city'=>'required',
            'province'=>'required',
            'country'=>'required',
            'zipcode'=>'required',
            'paymentmode' => 'required',
        ]);

        if($this->is_shipping_different){

            $this->validateOnly($fields,[
                's_firstname'=>'required',
                's_lastname'=>'required',
                's_email'=>'required|email',
                's_mobile'=>'required|numeric',
                's_line1'=>'required',
                's_city'=>'required',
                's_province'=>'required',
                's_country'=>'required',
                's_zipcode'=>'required',
            ]);

        }

        if($this->paymentmode == 'card'){

            $this->validateOnly($fields,[
                'card_no' => 'required|numeric',
                'exp_month' => 'required|numeric',
                'exp_year' => 'required|numeric',
                'cvc' => 'required|numeric',

            ]);
        }
    }
    //دالة أنشاء الطلب
    public function placeorder(){
        //التحقق من البيانات أثناء أنشاء المستخدم للطلب 
        $this->validate([
            'firstname'=>'required',
            'lastname'=>'required',
            'email'=>'required|email',
            'mobile'=>'required|numeric',
            'line1'=>'required',
            'city'=>'required',
            'province'=>'required',
            'country'=>'required',
            'zipcode'=>'required',
            'paymentmode' => 'required',
        ]);

        if($this->paymentmode == 'card'){

            $this->validate([
                'card_no' => 'required|numeric',
                'exp_month' => 'required|numeric',
                'exp_year' => 'required|numeric',
                'cvc' => 'required|numeric',

            ]);
        }
        //أنشاء كائن جديد وتخزين البيانات التالية به
        $order = new Order();
        // قم بعمل auth للمستخدم بواسطة معرف id
        $order->user_id = Auth::user()->id;
        //قم بتمرير المعاملات التالية الي جلسة المستخدم session 
        $order->subtotal = session()->get('checkout')['subtotal'];
        $order->discount = session()->get('checkout')['discount'];
        $order->tax = session()->get('checkout')['tax'];
        $order->total = session()->get('checkout')['total'];
        $order->firstname = $this->firstname;
        $order->lastname = $this->lastname;
        $order->email = $this->email;
        $order->mobile = $this->mobile;
        $order->line1 = $this->line1;
        $order->line2 = $this->line2;
        $order->city = $this->city;
        $order->province = $this->province;
        $order->country = $this->country;
        $order->zipcode = $this->zipcode;
        //قم باضافة حالة الطلب الي تم الطلب ordered
        $order->status = 'ordered';
        //تفعيل الشحن الي عنوان أخر أو لا تم أستخدام الشرط if في 
        $order->is_shipping_different = $this->is_shipping_different ? 1:0;
        //حفظ بيانات الطلب 
        $order->save();
        //الأن حلقة لتخزين بيانات السلة في طلب جديد في جدول orderitem
        foreach(Cart::instance('cart')->content() as $item){
            //قم بانشاء كائن جديد من الموديل orderitem
            $orderItem = new OrderItem();
            $orderItem->product_id = $item->id;
            $orderItem->order_id = $item->id;
            $orderItem->price = $item->price;
            $orderItem->quantity = $item->qty;
            //حقظ بيانات الطلب الجديد
            $orderItem->save();
        }
        if($this->is_shipping_different){

            $this->validate([
                's_firstname'=>'required',
                's_lastname'=>'required',
                's_email'=>'required|email',
                's_mobile'=>'required|numeric',
                's_line1'=>'required',
                's_city'=>'required',
                's_province'=>'required',
                's_country'=>'required',
                's_zipcode'=>'required',
            ]);

            $shipping = new Shipping();
            $shipping->order_id = $order->id;
            $shipping->firstname = $this->s_firstname;
            $shipping->lastname = $this->s_lastname;
            $shipping->email = $this->s_email;
            $shipping->mobile = $this->s_mobile;
            $shipping->line1 = $this->s_line1;
            $shipping->line2 = $this->s_line2;
            $shipping->city = $this->s_city;
            $shipping->province = $this->s_province;
            $shipping->country = $this->s_country;
            $shipping->zipcode = $this->s_zipcode;
            $shipping->save();
        }

        if($this->paymentmode == 'cod'){

            $this->maketransaction($order->id,'pending');
            $this->resetCart();

            
        }
        elseif($this->paymentmode == 'card' ){


            $stripe = Stripe::make(env('STRIPE_KEY'));

            try{
                $token = $stripe->tokens()->create([
                    'card' =>[
                        'number'=> $this->card_no,
                        'exp_month'=> $this->exp_month,
                        'exp_year'=> $this->exp_year,
                        'cvc'=> $this->cvc,
                    ]
                ]);
                if(!isset($token['id'])){
                    session()->flash('stripe_error','The Stripe Token Was Not Generated Correctly!');
                    $this->thankyou = 0;
                }

                $customer = $stripe->customers()->create([
                    'name' => $this->firstname . ' ' . $this->lastname,
                    'email' => $this->email,
                    'phone' => $this->mobile,
                    'address'=> [
                        'line1' => $this->line1,
                        'postal_code' => $this->zipcode,
                        'city' => $this->city,
                        'state' => $this->province,
                        'country' => $this->country,

                    ],
                    'shipping' => [
                        'name' => $this->firstname . ' ' . $this->lastname,
                        'address'=> [
                            'line1' => $this->line1,
                            'postal_code' => $this->zipcode,
                            'city' => $this->city,
                            'state' => $this->province,
                            'country' => $this->country,
    
                        ],

                    ],
                    'source' => $token['id']

                ]);


                $charge = $stripe->charges()->create([

                    'customer' => $customer['id'],
                    'currency' => 'USD',
                    'amount'  => session()->get('checkout')['total'],
                    'description' => 'Payment For No ' . $order->id,

                ]);
                if($charge['status'] == 'succeeded' ){

                    $this->maketransaction($order->id,'approved');
                    $this->resetCart();

                }
                else{
                    session()->flash('stripe_error','Error In Transaction!');
                    $this->thankyou = 0;

                }

            } catch(Exception $e){
                session()->flash('stripe_error',$e->getMessage());
                $this->thankyou = 0;
                
            }
        }
        
       
        
    }

    public function resetCart(){
        $this->thankyou = 1;
        Cart::instance('cart')->destroy();
        session()->forget('checkout');

    }

    public function maketransaction($order_id,$status){
            $transaction = new Transaction();
            $transaction->user_id  = Auth::user()->id;
            $transaction->order_id =$order_id;
            $transaction->mode = $this->paymentmode;
            $transaction->status = $status;
            $transaction->save();

    }

    public function verifyforcheckout(){

        if(!Auth::check()){
            return redirect()->route('login');
        }

        elseif($this->thankyou){

            return redirect()->route('thank-you');

        }
        elseif(!session()->get('checkout')){


            return redirect()->route('product.cart');
        }
    }

    public function render()
    {
        $this->verifyforcheckout();
        return view('livewire.check-out-component')->layout('layouts.base');
    }
}
