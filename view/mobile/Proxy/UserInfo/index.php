<?php $this->load('Common.baseHeader');?>
<div class="userinfo-top">
	<div class="flex">
		<div class="avatar">
			<img src="<?php echo $info['avatar'] ?? mediaUrl('image/computer/male.jpg');?>">
		</div>
		<div class="userinfo font-16 text-left">
			<div class="item"><?php echo $info['name'] ?? '游客';?></div>
			<div class="item"><?php echo $info['mobile'] ?? '138****000';?></div>
		</div>
		<a href="<?php echo url('login/logout');?>" class="logout-btn icon icon-switch font-30 color-w"></a>
	</div>
</div>
<div class="line10"></div>
<div class="wallet">
	<ul>
		<li>
			<a href="<?php echo url('wallet');?>" class="block">
				<div class="icon icon-wallet font-30"></div>
				<?php if (isset($info['subtotal'])) { ?>
				<div class="font-12">总额: <?php echo $info['subtotal'];?></div>
				<?php } else { ?>
				<div class="font-12">--</div>
				<?php } ?>
			</a>
		</li>
		<li>
			<a href="<?php echo url('wallet', ['type'=>1]);?>" class="block">
				<div class="icon icon-discounts font-30"></div>
				<?php if (isset($info['balance'])) { ?>
				<div class="font-12">余额: <?php echo $info['balance'];?></div>
				<?php } else { ?>
				<div class="font-12">--</div>
				<?php } ?>
			</a>
		</li>
		<li>
			<a href="<?php echo url('wallet', ['type'=>2]);?>" class="block">
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
<div class="line10"></div>
<div class="setting">
	<ul>
		<li class="flex">
			<a href="<?php echo url('customer');?>" class="block font-16 w100 text-left">
				<span class="icon icon-customermanagement font-20"></span>
				<span>我的客户</span>
				<span class="icon icon-arrow-right font-20 right"></span>
			</a>
		</li>
		<li class="flex">
			<a href="<?php echo url('proxy');?>" class="block font-16 w100 text-left">
				<span class="icon icon-connections font-20"></span>
				<span>我的代理</span>
				<span class="icon icon-arrow-right font-20 right"></span>
			</a>
		</li>
		<li class="flex">
			<a href="javascript:;" class="block font-16 w100 text-left">
				<span class="icon icon-rmb font-20"></span>
				<span>提现</span>
				<span class="icon icon-arrow-right font-20 right"></span>
			</a>
		</li>
		<li class="flex">
			<a href="<?php echo url('userInfo/setting');?>" class="block font-16 w100 text-left">
				<span class="icon icon-set font-20"></span>
				<span>设置</span>
				<span class="icon icon-arrow-right font-20 right"></span>
			</a>
		</li>
	</ul>
</div>
<div class="container-10 margin-top-16">
	<a href="<?php echo url('login/logout');?>" class="btn btn-blue">退出登录</a>
</div>
<?php $this->load('Common.baseFooter');?>