<?php 
/**
 * @name 生蚝科技RBAC开发框架-V-菜单管理
 * @author Jerry Cheung <master@xshgzs.com>
 * @since 2018-02-17
 * @version 2019-05-18
 */
?>
<!DOCTYPE html>
<html>

<head>
	<?php $this->load->view('include/header'); ?>
	<title>菜单管理 / <?=$this->setting->get('systemName');?></title>
	<style>
		.ztree li span.button.add {margin-left:2px; margin-right: -1px; background-position:-144px 0; vertical-align:top; *vertical-align:middle}
		ul.ztree {margin-top: 10px;border: 1px solid #617775;background: #f0f6e4;height:360px;overflow-y:scroll;overflow-x:auto;}
	</style>
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

		<ul id="treeDemo" class="ztree"></ul>
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
var setting = {
	view: {
		selectedMulti: false
	},
	data: {
		simpleData: {
			enable: true
		}
	},
	view: {
		addHoverDom: addHoverDom,
		removeHoverDom: removeHoverDom,
	}
};

var zNodes=getAllMenu();

$(document).ready(function(){
	$.fn.zTree.init($("#treeDemo"),setting,zNodes);
});
function addHoverDom(treeId, treeNode) {
	var aObj=$("#"+treeNode.tId+"_a");
	var editStr=""
		+"<button class='btn btn-info' id='treeBtn_edit_"+treeNode.id+"' onclick='window.location.href="+'"'+"<?=base_url('admin/menu/edit/');?>"+treeNode.id+'"'+"'>编辑</button> "
		+"<button class='btn btn-danger' id='treeBtn_delete_"+treeNode.id+"' onclick="+'"'+"del_ready("+treeNode.id+",'"+treeNode.name+"')"+'"'+"'>删除</button> "
		+"<button class='btn btn-success' id='treeBtn_add_"+treeNode.id+"' onclick='window.location.href="+'"'+"<?=base_url('admin/menu/add/');?>"+treeNode.id+'"'+"'>新增子菜单</button>";
	
	// 如果已存在button就返回
	if($("#treeBtn_edit_"+treeNode.id).length>0) return;
	if($("#treeBtn_delete_"+treeNode.id).length>0) return;
	if($("#treeBtn_add_"+treeNode.id).length>0) return;
	
	aObj.append(editStr);
	
	// 三级菜单不允许新增子菜单
	if(treeNode.level>=2) $("#treeBtn_add_"+treeNode.id).remove();
}
function removeHoverDom(treeId, treeNode) {
	$("#treeBtn_edit_"+treeNode.id).unbind().remove();
	$("#treeBtn_delete_"+treeNode.id).unbind().remove();
	$("#treeBtn_add_"+treeNode.id).unbind().remove();
}

function getAllMenu(){
	roleId=$("#roleId").val();
	
	$.ajax({
		url:"<?=base_url('api/role/getRoleMenuForZtree/0');?>",
		dataType:"json",
		async:false,
		error:function(e){
			console.log(e);
			unlockScreen();
			showModalTips("服务器错误！<hr>请联系技术支持并提交以下错误码：<br><font color='blue'>"+e.status+"</font>");

			$("#tipsModal").modal('show');
			return false;
		},
		success:function(got){
			ret=got;
		}
	});
	return ret;
}


function del_ready(id,name){
	$("#delId").val(id);
	$("#delName_show").html(id+". "+name);
	$("#delModal").modal('show');
}


function del_sure(){
	lockScreen();
	id=$("#delId").val();

	$.ajax({
		url:"<?=base_url('admin/menu/toDelete');?>",
		type:"post",
		dataType:"json",
		data:{<?=$this->ajax->showAjaxToken();?>,"id":id},
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
			}else if(ret.code==0){
				showModalTips("参数缺失！请联系技术支持！");
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
				<input type="hidden" id="delId">
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
