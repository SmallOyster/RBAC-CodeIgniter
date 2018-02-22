<?php 
/**
 * @name V-给角色设置权限
 * @author SmallOysyer <master@xshgzs.com>
 * @since 2018-02-17
 * @version V1.0 2018-02-22
 */
?>

<!DOCTYPE html>
<html>

<head>
	<?php $this->load->view('include/header'); ?>
	<title>设置角色权限 / <?php echo $this->config->item('systemName'); ?></title>
	<style>
	.ztree li span.button.add {margin-left:2px; margin-right: -1px; background-position:-144px 0; vertical-align:top; *vertical-align:middle}
	ul.ztree {margin-top: 10px;border: 1px solid #617775;background: #f0f6e4;height:360px;overflow-y:scroll;overflow-x:auto;}
</style>
</head>

<body>
<div id="wrapper">

<?php $this->load->view('include/navbar'); ?>

<div id="page-wrapper">
<!-- Page Main Content -->

<input type="hidden" id="roleID" value="<?php echo $roleID; ?>">
<input type="hidden" id="menuIDs">

<!-- Page Name-->
<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header">
			设置角色权限
			<font style="font-size: 28px;">（角色名：<font color="blue"><b><?php echo urldecode($roleName); ?></b></font>）</font>
		</h1>
	</div>
</div>
<!-- ./Page Name-->

<ul id="treeDemo" class="ztree"></ul>

<hr>

<button onclick='getCheckedNodes();toSetPermission();' class="btn btn-success" style="width: 100%">确 认 分 配 权 限</button>

<!-- ./Page Main Content -->
</div>
<!-- ./Page -->
</div>

<script>
window.onload=function(){
}

var setting = {
	view: {
		selectedMulti: false
	},
	check: {
		enable: true
	},
	data: {
		simpleData: {
			enable: true
		}
	}
};

var zNodes=getAllMenu();

function getCheckedNodes(){
	ids="";
	var zTree = $.fn.zTree.getZTreeObj("treeDemo"),
	nodes = zTree.getCheckedNodes();
	for (i=0,l=nodes.length;i<l;i++){
		ids+=nodes[i].id+",";
	}
	ids=ids.substr(0,ids.length-1);
	console.log(ids);
	$("#menuIDs").val(ids);
}

$(document).ready(function(){
	$.fn.zTree.init($("#treeDemo"),setting,zNodes);
});

function getAllMenu(){
	roleID=$("#roleID").val();
	
	$.ajax({
		url:"<?php echo site_url('api/getAllMenuForZtree'); ?>",
		type:"post",
		dataType:"json",
		async:false,
		data:{<?php echo $this->ajax->showAjaxToken(); ?>,'roleID':roleID},
		error:function(e){
			console.log(e);
			unlockScreen();
			$("#tips").html("服务器错误！<hr>请联系技术支持并提交以下错误码：<br><font color='blue'>"+e.status+"</font>");

			$("#tipsModal").modal('show');
			return false;
		},
		success:function(got){
			ret=got;
		}
	});
	return ret;
}
function toSetPermission(){
	lockScreen();
	roleID=$("#roleID").val();
	menuIDs=$("#menuIDs").val();

	$.ajax({
		url:"<?php echo site_url('admin/role/toSetPermission'); ?>",
		type:"post",
		dataType:"json",
		data:{<?php echo $this->ajax->showAjaxToken(); ?>,'roleID':roleID,'menuIDs':menuIDs},
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
				alert("权限分配成功！");
				history.go(-1);
				return true;
			}else if(ret.message=="quantityMismatch"){
				$("#tips").html("权限分配数量不匹配！！<br>请联系管理员！");
				$("#tipsModal").modal('show');
				return false;
			}else if(ret.message=="truncateFailed"){
				$("#tips").html("权限清空失败！！<br>请联系管理员！");
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

<div class="modal fade" id="ztreeModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
        <h3 class="modal-title" id="ModalTitle">温馨提示</h3>
      </div>
      <div class="modal-body">
        <div style="overflow:hidden;">
        </div>
        <form method="post" name="OprNode">
        <input type="hidden" id="OprType" name="OprType">
        <p id="msg"></p>       
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">&lt; 取消</button>
        <button type="button" class="btn btn-success" id='okbtn' onclick="submitOpr()">确定 &gt;</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

</body>
</html>
