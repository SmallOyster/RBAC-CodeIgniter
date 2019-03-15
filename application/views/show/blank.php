<?php 
/**
 * @name V-空白页
 * @author Jerry Cheung <master@xshgzs.com>
 * @since 2018-03-15
 * @version V1.0 2018-08-07
 */ 
?>

<!DOCTYPE html>
<html>

<head>
  <?php $this->load->view('include/header'); ?>
  <title>空白页 / <?=$this->Setting_model->get('systemName');?></title>
</head>

<body>
<div id="wrapper">

<?php $this->load->view('include/navbar'); ?>

<!-- Page Content -->
<div id="page-wrapper">

<!-- Page Name-->
<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header">空白页</h1>
	</div>
</div>
<!-- ./Page Name-->
  
<?php $this->load->view('include/footer'); ?>

<!-- ./Page Main Content -->
</div>
<!-- ./Page -->
</div>
</body>
</html>
