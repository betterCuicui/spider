<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html lang="zh">
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"> 
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<title>搜索</title>
<style type="text/css">
em {
	color:red;
}
</style>
<link rel="stylesheet" type="text/css" href="__PUBLIC__/Css/default.css" />

<!--必要样式-->
<link rel="stylesheet" type="text/css" href="__PUBLIC__/Css/search-form.css" />

</head>
<body>

<form method='get'>
	<div class="search-wrapper" style="top:10%;">
		<div class="input-holder">
			<input type="text" name="search_contents" class="search-input" placeholder="请输入关键词" />
			<button class="search-icon" onClick="searchToggle(this, event);"><span></span></button>
		</div>
		<span class="close" onClick="searchToggle(this, event);"></span>
		<div class="result-container">

		</div>
	</div>
</form>
<div id='show_search_contents' style="position:relative;top:20%;margin-left:10%;margin-right: 10%;">
	<?php if(is_array($res)): $i = 0; $__LIST__ = $res;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$data): $mod = ($i % 2 );++$i;?><a href="<?php echo ($data['url']); ?>" target="_blank">
		<span class = 'tittle' style="font-size: 25px;color:black;"><?php echo ($data['tittle']); ?></span><br/>
		<?php echo ($data['public_time']); ?>
		<span class = 'tittle' style="color:black;"><?php echo ($data['contents']); ?></span><br/>
		</a>
		<br/><br/><?php endforeach; endif; else: echo "" ;endif; ?>
</div>
<script src="__PUBLIC__/Js/jquery-1.11.0.min.js" type="text/javascript"></script>
<script type="text/javascript">
function searchToggle(obj, evt){
	var container = $(obj).closest('.search-wrapper');

	if(!container.hasClass('active')){
		  container.addClass('active');
		  evt.preventDefault();
	}
	else if(container.hasClass('active') && $(obj).closest('.input-holder').length == 0){
		  container.removeClass('active');
		  // clear input
		  container.find('.search-input').val('');
		  // clear and hide result container when we press close
		  container.find('.result-container').fadeOut(100, function(){$(this).empty();});
	}
}
</script>
</body>
</html>