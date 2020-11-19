<?php $this->load('Common.baseHeader');?>
<div class="row">
	<a class="w50 inline-block" href="<?php echo url('customer');?>">
		<div class="icon icon-customermanagement font-60"></div>
		<div class="font-14 font-600">我的客户</div>
	</a>
	<a class="w50 inline-block" href="<?php echo url('proxy');?>">
		<div class="icon icon-connections font-60"></div>
		<div class="font-14 font-600">我的代理</div>
	</a>
	<a class="w50 inline-block margin-top-20" href="<?php echo url('wallet', ['type' => 1]);?>">
		<div class="icon icon-wallet font-60"></div>
		<div class="font-14 font-600">钱包</div>
	</a>
	<a class="w50 inline-block margin-top-20" href="<?php echo url('wallet/tixian');?>">
		<div class="icon icon-rmb font-60"></div>
		<div class="font-14 font-600">提现</div>
	</a>
</div>
<?php $this->load('Common.baseFooter');?>