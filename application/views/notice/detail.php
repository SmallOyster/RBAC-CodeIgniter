<?php 
/**
 * @name 生蚝体育竞赛管理系统后台-V-通知详情
 * @author Jerry Cheung <master@xshgzs.com>
 * @since 2018-03-30
 * @version 2019-03-17
 */ 
?>
<!DOCTYPE html>
<html>
<head>
	<?php $this->load->view('include/header'); ?>
	<title>通知详情 / <?=$this->setting->get('systemName');?></title>
</head>

<body class="hold-transition skin-cyan sidebar-mini">
<div class="wrapper">

<?php $this->load->view('include/navbar'); ?>

<!-- 页面内容 -->
<div id="app" class="content-wrapper">
	<?php $this->load->view('include/pagePath',['name'=>$info['title'],'path'=>[['通知列表','',1]]]); ?>

	<!-- 页面主要内容 -->
	<section class="content">
		<?=$info['content']; ?> 
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
