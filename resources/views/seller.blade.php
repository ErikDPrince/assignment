<!DOCTYPE html>
<html>
<head>

<link rel="stylesheet" href="{{asset('css/seller.css')}}">
<style>
body {
  font-family: "Lato", sans-serif;
}

.sidebar {
  height: 100%;
  width: 160px;
  position: fixed;
  z-index: 1;
  top: 0;
  left: 0;
  background-color: #111;
  overflow-x:hidden;
  transition: 0.5s;
  padding-top: 40px;
}

.sidebar a {
  padding: 15px;
  text-decoration: none;
  font-size: 20px;
  color: #818181;
  display: block;
  text-align:center;
  transition: 0.3s;
}

.sidebar a:hover {
  color: #f1f1f1;
}

.sidebar .closebtn {
  position: absolute;
  top: 0;
  right: 25px;
  font-size: 36px;
  margin-left: 50px;
}

.openbtn {
  font-size: 20px;
  cursor: pointer;
  background-color: #111;
  color: white;
  padding: 10px 15px;
  border: none;
}

.openbtn:hover {
  background-color: #444;
}

}

/* On smaller screens, where height is less than 450px, change the style of the sidenav (less padding and a smaller font size) */
@media screen and (max-height: 450px) {
  .sidebar {padding-top: 15px;}
  .sidebar a {font-size: 18px;}
}
</style>
</head>
<body>

<div id="mySidebar" class="sidebar">
  <a href="#"><i class="fa fa-home" aria-hidden="true"></i>Product</a>
  <a href="#">Agency</a>
  <a href="#">Homepage</a>


   
</body>
</html> 
