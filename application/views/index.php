<?php 
/**
 * @name V-主页
 * @author SmallOysyer <master@xshgzs.com>
 * @since 2018-02-06
 * @version V1.0 2018-03-28
 */ 
?>
<!DOCTYPE html>
<html>

<head>
  <?php $this->load->view('include/header'); ?>
  <title><?php echo $this->Setting_model->get('systemName'); ?></title>
</head>

<body>
<div id="wrapper">

<?php $this->load->view('include/navbar'); ?>

<!-- Page Main Content -->
<div id="page-wrapper">
<div class="row">
  <div class="col-lg-12">
    <h1 class="page-header">Dashboard</h1>
  </div>
  <!-- /.col-lg-12 -->
</div>
<!-- /.row -->

<div class="row">
  <div class="col-lg-3 col-md-6">
    <div class="panel panel-primary">
      <div class="panel-heading">
        <div class="row">
          <div class="col-xs-3">
            <i class="fa fa-comments fa-5x"></i>
          </div>
          <div class="col-xs-9 text-right">
            <div class="huge">26</div>
            <div>New Comments!</div>
          </div>
        </div>
      </div>
      <a href="#">
        <div class="panel-footer">
          <span class="pull-left">View Details</span>
          <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
          <div class="clearfix"></div>
        </div>
      </a>
    </div>
  </div>
</div>

<!-- ▼ 通知栏 ▼ -->
<ul class="list-group">
	<?php foreach($allNotice as $info){ ?>
	<li class="list-group-item">
		<div class="row">
			<div class="col-xs-8">
				<a href="<?php echo site_url('notice/detail/').$info['id']; ?>" target="_blank">
					<i class="fa fa-bullhorn"></i> <?php echo $info['title']; ?>
				</a>
			</div>
		
			<div class="col-xs-4" style="text-align:right;">
				<?php echo substr($info['create_time'],0,10);?>
		</div>		
		</div>
	</li>
	<?php } ?>
</ul>
<!-- ▲ 通知栏 ▲ -->

<!-- ./Page Main Content -->
</div>
<!-- ./Page -->
</div>
</body>
</html>
