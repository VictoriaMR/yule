<?php $this->load('Common.baseHeader');?>
<div class="customer-title">
	<ul>
		<li>头像</li>
		<li>名称</li>
		<li>客户人数</li>
		<li>佣金</li>
		<li>可提现</li>
	</ul>
</div>
<div id="customer-content">
	<ul class="font-14" data-page="1">
	</ul>
</div>
<div id="proxy-modal" class="modal" style="display: none;">
	<div class="mask"></div>
	<div class="centerShow">
		<h3 class="font-22 font-600 margin-bottom-14">添加代理</h3>
		<form>
			<div class="input-group margin-bottom-16">
	            <div class="input-group-addon"><span>手机号</span>：</div>
	            <input class="form-control" name="mobile" maxlength="13" required="required" />
	        </div>
	        <div class="input-group margin-bottom-16">
	            <div class="input-group-addon"><span>名称</span>：</div>
	            <input class="form-control" name="nickname" maxlength="20" required="required" />
	        </div>
	        <div class="input-group margin-bottom-16">
	            <div class="input-group-addon"><span>密码</span>：</div>
	            <input class="form-control" name="password" maxlength="20" required="required" />
	        </div>
	        <button type="button" class="btn btn-blue" id="add-form-btn">确定</button>
		</form>
	</div>
</div>
<script type="text/javascript">
$(function(){
	PROXY.init();
});
</script>
<?php $this->load('Common.baseFooter');?>