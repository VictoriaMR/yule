<?php $this->load('Common.baseHeader');?>
<div class="customer-title">
	<ul>
		<li>头像</li>
		<li>名称</li>
		<li>推荐人</li>
		<li>充值</li>
		<li>消费</li>
	</ul>
</div>
<div id="customer-content">
	<ul class="font-14"
	<?php foreach ($param as $key => $value) { ?>
	data-<?php echo $key;?>="<?php echo $value;?>"
	<?php } ?>
	>
	</ul>
</div>
<script type="text/javascript">
$(function(){
	CUSTOMER.init();
});
</script>
<?php $this->load('Common.baseFooter');?>