var BJL = {
	init: function()
	{
		var _this = this;
		$('.progress').animate({'width': '100%'}, 800, function(){
			$('#loading-page').hide();
			$('#main-page').show();
		});
		$('.footer .chip-number').on('click', function(){
			$(this).addClass('select').siblings().removeClass('select');
		});
	}
};