<!DOCTYPE html>
<html>
<head>
	<title>测试授权</title>
</head>
<body>
	<form id="test_form" method="get" action="<?php echo $url;?>"></form>
	<script type="text/javascript">
		var form = document.getElementById('test_form');
		//再次修改input内容
		form.submit();
	</script>
</body>
</html>