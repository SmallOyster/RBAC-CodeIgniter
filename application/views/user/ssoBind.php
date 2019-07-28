<?php 
/**
 * @name 生蚝科技RBAC开发框架-V-绑定SSO通行证
 * @author Jerry Cheung <master@xshgzs.com>
 * @since 2019-07-27
 * @version 2019-07-28
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
				<center v-if="bindStatus==1"><h3 style="color:green;font-weight: bold;">已 绑 定 通 行 证 信 息</h3></center>
				<center v-else><h3 style="color:purple;font-weight: bold;">登 录 绑 定 通 行 证</h3></center>

				<div class="form-group" id="userName_div">
					<label for="ssoUserName">通行证账号</label>
					<input class="form-control" id="ssoUserName" v-model='ssoUserName' onkeyup='if(event.keyCode==13)$("#ssoPassword").focus();' autocomplete="false">
				</div>
				<br>
				<div class="form-group" id="password_div">
					<label for="ssoPassword">通行证密码</label>
					<input type="password" class="form-control" id="ssoPassword" v-model='ssoPassword' onkeyup='if(event.keyCode==13)vm.login();' autocomplete="false">
				</div>

				<!-- SSO通行证账户信息 -->
				<div id="ssoUserInfo_div" style="display: none;">
					<div class="form-group">
						<label>通行证帐号昵称</label>
						<input class="form-control" v-model='nickName' disabled>
					</div>
					<br>
					<div class="form-group">
						<label>通行证手机号</label>
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
					<div style="text-align: center;" id="bindTips_div">
						<div class="alert alert-warning">
							<i aria-hidden="true" class="fa fa-info-circle"></i> 请确认以上信息是否相符！<br>如相符，请点击下方“确认绑定”按钮。<br>如有疑问，请登录<a href="<?=$this->setting->get('ssoServerHost');?>" target="_blank" style="color:#6c2be6;font-weight: bold;">SSO用户中心</a>以确认
						</div>
					</div>
				</div>
				<!-- ./SSO通行证账户信息 -->

				<div style="text-align: center;font-size:16px;" id="timeTips_div">
					<div class="alert alert-info">
						<i aria-hidden="true" class="fa fa-clock-o"></i> 登录通行证后，<font style="color:#dfff3c;font-weight: bold;">请在2分钟内完成绑定操作！</font><br>如提示已超时，请重新登录通行证！
					</div>

					<hr>
				</div>

				<button id="loginButton" class="btn btn-primary btn-block" @click='login' style="font-size: 18px;font-weight: bold;"><i class="fa fa-sign-in" aria-hidden="true"></i> 通 行 证 登 录</button>
				<button id="bindButton" class="btn btn-success btn-block" @click='bind' style="font-size: 18px;font-weight: bold;display: none;"><i class="fa fa-link" aria-hidden="true"></i> 确 认 绑 定 通 行 证</button>
				<button id="unbindButton" class="btn btn-danger btn-block" onclick='$("#passwordModal").modal("show");' style="font-size: 18px;font-weight: bold;display: none;"><i class="fa fa-chain-broken" aria-hidden="true"></i> 解 绑 通 行 证</button>
			</div>
		</div>
	</section>

	<div class="modal fade" id="passwordModal">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title">校验通行证密码</h4>
				</div>
				<div class="modal-body">
					<h3 style="line-height:38px;">
						确认要解绑[生蚝科技网站生态群通行证]吗？<br>
						如已确认，请在下方输入您的<font color="green"><b>当前系统的帐户密码</b></font>：<br>
						（输入本系统用户密码，非通行证密码）
					</h3>
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-key" aria-hidden="true"></i></span>
						<input type="password" id="unbindPassword" v-model="unbindPassword" class="form-control" placeholder="请输入您的 [本系统用户密码]">
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-success" data-dismiss="modal">&lt; 取消</button>
					<button @click="unbind" class="btn btn-danger">确认解绑 &gt;</a>
				</div>
			</div>
			<!-- /.modal-content -->
		</div>
		<!-- /.modal-dialog -->
	</div>
	<!-- /.modal -->
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
			bindStatus:0,
			token:'',
			tokenExpireTime:0,
			ssoUserName:'',
			ssoPassword:'',
			ssoUnionId:'',
			nickName:'',
			phone:'',
			email:'',
			roleName:'',
			status:'',
			createTime:'',
			lastLogin:'',
			unbindPassword:''
		}
	},
	methods:{
		checkBind:function(){
			lockScreen();
			this.bindStatus=0;

			$.ajax({
				url:'./checkBind',
				dataType:'json',
				error:e=>{
					unlockScreen();
					showModalTips("服务器错误！<br>请联系技术支持！");
					console.log(e);
				},
				success:ret=>{
					if(ret.code==200){
						unlockScreen();
						let data=ret.data['userInfo'];

						this.bindStatus=1;
						this.nickName=data.nickName;
						this.phone=data.phone;
						this.email=data.email;
						this.createTime=data.createTime;
						this.lastLogin=data.lastLogin;

						// 角色名称处理
						let roleName='';
						for(i in data.roleInfo) roleName+=data.roleInfo[i]['name']+",";
						roleName=roleName.substr(0,roleName.length-1);
						
						// 账号状态中文化
						let status="";
						if(data.status==1) status="正常";
						else if(data.status==2) status="未激活";
						else status="禁用";
						
						this.roleName=roleName;
						this.status=status;

						$("#ssoUserInfo_div").show(500);
						$("#userName_div").hide();
						$("#password_div").hide();
						$("#bindButton").hide();
						$("#unbindButton").show();
						$("#loginButton").hide();
						$("#bindTips_div").hide();
						$("#timeTips_div").hide();
					}else if(ret.code==4001){
						unlockScreen();
						showModalTips("系统用户不存在！<br>请联系技术支持！");
						return false;
					}else if(ret.code==4002){
						// 未绑定
						$("#ssoUserInfo_div").hide();
						$("#userName_div").show();
						$("#password_div").show();
						$("#bindButton").hide();
						$("#unbindButton").hide();
						$("#loginButton").show();
						$("#bindTips_div").hide();
						$("#timeTips_div").show();
						unlockScreen();
						return false;
					}else if(ret.code==4003){
						unlockScreen();
						showModalTips("当前账号已绑定通行证<br>但通行证可能因各种原因无法查询！<br>请先登录"+'<a href="<?=$this->setting->get('ssoServerHost');?>" target="_blank" style="color:#6c2be6;font-weight: bold;">'+"SSO用户中心</a>确认！");
						return false;
					}else{
						unlockScreen();
						showModalTips("系统错误！<hr>请提交此错误内容给技术支持：<br>"+ret.code+"-"+ret.message);
						return false;
					}
				}
			})
		},
		login:function(){
			if(this.ssoUserName==''){
				showModalTips("请输入通行证账号！");
				return false;
			}
			if(this.ssoPassword==''){
				showModalTips("请输入通行证密码！");
				return false;
			}

			lockScreen();

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
						let data=ret.data['userInfo'];

						this.token=ret.data['token'];
						this.tokenExpireTime=ret.data['expireTime'];
						this.ssoUnionId=data.ssoUnionId;
						this.nickName=data.nickName;
						this.phone=data.phone;
						this.email=data.email;
						this.createTime=data.createTime;
						this.lastLogin=data.lastLogin;
						
						// 角色名称处理
						let roleName='';
						for(i in data.roleInfo) roleName+=data.roleInfo[i]+",";
						roleName=roleName.substr(0,roleName.length-1);
						
						// 账号状态中文化
						let status="";
						if(data.status==1) status="正常";
						else if(data.status==2) status="未激活";
						else status="禁用";
						
						this.roleName=roleName;
						this.status=status;

						$("#ssoUserInfo_div").show(500);
						$("#bindButton").show();
						//$("#loginButton").hide();

						return;
					}else if(ret.code==4031){
						unlockScreen();
						showModalTips("通行证帐号或密码有误！");
						return false;
					}else if(ret.code==4032){
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
		bind:function(){		
			if(this.ssoUnionId.length!=8){
				showModalTips("非法通行证UnionID！<br>请检查是否已核实通行证资料<br>如有疑问，请联系技术支持！");
				return false;
			}
			if(this.token.length<32){
				showModalTips("Token无效！<br>请联系技术支持！");
				return false;
			}
			if(this.tokenExpireTime<(Math.round(new Date().getTime()/1000))){
				showModalTips("Token已过期！<br>请重新点击“登录”并再次绑定！<hr>Tips: 登录成功后请在2分钟内绑定！");
				return false;
			}

			lockScreen();
			
			$.ajax({
				url:"toBind",
				type:'post',
				data:{"token":this.token,"unionId":this.ssoUnionId},
				dataType:'json',
				error:e=>{
					unlockScreen();
					showModalTips("服务器错误！<br>请联系技术支持！");
					console.log(e);
				},
				success:ret=>{
					if(ret.code==200){
						unlockScreen();
						vm.checkBind();
						alert("尊敬的"+vm.nickName+"用户：\n    恭喜您成功绑定通行证！\n    欢迎使用 <?=$this->setting->get('systemName');?>！");
					}else if(ret.code==4031){
						unlockScreen();
						showModalTips("Token无效！<br>请联系技术支持！");
						return false;
					}else if(ret.code==4032){
						unlockScreen();
						showModalTips("非法通行证UnionID！<br>请检查是否已核实通行证资料<br>如有疑问，请联系技术支持！");
						return false;
					}else if(ret.code==4033){
						unlockScreen();
						showModalTips("Token已过期！<br>请重新点击“登录”并再次绑定！<hr>Tips: 登录成功后请在2分钟内绑定！");
						return false;
					}else if(ret.code==500){
						unlockScreen();
						showModalTips("数据库操作失败！<br>请联系技术支持！");
						return false;
					}else{
						unlockScreen();
						showModalTips("系统错误！<hr>请提交此错误内容给技术支持：<br>"+ret.code+"-"+ret.message);
						return false;
					}
				}
			})
		},
		unbind:function(){
			if(this.unbindPassword.length<6){
				showModalTips("请正确输入当前系统的账户密码！");
				return false;
			}

			lockScreen();
			$("#passwordModal").modal("hide");

			$.ajax({
				url:"./unbind",
				type:"post",
				data:{"password":this.unbindPassword},
				dataType:"json",
				error:e=>{
					unlockScreen();
					showModalTips("服务器错误！<br>请联系技术支持！");
					console.log(e);
				},
				success:ret=>{
					if(ret.code==200){
						unlockScreen();
						vm.checkBind();
						showModalTips("解绑成功！<hr>欢迎您再次使用[生蚝科技网站生态群通行证]服务！");
					}else if(ret.code==403){
						unlockScreen();
						vm.checkBind();
						showModalTips("密码错误！");
					}else if(ret.code==500){
						unlockScreen();
						vm.checkBind();
						showModalTips("解绑失败！");
					}else{
						unlockScreen();
						vm.checkBind();
						showModalTips("系统错误！<hr>请提交此错误内容给技术支持：<br>"+ret.code+"-"+ret.message);
					}
				}
			})
		}
	},
	mounted:function(){
		this.checkBind();
	}
});

</script>

</body>
</html>
