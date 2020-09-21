var LOGIN = {
	init: function()
	{
		var _this = this;
		_this.$ele = $('#verify-wrap');
		_this.slideBtn = $('#verify-wrap .drag-btn');
		_this.slideProEle = $('#verify-wrap .dragProgress');
        _this.slideSucMsgEle = $('#verify-wrap .sucMsg');
        _this.slideFixTipsEle = $('#verify-wrap .fixTips');
		_this.maxSlideWid = _this.calSlideWidth();
		_this._touchstart();
        _this._touchmove();
        _this._touchend();
		_this.formInit();
	},
	formInit: function()
	{
		$('#login-btn').on('click', function() {
			var msg = '';
			$('#login-error').hide();
			var tempobj = $(this);
			tempobj.parent('form').find('input:visible').each(function(){
				var name = $(this).attr('name');
				if (!VERIFY[name]($(this).val())) {
					switch (name) {
						case 'phone':
							msg = '手机号码格式不正确';
							break;
						case 'password':
							msg = '密码格式不正确';
							break;
						default:
							msg = '输入错误';
							break;
					}
					return false;
				}
			});

			if (msg != '') {
				$('#login-error').show().find('#login-error-msg').text(msg);
				return false;
			}
			if (!$('#verify-wrap .drag-btn').hasClass('suc-drag-btn')) {
				$('#login-error').show().find('#login-error-msg').text('拖动模块验证');
				return false;
			}
			tempobj.button('loading');
			API.post(URI+'login/login', tempobj.parent('form').serializeArray(), function(res) {
				if (res.code == 200) {
					// window.location.href = URI;
				} else {
					$('#login-error-msg').text(res.message);
					$('#login-error').show();
					tempobj.button('reset');
				}
			});
		});
	},
	_touchstart:function(){
    	var _this = this;
    	_this.slideBtn.on('touchstart',function(e){
    		_this.touchX = e.originalEvent.targetTouches[0].pageX;
			if(_this.slideFinishState || _this.isAnimated()){
				_this.cancelTouchmove();
				return false;
			}
    	});
    },
    _touchmove:function(){
    	var _this = this;
    	_this.slideBtn.on('touchmove',function(e){
    		e.preventDefault();
    		var curX = e.originalEvent.targetTouches[0].pageX - _this.touchX;
    		if(curX >= _this.maxSlideWid){
				_this.setDragBtnSty(_this.maxSlideWid);
				_this.setDragProgressSty(_this.maxSlideWid);
				_this.cancelTouchmove();
				_this.successSty();
				_this.slideFinishState = true;
			}else if(curX <= 0){
				_this.setDragBtnSty('0');
				_this.setDragProgressSty('0');
			}else{
				_this.setDragBtnSty(curX);
				_this.setDragProgressSty(curX);
			}
    	})
    },
    _touchend:function(){
    	var _this = this;
    	_this.slideBtn.on('touchend',function(){
    		if(_this.slideFinishState){
				_this.cancelTouchmove();
			}else{
				_this.failAnimate();
			}
    	})
    },
    cancelTouchmove:function(){
    	this.slideBtn.off('touchmove');
    },
    isAnimated:function()
    {
    	return this.slideBtn.is(':animated');
    },
    getDragBtnWid:function()
    {
    	return parseInt(this.slideBtn.width());
    },
    getDragWrapWid:function()
    {
    	return parseFloat(this.$ele.outerWidth());
    },
    calSlideWidth:function()
    {
    	return this.getDragWrapWid() - this.getDragBtnWid();
    },
    setDragBtnSty:function(left)
    {
    	this.slideBtn.css({
			'left':left
		});
    },
    setDragProgressSty:function(wid)
    {
    	this.slideProEle.css({
			'width':wid
		});
    },
    cancelMouseMove:function()
    {
    	$(document).off('mousemove');
    },
    successSty:function()
    {
    	this.slideSucMsgEle.show();
		this.slideBtn.addClass('suc-drag-btn');
		this.slideFixTipsEle.hide();
    },
    failAnimate:function()
    {
    	this.slideBtn.animate({
			'left':'-0.01rem'
		},200);
		this.slideProEle.animate({
			'width':0
		},200);
    }
};