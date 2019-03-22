<?php 
/**
 * @name 生蚝科技RBAC开发框架-V-通知编辑
 * @author Jerry Cheung <master@xshgzs.com>
 * @since 2018-03-31
 * @version 2019-03-22
 */
?>
<!DOCTYPE html>
<html>

<head>
	<?php $this->load->view('include/header'); ?>
	<title>通知编辑 / <?=$this->Setting_model->get('systemName');?></title>
</head>

<body class="hold-transition skin-cyan sidebar-mini">
<div class="wrapper">

<?php $this->load->view('include/navbar'); ?>

<!-- 页面内容 -->
<div id="app" class="content-wrapper">
	<?php $this->load->view('include/pagePath',['name'=>'通知编辑','path'=>[['通知管理',base_url('admin/notice/list')],['通知编辑','',1]]]); ?>

	<!-- 页面主要内容 -->
	<section class="content">
		<input type="hidden" id="noticeId" value="<?=$info['id']; ?>">

		<div class="panel panel-default">
			<div class="panel-body">
				<div class="form-group">
					<label for="title">通知标题</label>
					<input class="form-control" id="title" onkeyup='if(event.keyCode==13)$("#content").focus();' value="<?=$info['title']; ?>">
					<p class="help-block">请输入<font color="green">1</font>-<font color="green">50</font>字的通知标题</p>
				</div>
				<br>
				<div class="form-group">
					<label for="content">通知内容</label>
					<div id="wangEditor_div"></div>
				</div>
				<hr>
				<button class="btn btn-success btn-block" onclick='edit()'>确 认 修 改 &gt;</button>
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
editor.txt.html("<?=$info['content']; ?>");

$(function(){
	$('#tipsModal').on('hidden.bs.modal',function (){
		$("#wangEditor_div").removeAttr("style");
	});
});

function edit(){
	lockScreen();
	id=$("#noticeId").val();
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
		editor.$textElem.attr('contenteditable', true)
		showModalTips("请输入通知内容！");
		return false;
	}
	if(title.length<1 || title.length>50){
		unlockScreen();
		showModalTips("请输入 1-50字 的通知标题！");
		return false;
	}

	$.ajax({
		url:"<?=base_url('admin/notice/toEdit');?>",
		type:"post",
		data:{<?=$this->ajax->showAjaxToken(); ?>,"id":id,"title":title,"content":content},
		dataType:'json',
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
				showModalTips("系统错误！<hr>请联系技术支持并提交以下错误码：<br><font color='blue'>"+ret.code+"</font>");
				return false;
			}
		}
	});
}
</script>

</body>
</html>
