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
			<div id="time-count"></div>
			<div class="top-box">
				<div class="top">
					<div class="fontresize text-center" id="qishu-box">
						<span>第&nbsp;</span>
						<span id="qishu-no">202001010001</span>
						<span>&nbsp;期</span>
					</div>
				</div>
				<div class="number-box fontresize">
					<span class="number" id="ffc_num1">0</span>
					<span class="number" id="ffc_num2">0</span>
					<span class="number" id="ffc_num3">0</span>
					<span class="number" id="ffc_num4">0</span>
					<span class="number" id="ffc_num5" class="margin-right-0">0</span>
				</div>
				<div class="gonggao">
					<div class="table-cell">
						<span id="gonggao-text">公告</span>
					</div>
				</div>
			</div>
			<div class="icon-right-box">
				<a class="item" href="javascript:;" id="zoushitu-icon">
					<img src="<?php echo mediaUrl('image/common/zoushi.png');?>">
				</a>
				<a class="item" href="javascript:;" id="xiazhujilu-icon">
					<img src="<?php echo mediaUrl('image/common/logger.png');?>">
				</a>
				<a class="item" href="javascript:;" id="lianxikefu-icon">
					<img src="<?php echo mediaUrl('image/common/kefu.png');?>">
				</a>
			</div>
			<div class="icon-left-box">
				<a class="item" href="javascript:;" id="bjyinyue-icon" data-type="1">
					<img src="<?php echo mediaUrl('image/common/musicon.png');?>">
				</a>
				<a class="item" href="javascript:;" id="jiaoyi-icon">
					<img src="<?php echo mediaUrl('image/common/jiaoyi.png');?>">
				</a>
				<a class="item" href="javascript:;" id="ruletext-icon">
					<img src="<?php echo mediaUrl('image/common/rule.png');?>">
				</a>
			</div>
			<div id="jiangqubox" data-status="0">
				<div class="three-item-box">
					<div class="item xian-item" data-type="3"></div>
					<div class="item he-item" data-type="2"></div>
					<div class="item zhuan-item" data-type="1"></div>
				</div>
				<div class="two-item-box">
					<div class="item" data-type="5"></div>
					<div class="item" data-type="4"></div>
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
									<a href="javascript:;" class="cancel" id="cancel-btn" data-status="1">
										<img src="<?php echo mediaUrl('image/common/cancel.png');?>">
									</a>
								</div>
							</div>
						</td>
					</tr>
				</table>
			</div>
			<div id="zoushitu" class="bjl-modal">
				<div class="modal-top table">
					<span class="font-20 font-600 table-cell">走势图</span>
				</div>
				<div class="modal-middle">
					<ul data-type='zoushitu' data-page="1" data-end="false">
					</ul>
				</div>
				<div class="modal-footer">
					<a href="javascript:;" class="confirm-btn">
						<img src="<?php echo mediaUrl('image/common/confirm.png');?>">
					</a>
				</div>
			</div>
			<div id="jiaoyi" class="bjl-modal">
				<div class="modal-top table">
					<span class="font-20 font-600 table-cell">上下分记录</span>
				</div>
				<div class="modal-middle">
					<ul data-type='jiaoyi' data-page="1" data-end="false"></ul>
				</div>
				<div class="modal-footer">
					<a href="javascript:;" class="confirm-btn">
						<img src="<?php echo mediaUrl('image/common/confirm.png');?>">
					</a>
				</div>
			</div>
			<div id="xiazhujilu" class="bjl-modal">
				<div class="modal-top table">
					<span class="font-20 font-600 table-cell">下注记录</span>
				</div>
				<div class="modal-middle">
					<ul data-type='xiazhujilu' data-page="1" data-end="false"></ul>
				</div>
				<div class="modal-footer">
					<a href="javascript:;" class="confirm-btn">
						<img src="<?php echo mediaUrl('image/common/confirm.png');?>">
					</a>
				</div>
			</div>
			<div id="lianxikefu" class="bjl-modal">
				<div class="modal-top table">
					<span class="font-20 font-600 table-cell">上下分请加客服微信</span>
				</div>
				<div class="modal-middle">
					<div class="kefu-box table">
						<div class="table-cell">
							<img src="<?php echo mediaUrl('image/common/chat.png');?>">
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<a href="javascript:;" class="confirm-btn chushihua">
						<img src="<?php echo mediaUrl('image/common/confirm.png');?>">
					</a>
				</div>
			</div>
			<div id="ruletext" class="bjl-modal">
				<div class="modal-top table">
					<span class="font-20 font-600 table-cell">游戏规则</span>
				</div>
				<div class="modal-middle">
					<img src="<?php echo mediaUrl('image/common/ruletext.png');?>">
				</div>
				<div class="modal-footer">
					<a href="javascript:;" class="confirm-btn">
						<img src="<?php echo mediaUrl('image/common/confirm.png');?>">
					</a>
				</div>
			</div>
		</div>
	</div>
</div>
<audio id="choma">
    <source = src="<?php echo mediaUrl('media/choma.mp3');?>" type="audio/mp3">
</audio>
<audio id="he">
    <source = src="<?php echo mediaUrl('media/he.mp3');?>" type="audio/mp3">
</audio>
<audio id="xian">
    <source = src="<?php echo mediaUrl('media/xian.mp3');?>" type="audio/mp3">
</audio>
<audio id="zhuang">
    <source = src="<?php echo mediaUrl('media/zhuang.mp3');?>" type="audio/mp3">
</audio>
<audio id="start">
    <source = src="<?php echo mediaUrl('media/start.mp3');?>" type="audio/mp3">
</audio>
<audio id="stop">
    <source = src="<?php echo mediaUrl('media/stop.mp3');?>" type="audio/mp3">
</audio>
<audio id="warning">
    <source = src="<?php echo mediaUrl('media/warning.mp3');?>" type="audio/mp3">
</audio>
<script type="text/javascript">
$(function(){
	BJL.init();
})
</script>
<?php $this->load('Common.baseFooter');?>