<style>
    nav svg{
        height: 20px;
    }
    nav .hidden{
        display: block !important;
    }
</style>
<div dir="rtl" style="text-align: right">
    <div class="container" style="padding: 30px 0;">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-md-4 pull-right">
                                {{__('mshmk.All_Products')}}
                            </div>
                            <div class="col-md-4">
                               <a href="{{ route('admin.addproduct') }}" class="btn btn-success pull-right">{{__('mshmk.Add_New')}}</a>
                            </div>

                            <div class="col-md-4">
                                <input type="text" class="form-control" placeholder="{{__('mshmk.search...')}}" wire:model='searchterm'>
                            </div>
                        </div>    
                    </div>
                    <div class="panel-body">
                        @if(Session::has('message'))
                            <div class="alert alert-success" role="alert">{{ Session::get('message')}}</div>
                        @endif
                        <table class="table table-striped">
                            <thead>
                                <tr >
                                    <th>{{__('mshmk.Id')}}</th>
                                    <th>{{__('mshmk.Image')}}</th>
                                    <th>{{__('mshmk.Name')}}</th>
                                    <th>{{__('mshmk.Stock')}}</th>
                                    <th>{{__('mshmk.Price')}}</th>
                                    <th>{{__('mshmk.Sale_Price')}}</th>
                                    <th>{{__('mshmk.Category')}}</th>
                                    <th>{{__('mshmk.Date')}}</th>
                                    <th>{{__('mshmk.Action')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($products as $product)
                                    <tr>
                                        <td>{{$product->id}}</td>
                                        <td><img src="{{ asset('assets/images/products') }}/{{$product->image}}" alt="{{$product->name}}" width="60"></td>
                                        <td>{{$product->name}}</td>
                                        <td>{{$product->stock_status}}</td>
                                        <td>{{$product->regular_price}}</td>
                                        <td>{{$product->sale_price}}</td>
                                        <td>{{$product->category->name}}</td>
                                        <td>{{$product->created_at}}</td>
                                        <td>
                                            <a href="{{ route('admin.editproduct' ,['product_slug' => $product->slug ])}}">
                                                <i class="fa fa-edit fa-2x text-info"></i> 
                                            </a>
                                            <a onclick="confirm('Are You Sure, You Want To Delete This Product ? ') || event.stopImmediatePropagation()" style="margin-left:10px;"
                                            wire:click.prevent='deleteProduct({{$product->id}})'>
                                            <i class="fa fa-times fa-2x text-danger" style="margin-right: 20px;"></i></a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{ $products->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
