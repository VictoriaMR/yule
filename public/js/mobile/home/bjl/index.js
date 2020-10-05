var BJL = {
	init: function()
	{
		var _this = this;
		_this.socket;
		_this.x = 0;
		_this.y = 0;
		$('.progress').animate({'width': '100%'}, 800, function(){
			$('#loading-page').hide();
			$('#main-page').show();
		});
		$('.footer .chip-number').on('click', function(){
			$(this).addClass('select').siblings().removeClass('select');
		});
		$('#jiangqubox').on('click', '.item', function(e){
			if ($('.chip-number.select').length == 0) {
				POP.tips('先选择下注金额');
				return false;
			}
			var _thisobj = $(this);
			var type = _thisobj.data('type');
			var amount = $('.chip-number.select').data('amount');
			API.post(URI+'bjl/wager', {amount: amount, type: type}, function(res) {
            	POP.tips(res.message);
				if (typeof res.data.balance != 'undefined') {
					$('#user-balance').text(res.data.balance);
					_this.moveChip($('.chip-number.select'), _thisobj, $('.chip-number.select').offset().left, $('.chip-number.select').offset().top, _this.x, _this.y);
				}
            });
		});
		$('#jiangqubox').on('mousemove', '.item', function(e){
			if ($('.chip-number.select').length == 0) {
				return false;
			}
			_this.x = e.pageX;
			_this.y = e.pageY;
		});

		_this.start();
	},
	moveChip: function(from, to, ox, oy, x, y)
	{
		var tempObj = from.clone(true).appendTo(to).removeClass('select').css({'position': 'fixed', 'max-width': '0.26rem', 'max-height': '0.26rem', 'top': oy, 'left': ox}).animate({'top': y, 'left': x}, 100, function(){
		});

	},
	start: function()
	{
		var _this = this;
		var domain = DOMAIN.replace('http', 'ws').replace(/(^\/)|(\/$)/g,'');
		_this.socket = new WebSocket(domain+':8282');
		_this.socket.onopen = function(evt) {};
		_this.socket.onmessage = function(e) {
			var data = eval('(' +e.data + ')');
		    var type = data.type || '';
		    switch(type){
		        case 'init':
		            API.post(URI+'bjl/initGame', {client_id: data.client_id}, function(res){
		            	// POP.tips(res.message);
		            });
		            break;
		        case 'message':
		        	var html = _this.sendMessage(data);
		        	$('#content .content').append(html);
					_this.toBottom();
		        	break;
		    }
		};
		_this.socket.onclose = function(evt) {
		  	console.log('Connection closed.');
		};  
	}
};