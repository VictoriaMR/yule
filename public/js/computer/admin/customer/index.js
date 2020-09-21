var CUSTOMER = {
	init: function()
	{
		var _this = this;
		$('#dealbox').offsetCenter();
		//增加人员
		$('.add-btn').on('click', function(event) {
	    	event.stopPropagation();
	    	_this.initShow($(this).data());
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
	    	if (!VERIFY.phone($(this).parents('form').find('[name="mobile"]').val())) {
	    		$(this).parents('form').find('[name="mobile"]').focus();
	    		errorTips('手机号码不正确');
	    		return false;
	    	}
	    	if (!$(this).parents('form').find('[name="mem_id"]').val() && !$(this).parents('form').find('[name="password"]').val()) {
	    		errorTips('新建账号请设密码');
	    		return false;
	    	}
	    	$(this).button('loading');
	    	var _thisobj = $(this);
	    	API.post(URI+'customer', $('#dealbox form').serializeArray(), function(res) {
	    		if (res.code == 200) {
	    			// window.location.reload();
	    		} else {
	    			errorTips(res.message);
	    			_thisobj.button('reset');
	    		}
	    	});
		});
		//状态
	    $('table .switch_botton.status .switch_status').on('click', function(event) {
	    	var _thisobj = $(this);
	    	var mem_id = _thisobj.parents('tr').data('mem_id');
	    	var status = _thisobj.hasClass('on') ? 0 : 1;
	    	API.post(URI + 'customer', {mem_id: mem_id, status: status, opt: 'edit'}, function(res) {
    			if (res.code == 200) {
    				successTips(res.message);
    				switch_status(_thisobj, status);
    				_thisobj.parents('tr').data('status', status);
    				if (status == 0 && _thisobj.parents('tr').hasClass('parent')) {
    					switch_status(_thisobj.parents('tr').nextUntil('.parent').data('status', status).find('.switch_status'), status);
    				}
    			} else {
    				errorTips(res.message);
    			}
    		});
    		event.stopPropagation();
	    });
	    //删除
	    $('.delete-btn').on('click', function(event){
	    	event.stopPropagation();
	    	var _thisobj = $(this);
	    	confirm('确定删除吗?', function(){
	    		var mem_id = _thisobj.parents('tr').data('mem_id');
	    		API.post(URI+'customer', { mem_id: mem_id, opt: 'delete'}, function(res) {
	    			if (res.code == 200) {
	    				successTips(res.message);
	    				window.location.reload();
	    			} else {
	    				errorTips(res.message);
	    			}
	    		});
	    	});
	    });
	},
	initShow:function (data)
	{	
		if (!data) {
			data = {
				'mem_id': 0,
				'name': '',
				'nickname': '',
				'mobile': '',
				'status': 1,
				'level': '',
				'remark': '',
				'recommender': '',
				'commission': '',
			};
		}
		data.password = '';
		for (var i in data) {
			$('#dealbox form').find('[name="'+i+'"]').val(data[i]);
		}
		$('#dealbox .selectpicker').selectpicker('refresh');
		if (data.status == 0) {
			$('#dealbox [name="status"]').val(0).parents('.input-group').find('.switch_status').removeClass('on').addClass('off');
		} else {
			$('#dealbox [name="status"]').val(1).parents('.input-group').find('.switch_status').removeClass('off').addClass('on');
		}
		$('#dealbox').show();
	},
};