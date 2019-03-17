<?php 
/**
 * @name 生蚝科技RBAC开发框架-V-发布新通知
 * @author Jerry Cheung <master@xshgzs.com>
 * @since 2018-03-29
 * @version 2019-03-17
 */
?>
<!DOCTYPE html>
<html>

<head>
	<?php $this->load->view('include/header'); ?>
	<title>发布新通知 / <?=$this->Setting_model->get('systemName');?></title>
</head>

<body class="hold-transition skin-cyan sidebar-mini">
<div class="wrapper">

<?php $this->load->view('include/navbar'); ?>

<!-- 页面内容 -->
<div id="app" class="content-wrapper">
	<?php $this->load->view('include/pagePath',['name'=>'发布新通知','path'=>[['通知管理',base_url('admin/notice/list')],['发布新通知','',1]]]); ?>

	<!-- 页面主要内容 -->
	<section class="content">

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
var E = window.wangEditor;
var editor = new E('#wangEditor_div');
editor.create();

$(function(){
	$('#tipsModal').on('hidden.bs.modal',function (){
		$("#wangEditor_div").removeAttr("style");
	});
});

function publish(){
	lockScreen();
	title=$("#title").val();
	content=editor.txt.html();
	$("#wangEditor_div").attr("style","display:none;");
	
	if(title==""){
		unlockScreen();
		showModalTips("请输入通知标题！");
		return false;
	}
	if(content==""){
		unlockScreen();
		showModalTips("请输入通知内容！");
		return false;
	}
	if(title.length<1 || title.length>50){
		unlockScreen();
		showModalTips("请输入 1-50字 的通知标题！");
		return false;
	}

	$.ajax({
		url:"<?=base_url('admin/notice/toPublish'); ?>",
		type:"post",
		data:{<?=$this->ajax->showAjaxToken(); ?>,"title":title,"content":content},
		dataType:'json',
		error:function(e){
			console.log(JSON.stringify(e));
			unlockScreen();
			showModalTips("服务器错误！<hr>请联系技术支持并提交以下错误码：<br><font color='blue'>"+e.status+"</font>");
			$("#tipsModal").modal('show');
			return false;
		},
		success:function(ret){
			unlockScreen();
			
			if(ret.code==200){
				alert("发布成功！");
				history.go(-1);
				return true;
			}else if(ret.code==1){
				showModalTips("发布失败！！！");
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
</script>

</body>
</html>
