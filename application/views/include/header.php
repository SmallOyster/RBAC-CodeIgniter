<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="">
<meta name="author" content="">

<link href="https://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.bootcss.com/metisMenu/1.1.3/metisMenu.min.css" rel="stylesheet">
<link href="https://cdn.bootcss.com/startbootstrap-sb-admin-2/3.3.7+1/css/sb-admin-2.min.css" rel="stylesheet">
<link href="https://cdn.bootcss.com/morris.js/0.5.0/morris.css" rel="stylesheet">
<link href="https://cdn.bootcss.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
<link href="https://cdn.bootcss.com/datatables/1.10.16/css/dataTables.bootstrap.min.css" rel="stylesheet">
<link src="<?php echo site_url('resource/css/dataTables.responsive.css'); ?>" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.bootcss.com/zTree.v3/3.5.28/css/zTreeStyle/zTreeStyle.min.css" type="text/css">

<script src="https://cdn.bootcss.com/jquery/3.1.0/jquery.min.js"></script>
<script src="https://cdn.bootcss.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="https://cdn.bootcss.com/metisMenu/1.1.3/metisMenu.min.js"></script>
<script src="https://cdn.bootcss.com/raphael/2.2.7/raphael.min.js"></script>
<script src="https://cdn.bootcss.com/morris.js/0.5.0/morris.min.js"></script>
<script src="https://cdn.bootcss.com/startbootstrap-sb-admin-2/3.3.7+1/js/sb-admin-2.min.js"></script>
<script src="https://cdn.bootcss.com/datatables/1.10.16/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.bootcss.com/datatables/1.10.16/js/dataTables.bootstrap.min.js"></script>
<script src="<?php echo site_url('resource/js/dataTables.responsive.js'); ?>"></script>
<script src="<?php echo site_url('resource/js/utils.js'); ?>"></script>
<script type="text/javascript" src="https://cdn.bootcss.com/zTree.v3/3.5.28/js/jquery.ztree.core.min.js"></script>
<script type="text/javascript" src="https://cdn.bootcss.com/zTree.v3/3.5.28/js/jquery.ztree.excheck.min.js"></script>
<script type="text/javascript" src="https://cdn.bootcss.com/zTree.v3/3.5.28/js/jquery.ztree.exedit.min.js"></script>


<script>

/**
* -----------------------------------
* isInArray 妫€娴嬫寚瀹氬瓧绗︿覆鏄惁瀛樺湪浜庢暟缁�
* -----------------------------------
* @param Array  寰呮娴嬬殑鏁扮粍
* @param String 鎸囧畾瀛楃涓�
* -----------------------------------
**/
function isInArray(arr,val){
  length=arr.length;
  
  if(length>0){
    for(var i=0;i<length;i++){
      if(arr[i] == val){
        return i;
      }
    }
    return false;
  }else{
    return false;
  }
}/**
* -------------------------------
* lockScreen 屏幕锁定，显示加载图标
* -------------------------------
**/
function lockScreen(){
$('body').append(
	'<div id="lockContent" style="opacity: 0.2; filter:alpha(opacity=20); width: 100%; height: 100%; z-index: 9999; position:fixed; _position:absolute; top:0; left:0;left:50%; margin-left:-20px; top:50%; margin-top:-20px;">'+
	'<div><img src="<?php echo site_url('resource/images/loading.gif'); ?>"></img></div>'+
	'</div>'+
	'<div id="lockScreen" style="background: #000; opacity: 0.2; filter:alpha(opacity=20); width: 100%; height: 100%; z-index: 9999; position:fixed; _position:absolute; top:0; left:0;">'+
	'</div>'
	);
}


/**
* -------------------------------
* unlockScreen 屏幕解锁
* -------------------------------
**/
function unlockScreen(){
	$('#lockScreen').remove();
	$('#lockContent').remove();
}
</script>
