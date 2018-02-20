<?php 
/**
 * @name V-登录
 * @author SmallOysyer <master@xshgzs.com>
 * @since 2018-02-20
 * @version V1.0 2018-02-20
 */
?>

<!DOCTYPE html>
<html>

<head>
	<?php $this->load->view('include/header'); ?>
	<title>登录 / <?php echo $this->config->item('systemName'); ?></title>
</head>

<body>
<div class="container">
	<div class="row">
		<div class="col-md-4 col-md-offset-4">
			<div class="login-panel panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title">欢迎登录<?php echo $this->config->item('systemName'); ?></h3>
				</div>
				<div class="panel-body">
					<div class="form-group">
						<input class="form-control" placeholder="用户名 / userName" id="userName" onkeyup='if(event.keyCode==13)$("#pwd").focus();'>
					</div>
					<div class="form-group">
						<input class="form-control" placeholder="密码 / Password" id="pwd" type="password" onkeyup='if(event.keyCode==13)toLogin();'>
					</div>
					<div class="checkbox">
						<label for="Remember">
							<input type="checkbox" id="Remember">记住用户名
						</label>
					</div>
					<button class="btn btn-lg btn-success btn-block" onclick='toLogin();'>登录 Login &gt;</button>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
var isAjaxing=0;

// 监听模态框关闭事件
$(function (){
	$('#tipsModal').on('hidden.bs.modal',function (){
		isAjaxing=0;
	});
});

window.onload=function(){
  
  /********** ▼ 记住密码 ▼ **********/
  Remember=getCookie("<?php echo $this->sessPrefix; ?>RmUN");
  if(Remember!=null){
    $("#userName").val(Remember);
    $("#pwd").focus();
    $("#Remember").attr("checked",true);
  }else{
    $("#userName").focus();
  }
  /********** ▲ 记住密码 ▲ **********/
}

function toLogin(){
	if(isAjaxing==1){
		return;
	}

	isAjaxing=1;
	lockScreen();
	$("#userName").attr("disabled",true);
	$("#pwd").attr("disabled",true);
	userName=$("#userName").val();
	pwd=$("#pwd").val();

	/********** ▼ 记住密码 ▼ **********/
	Remember=$("input[type='checkbox']").is(':checked');
	if(Remember==true){
		setCookie("<?php echo $this->sessPrefix; ?>RmUN",userName);
	}else{
		delCookie("<?php echo $this->sessPrefix; ?>RmUN");
	}
	/********** ▲ 记住密码 ▲ **********/

	if(userName==""){
		$("#tips").html("请输入用户名！");
		unlockScreen();
		$("#userName").removeAttr("disabled");
		$("#pwd").removeAttr("disabled");
		$("#tipsModal").modal('show');
		return false;
	}
	if(userName.length<4){
		$("#tips").html("用户名长度有误！");
		unlockScreen();
		$("#userName").removeAttr("disabled");
		$("#pwd").removeAttr("disabled");
		$("#tipsModal").modal('show');
		return false;
	}
	if(pwd==""){
		$("#tips").html("请输入密码！");
		unlockScreen();
		$("#userName").removeAttr("disabled");
		$("#pwd").removeAttr("disabled");
		$("#tipsModal").modal('show');
		return false;
	}
	if(pwd.length<6){
		$("#tips").html("密码长度有误！");
		unlockScreen();
		$("#userName").removeAttr("disabled");
		$("#pwd").removeAttr("disabled");
		$("#tipsModal").modal('show');
		return false;  
	}

	$.ajax({
		url:"<?php echo site_url('user/toLogin'); ?>",
		type:"post",
		data:{<?php echo $this->ajax->showAjaxToken(); ?>,"userName":userName,"pwd":pwd},
		dataType:"json",
		error:function(e){
			console.log(e);
			unlockScreen();
			$("#userName").removeAttr("disabled");
			$("#pwd").removeAttr("disabled");
			$("#delModal").modal('hide');
			$("#tips").html("服务器错误！<hr>请联系技术支持并提交以下错误码：<br><font color='blue'>"+e.status+"</font>");
			$("#tipsModal").modal('show');
			return false;
		},
		success:function(ret){
			unlockScreen();
			$("#userName").removeAttr("disabled");
			$("#pwd").removeAttr("disabled");

			if(ret.code==200){
				window.location.href="<?php echo site_url('/'); ?>"
			}else if(ret.message=="userForbidden"){
				$("#tips").html("当前用户被禁用！<br>请联系管理员！");
				$("#tipsModal").modal('show');
				return false;
			}else if(ret.message=="invaildPwd"){
				console.log(ret);$("#tips").html("用户名或密码错误！");
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

<div class="modal fade" id="tipsModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
        <h3 class="modal-title" id="ModalTitle">温馨提示</h3>
      </div>
      <div class="modal-body">
        <form method="post">
          <font color="red" style="font-weight:bolder;font-size:24px;text-align:center;">
            <p id="tips"></p>
          </font>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-warning" onclick='isAjaxing=0;$("#tipsModal").modal("hide");'>返回 &gt;</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

</body>
</html>
