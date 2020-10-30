<!DOCTYPE html>
<html lang="zh-cmn-Hans">
<head>
    <title><?php echo $_title ?? '众诚娱乐';?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="shortcut icon" href="/favicon.ico">
<?php if (is_array(\frame\Html::getCss())) { ?>
<?php foreach (\frame\Html::getCss() as $value) { ?>
    <link rel="stylesheet" type="text/css" href="<?php echo $value;?>">
<?php }?>
<?php }?>
<?php if (is_array(\frame\Html::getJs())) { ?>
<?php foreach (\frame\Html::getJs() as $value) { ?>
    <script type="text/javascript" src="<?php echo $value;?>"></script>
<?php }?>
<?php }?>
</head>
<body>
<script type="text/javascript">
var URI = "<?php echo url();?>";
var DOMAIN = "<?php echo env('APP_DOMAIN');?>"
</script>