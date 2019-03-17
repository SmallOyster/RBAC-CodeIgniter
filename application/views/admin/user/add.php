<?php 
/**
 * @name 生蚝科技RBAC开发框架-V-新增用户
 * @author Jerry Cheung <master@xshgzs.com>
 * @since 2018-02-14
 * @version 2019-03-17
 */
?>
<!DOCTYPE html>
<html>

<head>
	<?php $this->load->view('include/header'); ?>
	<title>新增用户 / <?=$this->Setting_model->get('systemName');?></title>
</head>

<body class="hold-transition skin-cyan sidebar-mini">
<div class="wrapper">

<?php $this->load->view('include/navbar'); ?>

<!-- 页面内容 -->
<div id="app" class="content-wrapper">
	<?php $this->load->view('include/pagePath',['name'=>'新增用户','path'=>[['用户列表',base_url('admin/user/list')],['新增用户','',1]]]); ?>

	<!-- 页面主要内容 -->
	<section class="content">

		<div class="panel panel-default">
			<div class="panel-heading">新增用户</div>
			<div class="panel-body">
				<div class="form-group">
					<label for="userName">用户名</label>
					<input class="form-control" id="userName" onkeyup='if(event.keyCode==13)$("#nickName").focus();'>
					<p class="help-block">请输入<font color="green">4</font>-<font color="green">20</font>字的用户名</p>
				</div>
				<br>
				<div class="form-group">
					<label for="nickName">昵称</label>
					<input class="form-control" id="nickName" onkeyup='if(event.keyCode==13)$("#phone").focus();'>
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
					<input type="email" class="form-control" id="email">
				</div>
				<br>
				<div class="form-group">
					<label for="roleID">角色</label>
					<select class="form-control" id="roleID">
						<option value="-1" selected disabled>--- 请选择角色 ---</option>
					</select>
				</div>

				<hr>

				<button class="btn btn-success btn-block" onclick='add()'>确 认 新 增 用 户 &gt;</button>
			</div>
		</div>
	</section>
	<!-- ./页面主要内容 -->
</div>
<!-- ./页面内容 -->

<?php $this->load->view('include/footer'); ?>

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
		url:"<?=base_url('api/getAllRole'); ?>",
		type:"post",
		data:{<?=$this->ajax->showAjaxToken();?>},
		dataType:'json',
		error:function(e){
			console.log(JSON.stringify(e));
			unlockScreen();
			showModalTips("服务器错误！<hr>请联系技术支持并提交以下错误码：<br><font color='blue'>"+e.status+"</font>");
			return false;
		},
		success:function(ret){
			unlockScreen();
			
			if(ret.code==200){
				for(i in ret.data['list']){
					roleID=ret.data['list'][i]['id']
					roleName=ret.data['list'][i]['name'];
					$("#roleID").append('<option value="'+roleID+'">'+roleID+'. '+roleName+'</option>');
				}
				return true;
			}else if(ret.code==403001){
				showModalTips("Token无效！<hr>Tips:请勿在提交前打开另一页面哦~");
				return false;
			}else{
				showModalTips("系统错误！<hr>请联系技术支持并提交以下错误码：<br><font color='blue'>"+ret.code+"</font>");
				return false;
			}
		}
	});
}


function add(){
	lockScreen();
	userName=$("#userName").val();
	nickName=$("#nickName").val();
	phone=$("#phone").val();
	email=$("#email").val();
	roleID=$("#roleID").val();

	if(userName==""){
		unlockScreen();
		showModalTips("请输入用户名！");
		return false;
	}
	if(userName.length<4 || userName.length>20){
		unlockScreen();
		showModalTips("请输入 4-20字 的用户名！");
		return false;
	}
	if(nickName==""){
		unlockScreen();
		showModalTips("请输入昵称！");
		return false;
	}
	if(phone==""){
		unlockScreen();
		showModalTips("请输入手机号！");
		return false;
	}
	if(phone.length!=11){
		unlockScreen();
		showModalTips("请正确输入手机号！");
		return false;
	}
	if(email==""){
		unlockScreen();
		showModalTips("请输入邮箱！");
		return false;
	}
	if(roleID=="-1"){
		unlockScreen();
		showModalTips("请选择角色！");
		return false;
	}

	$.ajax({
		url:"<?=base_url('admin/user/toAdd');?>",
		type:"post",
		data:{<?=$this->ajax->showAjaxToken();?>,"userName":userName,"nickName":nickName,"phone":phone,"email":email,"roleID":roleID},
		dataType:'json',
		error:function(e){
			console.log(e);
			unlockScreen();
			showModalTips("服务器错误！<hr>请联系技术支持并提交以下错误码：<br><font color='blue'>"+e.status+"</font>");
			return false;
		},
		success:function(ret){
			unlockScreen();
			
			if(ret.code==200){
				$("#info_userName_show").html(userName);
				$("#info_nickName_show").html(nickName);
				$("#info_phone_show").html(phone);
				$("#info_email_show").html(email);
				$("#info_originPwd_show").html(ret.data['originPwd']);
				$("#infoModal").modal('show');
				return true;
			}else if(ret.code==500){
				showModalTips("新增失败！！！");
				return false;
			}else if(ret.code==1){
				showModalTips("此用户名已存在！<br>请输入其他用户名！");
				return false;
			}else if(ret.code==2){
				showModalTips("此手机号已存在！<br>请输入其他手机号！");
				return false;
			}else if(ret.code==3){
				showModalTips("此邮箱已存在！<br>请输入其他邮箱！");
				return false;
			}else if(ret.code==403001){
				showModalTips("Token无效！<hr>Tips:请勿在提交前打开另一页面哦~");
				return false;
			}else if(ret.code==0){
				showModalTips("参数缺失！请联系技术支持！");
				return false;
			}else{
				showModalTips("系统错误！<hr>请联系技术支持并提交以下错误码：<br><font color='blue'>"+ret.code+"</font>");
				return false;
			}
		}
	});
}
</script>

<div class="modal fade" id="infoModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
        <h3 class="modal-title" id="ModalTitle">用户详细资料</h3>
      </div>
      <div class="modal-body">
        <table class="table table-hover table-striped table-bordered" style="border-radius: 5px; border-collapse: separate;text-align: center;">
        <tr>
          <td>用户名</td>
          <th><p id="info_userName_show"></p></th>
        </tr>
        <tr>
          <td>昵称</td>
          <th><p id="info_nickName_show"></p></th>
        </tr>
        <tr>
          <td>手机</td>
          <th><p id="info_phone_show"></p></th>
        </tr>
        <tr>
          <td>邮箱</td>
          <th><p id="info_email_show"></p></th>
        </tr>
        <tr>
          <td>初始密码</td>
          <th><p id="info_originPwd_show" style="color: green;font-weight: bold;"></p></th>
        </tr>
      </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" onclick="history.go(-1);">确认 &gt;</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

</body>
</html>
