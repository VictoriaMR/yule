var BJL = {
	init: function()
	{
		var _this = this;
		_this.socket;
		_this.x = 0;
		_this.y = 0;
		_this.interval = null;
		$('.progress').animate({'width': '100%'}, 800, function(){
			$('#loading-page').hide();
			$('#main-page').show();
		});
		$('.footer').on('click', '.chip-number', function(){
			$(this).addClass('select').siblings().removeClass('select');
		});
		//取消
		$('#cancel-btn').on('click', function(){
			if ($('#cancel-btn').data('status')) {
				confirm('每期只能取消一次, 确定取消吗', function(){
					API.post(URI+'bjl/cancelWager', function(res) {
						POP.tips(res.message);
						if (res.code == 200) {
							$('#cancel-btn').data('status', 0);
							$('#user-balance').text(res.data.balance);
						}
					});
				});
			}
		});
		//下注
		$('#jiangqubox').on('click', '.item', function(e){
			if (!$('#jiangqubox').data('status')) {
				POP.tips('停止下注');
				return false;
			}
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
					_this.moveChip($('.chip-number.select'), _thisobj, $('.chip-number.select').offset().left, $('.chip-number.select').offset().top, _this.x - 13, _this.y - 13);
					_this.music('choma');
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
		//走势图
		$('#zoushitu-icon').on('click', function(){
			$('.modal').hide();
			$('#zoushitu .modal-middle ul').html('');
			$('#zoushitu').show();
			POP.loading($('#zoushitu .modal-middle'));
			var html = _this.getzoushiList(1);
			$('#zoushitu .modal-middle ul').html(html);
			$('#zoushitu .modal-middle ul').data('page', 1);
			$('#zoushitu .modal-middle').animate({scrollTop: 0}, 100);
			POP.loadout($('#zoushitu .modal-middle'));
		});
		$('.modal').on('click', '.confirm-btn', function(){
			$(this).parents('.modal').hide();
		});
		//下注记录
		$('#xiazhujilu-icon').on('click', function(){
			$('.modal').hide();
			$('#xiazhujilu .modal-middle ul').html('');
			$('#xiazhujilu').show();
			POP.loading($('#zoushitu .modal-middle'));
			var html = _this.getxiazhuList(1);
			$('#xiazhujilu .modal-middle ul').html(html);
			$('#xiazhujilu .modal-middle ul').data('page', 1);
			$('#xiazhujilu .modal-middle').animate({scrollTop: 0}, 100);
			POP.loadout($('#xiazhujilu .modal-middle'));
		});
		//联系客服
		$('#lianxikefu-icon').on('click', function(){
			$('.modal').hide();
			$('#lianxikefu').show();
		});
		//背景音乐
		$('#bjyinyue-icon').on('click', function(){
			var type = $(this).data('type');
			if (type) {
				$(this).find('img').attr('src', DOMAIN+'image/common/musicoff.png');
				$(this).data('type', 0);
				$('.bg-music').remove();
			} else {
				$(this).find('img').attr('src', DOMAIN+'image/common/musicon.png');
				$(this).data('type', 1);
			}
		});
		//规则
		$('#ruletext-icon').on('click', function(){
			$('.modal').hide();
			$('#ruletext').show();
		});
		//交易记录
		$('#jiaoyi-icon').on('click', function(){
			$('.modal').hide();
			$('#jiaoyi .modal-middle ul').html('');
			$('#jiaoyi').show();
			POP.loading($('#jiaoyi .modal-middle'));
			var html = _this.getjiaoyiList(1);
			$('#jiaoyi .modal-middle ul').html(html);
			$('#jiaoyi .modal-middle ul').data('page', 1);
			$('#jiaoyi .modal-middle').animate({scrollTop: 0}, 100);
			POP.loadout($('#jiaoyi .modal-middle'));
		});
		//监听滚动到底部
		$('.modal .modal-middle').scroll(function(){
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
					$('#zoushitu .modal-middle').animate({scrollTop: height}, 100);
				} else {
					_thisobj.find('ul').append('<li class="loading-li"><img src="' + DOMAIN + 'image/common/loading_c.png" class="loading" style="max-height:0.2rem;max-width:0.2rem;"></li>');
					var page = _thisobj.find('ul').data('page') + 1;
					var type = _thisobj.find('ul').data('type');
					$('#zoushitu .modal-middle').animate({scrollTop: height}, 300, function(){
						switch (type) {
							case 'zoushitu':
								var html = _this.getzoushiList(page);
								if (html == '') {
									$('#zoushitu .modal-middle ul').data('end', 'true');
								}
								$('#zoushitu .modal-middle ul .loading-li').remove();
								$('#zoushitu .modal-middle ul').append(html);
								$('#zoushitu .modal-middle ul').data('page', page);
								break;
							case 'xiazhujilu':
								var html = _this.getxiazhuList(page);
								if (html == '') {
									$('#xiazhujilu .modal-middle ul').data('end', 'true');
								}
								$('#xiazhujilu .modal-middle ul .loading-li').remove();
								$('#xiazhujilu .modal-middle ul').append(html);
								$('#xiazhujilu .modal-middle ul').data('page', page);
								break;
							case 'jiaoyi':
								var html = _this.getjiaoyiList(page);
								if (html == '') {
									$('#jiaoyi .modal-middle ul').data('end', 'true');
								}
								$('#jiaoyi .modal-middle ul .loading-li').remove();
								$('#jiaoyi .modal-middle ul').append(html);
								$('#jiaoyi .modal-middle ul').data('page', page);
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
							<span class="font-600 font-14">`+res.data[i].ffc_key+`&nbsp;期</span>
						</div>
						<div style="margin-left: auto;">
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
								for (var j in res.data[i].xian) {
									html += `<img class="image`+j+`" src="`+res.data[i].xian[j]+`" />`;
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
	getjiaoyiList: function(page)
	{
		var res = API.get(URI+'bjl/getjiaoyiList', {page: page});
		var html = '';
		if (res.code == 200) {
			for (var i in res.data) {
				html += `<li>
							<div class="flex font-600 font-14">
								<div style="margin-right: auto;">`+res.data[i].entity_text+`</div>
								<div style="margin-left: auto;">`+res.data[i].subtotal+`</div>
							</div>
							<div class="flex font-12 color-g margin-top-4">
								<div style="margin-right: auto;">`+res.data[i].type_text+`</div>
								<div style="margin-left: auto;">`+res.data[i].create_at+`</div>
							</div>
						</li>`;
			}
		}
		return html;
	},
	getxiazhuList: function(page)
	{
		var res = API.get(URI+'bjl/getxiazhuList', {page: page});
		var html = '';
		if (res.code == 200) {
			for (var i in res.data) {
				html += `<li>
							<div class="flex font-600 font-14">
								<div style="margin-right: auto;">`+res.data[i].entity_text+`</div>
								<div style="margin-left: auto;">`+res.data[i].amount+`</div>
							</div>
							<div class="flex font-12 color-g margin-top-4">
								<div style="margin-right: auto;">`+res.data[i].status_text+`</div>
								<div style="margin-left: auto;">`+res.data[i].create_at+`</div>
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
		    console.log(data)
		    switch(type){
		        case 'init':
		            API.post(URI+'bjl/initGame', {client_id: data.client_id}, function(res){
		            	if (res.code == 200) {
		            		_this.startBling(res.data.time, res.data.qishu, res.data.value);
		            	} else {
		            		POP.tips(res.message);
		            	}
		            });
		            break;
		        case 'wallet':
		        	$('#user-balance').text(data.balance);
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
		        	_this.music('choma');
		        	break;
		        case 'prize':
		        	//开始
		        	if (data.status) {
		        		//输出结果
		        		_this.music(data.result);
		        		$('#cancel-btn').data('status', 1);
		        		setTimeout(function(){
		        			_this.startBling(data.time-2, data.qishu, data.value);
		        		}, 2000);
		        	} else {
		        		_this.stopBling();
		        	}
		        	break;
		        case 'wait':
		        	$('#time-count').text('等待开奖');
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
	},
	startBling: function(time, qishu, value)
	{
		var _this = this;
		if (time <= 0) {
			_this.stopBling();
		} else {
			_this.music('start');
			$('#jiangqubox').data('status', 1);
			$('#jiangqubox .item').html('');
			$('#qishu-no').text(qishu);
			for (var i in value) {
				$('#'+i).text(value[i]);
			}
			_this.interval = setInterval(function(){
				$('#time-count').text(time+' s');
				time--;
				if (time < 5) {
					_this.music('warning');
				}
				//倒计时结束
				if (time <= 0) {
					clearInterval(_this.interval);
					_this.interval = null;
					$('#jiangqubox').data('status', 0);
					$('#time-count').text('停止下注');
				}
			}, 1000);
		}
	},
	stopBling: function(type)
	{
		var _this = this;
		$('#jiangqubox').data('status', 0);
		$('#time-count').text('停止下注');
		_this.music('stop');
	},
	music: function(type)
	{
		if (!$('#bjyinyue-icon').data('type')) {
			return false;
		}
		switch(type) {
			case 'start':
				break;
			case 'stop':
				break;
			case 'choma':
				break;
			case 'he':
				break;
			case 'zhuang':
				break;
			case 'xian':
				break;
			case 'warning':
				break;
			default:
				return false;
				break;
		}
		$('embed').remove();
        $('embed').append('<embed src="'+DOMAIN+'media/'+type+'.mp3" autostart="true" hidden="true" loop="false">');
	}
};