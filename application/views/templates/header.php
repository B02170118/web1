<header class="main-header">
    <!-- Logo -->
    <a href="/" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini"><b>後台</b></span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg"><b></b>後台</span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <!-- User Account: style can be found in dropdown.less -->
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <span class="hidden-xs">admin</span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <li class="user-header">
                            <!-- <img src="assets/dist/img/user2-160x160.jpg" class="img-circle" alt="User Image"> -->
                            <p>admin</p>
                        </li>
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="pull-right">
                                <a href="/logout" class="btn btn-default btn-flat">登出</a>
                            </div>
                        </li>
                    </ul>
                </li>
                <!-- Control Sidebar Toggle Button -->
            </ul>
        </div>
    </nav>
</header>

<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <!-- <div class="user-panel">
            <h3>admin</h3>
        </div> -->
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu" data-widget="tree">
            <li class="header">MENU</li>
            <li <?php echo ($this->router->class == 'activity')? 'class="active"': NULL?>>
                <a href="./activity"><span>首頁活動管理</span></a>
            </li>
            <li <?php echo ($this->router->class == 'image')? 'class="active"': NULL?>>
                <a href="./image"><span>攝影作品管理</span></a>
            </li>
            <li <?php echo ($this->router->class == 'video')? 'class="active"': NULL?>>
                <a href="./video"><span>影片作品管理</span></a>
            </li>
            <li <?php echo ($this->router->class == 'makeup')? 'class="active"': NULL?>>
                <a href="./makeup"><span>新秘造型管理</span></a>
            </li>
            <li <?php echo ($this->router->class == 'dress')? 'class="active"': NULL?>>
                <a href="./dress"><span>婚紗禮服管理</span></a>
            </li>
            <li <?php echo ($this->router->class == 'staff')? 'class="active"': NULL?>>
                <a href="./staff"><span>關於我們管理</span></a>
            </li>
            <li <?php echo ($this->router->class == 'blog')? 'class="active"': NULL?>>
                <a href="./blog"><span>部落格管理</span></a>
            </li>
            <li <?php echo ($this->router->class == 'combowe')? 'class="active"': NULL?>>
                <a href="./combowe"><span>包套方案管理</span></a>
            </li>
            <li <?php echo ($this->router->class == 'qa')? 'class="active"': NULL?>>
                <a href="./qa"><span>常見問答管理</span></a>
            </li>
            <li <?php echo ($this->router->class == 'contact')? 'class="active"': NULL?>>
                <a href="./contact"><span>預約紀錄</span></a>
            </li>
        </ul>
    </section>
</aside>