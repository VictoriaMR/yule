<?php $this->load('Common.baseHeader');?>
<div class="row">
	<?php if (!empty($list)) { ?>
	<?php foreach ($list as $key => $value) { ?>
		<?php if (!empty($value['son'])) { ?>
		<?php foreach ($value['son'] as $k => $v) { ?>
		<a class="w50 inline-block margin-top-20" href="<?php echo $v['url'];?>">
			<img src="<?php echo $v['icon_url'];?>">
			<div class="font-14 font-600"><?php echo $v['name'];?></div>
		</a>
		<?php } ?>
		<?php } ?>
	<?php } ?>
	<?php } ?>
</div>
<?php $this->load('Common.baseFooter');?>