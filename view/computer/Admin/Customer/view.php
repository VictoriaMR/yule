<?php $this->load('Common.baseHeader');?>
<div class="container-fluid">
	<div class="info-box">
		<h3 class="font-16">基本信息</h3>
		<div class="line-gray margin-top-14"></div>
		<form class="info-box margin-top-14">
			<table width="100%" border="0" class="font-14">
				<tbody>
					<?php if (!empty($info)) { ?>
					<tr>
						<td width="20%" rowspan="2">
							<a href="javascript:;" class="avatar text-center block">
								<img src="<?php echo $info['avatar'];?>">
							</a>
							<div class="text-center margin-top-8" data-id="<?php echo $info['mem_id'];?>" data-site="avatar">
								<a href="javascript:;" class="color-red margin-left-10 upload-item">[更换头像]</a>
							</div>
						</td>
						<td width="80%">
							<div class="flex">
								<label>1</label>
								<label>2</label>
							</div>
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
</div>
<?php $this->load('Common.baseFooter');?>