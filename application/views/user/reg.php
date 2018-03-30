<?php 
/**
 * @name V-用户注册
 * @author SmallOysyer <master@xshgzs.com>
 * @since 2018-02-22
 * @version V1.0 2018-03-09
 */
?>

<!DOCTYPE html>
<html>

<head>
	<?php $this->load->view('include/header'); ?>
	<title>注册 / <?php echo $this->config->item('systemName'); ?></title>
	<style>
	body{
		padding-top: 40px;
	}
	</style>
</head>

<body>
<div class="container">
	<div class="col-md-6 col-md-offset-3">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">欢迎注册<?php echo $this->config->item('systemName'); ?></h3>
			</div>
			<div class="panel-body">
				<div class="form-group">
					<label for="userName">用户名</label>
					<input class="form-control" id="userName" onkeyup='if(event.keyCode==13)$("#nickName").focus();'>
					<p class="help-block">请输入<font color="green">4</font>-<font color="green">20</font>字的用户名</p>
				</div>
				<br>
				<div class="form-group">
					<label for="nickName">昵称</label>
					<input class="form-control" id="nickName" onkeyup='if(event.keyCode==13)$("#pwd").focus();'>
				</div>

				<hr>

				<div class="form-group">
					<label for="pwd">密码</label>
					<input type="password" class="form-control" id="pwd" onkeyup='if(event.keyCode==13)$("#checkPwd").focus();'>
					<p class="help-block">请输入<font color="green">6</font>-<font color="green">20</font>字的密码</p>
				</div>
				<br>
				<div class="form-group">
					<label for="checkPwd">确认密码</label>
					<input type="password" class="form-control" id="checkPwd" onkeyup='if(event.keyCode==13)$("#phone").focus();'>
					<p class="help-block">请再次输入密码</p>
				</div>

				<hr>

				<div class="form-group">
					<label for="phone">手机号</label>
					<input type="number" class="form-control" id="phone" onkeyup='if(event.keyCode==13)$("#email").focus();'>
					<p class="help-block">目前仅支持中国大陆的手机号码</p>
				</div>
				<br>
				<div class="form-group">
					<label for="email">邮箱</label>
					<input type="email" class="form-control" id="email" onkeyup='if(event.keyCode==13)reg();'>
				</div>
				<button class="btn btn-lg btn-success btn-block" onclick='reg();'>注册 Register &gt;</button>
			</div>
		</div>
	</div>
</div>

<?php $this->load->view('include/footer'); ?>

<script>
function reg(){
	lockScreen();
	userName=$("#userName").val();
	nickName=$("#nickName").val();
	pwd=$("#pwd").val();
	checkPwd=$("#checkPwd").val();
	phone=$("#phone").val();
	email=$("#email").val();

	if(userName==""){
		unlockScreen();
		$("#tips").html("请输入用户名！");
		$("#tipsModal").modal('show');
		return false;
	}
	if(userName.length<4 || userName.length>20){
		unlockScreen();
		$("#tips").html("请输入 4~20字 的用户名！");
		$("#tipsModal").modal('show');
		return false;
	}
	if(nickName==""){
		unlockScreen();
		$("#tips").html("请输入昵称！");
		$("#tipsModal").modal('show');
		return false;
	}
	
	if(pwd==""){
		unlockScreen();
		$("#tips").html("请输入密码！");
		$("#tipsModal").modal('show');
		return false;
	}
	if(checkPwd==""){
		unlockScreen();
		$("#tips").html("请再次输入密码！");
		$("#tipsModal").modal('show');
		return false;
	}
	if(pwd.length<6 || pwd.length>20){
		unlockScreen();
		$("#tips").html("请输入 6~20 字的密码！");
		$("#tipsModal").modal('show');
		return false;
	}
	if(pwd!=checkPwd){
		unlockScreen();
		$("#tips").html("两次输入的密码不相同！<br>请重新输入！");
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

	$.ajax({
		url:"<?php echo site_url('user/toReg'); ?>",
		type:"post",
		data:{<?php echo $this->ajax->showAjaxToken(); ?>,"userName":userName,"nickName":nickName,"phone":phone,"email":email,"pwd":pwd},
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
				alert("注册成功！即将跳转至登录页面！");
				window.location.href="<?php echo site_url('user/login'); ?>";
				return true;
			}else if(ret.message=="regFailed"){
				$("#tips").html("注册失败！！！");
				$("#tipsModal").modal('show');
				return false;
			}else if(ret.message=="noRoleInfo"){
				$("#tips").html("获取角色信息失败！请联系管理员！");
				$("#tipsModal").modal('show');
				return false;
			}else if(ret.message=="haveUserName"){
				$("#tips").html("此用户名已存在！<br>请输入其他用户名！");
				$("#tipsModal").modal('show');
				return false;
			}else if(ret.message=="havePhone"){
				$("#tips").html("此手机号已存在！<br>请输入其他手机号！");
				$("#tipsModal").modal('show');
				return false;
			}else if(ret.message=="haveEmail"){
				$("#tips").html("此邮箱已存在！<br>请输入其他邮箱！");
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
