<?php $this->load('Common.baseHeader');?>
<div class="tixian-content">
	<form action="<?php echo url('wallet/tixian');?>" method="post">
		<div class="item">
			<input type="text" name="account" value="<?php echo $info['alipay_account'] ?? '' ;?>" <?php if ($tixian <= 0) { echo 'disabled="disabled';}?>>
		</div>
		<button type="button">确定</button>
	</form>
</div>
<?php $this->load('Common.baseFooter');?>