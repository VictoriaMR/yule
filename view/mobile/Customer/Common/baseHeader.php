<!DOCTYPE html>
<html>
<head>
    <title>管理后台</title>
     <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="shortcut icon" href="/favicon.ico">
    <link rel="stylesheet" type="text/css" href="<?php echo mediaUrl('css/mobile/customer/common.css');?>">
<?php foreach (\frame\Html::getCss() as $value) { ?>
    <link rel="stylesheet" type="text/css" href="<?php echo $value;?>">
<?php }?>
<?php foreach (\frame\Html::getJs() as $value) { ?>
    <script type="text/javascript" src="<?php echo $value;?>"></script>
<?php }?>
</head>
<body>
<script type="text/javascript">
var URI = "<?php echo url();?>";
</script>
<?php if (\frame\Router::$_route['path'] != 'Login') { ?>
<div class="common-header">

</div>
<?php } ?>