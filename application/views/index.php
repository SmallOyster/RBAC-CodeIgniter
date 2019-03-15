<?php 
/**
 * @name 生蚝科技RBAC开发框架-V-主页
 * @author Jerry Cheung <master@xshgzs.com>
 * @since 2018-10-20
 * @version 2019-03-15
 */
?>
<!DOCTYPE html>
<html>

<head>
	<?php $this->load->view('include/header'); ?>
	<title><?=$this->setting->get('systemName'); ?></title>
</head>

<body class="hold-transition skin-cyan sidebar-mini">
<div class="wrapper">

<?php $this->load->view('include/navbar'); ?>

<!-- 页面内容 -->
<div id="app" class="content-wrapper">
	<!-- 头部(显示页面名称和路径) -->
	<section class="content-header">
		<h1><?=$this->setting->get('systemName'); ?><small>首页</small></h1>
		<ol class="breadcrumb">
			<li><a href="<?=base_url('dashborad');?>"><i class="fa fa-dashboard"></i> <?=$this->setting->get('systemName'); ?></a></li>
			<li class="active">首页</li>
		</ol>
	</section>

	<!-- 页面主要内容 -->
	<section class="content">
		<!-- ▼ 通知栏 ▼ -->
		<ul class="list-group">
			<li class="list-group-item" style="background-color:#eee;font-size:16px;font-weight:bold;">通知列表</li>
			<?php foreach($allNotice as $info){ ?>
				<li class="list-group-item">
					<div class="row">
						<div class="col-xs-8">
							<a href="<?=base_url('notice/detail/').$info['id'];?>" target="_blank">
								<i class="fa fa-bullhorn"></i> <?=$info['title'];?>
							</a>
						</div>

						<div class="col-xs-4" style="text-align:right;">
							<?=substr($info['create_time'],0,10);?>
						</div>
					</div>
				</li>
			<?php } ?>
		</ul>
		<!-- ▲ 通知栏 ▲ -->
	</section>
	<!-- ./页面主要内容 -->
</div>
<!-- ./页面内容 -->

<?php $this->load->view('include/footer'); ?>

<!-- ./Page Main Content -->
</div>
<!-- ./Page -->
</div>
</body>
</html>
