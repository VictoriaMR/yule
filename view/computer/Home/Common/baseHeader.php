<!DOCTYPE html>
<html>
<head>
    <title><?php echo $title ?? '';?></title>
    <meta name="renderer" content="webkit|ie-comp|ie-stand">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <link rel="shortcut icon" href="favicon.ico">
    <link rel="stylesheet" type="text/css" href="<?php echo \frame\Html::getCss();?>" />
    <script type="text/javascript" src="<?php echo \frame\Html::getJs();?>"></script>
</head>
<body>
<script type="text/javascript">
var URI = "<?php echo url();?>";
</script>
