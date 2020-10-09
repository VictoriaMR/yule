var API = {
	get: function(url, param, callback) {
		var returnData = {};
		$.ajaxSetup({async: false});
		$.get(url, param, function(res) {
			if (callback) callback(res);
			else returnData = res;
		}, 'json');
		return returnData;
	},
	post: function(url, param, callback) {
		var returnData = {};
		$.ajaxSetup({async: false});
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
var tipsinterval = null;
var POP = {
	tips:function(text, time)
	{
        if(typeof time == 'undefined') {
            time = 2000;
        }
        clearInterval(tipsinterval);
        if($('.pop-tips').length==0){
            $('body').append('<div class="pop-tips"></div>');
        }
        $('.pop-tips').html(text);
        $('.pop-tips').show();
        var tips_width= $('.pop-tips').width();
        var win_width = $(window).width();
        var tips_height = $('.pop-tips').height();
        var win_height = $(window).height();
        $('.pop-tips').css('left', (win_width-tips_width)/2-20);
        $('.pop-tips').css('top', (win_height-tips_height)/2-10);
        tipsinterval = setInterval(function(){
            $('.pop-tips').hide();
        }, time);
    },
    loading: function(obj)
    {
        var html = '<div class="load-ing">\
			<div class="load-mask"></div>\
			<img src="' + DOMAIN + 'image/common/loading_c.png" class="loading">\
		</div>';
        obj.find('.load-ing').remove();
        obj.append(html);
    },
    loadout: function(obj)
    {
        obj.find('.load-ing').remove();
    }
};
function confirm(text, callback)
{
    if ($('#confirm-modal').length == 0) {
        $('body').append(
            '<div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" id="confirm-modal">'+
              '<div class="modal-dialog modal-sm" role="document">'+
                '<div class="modal-content">'+
                  '<div class="modal-header">'+
                    '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+
                    '<h4 class="modal-title">提示</h4>'+
                  '</div>'+
                  '<div class="modal-body">'+
                    '<p>One fine body&hellip;</p>'+
                  '</div>'+
                  '<div class="modal-footer">'+
                    '<button type="button" class="btn btn-default btn-sm" data-dismiss="modal">取消</button>'+
                    '<button type="button" class="btn btn-primary btn-sm confirm-btn">确定</button>'+
                  '</div>'+
                '</div>'+
              '</div>'+
            '</div>'
        );
    }
    $('#confirm-modal .modal-body p').text(text);
    $('#confirm-modal .modal-body .confirm-btn').unbind('click');
    if(callback) {
        $('#confirm-modal .confirm-btn').bind('click', callback);
    }
    $('#confirm-modal').show();
}