<?php 
/**
 * @name 生蚝科技RBAC开发框架-V-菜单管理
 * @author Jerry Cheung <master@xshgzs.com>
 * @since 2018-02-17
 * @version 2019-03-17
 */
?>
<!DOCTYPE html>
<html>

<head>
	<?php $this->load->view('include/header'); ?>
	<title>菜单管理 / <?=$this->Setting_model->get('systemName');?></title>
</head>

<body class="hold-transition skin-cyan sidebar-mini">
<div class="wrapper">

<?php $this->load->view('include/navbar'); ?>

<!-- 页面内容 -->
<div id="app" class="content-wrapper">
	<?php $this->load->view('include/pagePath',['name'=>'菜单管理','path'=>[['菜单管理','',1]]]); ?>

	<!-- 页面主要内容 -->
	<section class="content">
		<a href="<?=base_url('admin/menu/add/0'); ?>" class="btn btn-primary btn-block">新 增 主 菜 单</a>
		<table class="table table-hover" style="border-radius: 5px; border-collapse: separate;">
			<thead>
				<tr>
					<th>菜单名</th>
					<th>操作</th>
				</tr>
			</thead>

			<tbody>
			<?php foreach($list as $info){ ?>
				<tr>
					<td><i class="fa fa-<?=$info['icon']; ?>" aria-hidden="true"></i> <?=$info['name']; ?></td>
					<td><a href="<?=base_url('admin/menu/edit/').$info['id']; ?>" class="btn btn-info">编辑</a> <button onclick='del_ready("<?=$info['id']; ?>","<?=$info['name']; ?>")' class="btn btn-danger">删除</button> <a href="<?=base_url('admin/menu/add/').$info['id']; ?>" class="btn btn-success">新增子菜单</a></td>
				</tr>
				<?php foreach($info['child'] as $child_info){ ?>
				<tr>
					<td>---- <i class="fa fa-<?=$child_info['icon']; ?>" aria-hidden="true"></i> <?=$child_info['name']; ?></td>
					<td><a href="<?=base_url('admin/menu/edit/').$child_info['id']; ?>" class="btn btn-info">编辑</a> <button onclick='del_ready("<?=$child_info['id']; ?>","<?=$child_info['name']; ?>")' class="btn btn-danger">删除</button> <a href="<?=base_url('admin/menu/add/').$child_info['id']; ?>" class="btn btn-success">新增子菜单</a></td>
				</tr>
				<?php foreach($child_info['child'] as $child2_info){ ?>
				<tr>
					<td>-------- <i class="fa fa-<?=$child2_info['icon']; ?>" aria-hidden="true"></i> <?=$child2_info['name']; ?></td>
					<td><a href="<?=base_url('admin/menu/edit/').$child2_info['id']; ?>" class="btn btn-info">编辑</a> <button onclick='del_ready("<?=$child2_info['id']; ?>","<?=$child2_info['name']; ?>")' class="btn btn-danger">删除</button></td>
				</tr>
				<?php } } } ?>
			</tbody>
		</table>
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
function del_ready(id,name){
	$("#delID").val(id);
	$("#delName_show").html(id+". "+name);
	$("#delModal").modal('show');
}


function del_sure(){
	lockScreen();
	id=$("#delID").val();

	$.ajax({
		url:"<?=base_url('admin/menu/toDelete'); ?>",
		type:"post",
		dataType:"json",
		data:{<?=$this->ajax->showAjaxToken(); ?>,"id":id},
		error:function(e){
			console.log(e);
			unlockScreen();
			$("#delModal").modal('hide');
			showModalTips("服务器错误！<hr>请联系技术支持并提交以下错误码：<br><font color='blue'>"+e.status+"</font>");
			return false;
		},
		success:function(ret){
			unlockScreen();
			
			if(ret.code==200){
				$("#delModal").modal('hide');
				alert("删除成功！");
				location.reload();
				return true;
			}else if(ret.code==1){
				$("#delModal").modal('hide');
				showModalTips("删除失败！！！");
				return false;
			}else if(ret.code==403001){
				$("#delModal").modal('hide');
				showModalTips("Token无效！<hr>Tips:请勿在提交前打开另一页面哦~");
				return false;
			}else{
				$("#delModal").modal('hide');
				showModalTips("系统错误！<hr>请联系技术支持并提交以下错误码：<br><font color='blue'>"+ret.code+"</font>");
				return false;
			}
		}
	});
}
</script>

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
				<font color="red" style="font-weight:bolder;font-size:23px;">确定要删除下列菜单吗？</font>
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

</body>
</html>
