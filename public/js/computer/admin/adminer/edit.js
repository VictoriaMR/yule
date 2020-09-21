var ADMINEREDIT = {
	init: function()
	{
		UPLOAD.init({
			obj: $('.upload-item'),
			success: function(res){
				if(res.code == 200) {
					$('.avatar').find('img').attr('src', res.data.url);
				} else {
					errorTips(res.message);
				}
			},
			error: function(res){
				errorTips('上传失败');
			}
		});
	}
}