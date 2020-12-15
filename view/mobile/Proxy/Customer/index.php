<?php $this->load('Common.baseHeader');?>
<div class="customer-search container-10" style="border-bottom: 1px solid #ccc; padding: 5px 10px">
	<form class="flex" method="get" action="<?php echo url('customer/index');?>">
		<input type="text" name="keyword" class="input" placeholder="输入关键字搜索" value="<?php echo $param['keyword'] ?? '';?>" style="height: 30px;">
		<?php if (empty($param['mem_id'])) { ?>
		<input type="text" name="recommender" class="input" placeholder="输入推荐人搜索" value="<?php echo $param['recommender'] ?? '';?>" style="height: 30px;">
		<?php } else { ?>
		<input type="hidden" name="mem_id" value="<?php echo $param['mem_id'];?>">
		<?php } ?>
		<button type="submit" class="btn-small btn-blue flex-1" style="height: 30px;">搜索</button>
	</form>
</div>
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