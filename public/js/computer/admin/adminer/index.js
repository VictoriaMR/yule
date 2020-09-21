var ADMINER = {
	init: function()
	{
		var _this = this;
		$('#dealbox').offsetCenter();
		//新增
		$('.add-btn').on('click', function(){
			_this.initShow();
		});
		//状态
	    $('table .switch_botton.status .switch_status').on('click', function(event) {
    		event.stopPropagation();
	    	var _thisobj = $(this);
	    	var id = _thisobj.parents('tr').data('con_id');
	    	var status = _thisobj.hasClass('on') ? 0 : 1;
	    	API.post(URI + 'adminer/index', {id: id, status: status, opt: 'edit'}, function(res) {
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
	    });
	},
	initShow: function(data)
	{
		if (!data) {
			data = {
				'mem_id': 0,
				'name': '',
				'nickname': '',
				'mobile': '',
				'status': 1,
			};
		}
		for (var i in data) {
			$('#dealbox [name="'+i+'"]').val(data[i]);
		}
		if (data.status == 0) {
			$('#dealbox [name="status"]').val(0).parents('.input-group').find('.switch_status').removeClass('on').addClass('off');
		} else {
			$('#dealbox [name="status"]').val(1).parents('.input-group').find('.switch_status').removeClass('off').addClass('on');
		}
		if (data.mem_id > 0) {
			$('#dealbox [name="mobile"]').attr('readonly', true);
		} else {
			$('#dealbox [name="mobile"]').attr('readonly', false);
		}
		$('#dealbox').show();
	},
	save: function ()
	{
    	API.post(URI + 'set/index', $('#dealbox form').serializeArray(), function(res){
    		if (res.code == 200) {
    			successTips(res.message);
    			window.location.reload();
    		} else {
    			errorTips(res.message);
    		}
    	});
	}
};