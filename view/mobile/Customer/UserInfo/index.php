<?php $this->load('Common.baseHeader');?>
<div class="userinfo-top">
	<div class="flex">
		<div class="avatar">
			<img src="<?php echo $info['avatar'];?>">
		</div>
		<div class="userinfo font-16 text-left">
			<div class="item"><?php echo $info['name'];?></div>
			<div class="item"><?php echo $info['mobile'];?></div>
		</div>
		<a href="<?php echo url('login/logout');?>" class="icon icon-zhuxiao font-30 color-w"></a>
	</div>
</div>
<div class="line10"></div>
<div class="wallet">
	<ul>
		<li>
			<a href="<?php echo url('wallet');?>" class="block">
				<div class="icon icon-qianbao font-30"></div>
				<div class="font-12">总额: <?php echo $info['subtotal'];?></div>
			</a>
		</li>
		<li>
			<a href="<?php echo url('wallet', ['type'=>'1']);?>" class="block">
				<div class="icon icon-pingzhengzhongxin font-30"></div>
				<div class="font-12">余额: <?php echo $info['balance'];?></div>
			</a>
		</li>
		<li>
			<a href="<?php echo url('wallet', ['type'=>'2']);?>" class="block">
				<div class="icon icon-yingyeshouru font-30"></div>
				<div class="font-12">提现: <?php echo sprintf('%.2f', $info['subtotal'] - $info['balance']);?></div>
			</a>
		</li>
	</ul>
	<div class="clear"></div>
</div>
<div class="line10"></div>
<div class="setting">
	<ul>
		<li></li>
	</ul>
</div>
<?php $this->load('Common.baseFooter');?>