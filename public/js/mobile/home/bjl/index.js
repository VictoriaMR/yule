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
		$('.footer').on('click', '.chip-number', function(){
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
					// _this.moveChip($('.chip-number.select'), _thisobj, $('.chip-number.select').offset().left, $('.chip-number.select').offset().top, _this.x - 13, _this.y - 13);
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
		$('#zoushitu-icon').on('click', function(){
			$('#zoushitu').show();
			POP.loading($('#zoushitu .middle'));
			var html = _this.getzoushiList(1);
			$('#zoushitu .middle ul').html(html);
			$('#zoushitu .middle ul').data('page', 1);
			$('#zoushitu .middle').animate({scrollTop: 0}, 100);
			POP.loadout($('#zoushitu .middle'));
		});
		$('#zoushitu').on('click', '.confirm-btn', function(){
			$('#zoushitu').hide();
		});
		//监听滚动到底部
		$('.modal .middle').scroll(function(){
			if ($(this).find('ul .end-li').length > 0 || $(this).find('ul .loading-li').length > 0) {
				return false;
			}
			var _thisobj = $(this);
			var top = _thisobj.scrollTop();
			var h = _thisobj.height();
			var height = _thisobj.find('ul').height();
			if (top + h >= height) {
				var end = _thisobj.find('ul').data('end');
				if (end) {
					if (_thisobj.find('ul .end-li').length == 0) {
						_thisobj.find('ul').append('<li class="end-li"><span class="font-12">已经到底了</span></li>');
					}
					$('#zoushitu .middle').animate({scrollTop: height}, 100);
				} else {
					_thisobj.find('ul').append('<li class="loading-li"><img src="' + DOMAIN + 'image/common/loading_c.png" class="loading"></li>');
					var page = _thisobj.find('ul').data('page') + 1;
					var type = _thisobj.find('ul').data('type');
					$('#zoushitu .middle').animate({scrollTop: height}, 300, function(){
						switch (type) {
							case 'zoushitu':
								var html = _this.getzoushiList(page);
								$('#zoushitu .middle ul .loading-li').remove();
								$('#zoushitu .middle ul').append(html);
								$('#zoushitu .middle ul').data('page', page);
								break;
						}
					});
				}
			}
		});
		_this.start();
	},
	getzoushiList: function(page)
	{
		var res = API.get(URI+'bjl/getzoushiList', {page: page});
		var html = '';
		if (res.code == 200) {
			for (var i in res.data) {
				html += `<li>
					<div class="li-top flex">
						<div class="flex1 text-left">
							<span class="font-600 font-16">`+res.data[i].ffc_key+`&nbsp;期</span>
						</div>
						<div style="margin-left: auto;min-width: 150px;">
							<div class="number-box">
								<span class="number">`+res.data[i].ffc_num1+`</span>
								<span class="number">`+res.data[i].ffc_num2+`</span>
								<span class="number">`+res.data[i].ffc_num3+`</span>
								<span class="number">`+res.data[i].ffc_num4+`</span>
								<span class="number">`+res.data[i].ffc_num5+`</span>
							</div>
						</div>
					</div>
					<div class="li-footer flex margin-top-4">
						<div class="flex1">
							<div class="xian font-600 font-20 left">闲</div>
							<div class="left margin-left-4 relative image-box">`;
								for (var j in res.data[i].he) {
									html += `<img class="image`+j+`" src="`+res.data[i].he[j]+`" />`;
								}
					html += `</div>
						</div>
						<div style="margin-left: auto;">
							<div class="zhuang font-600 font-20 left">庄</div>
							<div class="left margin-left-4 relative image-box">`;
							for (var j in res.data[i].zhuang) {
								html += `<img class="image`+j+`" src="`+res.data[i].zhuang[j]+`" />`;
							}
					html += `</div>
						</div>
					</div>
				</li>`;
			}
		}
		return html;
	},
	moveChip: function(from, to, ox, oy, x, y)
	{
		from.clone(true).removeClass('select').appendTo(to).css({'position': 'fixed', 'max-width': '0.26rem', 'max-height': '0.26rem', 'top': oy, 'left': ox}).animate({'top': y, 'left': x}, 100);
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
		        case 'bjl':
		        	var ox = $('.icon-right-box .item').eq(2).offset().left;
		        	var oy = $('.icon-right-box .item').eq(2).offset().top;
		        	var tempx = $('#jiangqubox [data-type="'+data.entity_type+'"]').offset().left;
		        	var tempy = $('#jiangqubox [data-type="'+data.entity_type+'"]').offset().top;
		        	var w = $('#jiangqubox [data-type="'+data.entity_type+'"]').width() - 20;
		        	var h = $('#jiangqubox [data-type="'+data.entity_type+'"]').height() - 20;
		        	var x = Math.random()*w + tempx;
		        	var y = Math.random()*h + tempy;
		        	_this.moveChip($('.chip-number[data-amount="'+data.amount+'"]'), $('#jiangqubox [data-type="'+data.entity_type+'"]'), ox, oy, x, y);
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