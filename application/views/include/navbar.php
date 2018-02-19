<!-- Navigation -->
<nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
	<div class="navbar-header">
		<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
			<span class="sr-only">Toggle navigation</span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
		</button>
		<a class="navbar-brand" href="<?php echo site_url(); ?>"><?php echo $this->config->item('systemName'); ?></a>
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
				<li>
					<!-- @TODO 动态显示用户名 -->
					<!-- @TODO 显示对应时段的问候语 -->
					<a href="javascript:void(0)"><b><font color="green">super</font></b>，你好！</a>
				</li>
				<li>
					<!-- @TODO 动态显示角色名 -->
					<a href="javascript:void(0)">角色：<b><font color="#F57C00">超级管理员</font></b></a>
				</li>
				<li class="divider"></li>
				<li>
					<a href="<?php echo site_url('user/updateProfile'); ?>">
						<i class="fa fa-user fa-fw"></i>修改个人资料</a>
				</li>
				<li>
					<a href="login.html">
						<i class="fa fa-sign-out fa-fw"></i>登出系统</a>
				</li>
			</ul>
		</li>
		<!-- /.dropdown-user -->
	</ul>
	<!-- /.dropdown-head-right -->

	<!-- /.navbar-main -->
	<div class="navbar-default sidebar" role="navigation">
		<div class="sidebar-nav navbar-collapse">
			<ul class="nav" id="side-menu">
				<li>
					<a href="<?php echo site_url(); ?>">
						<i class="fa fa-home fa-fw"></i>主页面</a>
				</li>
				<li>
					<a href="#">
						<i class="fa fa-files-o fa-fw"></i>Sample Pages
						<span class="fa arrow"></span></a>
					<ul class="nav nav-second-level">
						<li>
							<a href="<?php echo site_url('show/blank'); ?>">Blank Page</a></li>
						<li>
							<a href="<?php echo site_url('show/login'); ?>">Login Page</a></li>
					</ul>
				</li>
				<!-- nav-second-level -->
				<?php
				// 显示父菜单
				foreach($navData as $info){
					if($info['hasChild']!="1"){
					// 没有二级菜单
				?>
					<li>
						<a href="<?php echo site_url($info['url']); ?>">
							<i class="fa fa-<?php echo $info['icon']; ?>" aria-hidden="true"></i>
							<?php echo $info['name']; ?>
						</a>
					</li>
					<!-- ./父菜单 -->
				<?php
					}else{
					// 有二级菜单
				?>
					<li>
						<a href="#">
							<i class="fa fa-<?php echo $info['icon']; ?>" aria-hidden="true"></i>
							<?php echo $info['name']; ?>
							<span class="fa arrow"></span>
						</a>
						<ul class="nav nav-second-level">
						<?php 
						// 显示二级菜单
						foreach($info['child'] as $child_info){
							if($child_info['hasChild']!="1"){
							// 没有三级菜单
						?>
							<li>
								<a href="<?php echo site_url($child_info['url']); ?>">
									<i class="fa fa-<?php echo $child_info['icon']; ?>" aria-hidden="true"></i>
									<?php echo $child_info['name']; ?>
								</a>
							</li>
							<!-- ./二级菜单 -->
						<?php
							}else{
							// 有三级菜单
						?>
							<li>
								<a href="#">
									<i class="fa fa-<?php echo $child_info['icon']; ?>" aria-hidden="true"></i>
									<?php echo $child_info['name']; ?>
									<span class="fa arrow"></span>
								</a>
								<ul class="nav nav-third-level">
									<?php
									// 显示三级菜单
									foreach($child_info['child'] as $child2_info){
									?>
									<li>
										<a href="<?php echo site_url($child2_info['url']); ?>">
											<i class="fa fa-<?php echo $child2_info['icon']; ?>" aria-hidden="true"></i>
											<?php echo $child2_info[ 'name']; ?>
										</a>
									</li>
									<!-- ./三级菜单 -->
									<?php } ?>
								</ul>
							</li>
							<!-- ./二级菜单 -->
						<?php } } ?>
					</ul>
				</li>
				<!-- ./父菜单 -->
				<?php } } ?>
			</ul>
		</div>
	</div>
	<!-- /.navbar-main -->
</nav>