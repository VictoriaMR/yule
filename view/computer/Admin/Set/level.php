<?php $this->load('Common.baseHeader');?>
<div class="container-fluid">
    <div class="sel-box">
        <form class="form-inline">
            <div class="form-group right">
                <button class="btn btn-success add-btn btn-sm" type="button"><i class="glyphicon glyphicon-plus"></i> 添加等级</button>
            </div>
        </form>
    </div>
    <div class="clear"></div>
    <div class="margin-top-10">
        <table class="table table-middle table-border-bottom font-14">
            <tr>
                <th class="col-md-1">ID</th>
                <th class="col-md-1">名称</th>
                <th class="col-md-1">值</th>
                <th class="col-md-2">操作</th>
            </tr>
            <?php if (!empty($list)) { ?>
            <?php foreach ($list as $key=>$value) { ?>
            <tr
            <?php foreach ($value as $k => $v) { ?>
                data-<?php echo $k;?>="<?php echo $v;?>"
            <?php } ?>
            >
                <td class="col-md-1"><?php echo $value['lev_id'];?></td>
                <td class="col-md-1"><?php echo $value['name'];?></td>
                <td class="col-md-1"><?php echo $value['value'];?></td>
                <td class="col-md-2">
                    <button class="btn btn-primary btn-sm modify-btn" type="button" ><i class="glyphicon glyphicon-edit"></i> 修改</button>
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
        <h3 class="margin-bottom-14 font-600 font-16">代理等级设置</h3>
        <input type="hidden" name="lev_id" value="0">
        <input type="hidden" name="opt" value="edit">
        <div class="input-group margin-bottom-14">
            <div class="input-group-addon"><span>名称：</span></div>
            <input type="text" class="form-control" name="name" required="required" autocomplete="off">
        </div>
        <div class="input-group margin-bottom-14">
            <div class="input-group-addon"><span>值：</span></div>
            <input type="text" class="form-control" name="value" required="required" autocomplete="off">
        </div>
        <button type="button" class="btn btn-primary btn-lg btn-block save-btn" data-loading-text="loading...">确认</button>
    </form>
</div>
<script type="text/javascript">
$(function(){
    LEVEL.init();
})
</script>
<?php $this->load('Common.baseFooter');?>