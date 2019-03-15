<?php 
/**
 * @name V-操作记录管理
 * @author SmallOysyer <master@xshgzs.com>
 * @since 2018-02-27
 * @version V1.0 2018-08-08
 */
 
?>

<!DOCTYPE html>
<html>

<head>
	<?php $this->load->view('include/header'); ?>
	<title>操作记录管理 / <?=$this->Setting_model->get('systemName');?></title>
</head>

<body>
<div id="wrapper">

<?php $this->load->view('include/navbar'); ?>

<div id="page-wrapper">
<!-- Page Main Content -->

<!-- Page Name-->
<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header">操作记录管理</h1>
		<button class="btn btn-danger btn-block" onclick="truncate_ready();">清 空 操 作 记 录</button>
	</div>
</div>
<!-- ./Page Name-->

<hr>

<table id="table" class="table table-striped table-bordered table-hover" style="border-radius: 5px; border-collapse: separate;">
	<thead>
		<tr>
			<th>类型</th>
			<th>内容</th>
			<th>用户名</th>
			<th>时间</th>
			<th>IP</th>
		</tr>
	</thead>
	
	<tbody>
	<?php
	foreach($list as $info){
	?>
	<tr>
		<td><?=$info['type']; ?></td>
		<td><?=$info['content']; ?></td>
		<td><?=$info['user_name']; ?></td>
		<td><?=$info['create_time']; ?></td>
		<td><?=$info['create_ip']; ?></td>
	</tr>
	<?php } ?>
</tbody>
</table>

<?php $this->load->view('include/footer'); ?>

<!-- ./Page Main Content -->
</div>
<!-- ./Page -->
</div>

<script>
function truncate_ready(){
	$("#truncatePwd").val("");
	$("#truncateModal").modal('show');
}


function truncate_sure(){
	lockScreen();
	pwd=$("#truncatePwd").val();

	$.ajax({
		url:"<?=site_url('admin/sys/log/toTruncate'); ?>",
		type:"post",
		dataType:"json",
		data:{<?=$this->ajax->showAjaxToken(); ?>,"pwd":pwd},
		error:function(e){
			console.log(e);
			unlockScreen();
			$("#truncateModal").modal('hide');
			$("#tips").html("服务器错误！<hr>请联系技术支持并提交以下错误码：<br><font color='blue'>"+e.status+"</font>");
			$("#tipsModal").modal('show');
			return false;
		},
		success:function(ret){
			unlockScreen();
			
			if(ret.code=="200"){
				$("#truncateModal").modal('hide');
				alert("清空成功！");
				location.reload();
				return true;
			}else if(ret.message=="truncateFailed"){
				$("#truncateModal").modal('hide');
				$("#tips").html("清空失败！！！");
				$("#tipsModal").modal('show');
				return false;
			}else if(ret.message=="invaildPwd"){
				$("#truncateModal").modal('hide');
				$("#tips").html("密码错误！");
				$("#tipsModal").modal('show');
				return false;
			}else if(ret.code=="403"){
				$("#truncateModal").modal('hide');
				$("#tips").html("Token无效！<hr>Tips:请勿在提交前打开另一页面哦~");
				$("#tipsModal").modal('show');
				return false;
			}else{
				$("#truncateModal").modal('hide');
				$("#tips").html("系统错误！<hr>请联系技术支持并提交以下错误码：<br><font color='blue'>"+ret.code+"</font>");
				$("#tipsModal").modal('show');
				return false;
			}
		}
	});
}
</script>

<?php $this->load->view('include/tipsModal'); ?>

<div class="modal fade" id="truncateModal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
				<h3 class="modal-title" id="ModalTitle">温馨提示</h3>
			</div>
			<div class="modal-body">
				<input type="hidden" id="truncateID">
				<center>
				<font color="red" style="font-weight:bolder;font-size:23px;">确定要清空系统日志吗？</font>
				<br>
				<font color="blue" style="font-weight:bolder;font-size:20px;">若已确定，请输入您的账户密码</font>
				<hr>
				<input type="password" class="form-control" id="truncatePwd" onkeyup='if(event.keyCode==13)truncate_sure();'>
				</center>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success" data-dismiss="modal">&lt; 取消</button> <button type="button" class="btn btn-danger" onclick="truncate_sure();">确定 &gt;</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

</body>
</html>
