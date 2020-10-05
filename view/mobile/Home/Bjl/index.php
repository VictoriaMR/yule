<?php $this->load('Common.baseHeader');?>
<div class="main">
	<div id="loading-page" class="hide">
		<div class="bigground">
			<img class="bigground-img" src="<?php echo mediaUrl('image/common/loading.png');?>">
			<div class="loadingbox">
				<div class="loading-text"><span>资源加载中, 请稍后....</span></div>
				<div class="progressbox">
					<img src="<?php echo mediaUrl('image/common/progress_time.png');?>">
					<div class="progress">
						<img src="<?php echo mediaUrl('image/common/progress.png');?>">
					</div>
				</div>
				
			</div>
		</div>
	</div>
	<div id="main-page">
		<div class="bigground">
			<div class="bg-mask"></div>
			<div class="bigground-img">
				<img class="bigground-img" src="<?php echo mediaUrl('image/common/bg_bj.png');?>">
			</div>
			<div class="top-box">
				<div class="top">
					<div class="fontresize text-center" id="qishu-box">
						<span>第&nbsp;</span>
						<span id="qishu-no">202009260476</span>
						<span>&nbsp;期</span>
					</div>
				</div>
				<div class="number-box fontresize">
					<span class="number" id="number-1">0</span>
					<span class="number" id="number-2">0</span>
					<span class="number" id="number-3">0</span>
					<span class="number" id="number-4">0</span>
					<span class="number" id="number-5" class="margin-right-0">0</span>
				</div>
				<div class="gonggao">
					<div class="table-cell">
						<span id="gonggao-text">公告</span>
					</div>
				</div>
			</div>
			<div class="icon-right-box">
				<a class="item" href="javascript:;">
					<img src="<?php echo mediaUrl('image/common/zoushi.png');?>">
				</a>
				<a class="item" href="javascript:;">
					<img src="<?php echo mediaUrl('image/common/logger.png');?>">
				</a>
				<a class="item" href="javascript:;">
					<img src="<?php echo mediaUrl('image/common/kefu.png');?>">
				</a>
			</div>
			<div class="icon-left-box">
				<a class="item" href="javascript:;">
					<img src="<?php echo mediaUrl('image/common/musicon.png');?>">
				</a>
				<a class="item" href="javascript:;">
					<img src="<?php echo mediaUrl('image/common/jiaoyi.png');?>">
				</a>
				<a class="item" href="javascript:;">
					<img src="<?php echo mediaUrl('image/common/rule.png');?>">
				</a>
			</div>
			<div id="jiangqubox">
				<div class="three-item-box">
					<div class="item xian-item" data-type="1"></div>
					<div class="item he-item" data-type="2"></div>
					<div class="item zhuan-item" data-type="3"></div>
				</div>
				<div class="two-item-box">
					<div class="item" data-type="4"></div>
					<div class="item" data-type="5"></div>
				</div>
			</div>
			<div class="footer">
				<table width="100%" height="100%">
					<tr>
						<td width="25%">
							<div class="userinfo table-cell">
								<div class="item"><?php echo $info['nickname'] ?? '';?></div>
								<?php if (!empty($info['avatar'])) { ?>
								<div class="jinbi">
									<img src="<?php echo $info['avatar'];?>">
								</div>
								<?php } ?>
								<div id="user-balance" class="item margin-top-4 wallet"><?php echo $info['balance'] ?? '';?></div>
								<div class="jinbi">
									<img src="<?php echo mediaUrl('image/common/jinbi.png');?>">
								</div>
							</div>
						</td>
						<td width="75%">
							<div class="chip-box table">
								<div class="table-cell">
									<a class="chip-number" href="javascript:;" data-amount="1000">
										<img src="<?php echo mediaUrl('image/common/chip1000.png');?>">
									</a>
									<a class="chip-number" href="javascript:;" data-amount="500">
										<img src="<?php echo mediaUrl('image/common/chip500.png');?>">
									</a>
									<a class="chip-number" href="javascript:;" data-amount="100">
										<img src="<?php echo mediaUrl('image/common/chip100.png');?>">
									</a>
									<a class="chip-number" href="javascript:;" data-amount="50">
										<img src="<?php echo mediaUrl('image/common/chip50.png');?>">
									</a>
									<a class="chip-number" href="javascript:;" data-amount="10">
										<img src="<?php echo mediaUrl('image/common/chip10.png');?>">
									</a>
								</div>
								<div class="table-cell">
									<a href="javascript:;" class="cancel">
										<img src="<?php echo mediaUrl('image/common/cancel.png');?>">
									</a>
								</div>
							</div>
						</td>
					</tr>
				</table>
			</div>
		</div>
		
	</div>
</div>
<script type="text/javascript">
$(function(){
	BJL.init();
})
</script>
<?php $this->load('Common.baseFooter');?>