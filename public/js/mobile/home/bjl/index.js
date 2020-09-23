var BJL = {
	init: function()
	{
		$('.progress').animate({'width': '100%'}, 800, function(){
			$('#loading-page').hide();
			$('#main-page').show();
		});
		$('.footer .chip span').on('click', function(){
			$(this).addClass('select').siblings().removeClass('select');
		});
	}
};