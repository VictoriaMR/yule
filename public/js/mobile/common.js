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
var tipsinterval = null;
var POP = {
	tips:function(text, time){
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
    }
};