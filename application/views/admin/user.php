<?php 
/**
 * @name 生蚝科技RBAC开发框架-V-用户列表
 * @author Jerry Cheung <master@xshgzs.com>
 * @since 2018-02-14
 * @version 2019-07-26
 */
?>
<!DOCTYPE html>
<html>

<head>
	<?php $this->load->view('include/header'); ?>
	<script src="<?=base_url('resource/js/select.js');?>"></script>
	<link rel="stylesheet" href="<?=base_url('resource/css/select.css');?>">
	<title>用户列表 / <?=$this->setting->get('systemName');?></title>
</head>

<body class="hold-transition skin-cyan sidebar-mini">
<div class="wrapper">

<?php $this->load->view('include/navbar'); ?>

<!-- 页面内容 -->
<div id="app" class="content-wrapper">
	<?php $this->load->view('include/pagePath',['name'=>'用户列表','path'=>[['用户列表','',1]]]); ?>

	<!-- 页面主要内容 -->
	<section class="content">
		<a onclick='vm.operateReady(1)' class="btn btn-primary btn-block">新 增 用 户</a>
		<hr>

		<div class="panel panel-default">
			<div class="panel-body">
				<table id="table" class="table table-striped table-bordered table-hover" style="border-radius: 5px; border-collapse: separate;">
					<thead>
						<tr>
							<th>用户名</th>
							<th>昵称</th>
							<th>状态</th>
							<th>操作</th>
						</tr>
					</thead>
					<tbody></tbody>
				</table>
			</div>
		</div>
	</section>
	<!-- ./页面主要内容 -->
	
	<div class="modal fade" id="operateModal">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
					<h3 class="modal-title">{{operateModalTitle}}</h3>
				</div>
				<div class="modal-body">
					<div class="form-group">
						<label for="userName">用户名</label>
						<input class="form-control" id="userName" onkeyup='if(event.keyCode==13)$("#nickName").focus();' v-model="userName">
						<p class="help-block">请输入<font color="green">4</font>-<font color="green">20</font>字的用户名</p>
					</div>
					<br>
					<div class="form-group">
						<label for="nickName">昵称</label>
						<input class="form-control" id="nickName" v-model="nickName" onkeyup='if(event.keyCode==13)$("#phone").focus();'>
					</div>
					<br>
					<div class="form-group">
						<label for="phone">手机号</label>
						<input type="number" class="form-control" id="phone" v-model="phone" onkeyup='if(event.keyCode==13)$("#email").focus();'>
						<p class="help-block">目前仅支持中国大陆的手机号码</p>
					</div>
					<br>
					<div class="form-group">
						<label for="email">邮箱</label>
						<input type="email" class="form-control" id="email" v-model="email" onkeyup='if(event.keyCode==13)$("#ssoUnionId").focus();'>
					</div>
					<br>

					<label for="email">生蚝科技网站生态群通行证UnionID</label>
					<div class="input-group">
						<input class="form-control" id="ssoUnionId" v-model="ssoUnionId">
						<div class="input-group-btn">
							<button id="nullSsoUnionIdBtn" class="btn btn-warning" @click='nullSsoUnionId(1)'>设置为空</button>
							<button id="notNullSsoUnionIdBtn" class="btn btn-success" @click='nullSsoUnionId(0)' style="display: none;">设置非空</button>
						</div>
					</div>
					<p class="help-block">
						请确保此UnionID已在 <b>“生蚝科技统一身份认证平台”</b> 注册<br>
						您可<a href="<?=$this->setting->get('ssoServerHost');?>" style="font-weight: bold" target="_blank">点此访问</a>管理平台以核实
					</p>

					<br><br>
					<div class="form-group">
						<label>角色</label>
						<div class="mainSelect" style="display: grid;">
							<div id="roleSelect"></div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button class="btn btn-warning" onclick="vm.userName='';vm.nickName='';vm.phone='';vm.email='';vm.operateType=-1;vm.operateuUserId=0;$('#operateModal').modal('hide');">&lt; 返回</button> <button class="btn btn-success" @click='operateSure'>{{operateModalBtn}}</button>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
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
	data:{
		updateId:0,
		statusNum:1,
		resetId:0,
		deleteId:0,
		userName:'',
		nickName:'',
		phone:'',
		email:'',
		ssoUnionId:'',
		operateType:0,
		operateUserId:0,
		operateUserRoleIds:'',
		operateOriginData:[],
		operateModalTitle:'',
		operateModalBtn:'',
		roleList:[{
			"name": "全选",
			"list": []
		}],
		option:{
			el: 'roleSelect',//容器名称
			type: 'more',//插件类型
			width: $("#email").css('width'),//内容显示宽度
			height: '40px',//内容显示高度
			background: '#FFFFFF',//默认背景色
			color: '#000000',//默认字体颜色
			selectBackground: '#337AB7',//选中背景色
			selectColor: '#FFFFFF',//选中字体颜色
			show: 'false',//是否展开
			content: '请选择用户角色',//要显示的内容
		}
	},
	methods:{
		getList(){
			$.ajax({
				url:"./get",
				dataType:'json',
				success:ret=>{
					if(ret.code==200){
						let list=ret.data['list'];
						
						// 先清空表格
						$.fn.dataTable.tables({api: true}).clear().draw();
						
						for(i in list){
							let statusHtml=
								list[i]['status']==0 ? "<a onclick='vm.updateStatus_ready("+'"'+list[i]['id']+'","'+list[i]['nick_name']+'"'+",1);'><font color='red'>已禁用</font></a>" : 
								(list[i]['status']==1 ? "<a onclick='vm.updateStatus_ready("+'"'+list[i]['id']+'","'+list[i]['nick_name']+'"'+",0);'><font color='green'>正常</font></a>" : "<font color='blue'>未激活</font>")
							let operateHtml=''
							               +'<a onclick="vm.operateReady(2,'+list[i]['id']+",'"+list[i]['user_name']+"','"+list[i]['nick_name']+"','"+list[i]['phone']+"','"+list[i]['email']+"','"+list[i]['sso_union_id']+"','"+list[i]['role_id']+"'"+');" class="btn btn-info">编辑</a> '
							               +"<a onclick='vm.resetPwd_ready("+'"'+list[i]['id']+'","'+list[i]['nick_name']+'"'+")' class='btn btn-warning'>重置密码</a> "
							               +"<a onclick='vm.del_ready("+'"'+list[i]['id']+'","'+list[i]['nick_name']+'"'+")' class='btn btn-danger'>删除</a>";

							let em=$.fn.dataTable.tables({api: true}).row.add({
								0: list[i]['user_name'],
								1: list[i]['nick_name'],
								2: statusHtml,
								3: operateHtml
							}).draw();
							console.log(em);
						}
					}
				}
			})
		},
		operateReady:(type=1,userId=0,userName='',nickName='',phone='',email='',ssoUnionId='',roleIds='')=>{
			vm.operateType=type;
			vm.operateUserId=userId;
			vm.userName=userName;
			vm.nickName=nickName;
			vm.phone=phone;
			vm.email=email;
			vm.ssoUnionId=ssoUnionId;
			vm.operateUserRoleIds=roleIds.split(",");
			vm.operateOriginData=[userName,nickName,phone,email,ssoUnionId,roleIds];
			
			if(type==1){
				vm.operateModalTitle="新 增 用 户";
				vm.operateModalBtn="确 认 新 增 用 户 >";
			}else if(type==2){
				vm.operateModalTitle="编 辑 用 户";
				vm.operateModalBtn="确 认 编 辑 用 户 >";
			}

			if(ssoUnionId=='null') vm.nullSsoUnionId(1);
			
			vm.getAllRole(roleIds);
			$("#operateModal").modal("show");
		},
		operateSure:function(){
			lockScreen();

			let userData={};
			let roleIds=$("#" + vm.option.el + " #selectValue").val();

			// 检查是否有修改数据
			if(vm.userName!==vm.operateOriginData[0]) userData.user_name=vm.userName;
			if(vm.nickName!==vm.operateOriginData[1]) userData.nick_name=vm.nickName;
			if(vm.phone!==vm.operateOriginData[2]) userData.phone=vm.phone;
			if(vm.email!==vm.operateOriginData[3]) userData.email=vm.email;
			if(vm.ssoUnionId!==vm.operateOriginData[3]) userData.sso_union_id=vm.ssoUnionId;
			if(roleIds!==vm.operateOriginData[5]) userData.role_id=roleIds;
			
			if(userData=={}){
				showModalTips('请填写需要操作的数据！');
				return;
			}
			
			$.ajax({
				url:"./toOperate",
				type:'post',
				data:{'type':this.operateType,'userId':this.operateUserId,userData},
				dataType:"json",
				error:function(e){
					console.log(e);
					unlockScreen();
					showModalTips("服务器错误！<hr>请联系技术支持并提交以下错误码：<br><font color='blue'>"+e.status+"</font>");
					return false;				
				},
				success:ret=>{
					if(ret.code==200){
						alert("操作成功！");
						$("#operateModal").modal('hide');
						unlockScreen();
						vm.getList();
						return;
					}else if(ret.code==4001){
						showModalTips("数据包含非法字段！<hr>请联系技术支持<br>并提交以下错误码：AU4001-"+ret.data);
						$("#operateModal").modal('hide');
						unlockScreen();
						return;
					}else if(ret.code==4002){
						showModalTips("数据包含空值！<hr>请联系技术支持<br>并提交以下错误码：AU4002-"+ret.data);
						$("#operateModal").modal('hide');
						unlockScreen();
						return;
					}else if(ret.code==500){
						showModalTips("数据库错误！<br>请联系技术支持！");
						$("#operateModal").modal('hide');
						unlockScreen();
						return;
					}else{
						showModalTips("系统错误！<br>请联系技术支持！");
						$("#operateModal").modal('hide');
						unlockScreen();
						return;
					}
				}
			})
		},
		nullSsoUnionId:(type=1)=>{
			if(type==1){
				$("#nullSsoUnionIdBtn").hide();
				$("#notNullSsoUnionIdBtn").show();
				$("#ssoUnionId").attr('disabled',true);
				vm.ssoUnionId='null';
			}else{
				$("#nullSsoUnionIdBtn").show();
				$("#notNullSsoUnionIdBtn").hide();
				$("#ssoUnionId").removeAttr('disabled');
				vm.ssoUnionId='';
			}
		},
		updateStatus_ready:(id,nickName,status)=>{
			vm.updateId=id;
			vm.statusNum=status;
			statusTips="确定要";

			if(status==0){
				statusTips+='<font color="red">禁用</font>';
			}else if(status==1){
				statusTips+='<font color="green">启用</font>';
			}else{
				showModalTips("错误的状态码！");
				return false;
			}

			statusTips+="用户["+nickName+"]吗？";
			$("#statusTips").html(statusTips);
			$("#statusModal").modal('show');
		},
		updateStatus_sure:()=>{
			lockScreen();

			$.ajax({
				url:headerVm.rootUrl+"admin/user/toUpdateStatus",
				type:"post",
				dataType:"json",
				data:{"userId":vm.updateId,"status":vm.statusNum},
				error:function(e){
					console.log(e);
					unlockScreen();
					$("#statusModal").modal('hide');
					showModalTips("服务器错误！<hr>请联系技术支持并提交以下错误码：<br><font color='blue'>"+e.status+"</font>");
					return false;
				},
				success:function(ret){
					unlockScreen();
					$("#statusModal").modal('hide');

					if(ret.code==200){
						alert("更新成功！");
						location.reload();
						return true;
					}else if(ret.code==400){
						showModalTips("禁止操作当前用户！");
						return false;
					}else if(ret.code==500){
						showModalTips("更新失败！！！");
						return false;
					}else if(ret.code==0){
						showModalTips("参数缺失！<hr>请从正确途径访问本功能！");
						return false;
					}else if(ret.code==403002){
						showModalTips("当前用户无操作权限！<br>请联系管理员！");
						return false;
					}else{
						showModalTips("系统错误！<hr>请联系技术支持并提交以下错误码：<br><font color='blue'>"+ret.code+"</font>");
						return false;
					}
				}
			});
		},
		resetPwd_ready:(id,name)=>{
			vm.resetId=id;
			$("#resetName_show").html(name);
			$("#resetModal").modal('show');
		},
		resetPwd_sure:()=>{
			lockScreen();

			$.ajax({
				url:headerVm.rootUrl+"admin/user/toResetPwd",
				type:"post",
				dataType:"json",
				data:{"userId":vm.resetId},
				error:function(e){
					console.log(e);
					unlockScreen();
					$("#resetModal").modal('hide');
					showModalTips("服务器错误！<hr>请联系技术支持并提交以下错误码：<br><font color='blue'>"+e.status+"</font>");
					return false;
				},
				success:function(ret){
					unlockScreen();
					$("#resetModal").modal('hide');

					if(ret.code==200){
						$("#info_userName_show").html(ret.data['userName']);
						$("#info_nickName_show").html(ret.data['nickName']);
						$("#info_originPwd_show").html(ret.data['originPwd']);
						$("#infoModal").modal('show');
						return true;
					}else if(ret.code==400){
						showModalTips("禁止操作当前用户！");
						return false;
					}else if(ret.code==1){
						showModalTips("无此用户！！！");
						return false;
					}else if(ret.code==500){
						showModalTips("重置失败！！！");
						return false;
					}else if(ret.code==0){
						showModalTips("参数缺失！<hr>请从正确途径访问本功能！");
						return false;
					}else if(ret.code==403002){
						showModalTips("当前用户无操作权限！<br>请联系管理员！");
						return false;
					}else{
						showModalTips("系统错误！<hr>请联系技术支持并提交以下错误码：<br><font color='blue'>"+ret.code+"</font>");
						return false;
					}
				}
			});
		},
		del_ready:(id,name)=>{
			vm.deleteId=id;
			$("#delName_show").html(name);
			$("#delModal").modal('show');
		},
		del_sure:()=>{
			lockScreen();
			
			$.ajax({
				url:headerVm.rootUrl+"admin/user/toDelete",
				type:"post",
				dataType:"json",
				data:{"userId":vm.deleteId},
				error:function(e){
					console.log(e);
					unlockScreen();
					$("#delModal").modal('hide');
					showModalTips("服务器错误！<hr>请联系技术支持并提交以下错误码：<br><font color='blue'>"+e.status+"</font>");
					return false;
				},
				success:function(ret){
					unlockScreen();
					$("#delModal").modal('hide');

					if(ret.code==200){
						alert("删除成功！");
						location.reload();
						return true;
					}else if(ret.code==400){
						showModalTips("禁止操作当前用户！");
						return false;
					}else if(ret.code==500){
						showModalTips("删除失败！！！");
						return false;
					}else if(ret.code==0){
						showModalTips("参数缺失！<hr>请从正确途径访问本功能！");
						return false;
					}else if(ret.code==403002){
						showModalTips("当前用户无操作权限！<br>请联系管理员！");
						return false;
					}else{
						showModalTips("系统错误！<hr>请联系技术支持并提交以下错误码：<br><font color='blue'>"+ret.code+"</font>");
						return false;
					}
				}
			});
		},
		getAllRole:function(roleIds=''){
			lockScreen();
			selectTool.remove(vm.option);

			if(vm.roleList[0].list.length==0){
				$.ajax({
					url:headerVm.apiPath+"role/get",
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
								pushData={"optionName":ret.data['list'][i]['name'],"optionId":ret.data['list'][i]['id']};
								$.inArray(ret.data['list'][i]['id'],vm.operateUserRoleIds)>=0 ? pushData.selected=true : '';
								vm.roleList[0].list.push(pushData);
							}

							vm.option.data=vm.roleList;
							selectTool.initialize(vm.option);
							$("#maincontent").hide();
							$("#" + vm.option.el + " #selectValue").val(roleIds);
							return true;
						}else{
							showModalTips("系统错误！<hr>请联系技术支持并提交以下错误码：<br><font color='blue'>"+ret.code+"</font>");
							return false;
						}
					}
				});
			}else{
				let roleList=vm.roleList[0].list;
				
				for(i in roleList){
					$.inArray(roleList[i].optionId,vm.operateUserRoleIds)>=0 ? roleList[i].selected=true : roleList[i].selected=false;
				}
				
				vm.roleList[0].list=roleList;
				vm.option.data=vm.roleList;
				selectTool.initialize(vm.option);
				$("#maincontent").hide();
				$("#" + vm.option.el + " #selectValue").val(roleIds);
				unlockScreen();
			}
		}
	},
	mounted:function(){
		$('#table').DataTable({
			responsive: true,
			"columnDefs":[{
				"targets":[3],
				"orderable": false
			}]
		});
		this.getList();

	}
});
</script>

<div class="modal fade" id="delModal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
				<h3 class="modal-title">温馨提示</h3>
			</div>
			<div class="modal-body">
				<center>
				<font color="red" style="font-weight:bolder;font-size:23px;">确定要删除下列用户吗？</font>
				<br><br>
				<font color="blue" style="font-weight:bolder;font-size:23px;"><p id="delName_show"></p></font>
				</center>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success" data-dismiss="modal">&lt; 返回</button> <button type="button" class="btn btn-danger" onclick="vm.del_sure();">确定 &gt;</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<div class="modal fade" id="resetModal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
				<h3 class="modal-title">温馨提示</h3>
			</div>
			<div class="modal-body">
				<center>
				<font color="red" style="font-weight:bolder;font-size:23px;">确定要重置下列用户的密码吗？</font>
				<br><br>
				<font color="blue" style="font-weight:bolder;font-size:23px;"><p id="resetName_show"></p></font>
				</center>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success" data-dismiss="modal">&lt; 返回</button> <button type="button" class="btn btn-danger" onclick="vm.resetPwd_sure();">确定 &gt;</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" id="statusModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
        <h3 class="modal-title">更新状态提示</h3>
      </div>
      <div class="modal-body">
        <font style="font-weight:bold;font-size:24px;text-align:center;">
          <p id="statusTips"></p>
        </font>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success" data-dismiss="modal">&lt; 返回</button>
        <button type="button" class="btn btn-primary" onclick="vm.updateStatus_sure();">确认 &gt;</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="infoModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
        <h3 class="modal-title">用户详细资料</h3>
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
          <td>初始密码</td>
          <th><p id="info_originPwd_show" style="color: green;font-weight: bold;"></p></th>
        </tr>
      </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-dismiss="modal">确认 &gt;</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

</body>
</html>
