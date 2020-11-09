var WALLET = {
	init: function()
	{
		this.data = $('#wallet-content>ul').data();
		var list = this.getList(this.data);
		$('#wallet-content>ul').html('');
		this.initListHtml(list);
		//监听滚动到底部
		var _this = this;
		$('#wallet-content').scroll(function(){
			if ($(this).find('ul .end').length > 0 || $(this).find('ul .loading-li').length > 0) {
				return false;
			}
			var _thisobj = $(this);
			var top = _thisobj.scrollTop();
			var h = _thisobj.height();
			var height = _thisobj.find('ul').height();
			if (top + h >= height) {
				var end = _thisobj.find('ul').data('end');
				if (end) {
					_thisobj.animate({scrollTop: height}, 100);
				} else {
					_thisobj.find('ul').append('<li class="loading-li"><img src="' + DOMAIN + 'image/common/loading_c.png" class="loading" style="max-height:0.2rem;max-width:0.2rem;"></li>');
					var page = _thisobj.find('ul').data('page') + 1;
					_this.data.page = page;
					var type = _thisobj.find('ul').data('type');
					_thisobj.animate({scrollTop: height}, 300, function(){
						var list = _this.getList(_this.data);
						_this.initListHtml(list);
						if (list.length === 0) {
							_thisobj.find('ul').data('end', 'true');
						}
						_thisobj.find('ul .loading-li').remove();
						_thisobj.find('ul').data('page', page);
					});
				}
			}
		});
	},
	getList: function(param)
	{
		var return_data = {};
		$.ajaxSetup({async: false});
		$.get(URI+'wallet/getLogList', param, function(res) {
			if (res.code == 200) {
				return_data = res.data;
			} else {
				POP.tips(res.message);
				return_data = false;
			}
		});
		return return_data;
	},
	initListHtml: function(data)
	{
		if (data !== false) {
			var html = '';
			if (data.length > 0) {
				for (var i in data) {
					html += `<li>
								<div class="left text-left">
									<p>`+data[i].remark+`</p>
									<p class="font-12 color-g">`+data[i].create_at+`</p>
								</div>
								<div class="right">
									<span class="icon icon-rmb"></span>
									<span>`+data[i].subtotal+`</span>
								</div>
								<div class="clear"></div>
							</li>`;
				}
			} else {
				html += `<li class="text-center color-g"><span class="font-12">没有数据了</span></li>`;
			}
			$('#wallet-content>ul').append(html);
		}
	},
};