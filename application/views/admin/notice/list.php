<?php 
/**
 * @name 生蚝科技RBAC开发框架-V-通知管理
 * @author Jerry Cheung <master@xshgzs.com>
 * @since 2018-03-28
 * @version 2019-05-26
 */ 
?>
<!DOCTYPE html>
<html>

<head>
	<?php $this->load->view('include/header'); ?>
	<title>通知管理 / <?=$this->setting->get('systemName');?></title>
</head>

<body class="hold-transition skin-cyan sidebar-mini">
<div class="wrapper">

<?php $this->load->view('include/navbar'); ?>

<!-- 页面内容 -->
<div id="app" class="content-wrapper">
	<?php $this->load->view('include/pagePath',['name'=>'通知管理','path'=>[['通知管理','',1]]]); ?>

	<!-- 页面主要内容 -->
	<section class="content">

		<a href="<?=base_url('admin/notice/pub'); ?>" class="btn btn-primary btn-block">发 布 新 通 知 &gt;</a>
		<hr>

		<div class="box">
			<div class="box-body">
				<table id="table" class="table table-striped table-bordered table-hover" style="border-radius: 5px; border-collapse: separate;">
					<thead>
						<tr>
							<th>标题</th>
							<th>作者</th>
							<th>时间</th>
							<th>操作</th>
						</tr>
					</thead>
					<tbody></tbody>
				</table>
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
let table;

var vm = new Vue({
	el:'#app',
	data:{
		deleteId:0
	},
	methods:{
		getList:()=>{
			$.ajax({
				url:headerVm.apiPath+"notice/get",
				data:{'type':'list'},
				dataType:'json',
				success:ret=>{
					if(ret.code==200){
						let list=ret.data['list'];

						table=$('#table').DataTable({
							responsive: true,
							"order":[[2,'desc']],
							"columnDefs":[{
								"targets":[3],
								"orderable": false
							}]
						});

						for(i in list){
							let operateHtml=''
							               +'<a href="'+headerVm.rootUrl+'notice/detail'+list[i]['id']+'" class="btn btn-primary">详细</a> '
							               +'<a href="'+headerVm.rootUrl+'admin/notice/edit'+list[i]['id']+'" class="btn btn-info">编辑</a> '
							               +"<a onclick='vm.del_ready("+'"'+list[i]['id']+'","'+escape(list[i]['title'])+'"'+")' class='btn btn-danger'>删除</a> ";

							table.row.add({
								0: list[i]['title'],
								1: list[i]['publisher'],
								2: list[i]['update_time'],
								3: operateHtml
							}).draw();
						}
					}
				}
			})
		},
		del_ready:(id,name)=>{
			vm.deleteId=id;
			$("#delName_show").html(unescape(name));
			$("#delModal").modal('show');
		},
		del_sure(){
			lockScreen();
			$.ajax({
				url:"./toDelete",
				type:"post",
				dataType:"json",
				data:{<?=$this->ajax->showAjaxToken(); ?>,"id":vm.deleteId},
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
	},
	mounted:function(){
		this.getList();
	}
});
</script>

<div class="modal fade" id="delModal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
				<h3 class="modal-title" id="ModalTitle">温馨提示</h3>
			</div>
			<div class="modal-body">
				<center>
				<font color="red" style="font-weight:bolder;font-size:23px;">确定要删除下列通知吗？</font>
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
