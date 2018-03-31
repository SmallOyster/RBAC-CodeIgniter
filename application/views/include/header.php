<meta charset="utf-8">
<meta name="description" content="">
<meta name="author" content="">
<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="https://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.bootcss.com/metisMenu/1.1.3/metisMenu.min.css" rel="stylesheet">
<link href="https://cdn.bootcss.com/startbootstrap-sb-admin-2/3.3.7+1/css/sb-admin-2.min.css" rel="stylesheet">
<link href="https://cdn.bootcss.com/morris.js/0.5.0/morris.css" rel="stylesheet">
<link href="https://cdn.bootcss.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
<link href="https://cdn.bootcss.com/datatables/1.10.16/css/dataTables.bootstrap.min.css" rel="stylesheet">
<link src="<?php echo site_url('resource/css/dataTables.responsive.css'); ?>" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.bootcss.com/zTree.v3/3.5.28/css/zTreeStyle/zTreeStyle.min.css" type="text/css">
<link href="https://cdn.bootcss.com/wangEditor/10.0.13/wangEditor.css" rel="stylesheet">

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
<script src="https://cdn.bootcss.com/wangEditor/10.0.13/wangEditor.min.js"></script>

<script>
/**
* -----------------------------------
* setCookie 设置Cookie
* -----------------------------------
* @param String Cookie名称
* @param String Cookie内容
* -----------------------------------
**/
function setCookie(name,value)
{
  var Days = 30;// Cookies有效天数
  var exp = new Date();
  exp.setTime(exp.getTime() + Days*24*60*60*1000);
  document.cookie = name + "="+ escape (value) + ";expires=" + exp.toGMTString();
}


/**
* -----------------------------------
* getCookie 获取Cookie
* -----------------------------------
* @param String Cookie名称
* -----------------------------------
**/
function getCookie(name)
{
  var arr;
  var reg=new RegExp("(^| )"+name+"=([^;]*)(;|$)");
  if(arr=document.cookie.match(reg)){
    return unescape(arr[2]);
  }else{
    return null;
  }
}


/**
* -----------------------------------
* delCookie 删除Cookie
* -----------------------------------
* @param String Cookie名称
* -----------------------------------
**/
function delCookie(name){
  var exp = new Date();
  exp.setTime(exp.getTime()-1);
  var cval=getCookie(name);
  if(cval!=null) document.cookie= name + "="+cval+";expires="+exp.toGMTString();
}

/**
 * lockScreen 屏幕锁定，显示加载图标
 **/
function lockScreen(){
$('body').append(
	'<div id="lockContent" style="opacity: 0.8; filter:alpha(opacity=20); width: 100%; height: 100%; z-index: 9999; position:fixed; _position:absolute; top:0; left:0;left:50%; margin-left:-20px; top:50%; margin-top:-20px;">'+
	'<div><i class="fa fa-circle-o-notch fa-spin fa-5x fa-fw"></i><span class="sr-only">Loading...</span></div>'+
	'</div>'+
	'<div id="lockScreen" style="background: #000; opacity: 0.35; filter:alpha(opacity=20); width: 100%; height: 100%; z-index: 9999; position:fixed; _position:absolute; top:0; left:0;">'+
	'</div>'
	);
}


/**
 * unlockScreen 屏幕解锁
 **/
function unlockScreen(){
	$('#lockScreen').remove();
	$('#lockContent').remove();
}
</script>
