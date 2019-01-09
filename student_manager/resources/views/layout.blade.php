<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laravel 5.7 CRUD Example Tutorial</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet" type="text/css" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>AdminLTE 2 | Simple Tables</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.7 -->
    <!-- Font Awesome -->

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- Ionicons -->

    <!-- Theme style -->
    <link rel="stylesheet" href="{{asset('css/AdminLTE.min.css')}}">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="{{asset('css/_all-skins.min.css')}}">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- Google Font -->
    <link rel="stylesheet" href="./AdminLTE%202%20_%20Simple%20Tables_files/css">
</head>
<body class="skin-blue sidebar-mini" style="height: auto; min-height: 100%;">
<div class="wrapper" style="height: auto; min-height: 100%;" id="app">

    <!-- Main Header -->
    <header class="main-header">

        <!-- Logo -->
        <a class="logo" href="index2.html">
            <!-- mini logo for sidebar mini 50x50 pixels -->
            <span class="logo-mini"><b>A</b>LT</span>
            <!-- logo for regular state and mobile devices -->
            <span class="logo-lg"><b>Admin</b>LTE</span>
        </a>

        <!-- Header Navbar -->
        <nav class="navbar navbar-static-top" role="navigation">
            <!-- Sidebar toggle button-->
            <a class="sidebar-toggle" role="button" href="#" data-toggle="push-menu">
                <span class="sr-only">Toggle navigation</span>
            </a>
        </nav>
    </header>
    <!-- Left side column. contains the logo and sidebar -->
    <aside class="main-sidebar">

        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">

            <!-- Sidebar user panel (optional) -->
            <div class="user-panel">
                <div class="pull-left image">
                    <img class="img-circle" alt="User Image" src="{{asset('images/user2-160x160.jpg')}}">
                </div>
                <div class="pull-left info">
                    <p>Alexander Pierce</p>
                    <!-- Status -->
                </div>
            </div>

            <!-- search form (Optional) -->
            <form class="sidebar-form" action="#" method="get">
                <div class="input-group">
                    <input name="q" class="form-control" type="text" placeholder="Search...">
                    <span class="input-group-btn">
              <button name="search" class="btn btn-flat" id="search-btn" type="submit"><i class="fa fa-search"></i>
              </button>
            </span>
                </div>
            </form>
            <!-- /.search form -->

            <!-- Sidebar Menu -->
            <ul class="sidebar-menu tree" data-widget="tree">
                <li class="header">HEADER</li>
                <!-- Optionally, you can add icons to the links -->
                <li class="active">
                    <a href="students"><i class="fa fa-dashboard"></i> <span>Student Management</span></a>
                </li>
            </ul>
            <!-- /.sidebar-menu -->
        </section>
        <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper" style="min-height: 462px;">
        <!-- Content Header (Page header) -->
        <section class="content-header">

        </section>

        <!-- Main content -->
        <section class="content container-fluid">

            @yield('content')

        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
</div>

<script src="{{asset('js/app.js')}}"></script>

</body>

<script async="" src="./AdminLTE%202%20_%20Simple%20Tables_files/analytics.js"></script><script src="./AdminLTE%202%20_%20Simple%20Tables_files/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="./AdminLTE%202%20_%20Simple%20Tables_files/bootstrap.min.js"></script>
<!-- Slimscroll -->
<script src="./AdminLTE%202%20_%20Simple%20Tables_files/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="./AdminLTE%202%20_%20Simple%20Tables_files/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="./AdminLTE%202%20_%20Simple%20Tables_files/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="./AdminLTE%202%20_%20Simple%20Tables_files/demo.js"></script>
<script src="{{ asset('js/app.js') }}" type="text/js"></script>
</body>
</html>
