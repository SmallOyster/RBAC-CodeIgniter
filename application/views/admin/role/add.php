<?php 
/**
 * @name V-新增角色
 * @author SmallOysyer <master@xshgzs.com>
 * @since 2018-02-09
 * @version V1.0 2018-02-22
 */
?>

<!DOCTYPE html>
<html>

<head>
	<?php $this->load->view('include/header'); ?>
	<title>新增角色 / <?php echo $this->config->item('systemName'); ?></title>
</head>

<body>
<div id="wrapper">

<?php $this->load->view('include/navbar'); ?>

<div id="page-wrapper">
<!-- Page Main Content -->

<!-- Page Name-->
<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header">新增角色</h1>
	</div>
</div>
<!-- ./Page Name-->

<div class="panel panel-default">
	<div class="panel-heading">新增角色</div>
	<div class="panel-body">
		<div class="form-group">
			<label>角色名称</label>
			<input class="form-control" id="name" onkeyup='if(event.keyCode==13)$("#remark").focus();'>
			<p class="help-block">请输入<font color="green">1</font>-<font color="green">20</font>字的角色名称</p>
		</div>
		<br>
		<div class="form-group">
			<label>备注</label>
			<textarea class="form-control" id="remark"></textarea>
			<p class="help-block">选填</p>
		</div>
		<hr>
		<button class="btn btn-success" style="width:100%" onclick='add()'>确 认 新 增 角 色 &gt;</button>
	</div>
</div>

<!-- ./Page Main Content -->
</div>
<!-- ./Page -->
</div>

<script>
function add(){
	lockScreen();
	name=$("#name").val();
	remark=$("#remark").val();

	if(name==""){
		unlockScreen();
		$("#tips").html("请输入角色名称！");
		$("#tipsModal").modal('show');
		return false;
	}
	if(name.length<1 || name.length>20){
		unlockScreen();
		$("#tips").html("请输入 1-20字 的角色名称！");
		$("#tipsModal").modal('show');
		return false;
	}

	$.ajax({
		url:"<?php echo site_url('admin/role/toAdd'); ?>",
		type:"post",
		data:{<?php echo $this->ajax->showAjaxToken(); ?>,"name":name,"remark":remark},
		dataType:'json',
		error:function(e){
			console.log(JSON.stringify(e));
			unlockScreen();
			$("#tips").html("服务器错误！<hr>请联系技术支持并提交以下错误码：<br><font color='blue'>"+e.status+"</font>");
			$("#tipsModal").modal('show');
			return false;
		},
		success:function(ret){
			unlockScreen();
			
			if(ret.code=="200"){
				alert("新增成功！");
				history.go(-1);
				return true;
			}else if(ret.message=="insertFailed"){
				$("#tips").html("新增失败！！！");
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

</body>
</html>
