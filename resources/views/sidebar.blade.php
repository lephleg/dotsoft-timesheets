<!-- Left side column. contains the sidebar -->
<aside class="main-sidebar">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        
        <!-- Sidebar Menu -->
        <ul class="sidebar-menu">
            <li class="header text-center">MANAGEMENT</li>
            <!-- Optionally, you can add icons to the links -->
            <li class="{{ Request::is('employees') ? 'active' : '' }}"><a href="{{ URL::to('employees') }}"><i
                            class="fa fa-users"></i> <span>Employees</span></a></li>
        </ul><!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
</aside>