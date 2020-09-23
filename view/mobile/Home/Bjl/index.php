<?php $this->load('Common.baseHeader');?>
<div class="bigground hide" id="loading-page">
	<div class="loading-text"><span>资源加载中, 请稍后....</span></div>
	<div class="loadingbox">
		<img src="<?php echo mediaUrl('image/common/progress_time.png');?>">
		<div class="progress">
			<img src="<?php echo mediaUrl('image/common/progress.png');?>">
		</div>
	</div>
</div>
<div class="bigground" id="main-page">
	<div class="top"></div>
	<div class="number-box">
		<span id="number-1">0</span>
		<span id="number-2">0</span>
		<span id="number-3">0</span>
		<span id="number-4">0</span>
		<span id="number-5" style="margin-right: 0">0</span>
	</div>
	<div class="gongao"></div>
	<div class="footer">
		<div class="flex">
			<div class="flex1">
				<div class="id">
					
				</div>
			</div>
			<div class="flex">
				<div class="chip flex">
					<span class="icon36 icon-chip10"></span>
					<span class="icon36 icon-chip50"></span>
					<span class="icon36 icon-chip100"></span>
					<span class="icon36 icon-chip1000"></span>
					<span class="icon36 icon-chip5000"></span>
				</div>
				<span class="icon48 icon-cancel margin-right-10"></span>
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