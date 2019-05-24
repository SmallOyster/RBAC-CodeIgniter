<?php 
/**
 * @name 生蚝科技RBAC开发框架-V-修改菜单
 * @author Jerry Cheung <master@xshgzs.com>
 * @since 2018-02-18
 * @version 2019-05-24
 */
?>
<!DOCTYPE html>
<html>

<head>
	<?php $this->load->view('include/header'); ?>
	<title>修改菜单 / <?=$this->setting->get('systemName');?></title>
</head>

<body class="hold-transition skin-cyan sidebar-mini">
<div class="wrapper">

<?php $this->load->view('include/navbar'); ?>

<!-- 页面内容 -->
<div id="app" class="content-wrapper">
	<?php $this->load->view('include/pagePath',['name'=>'修改菜单','path'=>[['菜单管理',base_url('admin/menu/list')],['修改菜单','',1]]]); ?>

	<!-- 页面主要内容 -->
	<section class="content">
		<input type="hidden" id="menuId" value="<?=$menuId;?>">

		<div class="panel panel-default">
			<div class="panel-heading">修改菜单（父菜单：<i class="fa fa-<?=$fatherIcon; ?>" aria-hidden="true"></i> <?=$fatherName; ?>）</div>

			<div class="panel-body">
				<div class="form-group">
					<label for="name">菜单名称</label>
					<input class="form-control" id="name" onkeyup='if(event.keyCode==13)$("#icon").focus();' value="<?=$info['name']; ?>">
					<p class="help-block">请输入<font color="green">1</font>-<font color="green">20</font>字的菜单名称</p>
				</div>
				<br>
				<div class="form-group">
					<label for="icon">菜单图标 ( 预览: <i id="icon_preview" class="" aria-hidden="true"></i> ) &nbsp;&nbsp;&nbsp;&nbsp;<button class="btn btn-primary" onclick="$('#icon').val('circle-o');iconPreview();">使用默认图标</button></label>
					<input class="form-control" id="icon" onkeyup='if(event.keyCode==13)$("#uri").focus();' oninput='iconPreview();' value="<?=$info['icon']; ?>">
					<p class="help-block">请输入Font-Awesome图标名称，无需输入前缀“fa-”，输入后可在上方预览</p>
				</div>
				<br>
				<div class="form-group">
					<label for="uri">链接URL</label>
					<input class="form-control" id="uri" onkeyup='if(event.keyCode==13)edit();' value="<?=$info['uri']; ?>">
					<p class="help-block">
						若此菜单为父菜单，请留空此项<br>
						如此菜单需跳出站外，请<a onclick="inputJumpOutURI()">点此输入</a>
					</p>
				</div>

				<hr>

				<div class="form-group">
					<label>创建时间</label>
					<input class="form-control" value="<?=$info['create_time']; ?>" disabled>
				</div>
				<br>
				<div class="form-group">
					<label>最后修改时间</label>
					<input class="form-control" value="<?=$info['update_time']; ?>" disabled>
				</div>

				<hr>

				<button class="btn btn-success btn-block" onclick='edit()'>确 认 修 改 菜 单 &gt;</button>
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
	iconPreview();
}

function iconPreview(){
	icon=$("#icon").val();
	$("#icon_preview").attr("class","fa fa-"+icon);
}

function inputJumpOutURI(){
  uri=prompt("请输入需要跳转到的网站的完整URL（包括HTTP/HTTPS头）","http://");
  
  if(uri=="http://" || uri=="https://" || uri==""){
  	alert("请输入需要跳转到的网站的完整URL（包括HTTP/HTTPS头）！");
  	return false;
  }else if(uri==null){
  	return;
  }else{
  	$("#uri").val("show/jumpout/"+uri);
  }
}

function edit(){
	lockScreen();
	menuId=$("#menuId").val();
	name=$("#name").val();
	icon=$("#icon").val();
	uri=$("#uri").val();

	if(name==""){
		unlockScreen();
		showModalTips("请输入菜单名称！");
		return false;
	}
	if(name.length<1 || name.length>20){
		unlockScreen();
		showModalTips("请输入 1-20字 的菜单名称！");
		return false;
	}
	if(icon==""){
		unlockScreen();
		showModalTips("请输入菜单图标名称！");
		return false;
	}
	
	$.ajax({
		url:"<?=base_url('admin/menu/toEdit'); ?>",
		type:"POST",
		data:{<?=$this->ajax->showAjaxToken(); ?>,"menuId":menuId,"name":name,"icon":icon,"uri":uri},
		dataType:"JSON",
		error:function(e){
			console.log(JSON.stringify(e));
			unlockScreen();
			showModalTips("服务器错误！<hr>请联系技术支持并提交以下错误码：<br><font color='blue'>"+e.status+"</font>");
			return false;
		},
		success:function(ret){
			unlockScreen();
			
			if(ret.code==200){
				alert("编辑成功！");
				history.go(-1);
				return true;
			}else if(ret.code==1){
				showModalTips("编辑失败！！！");
				return false;
			}else if(ret.code==403001){
				showModalTips("Token无效！<hr>Tips:请勿在提交前打开另一页面哦~");
				return false;
			}else if(ret.code==0){
				showModalTips("参数缺失！请联系技术支持！");
				return false;
			}else{
				console.log(ret);
				showModalTips("系统错误！<hr>请联系技术支持并提交以下错误码：<br><font color='blue'>"+ret.code+"</font>");
				return false;
			}
		}
	});
}
</script>

</body>
</html>
