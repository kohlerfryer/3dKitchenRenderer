<!doctype html>

<html lang="en">
<head>
  <meta charset="utf-8">

  <title>Mountain West Design</title>

  <meta name="description" content="Mountain West Design">
  <meta name="author" content="SitePoint">
  <meta name="viewport" content="width=device-width, maximum-scale=1">

  <link rel="stylesheet" href=" {{asset('css/slider_menu.css')}}">
  <link rel="stylesheet" href="{{asset('css/main.css')}}">

  <script src="{{asset('js/jquery.js')}}"></script>
  <script src="{{asset('js/main.js')}}"></script>
  <script src="{{asset('js/classie.js')}}"></script>
  <script src= "{{asset('js/kitchen_dreamer.js')}}"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">


</head>

<body>
  <div class="wrapper">
    <div class="header">
      <nav class="cbp-spmenu cbp-spmenu-vertical cbp-spmenu-right" id="cbp-spmenu-s2">
          <a href="/login">Login</a>
          <a href="/register">Register</a>
          <a href="/">Home</a>
        </nav>

       <div id="main_page_logo">
        <img  src="{{asset('images/mountain_logo_orange.png')}}" width="60px"/>
        <img id="words_logo" src="{{asset('images/mountain_words_logo_orange.png')}}" width="140px"/>
       </div>

      <div class="navbar_mobile">
        <button id="showRight">Menu</button>
      </div>

      <div class="navbar">
        <ul>
        <li><a href="/login">Login</a></li>
        <li><a href="/register" class="navbar_link">Register</a></li>
        </ul>
      </div>
    </div>
        @yield('content')
  </div>
    <div class="footer">
      <div style="padding-top:60px;width:100%">
        Copyright &copy; 2016 <a href="#">Mountain West Design</a>. All rights reserved.
            <a href="/admin/add_stone" class="fa fa-lock" style="font-size:25px;"></a>
      </div>
    </div>


</body>
</html>
