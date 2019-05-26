<?php 
/**
 * @name 生蚝科技RBAC开发框架-V-新增菜单
 * @author Jerry Cheung <master@xshgzs.com>
 * @since 2018-02-17
 * @version 2019-05-26
 */
?>
<!DOCTYPE html>
<html>

<head>
	<?php $this->load->view('include/header'); ?>
	<title>新增菜单 / <?=$this->setting->get('systemName');?></title>
</head>

<body class="hold-transition skin-cyan sidebar-mini">
<div class="wrapper">

<?php $this->load->view('include/navbar'); ?>

<!-- 页面内容 -->
<div id="app" class="content-wrapper">
	<?php $this->load->view('include/pagePath',['name'=>'新增菜单','path'=>[['菜单管理',base_url('admin/menu/list')],['新增菜单','',1]]]); ?>

	<!-- 页面主要内容 -->
	<section class="content">
		<div class="panel panel-default">
			<div class="panel-heading">新增菜单（父菜单：<i class="fa fa-<?=$fatherIcon; ?>" aria-hidden="true"></i> <?=$fatherName; ?>）</div>

			<div class="panel-body">
				<div class="form-group">
					<label for="name">菜单类型</label>
					<select class="form-control" id="type" v-model="type" onkeyup='if(event.keyCode==13)$("#name").focus()'>
						<option value="1">菜单</option>
						<option value="2">按钮</option>
						<option value="3">接口</option>
					</select>
					<p class="help-block">Tips</p>
				</div>
				<br>
				<div class="form-group">
					<label for="name">菜单名称</label>
					<input class="form-control" id="name" v-model="name" onkeyup='if(event.keyCode==13)$("#icon").focus()'>
					<p class="help-block">请输入<font color="green">1</font>-<font color="green">20</font>字的菜单名称</p>
				</div>
				<br>
				<div class="form-group">
					<label for="icon">菜单图标 ( 预览: <i id="icon_preview" class="" aria-hidden="true"></i> ) &nbsp;&nbsp;&nbsp;&nbsp;<button class="btn btn-primary" onclick="$('#icon').val('circle-o');vm.icon='circle-o';vm.iconPreview();">使用默认图标</button></label>
					<input class="form-control" id="icon" v-model="icon" onkeyup='if(event.keyCode==13)$("#uri").focus()' @input='iconPreview'>
					<p class="help-block">请输入Font-Awesome图标名称，无需输入前缀“fa-”，输入后可在上方预览</p>
				</div>
				<br>
				<div class="form-group">
					<label for="uri">链接URL</label>
					<input class="form-control" id="uri" v-model="uri" @keyup.enter='add'>
					<p class="help-block">
						若此菜单为父菜单，请留空此项<br>
						如此菜单需跳出站外，请<a @click="inputJumpOutURI">点此输入</a>
					</p>
				</div>
				<hr>
				<button class="btn btn-success btn-block" @click='add'>确 认 新 增 菜 单 &gt;</button>
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
var vm = new Vue({
	el:'#app',
	data:{
		fatherId:<?=$fatherId;?>,
		type:1,
		name:'',
		icon:'',
		uri:''
	},
	methods:{
		iconPreview:()=>{
			$("#icon_preview").attr("class","fa fa-"+vm.icon);
		},
		inputJumpOutURI:()=>{
			uri=prompt("请输入需要跳转到的网站的完整URL（包括HTTP/HTTPS头）","http://");

			if(uri=="http://" || uri=="https://" || uri==""){
				alert("请输入需要跳转到的网站的完整URL（包括HTTP/HTTPS头）！");
				return false;
			}else if(uri==null){
				return;
			}else{
				vm.uri="show/jumpout/"+uri;
				$("#uri").val("show/jumpout/"+uri);
			}
		},
		add:()=>{
			lockScreen();

			if(vm.name==""){
				unlockScreen();
				showModalTips("请输入菜单名称！");
				return false;
			}
			if(vm.name.length<1 || vm.name.length>20){
				unlockScreen();
				showModalTips("请输入 1-20字 的菜单名称！");
				return false;
			}
			if(vm.icon==""){
				unlockScreen();
				showModalTips("请输入菜单图标名称！");
				return false;
			}

			$.ajax({
				url:"<?=base_url('admin/menu/toAdd');?>",
				type:"post",
				data:{"fatherId":vm.fatherId,"type":vm.type,"name":vm.name,"icon":vm.icon,"uri":vm.uri},
				dataType:"JSON",
				error:function(e){
					console.log(e);
					unlockScreen();
					showModalTips("服务器错误！<hr>请联系技术支持并提交以下错误码：<br><font color='blue'>"+e.status+"</font>");
					return false;
				},
				success:function(ret){
					unlockScreen();

					if(ret.code==200){
						alert("新增成功！");
						history.go(-1);
						return true;
					}else if(ret.code==1){
						showModalTips("新增失败！！！");
						return false;
					}else if(ret.code==0){
						showModalTips("参数缺失！请联系技术支持！");
						return false;
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
	}
})
</script>

</body>
</html>
