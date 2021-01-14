<?php $this->load('Common.baseHeader');?>
<div class="tixian-content">
	<div class="font-14 margin-top-16">
		<span>余额:</span>
		<span><?php echo $balance;?></span>
	</div>
	<form action="<?php echo url('wallet/tixian');?>" method="post" class="margin-top-16">
		<div class="item">
			<input type="text" class="input" name="account" value="<?php echo $info['alipay_account'] ?? '' ;?>" <?php if ($balance <= 0) { echo 'disabled="disabled';}?> oninput="value=value.replace(/[^\d]/g,'')" maxlength="10" autocomplete="off"/>
		</div>
		<button id="tixian-btn" type="button" class="btn margin-top-16">确定</button>
	</form>
	<p class="margin-top-16 color-r font-12" id="error-tips"><?php echo $error;?></p>
</div>
<script type="text/javascript">
$(function(){
	$('#tixian-btn').on('click', function(){
		var errorobj = $('#error-tips');
		errorobj.text('');
		var obj = $('[name="account"]');
		var val = obj.val();
		if (val <= 0) {
			obj.focus();
			errorobj.text('请输入金额');
			return false;
		}
		$(this).parent().submit();
	});
});
</script>
<?php $this->load('Common.baseFooter');?>