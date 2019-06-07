<?php 
/**
 * @name 生蚝科技RBAC开发框架-V-主页
 * @author Jerry Cheung <master@xshgzs.com>
 * @since 2018-10-20
 * @version 2019-05-26
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
	<?php $this->load->view('include/pagePath',['name'=>$this->setting->get('systemName'),'path'=>[['首页','',1]]]); ?>

	<!-- 页面主要内容 -->
	<section class="content">
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
