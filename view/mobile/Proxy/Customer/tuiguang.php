<?php $this->load('Common.baseHeader');?>
<div class="tuiguang-content">
	<div class="img-btn">
		<img src="<?php echo $url;?>">
	</div>
	<div class="btn-content">
		<a class="btn" href="<?php echo url('customer/tuiguang', ['type' => 'reset']);?>">重新生成</a>
		<a class="btn" href="<?php echo url('customer/tuiguang', ['type' => 'download']);?>">下载</a>
	</div>
</div>
<?php $this->load('Common.baseFooter');?>