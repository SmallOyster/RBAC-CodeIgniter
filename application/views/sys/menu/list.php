<?php 
/**
 * @name V-菜单管理
 * @author SmallOysyer <master@xshgzs.com>
 * @since 2018-02-17
 * @version V1.0 2018-02-17
 */
?>

<!DOCTYPE html>
<html>

<head>
	<?php $this->load->view('include/header'); ?>
	<title>菜单管理 / <?php echo $this->config->item('systemName'); ?></title>
</head>

<body>
<div id="wrapper">

<?php $this->load->view('include/navbar'); ?>

<div id="page-wrapper">
<!-- Page Main Content -->

<!-- Page Name-->
<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header">菜单管理</h1>
		<a href="<?php echo site_url('sys/menu/add/0'); ?>" class="btn btn-success" style="width: 98%">新 增 主 菜 单</a>
	</div>
</div>
<!-- ./Page Name-->

<table class="table table-hover" style="border-radius: 5px; border-collapse: separate;">
	<thead>
		<tr>
			<th>菜单名</th>
			<th>操作</th>
		</tr>
	</thead>
	
	<tbody>
	<?php
	foreach($list as $info){
	?>
	<tr>
		<td><i class="fa fa-<?php echo $info['icon']; ?>" aria-hidden="true"></i> <?php echo $info['name']; ?></td>
		<td><a href="<?php echo site_url('sys/menu/edit/').$info['id']; ?>" class="btn btn-info">编辑</a> <button onclick='del_ready("<?php echo $info['id']; ?>","<?php echo $info['name']; ?>")' class="btn btn-danger">删除</button> <a href="<?php echo site_url('sys/menu/add/').$info['id']; ?>" class="btn btn-success">新增子菜单</a></td>
	</tr>
	<?php
	foreach($info['child'] as $child_info){
	?>
	<tr>
		<td>---- <i class="fa fa-<?php echo $child_info['icon']; ?>" aria-hidden="true"></i> <?php echo $child_info['name']; ?></td>
		<td><a href="<?php echo site_url('sys/menu/edit/').$child_info['id']; ?>" class="btn btn-info">编辑</a> <button onclick='del_ready("<?php echo $child_info['id']; ?>","<?php echo $child_info['name']; ?>")' class="btn btn-danger">删除</button> <a href="<?php echo site_url('sys/menu/add/').$child_info['id']; ?>" class="btn btn-success">新增子菜单</a></td>
	</tr>
	<?php
		foreach($child_info['child'] as $child2_info){
	?>
	<tr>
		<td>-------- <i class="fa fa-<?php echo $child2_info['icon']; ?>" aria-hidden="true"></i> <?php echo $child2_info['name']; ?></td>
		<td><a href="<?php echo site_url('sys/menu/edit/').$child2_info['id']; ?>" class="btn btn-info">编辑</a> <button onclick='del_ready("<?php echo $child2_info['id']; ?>","<?php echo $child2_info['name']; ?>")' class="btn btn-danger">删除</button></td>
	</tr>
	<?php
		}
	}
}
?>
</tbody>
</table>

<!-- ./Page Main Content -->
</div>
<!-- ./Page -->
</div>

<script>
function del_ready(id,name){
	$("#delID").val(id);
	$("#delName_show").html(id+". "+name);
	$("#delModal").modal('show');
}


function del_sure(){
	lockScreen();
	id=$("#delID").val();

	$.ajax({
		url:"<?php echo site_url('sys/menu/toDel'); ?>",
		type:"post",
		dataType:"json",
		data:{<?php echo $this->ajax->showAjaxToken(); ?>,"id":id},
		error:function(e){
			console.log(e);
			unlockScreen();
			$("#delModal").modal('hide');
			$("#tips").html("服务器错误！<hr>请联系技术支持并提交以下错误码：<br><font color='blue'>"+e.status+"</font>");

			$("#tipsModal").modal('show');
			return false;
		},
		success:function(ret){
			unlockScreen();
			
			if(ret.code=="200"){
				$("#delModal").modal('hide');
				alert("删除成功！");
				location.reload();
				return true;
			}else if(ret.message=="deleteFailed"){
				$("#delModal").modal('hide');
				$("#tips").html("删除失败！！！");
				$("#tipsModal").modal('show');
				return false;
			}else if(ret.code=="0"){
				$("#delModal").modal('hide');
				$("#tips").html("Token无效！<hr>Tips:请勿在提交前打开另一页面哦~");
				$("#tipsModal").modal('show');
				return false;
			}else{
				$("#delModal").modal('hide');
				$("#tips").html("系统错误！<hr>请联系技术支持并提交以下错误码：<br><font color='blue'>"+ret.code+"</font>");
				$("#tipsModal").modal('show');
				return false;
			}
		}
	});
}
</script>

<?php $this->load->view('include/tipsModal'); ?>

<div class="modal fade" id="delModal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
				<h3 class="modal-title" id="ModalTitle">温馨提示</h3>
			</div>
			<div class="modal-body">
				<input type="hidden" id="delID">
				<center>
				<font color="red" style="font-weight:bolder;font-size:23px;">确定要删除下列菜单吗？</font>
				<br><br>
				<font color="blue" style="font-weight:bolder;font-size:23px;"><p id="delName_show"></p></font>
				</center>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success" data-dismiss="modal">&lt; 返回</button> <button type="button" class="btn btn-danger" onclick="del_sure();">确定 &gt;</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

</body>
</html>
