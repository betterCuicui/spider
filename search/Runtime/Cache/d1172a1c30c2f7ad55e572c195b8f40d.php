<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
<title></title>
<meta charset="UTF-8">
<link rel="stylesheet" type="text/css" href="__PUBLIC__/Css/bootstrap.css" />
<link rel="stylesheet" type="text/css"
	href="__PUBLIC__/Css/bootstrap-responsive.css" />
<link rel="stylesheet" type="text/css" href="__PUBLIC__/Css/style.css" />
<script type="text/javascript" src="__PUBLIC__/Js/jquery.js"></script>
<script type="text/javascript" src="__PUBLIC__/Js/jquery.sorted.js"></script>
<script type="text/javascript" src="__PUBLIC__/Js/bootstrap.js"></script>
<script type="text/javascript" src="__PUBLIC__/Js/ckform.js"></script>
<script type="text/javascript" src="__PUBLIC__/Js/common.js"></script>
<script type="text/javascript" src="__PUBLIC__/assets/js/jquery-1.8.1.min.js"></script>
<style type="text/css">
body {
	padding-bottom: 40px;
}

.sidebar-nav {
	padding: 9px 0;
}

@media ( max-width : 980px) {
	/* Enable use of floated navbar text */
	.navbar-text.pull-right {
		float: none;
		padding-left: 5px;
		padding-right: 5px;
	}
}
</style>

<script type="text/javascript">
</script>

</head>
<body>
	<div class="form-inline definewidth m20">
		内容： <input type="text" name="menuname" id="bookname"
			class="abc input-default" placeholder="" value="">&nbsp;&nbsp;
		<button class="btn btn-primary" id = "search">查询</button>
		&nbsp;&nbsp;
	</div>
	<table class="table table-bordered table-hover definewidth m10" id = "table_books">
			<tr>
				<th>id</th>
				<th>tittle</th>
				<th>url</th>
				<th>public_time</th>
				<th>操作</th>
			</tr>		
	</table>

</body>
</html>