<?php $this->load('Common.baseHeader');?>
<div class="container-fluid">
    <div class="sel-box">
        <form action="<?php echo url('customer');?>" method="get" class="form-inline">
            <div class="col-md-12 padding-left-0">
                <div class="form-group operator_input">
                    <input type="text" class="form-control " name="keyword" value="<?php echo $keyword;?>" placeholder="名称/昵称/手机" style="width:138px;" autocomplete="off">
                </div>
                <div class="form-group margin-right-6">
                    <select class="form-control" name="status" style="width:138px;">
                        <option value="">请选择状态</option>
                        <option value="0" <?php if($status!=='' && $status==0){echo 'selected';}?>>停用</option>
                        <option value="1" <?php if($status==1){echo 'selected';}?>>启用</option>
                    </select>
                </div>
                <div class="form-group margin-right-6">
                    <button class="btn btn-primary btn-sm" type="submit"><i class="glyphicon glyphicon-search"></i> 查询</button>
                </div>
                <div class="form-group right">
                    <button class="btn btn-success add-btn btn-sm" type="button"><i class="glyphicon glyphicon-plus"></i> 添加人员</button>
                </div>
            </div>
        </form>
    </div>
    <div class="clear"></div>
    <div class="margin-top-10">
        <table class="table table-middle table-border-bottom font-14">
            <tr>
                <th class="col-md-1">ID</th>
                <th class="col-md-2">名称/昵称</th>
                <th class="col-md-1">手机号码</th>
                <th class="col-md-1">状态</th>
                <th class="col-md-2">上级代理</th>
                <th class="col-md-1">等级/费率</th>
                <th class="col-md-1">加入时间</th>
                <th class="col-md-1">备注</th>
                <th class="col-md-2">操作</th>
            </tr>
            <?php if (!empty($list)) { ?>
            <?php foreach ($list as $key=>$value) { ?>
            <tr data-mem_id="<?php echo $value['mem_id'];?>"
                data-name="<?php echo $value['name'];?>"
                data-nickname="<?php echo $value['nickname'];?>"
                data-mobile="<?php echo $value['mobile'];?>"
                data-status="<?php echo $value['status'];?>"
                data-level="<?php echo $value['level'];?>"
                data-remark="<?php echo $value['remark'];?>"
                data-recommender="<?php echo $value['recommender'];?>"
                data-commission="<?php echo $value['commission'];?>"
            >
                <td class="col-md-1"><?php echo $value['mem_id'];?></td>
                <td class="col-md-2 userAvatarInfo">
                    <span class="avatar"><img src="<?php echo $value['avatar'];?>"></span>
                    <span class="block margin-left-4"><?php echo $value['name'];?><br /><?php echo $value['nickname'];?></span>
                </td>
                <td class="col-md-1"><?php echo $value['mobile'];?></td>
                <td class="col-md-1">
                    <?php if($value['status']){?>
                        <div class="switch_botton status" data-status="1"><div class="switch_status on"></div></div>
                    <?php }else{ ?>
                        <div class="switch_botton status" data-status="0"><div class="switch_status off"></div></div>
                    <?php } ?>
                </td>
                <td class="col-md-2 userAvatarInfo">
                    <?php if (!empty($value['recommender_info'])) {?>
                    <span class="avatar"><img src="<?php echo $value['recommender_info']['avatar'];?>"></span>
                    <span class="block margin-left-4"><?php echo $value['recommender_info']['name'];?><br /><?php echo $value['recommender_info']['nickname'];?></span>
                    <?php } ?>
                </td>
                <td class="col-md-1">
                    <span>等级: <?php echo $value['level'];?></span>
                    <br />
                    <span>费率: <?php echo $value['commission'];?></span>
                </td>
                <td class="col-md-1"><?php echo $value['create_at'];?></td>
                <td class="col-md-1"><?php echo $value['remark'];?></td>
                <td class="col-md-2">
                    <button class="btn btn-primary btn-sm modify-btn" type="button" ><i class="glyphicon glyphicon-edit"></i> 修改</button>
                    <button class="btn btn-danger btn-sm delete-btn" type="button" ><i class="glyphicon glyphicon-trash"></i>  删除</button>
                </td>
            </tr>
        	<?php } ?>
            <?php } else { ?>
            <tr>
            	<td colspan="8"><div class="text-center orange">暂无数据</div></td>
            </tr>
        	<?php } ?>
        </table>
    </div>
    <?php echo $paginator;?>
</div>
<div id="dealbox" style="display: none;">
    <form class="form-horizontal">
        <button type="button" class="close" aria-hidden="true">&times;</button>
        <h3 class="margin-bottom-14 font-600 font-16">代理管理</h3>
        <input type="hidden" name="mem_id" value="0">
        <input type="hidden" name="opt" value="edit">
        <div class="input-group margin-bottom-14">
            <div class="input-group-addon"><span>名称：</span></div>
            <input type="text" class="form-control" name="name" autocomplete="off" required="required" maxlength="32">
        </div>
        <div class="input-group margin-bottom-14">
            <div class="input-group-addon"><span>昵称：</span></div>
            <input type="text" class="form-control" name="nickname" autocomplete="off" required="required" maxlength="32">
        </div>
        <div class="input-group margin-bottom-14">
            <div class="input-group-addon"><span>手机：</span></div>
            <input type="text" class="form-control" name="mobile" autocomplete="off" required="required" placeholder="手机号码用作登录账号" maxlength="11">
        </div>
        <div class="input-group margin-bottom-14">
            <div class="input-group-addon"><span>密码：</span></div>
            <input type="text" class="form-control" name="password" autocomplete="off" placeholder="填则修改密码" maxlength="32">
        </div>
        <div class="input-group margin-bottom-14">
            <div class="input-group-addon"><span>状态：</span></div>
            <div class="form-control">
                <div class="switch_botton status" data-status="1">
                    <div class="switch_status on"></div>
                </div>
                <input type="hidden" name="status" value="1">
            </div>
        </div>
        <div class="input-group margin-bottom-14">
            <div class="input-group-addon"><span>上级：</span></div>
            <select 
                class="form-control selectpicker" 
                title="上级选择" 
                name="recommender"
            >
                <option value="0">上级选择</option>
                <?php if (!empty($customerList)) { ?>
                <?php foreach ($customerList as $key => $value) { ?>
                <option data-content="<div class='flex'><span class='img'><img src='<?php echo $value['avatar'];?>'/></span><div class='flex-1' style='line-height:20px;'><?php echo $value['name'];?>&nbsp;&nbsp;&nbsp;<?php echo $value['mobile'];?></div></div>" value="<?php echo $value['mem_id'];?>"><?php echo $value['name'];?></option>
                <?php } ?>
                <?php } ?>
            </select>
        </div>
        <div class="input-group margin-bottom-14">
            <div class="input-group-addon"><span>等级：</span></div>
            <select 
                class="form-control selectpicker" 
                title="等级选择" 
                name="level"
            >
                <option value="0">等级选择</option>
                <?php if (!empty($levelList)) { ?>
                <?php foreach ($levelList as $key => $value) { ?>
                <option data-content="<div class='flex'><div class='flex-1'><?php echo $value['name'];?>&nbsp;&nbsp;&nbsp;费率: <?php echo $value['value'];?></div></div>" value="<?php echo $value['lev_id'];?>"><?php echo $value['name'];?></option>
                <?php } ?>
                <?php } ?>
            </select>
        </div>
        <div class="input-group margin-bottom-14">
            <div class="input-group-addon"><span>费率：</span></div>
            <input type="text" class="form-control" name="commission" autocomplete="off" placeholder="自定义费率" maxlength="5">
        </div>
        <div class="input-group margin-bottom-14">
            <div class="input-group-addon"><span>备注：</span></div>
            <textarea class="form-control" name="remark" maxlength="100"></textarea>
        </div>
        <button type="button" class="btn btn-primary btn-lg btn-block save-btn" data-loading-text="loading..">确认</button>
    </form>
</div>
<script type="text/javascript">
$(function(){
    CUSTOMER.init();
});
</script>
<?php $this->load('Common.baseFooter');?>