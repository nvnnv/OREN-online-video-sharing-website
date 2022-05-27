<?php defined('BASEPATH') OR exit('No direct script access allowed');?>

<div class="wrapper">
	<section class="banner-section p120">
		<div class="container">
			<div class="banner-text">
				<h2>Sign In</h2>
				<p>Please sign in to have access to all videos and many more.</p>
			</div><!--banner-text end-->
		</div>
	</section><!--banner-section end-->

	<section class="form_popup">
	
		<div class="login_form" id="login_form">
		 	<div class="hd-lg">
		 		<img src="/oren/images/logo.png" alt="">
		 		<span>Log into your Oren account</span>
		 	</div><!--hd-lg end-->
			<div class="user-account-pr">
					<?php if (isset($from_sign_up)) : ?>
						<h3 class="input-sec">
							Signup Successfully, Please check your email to activate it!
						</h3>
					<?php endif; ?>
				<form method='post' action='/oren/user/login'>
					<div class="input-sec">
						<?php if (isset($username)) : ?>
							<input type="text" name="username" placeholder="Username", value="<?php echo $username?>">
						<?php else : ?>
							<input type="text" name="username" placeholder="Username">
						<?php endif; ?>
					</div>
					<div class="input-sec">
						<input type="Password" name="password" placeholder="Password">
					</div>
					<div class="input-sec">
						<input type="Password" name="captcha" placeholder="">

						<img src='/oren/application/views/captcha.php'></img>
					</div>

					<?php if (isset($error)) : ?>
						<div class="input-sec">
							<?php echo $error; ?>
						</div>
					<?php endif; ?>
					<div class="input-sec mb-0">
						<button type="submit">Login</button>
					</div><!--input-sec end-->
				</form>
				<a href="#" title="" class="fg_btn">Forgot password?</a>
			</div><!--user-account end--->
			<div class="fr-ps">
				<h1>Don’t have an account? <a href="go_to_signup_page" title="" class="show_signup">Signup here.</a></h1>
			</div><!--fr-ps end-->
		</div><!--login end--->

	</section><!--form_popup end-->

</div><!--wrapper end-->

