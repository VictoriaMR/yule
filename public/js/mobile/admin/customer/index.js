var CUSTOMER = {
	init: function()
	{
		var data = $('#customer-content>ul').data();
		var list = this.getList(data);
		$('#customer-content>ul').html('');
		this.initListHtml(list);
		var _this = this;
		$('#customer-content').scroll(function(){
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
					var type = _thisobj.find('ul').data('type');
					_thisobj.animate({scrollTop: height}, 300, function(){
						var list = _this.getList({type: type, page: page});
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
		//充值按钮点击
		$('#customer-content').on('click', '.increate-btn', function(event){
			event.stopPropagation();
			var mem_id = $(this).data('mem_id');
			$('#proxy-modal [name="mem_id"]').val(mem_id);
			$('#proxy-modal').show();
			return false;
		});
		$('#proxy-modal .mask').on('click', function(){
			$('#proxy-modal').hide();
		});
		$('#add-form-btn').on('click', function(){
			var check = true;
			$(this).parent().find('[required="required"]').each(function(){
				var val = $(this).val();
				console.log(val)
				if (val == '' || val == 0) {
					$(this).focus();
					check = false;
					return false;
				}
			});
			if (!check) {
				return false;
			}
			$.post(URI+'customer/recharge', $(this).parent().serializeArray(), function(res) {
				POP.tips(res.message);
				if (res.code == 200) {
					window.location.reload();
				}
			});
		});
	},
	getList: function(param)
	{
		var return_data = {};
		$.ajaxSetup({async: false});
		$.get(URI+'customer/getList', param, function(res) {
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
					html += `<li data-mem_id="`+data[i].mem_id+`">
								<a href="`+URI+`customer/info?mem_id=`+data[i].mem_id+`" class="block">
									<div class="item">
										<div class="img-item">
											<img src="`+data[i].avatar+`" />
										</div>
									</div>
									<div class="item">
										<span>`+data[i].nickname+`</span>
									</div>
									<div class="item">
										<span>`+data[i].recommender_nickname+`</span>
									</div>
									<div class="item">
										<span>`+(typeof data[i].subtotal == 'undefined' ? '--' : data[i].subtotal)+`</span>
									</div>
									<div class="item">
										<span>`+(typeof data[i].subtotal == 'undefined' ? '--' : (data[i].subtotal - data[i].balance))+`</span>
									</div>
									<div class="item">
										<button type="button" class="btn-small btn-blue increate-btn" data-mem_id="`+data[i].mem_id+`">充值</button>
									</div>
								</a>
							</li>`;
				}
			} else {
				html += `<li class="text-center color-g"><span class="font-12">没有数据了</span></li>`;
			}
			$('#customer-content>ul').append(html);
		}
	},
};