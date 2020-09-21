var LEVEL = {
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
	    	API.post(URI+'set/level', $('#dealbox form').serializeArray(), function(res) {
	    		if (res.code == 200) {
	    			window.location.reload();
	    		} else {
	    			errorTips(res.message);
	    			_thisobj.button('reset');
	    		}
	    	});
		});
	},
	initShow: function(data)
	{
		if (!data) {
			data = {
				lev_id: 0,
				name: '',
				value: '',
			};
		}
		for (var i in data) {
			$('#dealbox form').find('[name="'+i+'"]').val(data[i]);
		}
		$('#dealbox').show();
	}
};