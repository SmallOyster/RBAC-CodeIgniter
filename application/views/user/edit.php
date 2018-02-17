<?php 
/**
 * @name V-修改用户
 * @author SmallOysyer <master@xshgzs.com>
 * @since 2018-02-17
 * @version V1.0 2018-02-17
 */
?>

<!DOCTYPE html>
<html>

<head>
	<?php $this->load->view('include/header'); ?>
	<title>修改用户 / <?php echo $this->config->item('systemName'); ?></title>
</head>

<body>
<div id="wrapper">

<?php $this->load->view('include/navbar'); ?>

<div id="page-wrapper">
<!-- Page Main Content -->

<input type="hidden" id="userID" value="<?php echo $userID; ?>">

<!-- Page Name-->
<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header">修改用户</h1>
	</div>
</div>
<!-- ./Page Name-->

<div class="panel panel-default">
	<div class="panel-heading">修改用户</div>
	<div class="panel-body">
		<div class="form-group">
			<label for="userName">用户名</label>
			<input class="form-control" id="userName" onkeyup='if(event.keyCode==13)$("#realName").focus();'>
			<p class="help-block">请输入<font color="green">1</font>-<font color="green">20</font>字的用户名</p>
		</div>
		<br>
		<div class="form-group">
			<label for="realName">真实姓名</label>
			<input class="form-control" id="realName" onkeyup='if(event.keyCode==13)$("#phone").focus();'>
		</div>
		<br>
		<div class="form-group">
			<label for="phone">手机号</label>
			<input type="number" class="form-control" id="phone" onkeyup='if(event.keyCode==13)$("#email").focus();'>
		</div>
		<br>
		<div class="form-group">
			<label for="email">邮箱</label>
			<input type="email" class="form-control" id="email">
		</div>
		<div class="form-group">
			<label for="roleID">角色</label>
			<select class="form-control" id="roleID">
				<option value="-1">--- 请选择角色 ---</option>
			</select>
		</div>

		<hr>

		<button class="btn btn-primary" style="width:100%" onclick='edit()'>确 认 修 改 用 户 &gt;</button>
	</div>
</div>

<!-- ./Page Main Content -->
</div>
<!-- ./Page -->
</div>

<script>
window.onload=function(){
	getAllRole();
}


function getAllRole(){
	lockScreen();

	$.ajax({
		url:"<?php echo site_url('api/getAllRole'); ?>",
		type:"post",
		data:{<?php echo $this->ajax->showAjaxToken(); ?>},
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
				for(i in ret.data['list']){
					roleID=ret.data['list'][i]['id']
					roleName=ret.data['list'][i]['name'];
					$("#roleID").append('<option value="'+roleID+'">'+roleID+'. '+roleName+'</option>');
				}
				return true;
			}else if(ret.message=="insertFailed"){
				$("#tips").html("新增失败！！！");
				$("#tipsModal").modal('show');
				return false;
			}else if(ret.code=="0"){
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


function edit(){
	lockScreen();
	name=$("#name").val();
	remark=$("#remark").val();
	roleID=$("#roleID").val();

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
		url:"<?php echo site_url('role/toEdit'); ?>",
		type:"post",
		data:{<?php echo $this->ajax->showAjaxToken(); ?>,"name":name,"remark":remark,'roleID':roleID},
		dataType:'json',
		error:function(e){
			console.log(JSON.stringify(e));
			unlockScreen();$("#tips").html("服务器错误！<hr>请联系技术支持并提交以下错误码：<br><font color='blue'>"+e.status+"</font>");

			$("#tipsModal").modal('show');
			return false;
		},
		success:function(ret){
			unlockScreen();
			
			if(ret.code=="200"){
				alert("修改成功！");
				history.go(-1);
				return true;
			}else if(ret.message=="updateFailed"){
				$("#tips").html("修改失败！！！");
				$("#tipsModal").modal('show');
				return false;
			}else if(ret.code=="0"){
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
