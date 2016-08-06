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
  <div class="header">
    <nav class="cbp-spmenu cbp-spmenu-vertical cbp-spmenu-right" id="cbp-spmenu-s2">
        <a href="/">Home</a>
        <a href="/portfolio">Portfolio</a>
        <a href="#">Quote</a>
        <a href="/about_us">About Us</a>
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
      <li><a href="#" >801-998-8195</a></li>
      <li><a href="/" class="navbar_link">Home</a></li>
      <li><a href="/" class="navbar_link">Browse</a></li>
      <li><a href="/" class="navbar_link">Quote</a></li>
      <li><a href="/" class="navbar_link">Portfolio</a></li>
      <li><a href="/" class="navbar_link">About Us</a></li>
      @if(Auth::guest())
        <li><a href="/login" class="navbar_link">Sign In</a></li>
        @else
        <li class="account_drop_down">
          <a href="#" class="navbar_link" >{{Auth::user()->name}}</a>
          <ul hidden>
            <li><a class="navbar_link" href="/auth/logout" >Sign Out</a></li>
          </ul>
        </li>
      @endif
      </ul>
    </div>
  </div>
  @yield('content')

  <div class="footer">
    <div style="padding-top:60px;margin:20px;width:100%">
      Copyright &copy; 2016 <a href="#">Mountain West Design</a>. All rights reserved.
          <a href="/login" class="fa fa-lock" style="font-size:25px;position:relative;right:0px"></a>
    </div>
  </footer>

</body>
</html>
