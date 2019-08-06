<!DOCTYPE html>
<html>
<head>
	<title>hi</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<link rel="stylesheet" type="text/css" href="{{ asset('css/style.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('css/productDetail.css') }}">

	<!-- bootstrap -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

	<!-- fontawesome -->
	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/css/all.css">

	<!-- jquery price slider -->
	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

	<!-- jquery -->
	<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
	<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-zoom/1.7.21/jquery.zoom.js"></script>

    <!-- jquery validate -->
	<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>

	<!-- validate form register jquery -->
	<script src="{{ asset('js/register.js') }}"></script>


    <!-- sweetalert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>


	<!-- bootstrap -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>

	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
	<!-- end bootstrap -->

	<script type="text/javascript" src="{{ asset('js/script.js') }}"></script>

	
</head>
<body>
<!-- menu navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-light sticky-top">
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
			        <a class="nav-link" href="#">Blog <span class="sr-only">(current)</span></a>
			    </li>
			    <li class="nav-item nav-text-menu">
			        <a class="nav-link" href="#">About</a>
			    </li>
			    <li class="nav-item nav-text-menu">
			        <a class="nav-link" href="#">Contact</a>
			    </li>
		    </ul>
		    <form class="form-inline my-2 my-lg-0" action="{{ url('/') }}" method="post">
		    	@csrf
		        <input class="form-control mr-sm-2" name="name" type="search" title="Search by name product, category, agency" placeholder="Search" aria-label="Search">
		        <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
		    </form>
		    <li class="nav-item dropdown">
		    	@guest
		    	<a class="nav-link" href="{{ route('buyer.signin') }}" id="navbarDropdown">
		          Login
		        </a>
		      	@else
		    	<a class="nav-link" href="#" id="navbarDropdown">
		          {{ Auth::user()->name }}
		        </a>
		        <div class="dropdown-content">
		        	@if(Auth::user()->level == 1)
		          	<a class="dropdown-item" href="{{ route('seller.product') }}"><i class="fas fa-tachometer-alt"></i> View dashboard</a>
		          	@elseif (Auth::user()->level == 0)
		          	<a class="dropdown-item" href="{{ route('buyer.profile') }}"><i class="fas fa-user-alt"></i> View profile</a>
		            @endif
		          	<form id="logout-form" action="{{ route('logout') }}" method="POST">
                    @csrf
		            <input type="submit" class="dropdown-item" value="Logout"/>
                    </form>
		        </li>
		        @endguest
		    <li class="nav-item cart-box">
		      	<a class="cart" href="{{ route('cart') }}"><i class="fas fa-cart-plus cart-icon"><span class="badge badge-danger quantity-product">0</span></i></a>
		    </li>
		  </div>
	</div>
</nav>
<!-- end menu navbar -->

<div id="top" title="Back to top">
	<i class="fas fa-angle-up icon-back-to-top"></i>
</div>


<div class="content-body">
<!-- list product -->
	@yield('content')
<!-- end list best sales product -->
</div>

<!-- footer page -->
<div class="mt-5">
	<div class="footer">
		<h1>Lavie</h1>
		<p>Contact: + 090909090</p>
		<i class="fas fa-circle-notch fa-spin"></i>
	</div>
</div>
<!-- end footer page -->

</body>
</html>