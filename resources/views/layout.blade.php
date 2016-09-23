<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html>
<head>
    <meta charset="UTF-8">
    <title>{{ $page_title or "Timesheets v2 alpha" }}</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <!-- Bootstrap 3.3.2 -->
    <link href="{{ asset("adminlte/bootstrap/css/bootstrap.min.css") }}" rel="stylesheet" type="text/css" />
    <!-- Font Awesome Icons -->
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <!-- Ionicons -->
    <link href="http://code.ionicframework.com/ionicons/2.0.0/css/ionicons.min.css" rel="stylesheet" type="text/css" />
    <!-- DataTables -->
    <link href="{{ asset("adminlte/plugins/datatables/dataTables.bootstrap.css") }}" rel="stylesheet" type="text/css" />
    <!-- FullCalendar & Scheduler -->
    <link href="{{ asset("adminlte/plugins/fullcalendar-scheduler/lib/fullcalendar.min.css") }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset("adminlte/plugins/fullcalendar-scheduler/scheduler.min.css") }}" rel="stylesheet" type="text/css" />
    <!-- bootstrap datepicker -->
    <link href="{{ asset("adminlte/plugins/datepicker/datepicker3.css") }}" rel="stylesheet" type="text/css" />

    <!-- Theme style -->
    <link href="{{ asset("adminlte/dist/css/AdminLTE.min.css")}}" rel="stylesheet" type="text/css" />
    <!-- AdminLTE Skins. We have chosen the skin-blue for this starter
          page. However, you can choose any other skin. Make sure you
          apply the skin class to the body tag so the changes take effect.
    -->
    <link href="{{ asset("adminlte/dist/css/skins/skin-red.min.css")}}" rel="stylesheet" type="text/css" />

    <!-- Custom theming -->
    <link href="{{ asset("css/custom.css") }}" rel="stylesheet" type="text/css" />


    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
</head>
<body class="skin-red sidebar-mini">
<div class="wrapper">

    <!-- Header -->
@include('header')

<!-- Sidebar -->
@include('sidebar')

<!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                {{ $pageTitle or null }}
                <small>{{ $pageDescription or null }}</small>
            </h1>
            <!-- You can dynamically generate breadcrumbs here -->
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
                <li class="active">Here</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <!-- Your Page Content Here -->
            @yield('content')
        </section><!-- /.content -->
    </div><!-- /.content-wrapper -->

    <!-- Footer -->
    @include('footer')

</div><!-- ./wrapper -->

<!-- REQUIRED JS SCRIPTS -->

<!-- jQuery 2.1.3 -->
<script src="{{ asset ("adminlte/plugins/jQuery/jquery-2.2.3.min.js") }}"></script>
<!-- Bootstrap 3.3.2 JS -->
<script src="{{ asset ("adminlte/bootstrap/js/bootstrap.min.js") }}" type="text/javascript"></script>
<!-- DataTables -->
<script src="{{ asset ("adminlte/plugins/datatables/jquery.dataTables.min.js") }}" type="text/javascript"></script>
<script src="{{ asset ("adminlte/plugins/datatables/dataTables.bootstrap.min.js") }}" type="text/javascript"></script>
<!-- FullCalendar -->
<script src="{{ asset ("adminlte/plugins/fullcalendar-scheduler/lib/moment.min.js") }}" type="text/javascript"></script>
<script src="{{ asset ("adminlte/plugins/fullcalendar-scheduler/lib/fullcalendar.min.js") }}" type="text/javascript"></script>
<script src="{{ asset ("adminlte/plugins/fullcalendar-scheduler/scheduler.js") }}" type="text/javascript"></script>
<!-- bootstrap datepicker -->
<script src="{{ asset ("adminlte/plugins/datepicker/bootstrap-datepicker.js") }}" type="text/javascript"></script>
<!-- AdminLTE App -->
<script src="{{ asset ("adminlte/dist/js/app.min.js") }}" type="text/javascript"></script>
<script src="{{ asset ("js/custom.js") }}" type="text/javascript"></script>

<!-- Optionally, you can add Slimscroll and FastClick plugins.
      Both of these plugins are recommended to enhance the
      user experience -->
</body>
</html>