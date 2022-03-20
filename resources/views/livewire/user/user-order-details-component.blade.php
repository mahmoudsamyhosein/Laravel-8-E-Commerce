<div>
    <div class="container" style="padding: 30px 0; text-align:right;" dir="rtl" >
        <div class="row">
            <div class="col-md-12">
                @if(Session::has('order_message'))
                <div class="alert alert-success" role="alert">{{Session::get('order_message')}}</div>
                @endif
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-md-6">
                                Order Details
                            </div>
                            <div class="col-md-6">
                                <a href="{{route('user.orders')}}" class="btn btn-success pull-left">My Orders</a>
                                @if ($order->status == 'ordered')
                                     <a href="#" class="btn btn-warning pull-right" wire:click.prevent='cancleorder'>Cancel Order</a>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="panel-body">
                        <table class="table">
                            <th>Order ID</th>
                            <td>{{$order->id}}</td>
                            <th>Order Date</th>
                            <td>{{$order->created_at}}</td>
                            <th>Status</th>
                            <td>{{$order->status}}</td>
                            @if ($order->status == 'delivered')
                            <th>Delivery Date</th>
                            <td>{{$order->delivered_date}}</td>
                            @elseif($order->status == 'canceled')
                                <th>Canceled Date</th>
                            <td>{{$order->canceled_date}}</td>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Ordered Items
                    </div>
                </div>
                <div class="panel-body">
                    <div class="wrap-iten-in-cart">
                            <h3 class="box-title">{{__('mshmk.Products_Name')}}</h3>
                            <ul class="products-cart">
                                @foreach($order->orderitems as $item )
                                        <li class="pr-cart-item">
                                            <div class="product-image">
                                                <figure><img src="{{asset('assets/images/products')}}/{{$item->product->image}}" alt="{{$item->product->name}}"></figure>
                                            </div>
                                            <div class="product-name">
                                                <a class="link-to-product" href="{{ route('products.details' ,['slug' => $item->product->slug ]) }}">{{$item->product->name}}</a>
                                            </div>
                                            <div class="price-field produtc-price"><p class="price">${{ $item->price }}</p></div>
                                            <div class="quantity">
                                                <h5>{{$item->quantity}}</h5>                                                
                                            </div>
                                            <div class="price-field sub-total"><p class="price">${{ $item->price * $item->quantity }}</p></div>
                                            @if ($order->status == 'delivered' && $item->rstatus == false)
                                                <div class="price-field sub-total"><p class="price"> <a href="{{ route('review.order',['order_item_id' => $item->id ])}}">Write Review</a></p></div>
                                            @endif
                                        </li>
                                @endforeach										
                            </ul>
                    </div>
                </div>
                <div class="summary">
                    <div class='order-summary'>
                        <h4 class="title-box">
                            Order Summery
                        </h4>
                        <p class="summary-info"><span class="title">{{__('mshmk.Subtotal')}}</span></span><b class="index">${{$order->subtotal}}</b></p>                        
                        <p class="summary-info"><span class="title">{{__('mshmk.Tax')}}</span></span><b class="index">{{$order->tax}}</b></p>                        
                        <p class="summary-info"><span class="title">{{__('mshmk.Shipping')}}</span></span><b class="index">{{__('mshmk.Free_Shipping')}}</b></p>                        
                        <p class="summary-info"><span class="title">{{__('mshmk.Total')}}</span></span><b class="index">{{$order->total}}</b></p>                                              
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                        Billing Details
                        </div>
                        <div class="panel-body">
                            <table class="table">
                                <tr>
                                    <th>First Name</th>
                                    <td>{{$order->firstname}}</td>
                                    <th>Last name</th>
                                    <td>{{$order->lastname}}</td>
                                </tr>
                                <tr>
                                    <th>Phone</th>
                                    <td>{{$order->phone}}</td>
                                    <th>Email</th>
                                    <td>{{$order->email}}</td>
                                </tr>
                                <tr>
                                    <th>Line1</th>
                                    <td>{{$order->line1}}</td>
                                    <th>Line2</th>
                                    <td>{{$order->line2}}</td>
                                </tr>

                                <tr>
                                    <th>City</th>
                                    <td>{{$order->city}}</td>
                                    <th>Province</th>
                                    <td>{{$order->province}}</td>
                                </tr>
                                <tr>
                                    <th>Country</th>
                                    <td>{{$order->country}}</td>
                                    <th>Zipcode</th>
                                    <td>{{$order->zipcode}}</td>
                                </tr>
                            </table> 
                        </div>
                </div>
        </div>
        </div>
        @if ($order->is_shipping_different)
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                        Shipping Details
                        </div>
                    </div>
                    <div class="panel-body">
                        <table class="table">
                            <tr>
                                <th>First Name</th>
                                <td>{{$order->shipping->firstname}}</td>
                                <th>Last name</th>
                                <td>{{$order->shipping->lastname}}</td>
                            </tr>
                            <tr>
                                <th>Phone</th>
                                <td>{{$order->shipping->phone}}</td>
                                <th>Email</th>
                                <td>{{$order->shipping->email}}</td>
                            </tr>
                            <tr>
                                <th>Line1</th>
                                <td>{{$order->shipping->line1}}</td>
                                <th>Line2</th>
                                <td>{{$order->shipping->line2}}</td>
                            </tr>

                            <tr>
                                <th>City</th>
                                <td>{{$order->shipping->city}}</td>
                                <th>Province</th>
                                <td>{{$order->shipping->province}}</td>
                            </tr>
                            <tr>
                                <th>Country</th>
                                <td>{{$order->shipping->country}}</td>
                                <th>Zipcode</th>
                                <td>{{$order->shipping->zipcode}}</td>
                            </tr>
                        </table> 
                        
                    </div>
                </div>
            </div>
        @endif
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                      Transaction
                    </div>
                </div>
                {{-- <div class="panel-body">
                    <table class="table text-right">
                        <tr>
                            <th>Transaction Mode </th>
                            <td>{{$order->transaction->mode}}</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>{{$order->transaction->status}}</td>
                        </tr>
                        <tr>
                            <th>Created_at </th>
                            <td>{{$order->transaction->created_at}}</td>
                        </tr>
                    </table>
                </div> --}}
            </div>
        </div>
    </div>
</div>