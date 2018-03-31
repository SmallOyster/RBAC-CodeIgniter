<?php 
/**
 * @name V-发布新通知
 * @author SmallOysyer <master@xshgzs.com>
 * @since 2018-03-29
 * @version V1.0 2018-03-31
 */
?>

<!DOCTYPE html>
<html>

<head>
	<?php $this->load->view('include/header'); ?>
	<title>发布新通知 / <?php echo $this->config->item('systemName'); ?></title>
</head>

<body>
<div id="wrapper">

<?php $this->load->view('include/navbar'); ?>

<div id="page-wrapper">
<!-- Page Main Content -->

<!-- Page Name-->
<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header">发布新通知</h1>
	</div>
</div>
<!-- ./Page Name-->

<div class="panel panel-default">
	<div class="panel-heading">发布新通知</div>
	<div class="panel-body">
		<div class="form-group">
			<label for="title">通知标题</label>
			<input class="form-control" id="title" onkeyup='if(event.keyCode==13)$("#content").focus();'>
			<p class="help-block">请输入<font color="green">1</font>-<font color="green">50</font>字的通知标题</p>
		</div>
		<br>
		<div class="form-group">
			<label for="content">通知内容</label>
			<div id="wangEditor_div"></div>
		</div>
		<button class="btn btn-success btn-block" onclick='publish()'>确 认 发 布 &gt;</button>
	</div>
</div>

<?php $this->load->view('include/footer'); ?>

<!-- ./Page Main Content -->
</div>
<!-- ./Page -->
</div>

<script>
var E = window.wangEditor;
var editor = new E('#wangEditor_div');
editor.create();

function publish(){
	lockScreen();
	title=$("#title").val();
	content=editor.txt.html();
	editor.$textElem.attr('contenteditable', false);

	if(title==""){
		unlockScreen();
		editor.$textElem.attr('contenteditable', true)
		$("#tips").html("请输入通知标题！");
		$("#tipsModal").modal('show');
		return false;
	}
	if(content==""){
		unlockScreen();
		editor.$textElem.attr('contenteditable', true)
		$("#tips").html("请输入通知内容！");
		$("#tipsModal").modal('show');
		return false;
	}
	if(title.length<1 || title.length>50){
		unlockScreen();
		editor.$textElem.attr('contenteditable', true)
		$("#tips").html("请输入 1-50字 的通知标题！");
		$("#tipsModal").modal('show');
		return false;
	}

	$.ajax({
		url:"<?php echo site_url('admin/notice/toPublish'); ?>",
		type:"post",
		data:{<?php echo $this->ajax->showAjaxToken(); ?>,"title":title,"content":content},
		dataType:'json',
		error:function(e){
			console.log(JSON.stringify(e));
			unlockScreen();
			editor.$textElem.attr('contenteditable', true)
			$("#tips").html("服务器错误！<hr>请联系技术支持并提交以下错误码：<br><font color='blue'>"+e.responseText+"</font>");
			$("#tipsModal").modal('show');
			return false;
		},
		success:function(ret){
			unlockScreen();
			
			if(ret.code=="200"){
				alert("发布成功！");
				history.go(-1);
				return true;
			}else if(ret.message=="publishFailed"){
				editor.$textElem.attr('contenteditable', true)
				$("#tips").html("发布失败！！！");
				$("#tipsModal").modal('show');
				return false;
			}else if(ret.code=="403"){
				editor.$textElem.attr('contenteditable', true)
				$("#tips").html("Token无效！<hr>Tips:请勿在提交前打开另一页面哦~");
				$("#tipsModal").modal('show');
				return false;
			}else{
				editor.$textElem.attr('contenteditable', true)
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
