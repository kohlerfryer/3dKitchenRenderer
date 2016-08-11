<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Mountain West Design</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="../css/bootstrap.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../css/AdminLTE.css">
  <link rel="stylesheet" href="../css/skin-blue.css">


    <!-- jQuery 2.2.3 -->
  <script src="../js/jquery.js"></script>
  <!-- Bootstrap 3.3.6 -->
  <script src="../js/admin.js"></script>
  <!-- AdminLTE App -->
  <script src="../js/app.min.js"></script>

  <script src="../js/admin_pannel.js"></script>

</head>

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <!-- Main Header -->
  <header class="main-header">

    <!-- Logo -->
    <a href="index2.html" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>M</b>WD</span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b>Admin Panel</span>
    </a>

    <nav class="navbar navbar-static-top" role="navigation">
      <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>
      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <li>
          <a href="/" class="fa fa-home" style="font-size:19px" role="button">
          </a>
          </li>
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <span class="hidden-xs" style="font-family:Source Sans Pro;font-weight:normal">Welcome {{$user_name->first_name}}!</span>
            </a>
          </li>
          @if ($new_quotes_count > 0)
          <li>
            <a href="/admin/new_quotes" class="fa fa-flag" style="font-size:18px;" role="button"><span style="font-size:14px;font-family:Source Sans Pro;padding-left:10px">{{$new_quotes_count}} new quotes</span></a>
          </li>
          @endif
          <li class="user-menu">
            <a href="#" class="btn btn-flat" id="btn_sign_out">Sign out</a>
          </li>
        </ul>
      </div>
    </nav>
  </header>
  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <ul class="sidebar-menu">
        @foreach ($current_admin_pages as $name => $link)
          @if($link == $page_request)
            <li  class="active"><a href="{{$link}}"><i class=""></i><span>{{$name}}</span></a></li>
          @else
            <li  class=""><a href="{{$link}}"><i class=""></i><span>{{$name}}</span></a></li>
          @endif
        @endforeach 
      </ul>
    </section>
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->

    @yield('content')
    <section class="content" id="loading_section" hidden>
      <center>
        <img src="../images/loader.gif" width="100px" height="100px"/>
        <h4>Updating Stone Information and rendering 3d room image layouts. Please Wait.</h4>
        </center>
    </section>
  </div>
  <!-- Main Footer -->
  <footer class="main-footer">
    <!-- To the right -->

    <!-- Default to the left -->
    <strong>Copyright &copy; 2016 <a href="#">Mountain West Design</a>.</strong> All rights reserved.
  </footer>


</div>

</body>
</html>
