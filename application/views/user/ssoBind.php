<?php 
/**
 * @name 生蚝科技RBAC开发框架-V-绑定SSO通行证
 * @author Jerry Cheung <master@xshgzs.com>
 * @since 2019-07-27
 * @version 2019-07-27
 */ 
?>
<!DOCTYPE html>
<html>

<head>
	<?php $this->load->view('include/header'); ?>
	<title>通行证绑定 / <?=$this->setting->get('systemName'); ?></title>
</head>

<body class="hold-transition skin-cyan sidebar-mini">
<div class="wrapper">

<?php $this->load->view('include/navbar'); ?>

<!-- 页面内容 -->
<div id="app" class="content-wrapper">
	<?php $this->load->view('include/pagePath',['name'=>'通行证绑定','path'=>[['用户中心',base_url('user/updateProfile'),0],['通行证绑定','',1]]]); ?>

	<!-- 页面主要内容 -->
	<section class="content">
		<div class="panel panel-default">
			<div class="panel-body">
				<div class="form-group">
					<label for="ssoUserName">通行证账号</label>
					<input class="form-control" id="ssoUserName" v-model='ssoUserName' onkeyup='if(event.keyCode==13)$("#ssoPassword").focus();' autocomplete="false">
				</div>
				<br>
				<div class="form-group">
					<label for="ssoPassword">通行证密码</label>
					<input type="password" class="form-control" id="ssoPassword" v-model='ssoPassword' autocomplete="false">
				</div>

				<!-- SSO通行证账户信息 -->
				<div id="ssoUserInfo_div" style="display: none;">
					<hr>

					<div class="form-group">
						<label for="ssoUserName">通行证手机号</label>
						<input class="form-control" v-model='phone' disabled>
					</div>
					<br>
					<div class="form-group">
						<label for="ssoPassword">通行证邮箱</label>
						<input class="form-control" v-model='email' disabled>
					</div>
					<br>
					<div class="form-group">
						<label for="ssoPassword">通行证角色信息</label>
						<input class="form-control" v-model='roleName' disabled>
					</div>
					<br>
					<div class="form-group">
						<label for="ssoPassword">通行证状态</label>
						<input class="form-control" v-model='status' disabled>
					</div>
					<br>
					<div class="form-group">
						<label for="ssoPassword">通行证注册时间</label>
						<input class="form-control" v-model='createTime' disabled>
					</div>
					<br>
					<div class="form-group">
						<label for="ssoPassword">通行证最后登录时间</label>
						<input class="form-control" v-model='lastLogin' disabled>
					</div>
					<br>
					<div style="text-align: center;">
						<div class="alert alert-warning">
							<i aria-hidden="true" class="fa fa-info-circle"></i> 请确认以上信息是否相符！<br>如相符，请点击下方按钮。<br>如有疑问，请登录<a href="<?=$this->setting->get('ssoServerHost');?>" target="_blank" style="color:#6c2be6;font-weight: bold;">SSO用户中心</a>以确认</div>
						</div>
				</div>
				<!-- ./SSO通行证账户信息 -->

				<hr>

				<button id="loginButton" class="btn btn-primary btn-block" @click='login' style="font-size: 18px;font-weight: bold;"><i class="fa fa-sign-in" aria-hidden="true"></i> 通 行 证 登 录</button>
				<button id="bindButton" class="btn btn-success btn-block" @click='bind' style="font-size: 18px;font-weight: bold;display: none;"><i class="fa fa-link" aria-hidden="true"></i> 确 认 绑 定 通 行 证</button>
			</div>
		</div>
	</section>
</div>
<!-- ./页面内容 -->

<?php $this->load->view('include/footer'); ?>

<!-- ./Page Main Content -->
</div>
<!-- ./Page -->
</div>

<script>
var vm = new Vue({
	el:'#app',
	data:function(){
		return {
			ssoUserName:'',
			ssoPassword:'',
			phone:'',
			email:'',
			roleName:'',
			status:'',
			createTime:'',
			lastLogin:''
		}
	},
	methods:{
		login:function(){
			lockScreen();

			if(this.ssoUserName==''){
				unlockScreen();
				showModalTips("请输入通行证账号！");
				return false;
			}
			if(this.ssoPassword==''){
				unlockScreen();
				showModalTips("请输入通行证密码！");
				return false;
			}

			$.ajax({
				url:'./bindLogin',
				type:'post',
				data:{'userName':this.ssoUserName,'password':this.ssoPassword},
				dataType:'json',
				error:e=>{
					unlockScreen();
					showModalTips("服务器错误！<br>请联系技术支持！");
					console.log(e);
				},
				success:ret=>{
					if(ret.code==200){
						unlockScreen();
						let data=ret.data;

						this.phone=data.phone;
						this.email=data.email;
						this.roleName=data.roleName;
						this.status=data.status;
						this.createTime=data.createTime;
						this.lastLogin=data.lastLogin;

						$("#ssoUserInfo_div").show(500);
						$("#bindButton").show();
						$("#loginButton").hide();

						return;
					}else if(ret.code==4031){
						unlockScreen();
						showModalTips("通行证帐号或密码有误！");
						return false;
					}else if(ret.code==4031){
						unlockScreen();
						showModalTips("此通行证无权限访问本系统！<br>如有疑问，请联系技术支持！");
						return false;
					}else{
						unlockScreen();
						showModalTips("请求SSO中心服务器失败！<br>请联系技术支持！");
						return false;
					}
				}
			})
		},
		bind:()=>{
		}
	}
});
</script>

</body>
</html>
