<!DOCTYPE html>
<html>
<head>
<!-- <title>Lavie</title> -->
	<link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">

	<!-- register css -->
	<link type="text/css" href="{{ asset('css/register.css') }}" rel="stylesheet">
	<link type="text/css" href="{{ asset('css/login.css') }}" rel="stylesheet">
	<link type="text/css" href="{{ asset('css/style.css') }}" rel="stylesheet">

	<!-- jquery -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

	<!-- jquery validate -->
	<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>

	<!-- validate form register jquery -->
	<script src="{{ asset('js/register.js') }}"></script>

</head>
<body>
<div id="app">
		<nav class="navbar navbar-expand-lg navbar-light bg-light sticky-top mb-5">
			<div class="container">
				<div class="text-center logo">
					<a class="navbar-brand" href="{{ url('/') }}">
					  <img class="rounded" src="{{ asset('uploads/logo/logo.png') }}"  alt="...">
				  	</a>
				</div>
				  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
				    <span class="navbar-toggler-icon"></span>
				  </button>

				  <div class="collapse navbar-collapse" id="navbarSupportedContent">
				    <ul class="navbar-nav mr-auto">
				  	  <li class="nav-item nav-text-menu">
				        <a class="nav-link" href="#"><span class="sr-only">(current)</span></a>
				      </li>
				  </div>
			</div>
		</nav>
		
	 	<main class="py-4">
            @yield('content')
        </main>
	</div>
</body>    
</html>
