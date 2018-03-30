<?php 
/**
 * @name V-用户列表
 * @author SmallOysyer <master@xshgzs.com>
 * @since 2018-02-14
 * @version V1.0 2018-03-29
 */
?>

<!DOCTYPE html>
<html>

<head>
	<?php $this->load->view('include/header'); ?>
	<title>用户列表 / <?php echo $this->config->item('systemName'); ?></title>
</head>

<body>
<div id="wrapper">

<?php $this->load->view('include/navbar'); ?>

<div id="page-wrapper">
<!-- Page Main Content -->

<!-- Page Name-->
<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header">用户列表</h1>
		<a href="<?php echo site_url('admin/user/add'); ?>" class="btn btn-primary btn-block">新 增 用 户</a>
		<hr>
	</div>
</div>
<!-- ./Page Name-->

<table id="table" class="table table-striped table-bordered table-hover" style="border-radius: 5px; border-collapse: separate;">
	<thead>
		<tr>
			<th>用户名</th>
			<th>昵称</th>
			<th>状态</th>
			<th>操作</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($list as $info){ ?>
		<tr>
			<td><?php echo $info['user_name']; ?></td>
			<td><?php echo $info['nick_name']; ?></td>
			<td>
				<?php if($info['status']==0){ ?>
				<a onclick='updateStatus_ready("<?php echo $info['id']; ?>","<?php echo $info['nick_name']; ?>",1);'><font color="red">已禁用</font></a>
				<?php }elseif($info['status']==1){ ?>
				<a onclick='updateStatus_ready("<?php echo $info['id']; ?>","<?php echo $info['nick_name']; ?>",0);'><font color="green">正常</font></a>
				<?php }elseif($info['status']==2){ ?>
				<font color="blue">未激活</font>
				<?php } ?>
			</td>
			<td><a href="<?php echo site_url('admin/user/edit/').$info['id']; ?>" class="btn btn-info">编辑</a> <a onclick='resetPwd_ready("<?php echo $info['id']; ?>","<?php echo $info['nick_name']; ?>")' class="btn btn-warning">重置密码</a> <a onclick='del_ready("<?php echo $info['id']; ?>","<?php echo $info['nick_name']; ?>")' class="btn btn-danger">删除</a></td>
		</tr>
	<?php } ?>
	</tbody>
</table>

<?php $this->load->view('include/footer'); ?>

<!-- ./Page Main Content -->
</div>
<!-- ./Page -->
</div>

<script>
var statusID="";
var statusNum="";

window.onload=function(){
	$('#table').DataTable({
		responsive: true
	});
};


function updateStatus_ready(id,nickName,status){
	statusID=id;
	statusNum=status;
	statusTips="确定要";
	
	if(status==0){
		statusTips+="<font color=red>禁用</font>";
	}else if(status==1){
		statusTips+="<font color=green>启用</font>";
	}else{
		$("#tips").html("错误的状态码！");
		$("#tipsModal").modal('show');
		return false;
	}
	
	statusTips+="用户["+nickName+"]吗？";
	$("#statusTips").html(statusTips);
	$("#statusModal").modal('show');
}


function updateStatus_sure(){
	lockScreen();

	$.ajax({
		url:"<?php echo site_url('admin/user/toUpdateStatus'); ?>",
		type:"post",
		dataType:"json",
		data:{<?php echo $this->ajax->showAjaxToken(); ?>,"id":statusID,"status":statusNum},
		error:function(e){
			console.log(e);
			unlockScreen();
			$("#statusModal").modal('hide');
			$("#tips").html("服务器错误！<hr>请联系技术支持并提交以下错误码：<br><font color='blue'>"+e.status+"</font>");
			$("#tipsModal").modal('show');
			return false;
		},
		success:function(ret){
			unlockScreen();
			$("#statusModal").modal('hide');
			
			if(ret.code=="200"){
				alert("更新成功！");
				location.reload();
				return true;
			}else if(ret.message=="nowUser"){
				$("#tips").html("禁止操作当前用户！");
				$("#tipsModal").modal('show');
				return false;
			}else if(ret.message=="updateFailed"){
				$("#tips").html("更新失败！！！");
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


function resetPwd_ready(id,name){
	$("#resetID").val(id);
	$("#resetName_show").html(name);
	$("#resetModal").modal('show');
}


function resetPwd_sure(){
	lockScreen();
	id=$("#resetID").val();
	
	$.ajax({
		url:"<?php echo site_url('admin/user/toResetPwd'); ?>",
		type:"post",
		dataType:"json",
		data:{<?php echo $this->ajax->showAjaxToken(); ?>,"id":id},
		error:function(e){
			console.log(e);
			unlockScreen();
			$("#resetModal").modal('hide');
			$("#tips").html("服务器错误！<hr>请联系技术支持并提交以下错误码：<br><font color='blue'>"+e.status+"</font>");
			$("#tipsModal").modal('show');
			return false;
		},
		success:function(ret){
			unlockScreen();
			$("#resetModal").modal('hide');
			
			if(ret.code=="200"){
				$("#info_userName_show").html(ret.data['userName']);
				$("#info_nickName_show").html(ret.data['nickName']);
				$("#info_originPwd_show").html(ret.data['originPwd']);
				$("#infoModal").modal('show');
				return true;
			}else if(ret.message=="nowUser"){
				$("#tips").html("禁止操作当前用户！");
				$("#tipsModal").modal('show');
				return false;
			}else if(ret.message=="resetFailed"){
				$("#tips").html("重置失败！！！");
				$("#tipsModal").modal('show');
				return false;
			}else if(ret.message=="noUser"){
				$("#tips").html("无此用户！！！");
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


function del_ready(id,name){
	$("#delID").val(id);
	$("#delName_show").html(name);
	$("#delModal").modal('show');
}


function del_sure(){
	lockScreen();
	id=$("#delID").val();

	$.ajax({
		url:"<?php echo site_url('admin/user/toDelete'); ?>",
		type:"post",
		dataType:"json",
		data:{<?php echo $this->ajax->showAjaxToken(); ?>,"id":id},
		error:function(e){
			console.log(e);
			unlockScreen();
			$("#delModal").modal('hide');
			$("#tips").html("服务器错误！<hr>请联系技术支持并提交以下错误码：<br><font color='blue'>"+e.status+"</font>");
			$("#tipsModal").modal('show');
			return false;
		},
		success:function(ret){
			unlockScreen();
			$("#delModal").modal('hide');
			
			if(ret.code=="200"){
				alert("删除成功！");
				location.reload();
				return true;
			}else if(ret.message=="nowUser"){
				$("#tips").html("禁止操作当前用户！");
				$("#tipsModal").modal('show');
				return false;
			}else if(ret.message=="deleteFailed"){
				$("#tips").html("删除失败！！！");
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

<div class="modal fade" id="delModal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
				<h3 class="modal-title" id="ModalTitle">温馨提示</h3>
			</div>
			<div class="modal-body">
				<input type="hidden" id="delID">
				<center>
				<font color="red" style="font-weight:bolder;font-size:23px;">确定要删除下列用户吗？</font>
				<br><br>
				<font color="blue" style="font-weight:bolder;font-size:23px;"><p id="delName_show"></p></font>
				</center>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success" data-dismiss="modal">&lt; 返回</button> <button type="button" class="btn btn-danger" onclick="del_sure();">确定 &gt;</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<div class="modal fade" id="resetModal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
				<h3 class="modal-title" id="ModalTitle">温馨提示</h3>
			</div>
			<div class="modal-body">
				<input type="hidden" id="resetID">
				<center>
				<font color="red" style="font-weight:bolder;font-size:23px;">确定要重置下列用户的密码吗？</font>
				<br><br>
				<font color="blue" style="font-weight:bolder;font-size:23px;"><p id="resetName_show"></p></font>
				</center>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success" data-dismiss="modal">&lt; 返回</button> <button type="button" class="btn btn-danger" onclick="resetPwd_sure();">确定 &gt;</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" id="statusModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
        <h3 class="modal-title" id="ModalTitle">更新状态提示</h3>
      </div>
      <div class="modal-body">
        <font style="font-weight:bold;font-size:24px;text-align:center;">
          <p id="statusTips"></p>
        </font>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success" data-dismiss="modal">&lt; 返回</button>
        <button type="button" class="btn btn-primary" onclick="updateStatus_sure();">确认 &gt;</button>
      </div>
    </div>
  </div>
</div>

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
