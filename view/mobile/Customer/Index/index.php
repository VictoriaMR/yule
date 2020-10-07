<?php $this->load('Common.baseHeader');?>
<div class="main">
	<div class="flex">
		<a class="w50 block" href="<?php echo url('customer');?>">
			<div class="icon icon-product font-60"></div>
			<div class="font-14 font-600">我的客户</div>
		</a>
		<a class="w50 block" href="<?php echo url('proxy');?>">
			<div class="icon icon-map font-60"></div>
			<div class="font-14 font-600">我的代理</div>
		</a>
	</div>
</div>
<?php $this->load('Common.baseFooter');?>