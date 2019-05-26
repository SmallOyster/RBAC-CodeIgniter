<?php 
/**
 * @name 生蚝科技RBAC开发框架-V-通知列表
 * @author Jerry Cheung <master@xshgzs.com>
 * @since 2018-07-19
 * @version 2019-05-26
 */
?>
<!DOCTYPE html>
<html>
<head>
	<?php $this->load->view('include/header'); ?>
	<title>通知列表 / <?=$this->setting->get('systemName');?></title>
</head>

<body class="hold-transition skin-cyan sidebar-mini">
<div class="wrapper">

<?php $this->load->view('include/navbar'); ?>

<!-- 页面内容 -->
<div id="app" class="content-wrapper">
	<?php $this->load->view('include/pagePath',['name'=>'通知列表','path'=>[['通知列表','',1]]]); ?>

	<!-- 页面主要内容 -->
	<section class="content">
		<div class="box">
			<div class="box-body">

				<table id="table" class="table table-striped table-bordered table-hover" style="border-radius: 5px; border-collapse: separate;">
					<thead>
						<tr>
							<th>标题</th>
							<th>作者</th>
							<th>时间</th>
						</tr>
					</thead>
					<tbody></tbody>
				</table>
			</div>
		</div>
	</section>
</div>

<?php $this->load->view('include/footer'); ?>

<script>
let table;

var vm = new Vue({
	el:'#app',
	data:{},
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
							"order":[[2,'desc']]
						});

						for(i in list){
							table.row.add({
								0: '<a href="'+headerVm.rootUrl+'notice/detail?id='+list[i]['id']+'">'+list[i]['title']+'</a>',
								1: list[i]['publisher'],
								2: list[i]['update_time']
							}).draw();
						}
					}
				}
			})
		}
	},
	mounted:function(){
		this.getList();
	}
});
</script>

</body>
</html>
