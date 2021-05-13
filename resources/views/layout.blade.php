<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<link rel="stylesheet" href="{{ URL::asset('/css/bootstrap.min.css') }}">
	<link rel="stylesheet" href="{{ URL::asset('/css/bvmd.css') }}">
	<script defer src="{{ URL::asset('/js/app.js') }}"></script>
	<link rel="stylesheet" href="{{ URL::asset('/font-awesome/css/font-awesome.min.css') }}">
	<link rel="icon" type="image/vnd.microsoft.icon" href="{{ URL::asset('favicon.ico') }}" />
</head>
<body id='app-body'>
	<div class="header">
		<div class="container">
			<div class="col-md-2">
				<h1><a href="{{ URL::to('/') }}"><img src="{{ URL::asset('/img/bvmd-trans.png') }}" width="150" height="75"></a></h1>
			</div>
			<div class="col-md-10">
				@if(isset($menuItems))
					<ul class="nav navbar-nav">
						@foreach ($menuItems as $menuItem)
							<li class="{{ $menuItem->Class }}">
								<a href="{{ URL::asset($menuItem->Url) }}">
									<i class="fa {{ $menuItem->Icon }}"></i>&nbsp; {{ $menuItem->Text }} 
								</a>
							</li>
						@endforeach	
					</ul>
				@endif	
			</div>
		</div>
	</div>

	<hr>

	<div class="container content">
		@yield('content')
	</div>
	
	<div id='marquee-holder'></div>
	
</body>

</html>
