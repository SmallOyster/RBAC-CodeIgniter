
<!-- Navigation -->
<nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
  <div class="navbar-header">
    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
      <span class="sr-only">Toggle navigation</span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
    </button>
    <a class="navbar-brand" href="index.php">SB Admin v2.0</a>
  </div>

  <!-- /.dropdown-head-right -->
  <ul class="nav navbar-top-links navbar-right">
    <!-- /.dropdown-message -->
    <li class="dropdown">
      <a class="dropdown-toggle" data-toggle="dropdown" href="#">
        <i class="fa fa-envelope fa-fw"></i>
        <i class="fa fa-caret-down"></i>
      </a>
      <ul class="dropdown-menu dropdown-messages">
        <li>
          <a href="#">
            <div>
              <strong>John Smith</strong>
              <span class="pull-right text-muted">
                <em>Yesterday</em></span>
            </div>
            <div>Message Content</div></a>
        </li>
        <li class="divider"></li>
        <li>
          <a class="text-center" href="#">
            <strong>Read All Messages</strong>
            <i class="fa fa-angle-right"></i>
          </a>
        </li>
      </ul>
    </li>
    <!-- /.dropdown-message -->

    <!-- /.dropdown-progress -->
    <li class="dropdown">
      <a class="dropdown-toggle" data-toggle="dropdown" href="#">
        <i class="fa fa-tasks fa-fw"></i>
        <i class="fa fa-caret-down"></i>
      </a>
      <ul class="dropdown-menu dropdown-tasks">
        <li>
          <a href="#">
            <div>
              <p>
                <strong>Task 1</strong>
                <span class="pull-right text-muted">40% Complete</span></p>
              <div class="progress progress-striped active">
                <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 40%">
                  <span class="sr-only">40% Complete (success)</span></div>
              </div>
            </div>
          </a>
        </li>
        <li class="divider"></li>
        <li>
          <a class="text-center" href="#">
            <strong>See All Tasks</strong>
            <i class="fa fa-angle-right"></i>
          </a>
        </li>
      </ul>
    </li>
    <!-- /.dropdown-progress -->

    <!-- /.dropdown-alert -->
    <li class="dropdown">
      <a class="dropdown-toggle" data-toggle="dropdown" href="#">
        <i class="fa fa-bell fa-fw"></i>
        <i class="fa fa-caret-down"></i>
      </a>
      <ul class="dropdown-menu dropdown-alerts">
        <li>
          <a href="#">
            <div>
              <i class="fa fa-comment fa-fw"></i>New Comment
              <span class="pull-right text-muted small">4 minutes ago</span></div>
          </a>
        </li>
      </ul>
    </li>
    <!-- /.dropdown-alert -->

    <!-- /.dropdown-user -->
    <li class="dropdown">
      <a class="dropdown-toggle" data-toggle="dropdown" href="#">
        <i class="fa fa-user fa-fw"></i>
        <i class="fa fa-caret-down"></i>
      </a>
      <ul class="dropdown-menu dropdown-user">
        <li><a href="#"><i class="fa fa-user fa-fw"></i>User Profile</a></li>
        <li class="divider"></li>
        <li><a href="login.html"><i class="fa fa-sign-out fa-fw"></i>Logout</a></li>
      </ul>
    </li>
    <!-- /.dropdown-user -->
  </ul>
  <!-- /.dropdown-head-right -->

  <!-- /.navbar-main -->
  <div class="navbar-default sidebar" role="navigation">
    <div class="sidebar-nav navbar-collapse">
      <ul class="nav" id="side-menu">
        <li><a href="index.html"><i class="fa fa-dashboard fa-fw"></i> Dashboard</a></li>
        <li>
          <a href="#"><i class="fa fa-bar-chart-o fa-fw"></i> Charts<span class="fa arrow"></span></a>
          <ul class="nav nav-second-level">
            <li><a href="flot.html">Flot Charts</a></li>
            <li><a href="morris.html">Morris.js Charts</a></li>
          </ul>
          <!-- /.nav-second-level -->
        </li>
        <li><a href="tables.html"><i class="fa fa-table fa-fw"></i> Tables</a></li>
        <li><a href="forms.html"><i class="fa fa-edit fa-fw"></i> Forms</a></li>
        <li>
          <a href="#"><i class="fa fa-wrench fa-fw"></i> UI Elements<span class="fa arrow"></span></a>
          <ul class="nav nav-second-level">
            <li><a href="panels-wells.html">Panels and Wells</a></li>
            <li><a href="buttons.html">Buttons</a></li>
            <li><a href="notifications.html">Notifications</a></li>
            <li><a href="typography.html">Typography</a></li>
            <li><a href="icons.html">Icons</a></li>
            <li><a href="grid.html">Grid</a></li>
          </ul>
          <!-- /.nav-second-level --></li>
        <li>
          <a href="#"><i class="fa fa-sitemap fa-fw"></i> Multi-Level Dropdown<span class="fa arrow"></span></a>
          <ul class="nav nav-second-level">
            <li><a href="#">Second Level Item</a></li>
            <li><a href="#">Third Level<span class="fa arrow"></span></a>
              <ul class="nav nav-third-level">
                <li><a href="#">Third Level Item</a></li>
              </ul>
            </li>
            <!-- /.nav-third-level -->
          </ul>
        </li>
        <!-- /.nav-second-level -->

        <li>
          <a href="#"><i class="fa fa-files-o fa-fw"></i> Sample Pages<span class="fa arrow"></span></a>
          <ul class="nav nav-second-level">
            <li><a href="<?php echo site_url('show/blank'); ?>">Blank Page</a></li>
            <li><a href="<?php echo site_url('show/login'); ?>">Login Page</a></li>
          </ul>
        </li>
        <!-- nav-second-level -->

        <li>
          <a href="#"><i class="fa fa-users fa-fw"></i> 用户管理<span class="fa arrow"></span></a>
          <ul class="nav nav-second-level">
            <li><a href="javascript:void(0);">用户列表</a></li>
          </ul>
        </li>

        <li><a href="#"><i class="fa fa-commenting" aria-hidden="true"></i> 短信管理</a></li>
        <li><a href="#" target="_blank"><i class="fa fa-database" aria-hidden="true"></i> 数据库后台</a></li>
        
        <?php
        // 显示父菜单
        foreach($navData as $info){
          if($info['hasChild']!="1"){
          // 没有二级菜单
        ?>
            <li><a href="<?php echo site_url($info['url']); ?>" target="_blank"><i class="fa fa-<?php echo $info['icon']; ?>" aria-hidden="true"></i> <?php echo $info['name']; ?></a></li>
        <?php
          }else{
        ?>
        <li>
          <a href="<?php echo site_url($info['url']); ?>" target="_blank"><i class="fa fa-<?php echo $info['icon']; ?>" aria-hidden="true"></i> <?php echo $info['name']; ?><span class="fa arrow"></span></a>
          <ul class="nav nav-second-level">
            <?php
            // 显示二级菜单
            foreach($info['child'] as $child_info){
              if($child_info['hasChild']!="1"){
              // 没有三级菜单
            ?>
            <li><a href="<?php echo site_url($child_info['url']); ?>" target="_blank"><i class="fa fa-<?php echo $child_info['icon']; ?>" aria-hidden="true"></i> <?php echo $child_info['name']; ?></a></li>
            <?php
              }else{
              // 有三级菜单
            ?>
            <li><a href="<?php echo site_url($child_info['url']); ?>" target="_blank"><i class="fa fa-<?php echo $child_info['icon']; ?>" aria-hidden="true"></i> <?php echo $child_info['name']; ?><span class="fa arrow"></span></a>
              <ul class="nav nav-third-level">
                <?php
                // 显示三级菜单
                foreach($child_info['child'] as $child2_info){
                ?>
                <li><a href="<?php echo site_url($child2_info['url']); ?>" target="_blank"><i class="fa fa-<?php echo $child2_info['icon']; ?>" aria-hidden="true"></i> <?php echo $child2_info['name']; ?></a></li>              
                <?php
                }
                ?>
              </ul>
            </li>
          <?php
              }
            }
          ?>
          </ul>
        </li>
        <?php
          }
        }
        ?>
      </ul>
    </div>
  </div>
  <!-- /.navbar-main -->
</nav>
