<?php 
/**
 * @name V-用户忘记密码
 * @author SmallOysyer <master@xshgzs.com>
 * @since 2018-02-24
 * @version V1.0 2018-02-25
 */
?>

<!DOCTYPE html>
<html>

<head>
	<?php $this->load->view('include/header'); ?>
	<title>忘记密码 / <?php echo $this->config->item('systemName'); ?></title>
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
				<h3 class="panel-title">忘记密码</h3>
			</div>
			<div class="panel-body">
				<div class="form-group">
					<label for="userName">用户名</label>
					<input class="form-control" id="userName" onkeyup='if(event.keyCode==13)$("#phone").focus();'>
					<p class="help-block">请输入<font color="green">4</font>-<font color="green">20</font>字的用户名</p>
				</div>
				<br>
				<div class="form-group">
					<label for="phone">手机号</label>
					<input type="number" class="form-control" id="phone" onkeyup='if(event.keyCode==13)$("#email").focus();'>
					<p class="help-block">目前仅支持中国大陆的手机号码</p>
				</div>
				<br>
				<div class="form-group">
					<label for="email">邮箱</label>
					<input type="email" class="form-control" id="email" onkeyup='if(event.keyCode==13)$("#newPwd").focus();'>
				</div>

				<hr>

				<div class="form-group">
					<label for="newPwd">新密码</label>
					<input type="password" class="form-control" id="newPwd" onkeyup='if(event.keyCode==13)$("#checkPwd").focus();'>
					<p class="help-block">请输入<font color="green">6</font>-<font color="green">20</font>字的密码</p>
				</div>
				<br>
				<div class="form-group">
					<label for="checkPwd">确认密码</label>
					<input type="password" class="form-control" id="checkPwd" onkeyup='if(event.keyCode==13)forgetPwd();'>
					<p class="help-block">请再次输入密码</p>
				</div>

				<hr>

				<button class="btn btn-success btn-block" onclick='forgetPwd();'>确认忘记密码 &gt;</button>
			</div>
		</div>
	</div>
</div>

<script>
function forgetPwd(){
	lockScreen();
	userName=$("#userName").val();
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
	if(userName.length<4 || userName.length>20){
		unlockScreen();
		$("#tips").html("请输入 4~20字 的用户名！");
		$("#tipsModal").modal('show');
		return false;
	}
	
	if(newPwd==""){
		unlockScreen();
		$("#tips").html("请输入新密码！");
		$("#tipsModal").modal('show');
		return false;
	}
	if(checkPwd==""){
		unlockScreen();
		$("#tips").html("请再次输入新密码！");
		$("#tipsModal").modal('show');
		return false;
	}
	if(newPwd.length<6 || newPwd.length>20){
		unlockScreen();
		$("#tips").html("请输入 6~20 字的密码！");
		$("#tipsModal").modal('show');
		return false;
	}
	if(newPwd!=checkPwd){
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
		$("#tips").html("请正确输入中国大陆手机号！");
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
		url:"<?php echo site_url('user/toForgetPwd'); ?>",
		type:"post",
		data:{<?php echo $this->ajax->showAjaxToken(); ?>,"userName":userName,"phone":phone,"email":email,"pwd":newPwd},
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
				alert("成功重置密码！\n请牢记您的新密码！\n\n即将跳转至登录页面！");
				window.location.href="<?php echo site_url('user/login'); ?>";
				return true;
			}else if(ret.message=="resetFailed"){
				$("#tips").html("重置密码失败！！！");
				$("#tipsModal").modal('show');
				return false;
			}else if(ret.message=="noUser"){
				$("#tips").html("无此用户资料！");
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
