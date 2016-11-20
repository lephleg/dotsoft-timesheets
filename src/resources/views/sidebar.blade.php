<!-- Left side column. contains the sidebar -->
<aside class="main-sidebar">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        
        <!-- Sidebar Menu -->
        <ul class="sidebar-menu">
            <li class="header text-center">MONITORING</li>
            <!-- Optionally, you can add icons to the links -->
            <li class="treeview {{ Request::is('/') || Request::is('month') ? 'active' : '' }}">
                <a href="#">
                    <i class="fa fa-calendar"></i>
                    <span>Timesheets</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li class="{{ Request::is('/') ? 'active' : '' }}">
                        <a href="{{ URL::to('/') }}">
                            <i class="fa fa-circle-o"></i>
                            <span>Daily</span>
                            <small class="label pull-right bg-green">
                                in test
                            </small>
                        </a>
                    </li>
                    <li class="{{Request::is('month') ? 'active' : '' }}">
                        <a href="{{ URL::to('month') }}">
                            <i class="fa fa-circle-o"></i>
                            <span>Monthly</span>
                            <span class="pull-right-container">
                              <small class="label pull-right bg-yellow">
                                  work in progress
                              </small>
                            </span>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="header text-center">MANAGEMENT</li>
            <!-- Optionally, you can add icons to the links -->
            <li class="{{ Request::is('employees') ? 'active' : '' }}">
                <a href="{{ URL::to('employees') }}">
                    <i class="fa fa-users"></i>
                    <span>Employees</span>
                    <small class="label pull-right bg-red">
                        dummy
                    </small>
                </a>
            </li>
        </ul><!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
</aside>