var API = {
	get: function(url, param, callback) {
		var returnData = {};
		$.get(url, param, function(res) {
			if (callback) callback(res);
			else returnData = res;
		}, 'json');
		return returnData;
	},
	post: function(url, param, callback) {
		var returnData = {};
		$.post(url, param, function(res) {
			if (callback) callback(res);
			else returnData = res;
		}, 'json');
		return returnData;
	}
};
var VERIFY = {
	phone: function (phone) {
		var reg = /^1[3456789]\d{9}$/;
		return VERIFY.check(phone, reg);
	},
	email: function (email) {
		var reg = /^([\.a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+(\.[a-zA-Z0-9_-])+/;
		return VERIFY.check(email, reg);
	},
	password: function (password) {
		var reg = /^[0-9A-Za-z]{6,}/;
		return VERIFY.check(password, reg);
	},
	code: function(code) {
		var reg = /^\d{4,}/;
		return VERIFY.check(code, reg);
	},
	check: function(input, reg) {
		input = input.trim();
		if (input == '') { 
			return false;
		}
		return reg.test(input);
	}
};
var UPLOAD = {
	init: function (data){
		var _this = this;
		_this.data = data;
		_this.data.obj.css({'cursor': 'pointer', 'position': 'relative'});
		_this.data.obj.on('click', function(event){
			event.stopPropagation();
			var pid = $(this).parent().data('id');
			var fileId = pid+'_file_';
			if ($('body').find('#'+fileId).length == 0) {
				$('body').append('<input id="'+fileId+'" type="file" style="display: none;" />');
			}
			$('#'+fileId).click();
		});

		$('body').on('change', 'input[type="file"]', function() {
			var id = $(this).attr('id');
			idArr = id.split('_file_');
			console.log(idArr)
			UPLOAD.loadFile($(this), $('[data-id="'+idArr[0]+'"]').data('site'));
		});
	},
	loadFile: function(obj, site)
	{
		var returnData = {};
        var formData = new FormData();

        if (!obj.get(0).files[0]) return false;
        
        formData.append('file', obj.get(0).files[0]);  //上传一个files对象
        formData.append('site', site);

        var id = obj.attr('id');
		idArr = id.split('_file_');

        $.ajax({//jQuery方法，此处可以换成其它请求方式
            url: API_URI + 'uploads',
            type: 'post',
            data: formData, 
            processData: false, //jquery 是否对数据进行 预处理
            contentType: false, // 不要自己修改请求内容类型
            error: function (res) {
                if (UPLOAD.data.error) {
                	UPLOAD.data.error(res, $('#'+idArr[0]).find('.upload-item').eq(idArr[1]));
                }
            },
            success: function (res) {
            	if (res.code == 200) {
					if ($('#'+idArr[0]).find('.upload-item').eq(idArr[1]).find('img').length == 0) {
						$('#'+idArr[0]).find('.upload-item').eq(idArr[1]).append('<img src="" />');
					}

					$('#'+idArr[0]).find('.upload-item').eq(idArr[1]).find('img').attr('src', res.data.url);
					$('#'+idArr[0]).find('.upload-item').eq(idArr[1]).attr('data-attach_id', res.data.attach_id);

	                UPLOAD.initItem($('#'+idArr[0]));

	                if (UPLOAD.data.success)
	                	UPLOAD.data.success(res, $('#'+idArr[0]).find('.upload-item').eq(idArr[1]));
            	} else {
            		errorTips(res.message);
            	}
            }
        });
	},
	initItem: function(parentObj)
	{
		var len = parentObj.data('length');
		if (len > parentObj.find('.upload-item').length) {
			var check = true;
			parentObj.find('.upload-item').each(function(){
				if ($(this).find('img').length == 0) {
					check = false;
					return false;
				} else {
					if (!$(this).find('img').attr('src')) {
						check = false;
						return false;
					}
				}
			});

			if (!check) return false;
			if (parentObj.find('li.upload-item').length > 0) {
				var node = parentObj.find('.upload-item').eq(0).clone(true);
				node.data('attach_id', 0);
				node.attr('data-attach_id', 0);
			} else {
				var node = parentObj.find('.upload-item').parent().eq(0).clone(true);
				node.find('.upload-item').data('attach_id', 0);
				node.find('.upload-item').attr('data-attach_id', 0);
			}

			node.find('img').attr('src', '');
			node.find('input').val('');
			parentObj.append(node)
		}
	}
};