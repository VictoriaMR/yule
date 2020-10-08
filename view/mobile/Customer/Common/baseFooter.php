<?php if (\frame\Router::$_route['path'] != 'Login') { ?>
</div>
<div class="common-footer">
	<a href="<?php echo url('');?>" class="font-16 color-w block w25 h100 left">
		<span class="icon icon-moneymanagement font-20"></span>
		<span>首页</span>
	</a>
	<a href="<?php echo url('customer');?>" class="font-16 color-w block w25 h100 left <?php echo \frame\Router::$_route['path'] == 'Customer' ? 'select' : '';?>">
		<span class="icon icon-customermanagement font-20"></span>
		<span>客户</span>
	</a>
	<a href="<?php echo url('proxy');?>" class="font-16 color-w block w25 h100 left <?php echo \frame\Router::$_route['path'] == 'Proxy' ? 'select' : '';?>">
		<span class="icon icon-connections font-20"></span>
		<span>代理</span>
	</a>
	<a href="<?php echo url('userInfo');?>" class="font-16 color-w block w25 h100 left <?php echo \frame\Router::$_route['path'] == 'UserInfo' ? 'select' : '';?>">
		<span class="icon icon-usercenter font-20"></span>
		<span>我的</span>
	</a>
</div>
<?php } ?>
</body>
</html>