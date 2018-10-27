<?php 
/**
 * @name V-系统设置列表
 * @author SmallOysyer <master@xshgzs.com>
 * @since 2018-03-03
 * @version 2018-10-24
 */
 
?>

<!DOCTYPE html>
<html>

<head>
	<?php $this->load->view('include/header'); ?>
	<title>系统设置列表 / <?=$this->Setting_model->get('systemName'); ?></title>
</head>

<body>
<div id="wrapper">

<?php $this->load->view('include/navbar'); ?>

<div id="page-wrapper">
<!-- Page Main Content -->

<!-- Page Name-->
<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header">系统设置列表</h1>
	</div>
</div>
<!-- ./Page Name-->

<hr>

<table id="table" class="table table-striped table-bordered table-hover" style="border-radius: 5px; border-collapse: separate;">
	<thead>
		<tr>
			<th>名称</th>
			<th>内容</th>
			<th>操作</th>
		</tr>
	</thead>
	
	<tbody>
	<?php
	foreach($list as $info){
	?>
	<tr>
		<td><?php echo $info['chinese_name']; ?></td>
		<td>
			<!-- 显示 -->
			<p id="<?php echo $info['name']; ?>_show"><?php echo $info['value']; ?></p>

			<!-- 输入框 -->
			<input type="hidden" id="<?php echo $info['name']; ?>_input" class="form-control" value="<?php echo $info['value']; ?>">
		</td>
		<td>
			<button class="btn btn-primary" id="<?php echo $info['name']; ?>_btn1" onclick='edit("<?php echo $info['name']; ?>")'>修改</button>
			<button class="btn btn-success" id="<?php echo $info['name']; ?>_btn2" onclick='save("<?php echo $info['name']; ?>")' style="display:none">保存</button>
		</td>
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
function edit(name){
	$("#"+name+"_show").attr("style","display:none");
	$("#"+name+"_input").attr("type","");
	$("#"+name+"_btn1").attr("style","display:none");
	$("#"+name+"_btn2").attr("style","");
}

function save(name){
	lockScreen();
	value=$("#"+name+"_input").val();

	$.ajax({
		url:"<?=base_url('admin/sys/setting/toSave');?>",
		type:"post",
		dataType:"json",
		data:{<?=$this->ajax->showAjaxToken();?>,"name":name,"value":value},
		error:function(e){
			console.log(e);
			unlockScreen();
			$("#truncateModal").modal('hide');
			$("#tips").html("服务器错误！<hr>请联系技术支持并提交以下错误码：<br><font color='blue'>"+e.responseText+"</font>");
			$("#tipsModal").modal('show');
			return false;
		},
		success:function(ret){
			unlockScreen();
			
			if(ret.code=="200"){
				alert("设置成功！");
				$("#"+name+"_show").attr("style","");
				$("#"+name+"_show").html(value);
				$("#"+name+"_input").attr("type","hidden");
				$("#"+name+"_btn1").attr("style","");
				$("#"+name+"_btn2").attr("style","display:none");
				return true;
			}else if(ret.message=="noSetting"){
				$("#tips").html("无此配置项！");
				$("#tipsModal").modal('show');
				return false;
			}else if(ret.message=="saveFailed"){
				$("#tips").html("保存失败！");
				$("#tipsModal").modal('show');
				return false;
			}else if(ret.code=="403"){
				$("#truncateModal").modal('hide');
				$("#tips").html("Token无效！<hr>Tips:请勿在提交前打开另一页面哦~");
				$("#tipsModal").modal('show');
				return false;
			}else{
				$("#truncateModal").modal('hide');
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
