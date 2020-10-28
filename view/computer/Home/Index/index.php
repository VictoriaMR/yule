<!DOCTYPE html>
<html lang="zxx">

<head>
	<title>Home</title>
	<!-- Meta tag Keywords -->
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta charset="UTF-8" />
	<meta name="keywords"
		content="" />
	<!-- //Meta tag Keywords -->
	<link href="http://fonts.googleapis.com/css2?family=Karla:wght@400;700&display=swap" rel="stylesheet">
	<link href="http://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
	<!--/Style-CSS -->
	<link rel="stylesheet" href="/test/css/style.css" type="text/css" media="all" />
	<!--//Style-CSS -->
</head>

<body>

	<!-- form section start -->
	<section class="w3l-mockup-form">
		<div class="wrapper">
		<!-- /form -->
		<div class="workinghny-form-grid">
				<div class="main-mockup">
					<div class="alert-close"> </div>
					<div class="content-wthree">
						<h1>Mockup</h1>
						<i class="fa fa-envelope" aria-hidden="true"></i>
						<h2>Subscribe & Stay Update </h2>
						<form action="#" method="post">
							<input type="email" class="email" name="eamil" placeholder="Enter Your Email" required="">
							<button class="btn" type="submit">Subscribe</button>
						</form>
						<div class="social-icons w3layouts">
							<ul>
								
								<li><a href="#" class="facebook"><img src="test/images/fb.png" title="facebook" alt="facebook"></a></li>
								<li><a href="#" class="twitter"><img src="test/images/tw.png" title="twitter" alt="twitter"></a></li>
								<li><a href="#" class="twitter"><img src="test/images/gg.png" title="twitter" alt="twitter"></a></li>
								<li><a href="#" class="googleplus"><img src="test/images/pin.png" title="googleplus" alt="googleplus"></a></li>
								<div class="clear"></div>
							</ul>	
						</div>
					</div>
				</div>
			</div>
			<!-- //form -->
			<!-- copyright-->
			<div class="copyright text-center">
				<div class="wrapper">
					<p class="copy-footer-29">Copyright &copy; 2020.Company name All rights reserved.</p>
				</div>
			</div>
			<!-- //copyright-->
	</section>
	<!-- //form section start -->

	<script src="/test/js/jquery.min.js"></script>
	<script>
		$(document).ready(function (c) {
			$('.alert-close').on('click', function (c) {
				$('.main-mockup').fadeOut('slow', function (c) {
					$('.main-mockup').remove();
				});
			});
		});
	</script>
</body>

</html>