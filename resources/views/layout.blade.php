<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<link rel="stylesheet" href="{{ URL::asset('/css/bootstrap.min.css') }}">
	<link rel="stylesheet" href="{{ URL::asset('/css/bvmd.css') }}">
	<link rel="stylesheet" href="{{ URL::asset('/css/maya.css') }}">
	<link rel="stylesheet" href="{{ URL::asset('/css/app.css') }}">
	<script defer src="{{ URL::asset('/js/app.js') }}"></script>
	<link rel="stylesheet" href="{{ URL::asset('/font-awesome/css/font-awesome.min.css') }}">
	<link rel="icon" type="image/vnd.microsoft.icon" href="{{ URL::asset('favicon.ico') }}" />
</head>
<body id='app-body' class='<?=isset($app_body_css) ? $app_body_css : ''?>'>
	<div class="header">
		<div class="container flexed-header-container">
			<div class="header-logo-container">
				<h1 class='header-logo-titel'>
					<a href="{{ URL::to('/') }}">
						<img class='header-logo' src="{{ URL::asset('/img/mendoo-logo.svg') }}" width="200" height="57">
					</a>
				</h1>
			</div>
			<div class="header-menu-container">
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
