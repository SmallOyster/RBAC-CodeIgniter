<?php 
/**
 * @name V-新增菜单
 * @author SmallOysyer <master@xshgzs.com>
 * @since 2018-02-17
 * @version V1.0 2018-03-02
 */
?>

<!DOCTYPE html>
<html>

<head>
	<?php $this->load->view('include/header'); ?>
	<title>新增菜单 / <?php echo $this->config->item('systemName'); ?></title>
</head>

<body>
<div id="wrapper">

<?php $this->load->view('include/navbar'); ?>

<div id="page-wrapper">
<!-- Page Main Content -->

<input type="hidden" id="fatherID" value="<?php echo $fatherID; ?>">

<!-- Page Name-->
<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header">新增菜单</h1>
	</div>
</div>
<!-- ./Page Name-->

<div class="panel panel-default">
	<div class="panel-heading">新增菜单（父菜单：<i class="fa fa-<?php echo $fatherIcon; ?>" aria-hidden="true"></i> <?php echo $fatherName; ?>）</div>
	
	<div class="panel-body">
		<div class="form-group">
			<label for="name">菜单名称</label>
			<input class="form-control" id="name" onkeyup='if(event.keyCode==13)$("#icon").focus();'>
			<p class="help-block">请输入<font color="green">1</font>-<font color="green">20</font>字的菜单名称</p>
		</div>
		<br>
		<div class="form-group">
			<label for="icon">菜单图标 (预览: <i id="icon_preview" class="" aria-hidden="true"></i>)</label>
			<input class="form-control" id="icon" onkeyup='if(event.keyCode==13)$("#uri").focus();' oninput='iconPreview();'>
			<p class="help-block">请输入Font-Awesome图标名称，无需输入前缀“fa-”，输入后可在上方预览</p>
		</div>
		<br>
		<div class="form-group">
			<label for="uri">链接URL</label>
			<input class="form-control" id="uri" onkeyup='if(event.keyCode==13)add();'>
			<p class="help-block">
				若此菜单为父菜单，请留空此项<br>
				如此菜单需跳出站外，请<a onclick="inputJumpOutURI()">点此输入</a>
			</p>
		</div>
		<hr>
		<button class="btn btn-success btn-block" onclick='add()'>确 认 新 增 菜 单 &gt;</button>
	</div>
</div>

<!-- ./Page Main Content -->
</div>
<!-- ./Page -->
</div>

<script>
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

function add(){
	lockScreen();
	fatherID=$("#fatherID").val();
	name=$("#name").val();
	icon=$("#icon").val();
	uri=$("#uri").val();

	if(name==""){
		unlockScreen();
		$("#tips").html("请输入菜单名称！");
		$("#tipsModal").modal('show');
		return false;
	}
	if(name.length<1 || name.length>20){
		unlockScreen();
		$("#tips").html("请输入 1-20字 的菜单名称！");
		$("#tipsModal").modal('show');
		return false;
	}
	if(icon==""){
		unlockScreen();
		$("#tips").html("请输入菜单图标名称！");
		$("#tipsModal").modal('show');
		return false;
	}
	
	$.ajax({
		url:"<?php echo site_url('admin/sys/menu/toAdd'); ?>",
		type:"POST",
		data:{<?php echo $this->ajax->showAjaxToken(); ?>,"fatherID":fatherID,"name":name,"icon":icon,"uri":uri},
		dataType:"JSON",
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
				alert("新增成功！");
				history.go(-1);
				return true;
			}else if(ret.message=="insertFailed"){
				$("#tips").html("新增失败！！！");
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
