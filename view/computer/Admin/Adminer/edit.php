<?php $this->load('Common.baseHeader');?>
<div class="container-fluid bgf">
	<div class="info-box">
		<h3 class="font-16">基本信息</h3>
		<div class="line-gray margin-top-14"></div>
		<form class="info-box">
			<table width="100%" border="0" class="font-14">
				<tbody>
					<?php if (!empty($info)) { ?>
					<tr>
						<td class="text-right" width="35%"><span class="color-red">*</span>头像:</td>
						<td width="65%">
							<a href="javascript:;" class="avatar table-cell">
								<img src="<?php echo $info['avatar'];?>">
							</a>
							<div class="table-cell" data-id="<?php echo $info['mem_id'];?>" data-site="avatar">
								<a href="javascript:;" class="color-red margin-left-10 upload-item">[更换头像]</a>
							</div>
						</td>
					</tr>
					<tr>
						<td class="text-right" width="35%">名称:</td>
						<td width="65%">
							<div class="input-box">
								<input type="text" name="name" class="input" value="<?php echo $info['name'];?>">
							</div>
						</td>
					</tr>
					<tr>
						<td class="text-right" width="35%">昵称:</td>
						<td width="65%">
							<div class="input-box">
								<input type="text" name="name" class="input" value="<?php echo $info['nickname'];?>">
							</div>
						</td>
					</tr>
					<tr>
						<td class="text-right" width="35%">手机号码:</td>
						<td width="65%">
							<div class="input-box">
								<input type="text" name="name" disabled="disabled" class="input" value="<?php echo $info['mobile'];?>">
							</div>
						</td>
					</tr>
					<tr>
						<td class="text-right" width="35%">状态:</td>
						<td width="65%">
							<div class="switch_botton status" data-status="<?php echo $info['status'];?>"><div class="switch_status <?php echo $info['status'] ? 'on' : 'off';?>"></div></div>
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<button type="button" class="btn btn-primary btn-lg btn-block save input-box center margin-top-14" data-loading-text="loading..">确认</button>
						</td>
					</tr>
					<?php } else { ?>
					<tr>
						<td colspan="2" class="text-center"><span class="color-red font-16">找不到用户</span></td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
		</form>
	</div>
	<?php if (!empty($rule)) { ?>
	<div class="info-box">
		<h3 class="font-16">权限列表</h3>
		<div class="line-gray margin-top-14"></div>
	</div>
	<?php } ?>
</div>
<script type="text/javascript">
$(function(){
	ADMINEREDIT.init();
})
</script>
<?php $this->load('Common.baseFooter');?>