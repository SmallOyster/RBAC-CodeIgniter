<?php 
/**
 * @name V-通知详情
 * @author SmallOysyer <master@xshgzs.com>
 * @since 2018-03-30
 * @version 2018-10-24
 */ 
?>

<!DOCTYPE html>
<html>

<head>
  <?php $this->load->view('include/header'); ?>
  <title>通知详情 / <?=$this->Setting_model->get('systemName'); ?></title>
</head>

<body>
<div id="wrapper">

<?php $this->load->view('include/navbar'); ?>

<!-- Page Content -->
<div id="page-wrapper">

<!-- Page Name-->
<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header"><?php echo $info['title']; ?></h1>
	</div>
</div>
<!-- ./Page Name-->

<?php echo $info['content']; ?> 

<?php $this->load->view('include/footer'); ?>

<!-- ./Page Main Content -->
</div>
<!-- ./Page -->
</div>
</body>
</html>
