<?php $this->load('Common.baseHeader');?>
<div class="userinfo-top">
	<div class="flex">
		<div class="avatar">
			<img src="<?php echo $info['avatar'] ?? mediaUrl('image/computer/male.jpg');?>">
		</div>
		<div class="userinfo font-16 text-left">
			<div class="item"><?php echo $info['name'] ?? '管理员';?></div>
			<div class="item"><?php echo $info['mobile'] ?? '000****000';?></div>
		</div>
		<a href="<?php echo url('login/logout');?>" class="logout-btn icon icon-switch font-30 color-w"></a>
	</div>
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
			<a href="<?php echo url('wallet/tixian');?>" class="block font-16 w100 text-left">
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