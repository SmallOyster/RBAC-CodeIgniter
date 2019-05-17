<?php 
/**
 * @name 生蚝科技RBAC开发框架-V-通知列表
 * @author Jerry Cheung <master@xshgzs.com>
 * @since 2018-07-19
 * @version 2019-05-13
 */
?>
<!DOCTYPE html>
<html>
<head>
	<?php $this->load->view('include/header'); ?>
	<title>通知列表 / <?=$this->setting->get('systemName');?></title>
</head>

<body class="hold-transition skin-cyan sidebar-mini">
<div class="wrapper">

<?php $this->load->view('include/navbar'); ?>

<!-- 页面内容 -->
<div id="app" class="content-wrapper">
	<?php $this->load->view('include/pagePath',['name'=>'通知列表','path'=>[['通知列表','',1]]]); ?>

	<!-- 页面主要内容 -->
	<section class="content">
		<div class="box">
			<div class="box-body">

				<table id="table" class="table table-striped table-bordered table-hover" style="border-radius: 5px; border-collapse: separate;">
					<thead>
						<tr>
							<th>标题</th>
							<th>作者</th>
							<th>时间</th>
						</tr>
					</thead>

					<tbody>
						<?php foreach($list as $info){ ?>
							<tr>
								<td><a href="<?=base_url('notice/detail/').$info['id'];?>"><?=$info['title'];?></a></td>
								<td><?=$info['publisherName'];?></td>
								<td><?=$info['create_time'];?></td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
		</div>
	</section>
</div>

<?php $this->load->view('include/footer'); ?>

<script>
window.onload=function(){
	$('#table').DataTable({
		responsive: true,
		"order":[[2,'desc']]
	});
};
</script>

</body>
</html>
