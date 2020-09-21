<?php $this->load('Common.baseHeader');?>
<div class="container-fluid">
    <div class="sel-box">
        <form class="form-inline">
            <div class="form-group right">
                <button class="btn btn-success add-btn btn-sm" type="button"><i class="glyphicon glyphicon-plus"></i> 添加密钥</button>
            </div>
        </form>
    </div>
    <div class="clear"></div>
    <div class="margin-top-10">
        <table class="table table-middle table-border-bottom font-14">
            <tr>
                <th class="col-md-1">ID</th>
                <th class="col-md-1">APPID</th>
                <th class="col-md-1">SECRET KEY</th>
                <th class="col-md-1">状态</th>
                <th class="col-md-2">备注</th>
                <th class="col-md-2">操作</th>
            </tr>
            <?php if (!empty($list)) { ?>
            <?php foreach ($list as $key=>$value) { ?>
            <tr
            <?php foreach ($value as $k => $v) { ?>
                data-<?php echo $k;?>="<?php echo $v;?>"
            <?php } ?>
            >
                <td class="col-md-1"><?php echo $value['sec_id'];?></td>
                <td class="col-md-1"><?php echo $value['appid'];?></td>
                <td class="col-md-1"><?php echo $value['secret'];?></td>
                <td class="col-md-1">
                    <div class="switch_botton status" data-status="<?php echo $value['status'];?>">
                        <div class="switch_status <?php echo $value['status'] ? 'on' : 'off';?>"></div>
                    </div>
                </td>
                <td class="col-md-2"><?php echo $value['remark'];?></td>
                <td class="col-md-2">
                    <button class="btn btn-primary btn-sm modify-btn" type="button"><i class="glyphicon glyphicon-edit"></i>&nbsp;修改</button>
                    <button class="btn btn-danger btn-sm delete-btn" type="button"><span class="glyphicon glyphicon-trash"></span>&nbsp;删除</button>
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
    <p>共 <?php echo count($list);?> 条配置</p>
</div>
<div id="dealbox" style="display: none;">
    <form class="form-horizontal">
        <button type="button" class="close" aria-hidden="true">&times;</button>
        <h3 class="margin-bottom-14 font-600 font-16">密钥设置</h3>
        <input type="hidden" name="sec_id" value="0">
        <input type="hidden" name="opt" value="edit">
        <div class="input-group margin-bottom-14">
            <div class="input-group-addon"><span>APPID：</span></div>
            <input type="text" class="form-control" name="appid" required="required" autocomplete="off">
        </div>
        <div class="input-group margin-bottom-14">
            <div class="input-group-addon"><span>SECRET KEY：</span></div>
            <input type="text" class="form-control" name="secret" required="required" autocomplete="off">
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
            <div class="input-group-addon"><span>备注：</span></div>
            <textarea class="form-control" name="remark"></textarea>
        </div>
        <button type="button" class="btn btn-primary btn-lg btn-block save-btn" data-loading-text="loading...">确认</button>
    </form>
</div>
<script type="text/javascript">
$(function(){
    SECRETKEY.init();
});
</script>
<?php $this->load('Common.baseFooter');?>