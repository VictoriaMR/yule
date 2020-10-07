<?php if (\frame\Router::$_route['path'] != 'Login') { ?>
<div class="common-footer">
	<a href="<?php echo url('');?>" class="font-16 color-w block w3 h100 left">
		<span class="icon icon-home font-18"></span>
		<span>首页</span>
	</a>
	<a href="<?php echo url('');?>" class="font-16 color-w block w3 h100 left">
		<span class="icon icon-category font-18"></span>
		<span>客户</span>
	</a>
	<a href="<?php echo url('userInfo');?>" class="font-16 color-w block w3 h100 left">
		<span class="icon icon-qq font-18"></span>
		<span>我的</span>
	</a>
</div>
<?php } ?>
</body>
</html>