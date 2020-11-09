<?php $this->load('Common.baseHeader');?>
<?php if (empty($error)) { ?>
<div class="wallet">
	<ul>
		<li <?php if ($type != 2 && $type != 1) { echo 'class="active"';}?>>
			<a href="<?php echo url('wallet', ['mem_id'=>$mem_id ?? 0]);?>" class="block">
				<div class="icon icon-wallet font-30"></div>
				<?php if (isset($info['subtotal'])) { ?>
				<div class="font-12">总额: <?php echo $info['subtotal'];?></div>
				<?php } else { ?>
				<div class="font-12">--</div>
				<?php } ?>
			</a>
		</li>
		<li <?php if ($type == 1) { echo 'class="active"';}?>>
			<a href="<?php echo url('wallet', ['type'=>1, 'mem_id'=>$mem_id ?? 0]);?>" class="block">
				<div class="icon icon-discounts font-30"></div>
				<?php if (isset($info['balance'])) { ?>
				<div class="font-12">余额: <?php echo $info['balance'];?></div>
				<?php } else { ?>
				<div class="font-12">--</div>
				<?php } ?>
			</a>
		</li>
		<li <?php if ($type == 2) { echo 'class="active"';}?>>
			<a href="<?php echo url('wallet', ['type'=>2, 'mem_id'=>$mem_id ?? 0]);?>" class="block">
				<div class="icon icon-rmb font-30"></div>
				<?php if (isset($info['subtotal']) && isset($info['balance'])) { ?>
				<div class="font-12">已提现: <?php echo sprintf('%.2f', $info['subtotal'] - $info['balance']);?></div>
				<?php } else { ?>
				<div class="font-12">--</div>
				<?php } ?>
			</a>
		</li>
	</ul>
	<div class="clear"></div>
</div>
<div id="wallet-content">
	<ul class="font-14" <?php if(!empty($type)) { echo "data-type='$type'";}?> data-page="1" <?php if(!empty($mem_id)) { echo "data-mem_id='$mem_id'";}?>>
	</ul>
</div>
<script type="text/javascript">
$(function(){
	WALLET.init();
});
</script>
<?php } else { ?>
<table width="100%" height="100%">
	<tr>
		<td class="font-16 color-o"><?php echo $error;?></td>
	</tr>
</table>
<?php } ?>
<?php $this->load('Common.baseFooter');?>