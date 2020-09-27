var BJL = {
	init: function()
	{
		var _this = this;
		_this.socket;
		$('.progress').animate({'width': '100%'}, 800, function(){
			$('#loading-page').hide();
			$('#main-page').show();
		});
		$('.footer .chip-number').on('click', function(){
			$(this).addClass('select').siblings().removeClass('select');
		});
		_this.start();
	},
	start: function()
	{
		var _this = this;
		var domain = DOMAIN.replace('http', 'ws').replace(/(^\/)|(\/$)/g,'');
		console.log(domain)
		_this.socket = new WebSocket(domain+':8282');
		_this.socket.onopen = function(evt) {};
		_this.socket.onmessage = function(e) {
		    // json数据转换成js对象
			var data = eval('(' +e.data + ')');
		    var type = data.type || '';
		    console.log(type)
		    switch(type){
		        case 'init':
		            API.post(URI+'bjl/initGame', {client_id: data.client_id}, function(res){
		            	errorTips(res.message)
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
		  	console.log("Connection closed.");
		};  
	}
};