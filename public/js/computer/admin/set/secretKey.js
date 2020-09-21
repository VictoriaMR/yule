var SECRETKEY = {
	init: function()
	{
		var _this = this;
		$('#dealbox').offsetCenter();
		//新增
		$('.add-btn').on('click', function(event) {
	    	event.stopPropagation();
	    	_this.initShow();
	    });
		//编辑
		$('.modify-btn').on('click', function(event){
			event.stopPropagation();
			_this.initShow($(this).parents('tr').data());
		});
		//编辑框开关切换
	    $('#dealbox .switch_status').on('click', function(){
	    	var _thisobj = $(this);
	    	var status = _thisobj.hasClass('on') ? 0 : 1;
	    	switch_status(_thisobj, status);
	    	_thisobj.parents('.form-control').find('input').val(status);
	    });
		//保存
		$('.save-btn').on('click', function(){
			event.stopPropagation();
	    	var check = true;
	    	$(this).parents('form').find('[required=required]').each(function(){
	    		var val = $(this).val();
	    		if (val == '') {
	    			check = false;
	    			var name = $(this).prev().text();
	    			errorTips('请将'+name.slice(0, -1)+'填写完整');
	    			$(this).focus();
	    			return false;
	    		}
	    	});
	    	if (!check) return false;
	    	$(this).button('loading');
	    	var _thisobj = $(this);
	    	API.post(URI+'set/secretKey', $('#dealbox form').serializeArray(), function(res) {
	    		if (res.code == 200) {
	    			window.location.reload();
	    		} else {
	    			errorTips(res.message);
	    			_thisobj.button('reset');
	    		}
	    	});
		});
		//删除
	    $('.delete-btn').on('click', function(event){
	    	event.stopPropagation();
	    	var _thisobj = $(this);
	    	confirm('确定删除吗?', function(){
	    		var id = _thisobj.parents('tr').data('sec_id');
	    		API.post(URI+'set/secretKey', { id: id, opt: 'delete'}, function(res) {
	    			if (res.code == 200) {
	    				successTips(res.message);
	    				window.location.reload();
	    			} else {
	    				errorTips(res.message);
	    			}
	    		});
	    	});
	    });
	    //状态
	    $('table .switch_status').on('click', function(event) {
	    	var _thisobj = $(this);
	    	var sec_id = _thisobj.parents('tr').data('sec_id');
	    	var status = _thisobj.hasClass('on') ? 0 : 1;
	    	API.post(URI + 'set/secretKey', {sec_id: sec_id, status: status, opt: 'edit'}, function(res) {
    			if (res.code == 200) {
    				successTips(res.message);
    				switch_status(_thisobj, status);
    				_thisobj.parents('tr').data('status', status);
    			} else {
    				errorTips(res.message);
    			}
    		});
    		event.stopPropagation();
	    });
	},
	initShow: function(data)
	{
		if (!data) {
			data = {
				sec_id: 0,
				appid: '',
				secret: '',
				status: 1,
				remark: '',
			};
		}
		for (var i in data) {
			$('#dealbox form').find('[name="'+i+'"]').val(data[i]);
		}
		if (data.status == 0) {
			$('#dealbox [name="status"]').val(0).parents('.input-group').find('.switch_status').removeClass('on').addClass('off');
		} else {
			$('#dealbox [name="status"]').val(1).parents('.input-group').find('.switch_status').removeClass('off').addClass('on');
		}

		$('#dealbox').show();
	}
};