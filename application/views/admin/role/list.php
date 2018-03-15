<?php 
/**
 * @name V-角色列表
 * @author SmallOysyer <master@xshgzs.com>
 * @since 2018-02-09
 * @version V1.0 2018-03-14
 */
?>

<!DOCTYPE html>
<html>

<head>
	<?php $this->load->view('include/header'); ?>
	<title>角色列表 / <?php echo $this->config->item('systemName'); ?></title>
</head>

<body>
<div id="wrapper">

<?php $this->load->view('include/navbar'); ?>

<div id="page-wrapper">
<!-- Page Main Content -->

<!-- Page Name-->
<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header">角色列表</h1>
		<a href="<?php echo site_url('admin/role/add'); ?>" class="btn btn-primary btn-block">新 增 角 色</a>
		<hr>
	</div>
</div>
<!-- ./Page Name-->

<table id="table" class="table table-striped table-bordered table-hover" style="border-radius: 5px; border-collapse: separate;">
	<thead>
		<tr>
			<th>角色名称</th>
			<th>操作</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($list as $info){ ?>
		<tr>
			<td><?php echo $info['name']; ?></td>
			<td>
				<a href="<?php echo site_url('admin/role/edit/').$info['id'].'/'.$info['name']; ?>" class="btn btn-info">编辑</a>
				<a onclick='del_ready("<?php echo $info['id']; ?>","<?php echo $info['name']; ?>")' class="btn btn-danger">删除</a>
				<a href="<?php echo site_url('admin/role/setPermission/').$info['id'].'/'.$info['name']; ?>" class="btn btn-success">分配权限</a>
				
				<?php if($info['is_default']==0){ ?>
				<a onclick='setDefaultRole("<?php echo $info['id']; ?>")' class="btn btn-primary">设为默认角色</a>
				<?php } ?>
			</td>
		</tr>
	<?php } ?>
	</tbody>
</table>

<!-- ./Page Main Content -->
</div>
<!-- ./Page -->
</div>

<script>
window.onload=function(){
	$('#table').DataTable({
		responsive: true
	});
};


function del_ready(id,name){
	$("#delID").val(id);
	$("#delName_show").html(name);
	$("#delModal").modal('show');
}


function del_sure(){
	lockScreen();
	id=$("#delID").val();

	$.ajax({
		url:"<?php echo site_url('admin/role/toDel'); ?>",
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
			}else if(ret.code=="403"){
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


function setDefaultRole(id){
	lockScreen();

	$.ajax({
		url:"<?php echo site_url('admin/role/toSetDefaultRole'); ?>",
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
				alert("设置成功！");
				location.reload();
				return true;
			}else if(ret.message=="setFailed"){
				$("#tips").html("设置失败！！！");
				$("#tipsModal").modal('show');
				return false;
			}else if(ret.code=="403"){
				$("#tips").html("Token无效！<hr>Tips:请勿在提交前打开另一页面哦~");
				$("#tipsModal").modal('show');
				return false;
			}else{
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
				<font color="red" style="font-weight:bolder;font-size:23px;">确定要删除下列角色吗？</font>
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
