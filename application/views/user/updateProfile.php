<?php 
/**
 * @name V-修改个人资料
 * @author SmallOysyer <master@xshgzs.com>
 * @since 2018-02-19
 * @version V1.0 2018-02-22
 */
?>

<!DOCTYPE html>
<html>

<head>
	<?php $this->load->view('include/header'); ?>
	<title>修改个人资料 / <?php echo $this->config->item('systemName'); ?></title>
</head>

<body>
<div id="wrapper">

<?php $this->load->view('include/navbar'); ?>

<div id="page-wrapper">
<!-- Page Main Content -->

<!-- Page Name-->
<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header">修改个人资料</h1>
	</div>
</div>
<!-- ./Page Name-->

<div class="panel panel-default">
	<div class="panel-heading">修改用户</div>
	<div class="panel-body">
		<div class="form-group">
			<label for="userName">用户名</label>
			<input class="form-control" id="userName" onkeyup='if(event.keyCode==13)$("#nickName").focus();' value="<?php echo $info['user_name']; ?>" disabled>
		</div>
		<br>
		<div class="form-group">
			<label for="realName">昵称</label>
			<input class="form-control" id="nickName" onkeyup='if(event.keyCode==13)$("#oldPwd").focus();' value="<?php echo $info['nick_name']; ?>">
		</div>
		
		<hr>

		<div class="form-group">
			<label for="userName">旧密码</label>
			<input class="form-control" type="password" id="oldPwd" onkeyup='if(event.keyCode==13)$("#newPwd").focus();'>
			<p class="help-block">如无需修改密码，请留空</p>
		</div>
		<br>
		<div class="form-group">
			<label for="userName">新密码</label>
			<input class="form-control" type="password" id="newPwd" onkeyup='if(event.keyCode==13)$("#checkPwd").focus();'>
			<p class="help-block">如无需修改密码，请留空</p>
		</div>
		<br>
		<div class="form-group">
			<label for="userName">确认密码</label>
			<input class="form-control" type="password" id="checkPwd" onkeyup='if(event.keyCode==13)$("#phone").focus();'>
			<p class="help-block">如无需修改密码，请留空</p>
		</div>

		<hr>

		<div class="form-group">
			<label for="phone">手机号</label>
			<input type="number" class="form-control" id="phone" onkeyup='if(event.keyCode==13)$("#email").focus();' value="<?php echo $info['phone']; ?>">
		</div>
		<br>
		<div class="form-group">
			<label for="email">邮箱</label>
			<input type="email" class="form-control" id="email" value="<?php echo $info['email']; ?>" onkeyup='if(event.keyCode==13)updateProfile();'>
		</div>

		<hr>

		<div class="form-group">
			<label>注册时间</label>
			<input class="form-control" value="<?php echo $info['create_time']; ?>" disabled>
		</div>
		<br>
		<div class="form-group">
			<label>最后修改时间</label>
			<input class="form-control" value="<?php echo $info['update_time']; ?>" disabled>
		</div>

		<hr>

		<button class="btn btn-success btn-block" onclick='updateProfile()'>确 认 修 改 用 户 &gt;</button>
	</div>
</div>

<!-- ./Page Main Content -->
</div>
<!-- ./Page -->
</div>

<script>
function updateProfile(){
	lockScreen();
	userName=$("#userName").val();
	nickName=$("#nickName").val();
	oldPwd=$("#oldPwd").val();
	newPwd=$("#newPwd").val();
	checkPwd=$("#checkPwd").val();
	phone=$("#phone").val();
	email=$("#email").val();

	if(userName==""){
		unlockScreen();
		$("#tips").html("请输入用户名！");
		$("#tipsModal").modal('show');
		return false;
	}
	if(userName.length<1 || userName.length>20){
		unlockScreen();
		$("#tips").html("请输入 1-20字 的用户名！");
		$("#tipsModal").modal('show');
		return false;
	}
	if(nickName==""){
		unlockScreen();
		$("#tips").html("请输入昵称！");
		$("#tipsModal").modal('show');
		return false;
	}
	if(phone==""){
		unlockScreen();
		$("#tips").html("请输入手机号！");
		$("#tipsModal").modal('show');
		return false;
	}
	if(phone.length!=11){
		unlockScreen();
		$("#tips").html("请正确输入手机号！");
		$("#tipsModal").modal('show');
		return false;
	}
	if(email==""){
		unlockScreen();
		$("#tips").html("请输入邮箱！");
		$("#tipsModal").modal('show');
		return false;
	}
	if(oldPwd!="" && newPwd==""){
		unlockScreen();
		$("#tips").html("请输入新密码！");
		$("#tipsModal").modal('show');
		return false;
	}
	if(newPwd!=checkPwd){
		unlockScreen();
		$("#tips").html("两次输入的新密码不相符！");
		$("#tipsModal").modal('show');
		return false;
	}

	$.ajax({
		url:"<?php echo site_url('user/toUpdateProfile'); ?>",
		type:"post",
		data:{<?php echo $this->ajax->showAjaxToken(); ?>,"userName":userName,"nickName":nickName,'oldPwd':oldPwd,'newPwd':newPwd,"phone":phone,"email":email},
		dataType:'json',
		error:function(e){
			console.log(e);
			unlockScreen();
			$("#tips").html("服务器错误！<hr>请联系技术支持并提交以下错误码：<br><font color='blue'>"+e.status+"</font>");
			$("#tipsModal").modal('show');
			return false;
		},
		success:function(ret){
			unlockScreen();
			
			if(ret.code=="200"){
				if(oldPwd!=""){
					alert("修改成功！请使用新密码重新登录！");
					window.location.href="<?php echo site_url('user/login'); ?>";
					return true;
				}else{
					alert("修改成功！");
					history.go(-1);
					return true;
				}
			}else if(ret.message=="updateFailed"){
				$("#tips").html("修改失败！！！");
				$("#tipsModal").modal('show');
				return false;
			}else if(ret.message=="userForbidden"){
				$("#tips").html("当前用户被禁用！<br>请联系管理员！");
				$("#tipsModal").modal('show');
				return false;
			}else if(ret.message=="invaildPwd"){
				$("#tips").html("旧密码错误！！！");
				$("#tipsModal").modal('show');
				return false;
			}else if(ret.code=="403"){
				$("#tips").html("Token无效！<hr>Tips:请勿在提交前打开另一页面哦~");
				$("#tipsModal").modal('show');
				return false;
			}else{
				console.log(ret);
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
