<div dir="rtl" style="text-align: right">
    <style>
        nav svg {
            height: 20px;
        }

        nav .hidden {
            display: block !important;
        }
        .sclist{
            list-style: none;
        }
        .sclist li{
            line-height: 33px;
            border-bottom: 1px solid #ccc;
            
        }
        .slink i{
            font-size: 16px;
            margin-left: 12px;
        }
        
    </style>
    <div class="container" style="padding: 30px 0;">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="col-md-6">
                            {{__('mshmk.All_Categories')}}
                        </div>
                        <div class="col-md-6">
                            <a href="{{ route('admin.addcategory') }}" class="btn btn-success pull-right">{{__('mshmk.ADD_New')}}</a>
                        </div>
                    </div>
                    <div class="panel-body">
                        @if(Session::has('message'))
                        <div class="alert alert-success" role="alert">
                            {{Session::get('message')}}
                        </div>
                        @endif
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>{{__('mshmk.Id')}}</th>
                                    <th>{{__('mshmk.Category_Name')}}</th>
                                    <th>{{__('mshmk.Slug')}}</th>
                                    <th>{{__('mshmk.Sub_Category')}}</th>
                                    <th>{{__('mshmk.Action')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($categories as $category)
                                <tr>
                                    <td>{{ $category->id }} </td>
                                    <td>{{ $category->name }} </td>
                                    <td>{{ $category->slug }}</td>
                                    <td>
                                        <ul class="sclist">
                                            @foreach ($category->subcategories as $scategory)
                                                <li>
                                                    <i class="fa fa-caret-right"></i>{{$scategory->name}} <a class="slink" href="{{route('admin.editcategory',[ 'category_slug'=> $category->slug , 'scategory_slug'=> $scategory->slug])}}"><i class="fa fa-edit"></i></a> 
                                                    <a class="slink" href="#" onclick="confirm('Are You Sure, You Want To Delete Subcategory?') || event.stopImmediatePropagation()" wire:click.prevent='deletesubcategory({{$scategory->id}})'><i class="fa fa-times text-danger"></i></a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </td>
                                    <td>
                                         <a
                                            href="{{route('admin.editcategory' ,['category_slug'=>$category->slug ] )}}"><i
                                                class="fa fa-edit fa-2x"></i></a> 
                                        <a href="#"
                                            onclick="confirm('{{__('mshmk.Are_You_Sure,You_Want_to_delete_this_Category?')}}') || event.stopImmediatePropagation()"
                                            wire:click.prevent='destroycategory({{$category->id}})'
                                            style="margin-left:10px;"><i class="fa fa-times fa-2x text-danger"></i></a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{ $categories->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>