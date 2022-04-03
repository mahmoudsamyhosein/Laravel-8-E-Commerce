<div dir="rtl" style="text-align: right;">
    <!--main area-->
	<main id="main" class="main-site">
		<title>@section('title','| الشروط والأحكام ')</title>

		<div class="container">

			<div class="wrap-breadcrumb">
				<ul>
					<li class="item-link"><a href="/" class="link">{{__('mshmk.Home')}}</a></li>
					<li class="item-link"><span>{{__('mshmk.Terms_&_Conditions')}}</span></li>
				</ul>
			</div>
		</div>
		
		<div class="container pb-60">
			<div class="row">
				<div class="col-md-12">
					<h3>{{__('mshmk.Terms_&_Conditions')}}</h3>
					{{$setting->about_faq_body}}
				</div>
			</div>
		</div><!--end container-->

	</main>
	<!--main area-->
</div>
