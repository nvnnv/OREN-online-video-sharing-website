<?php defined('BASEPATH') OR exit('No direct script access allowed');?>

<div class="wrapper">
	<section class="banner-section p120">
		<div class="container">
			<div class="banner-text">
				<h2>Register</h2>
				<p>Please Register to have access to all videos and many more.</p>
			</div><!--banner-text end-->
		</div>
	</section><!--banner-section end-->

	<section class="form_popup">
		
		<div class="signup_form" id="signup_form">
		 	<div class="hd-lg">
		 		<img src="/oren/images/logo.png" alt="">
		 		<span>Register your Oren account</span>
		 	</div><!--hd-lg end-->
			<div class="user-account-pr">
				<form method='post' action='/oren/user/signup'>
					<div class="input-sec">
						<?php if (isset($username)):?>
							<input type="email" name="username" placeholder="Email address" value="<?php echo $username?>">
						<?php else: ?>
							<input type="email" name="username" placeholder="Email address">
						<?php endif;?>
					</div>
					<div class="input-sec">
						<input type="password" name="password" placeholder="Password">
					</div>
					<div class="input-sec">
						<input type="password" name="confirm_password" placeholder="Re-enter password">
					</div>
					<!-- <div class="input-sec flatpickr">
						<input type="number" name="date" class="flatpickr-input" placeholder="Select Date..." data-input>
					</div> -->
					<div class="input-sec">
						<?php if(isset($nickname)): ?>
							<input type="text" name="nickname" placeholder="Nickname" value="<?php echo $nickname?>">
						<?php else: ?>
							<input type="text" name="nickname" placeholder="Nickname">
						<?php endif ?>
					</div>
					<?php if (isset($error)) : ?>
						<div class="input-sec">
							<?php echo $error ?>
						</div>
					<?php endif; ?>
					<div class="input-sec mb-0">
						<button type="submit">Signup</button>
					</div><!--input-sec end-->
				</form>
				<div class="form-text">
					<p>By sIgning up you agree to Oren’s <a href="#" title="">Terms of Service</a> and <a href="#" title="">Privacy Policy</a> </p>
				</div>
			</div><!--user-account end--->
			<div class="fr-ps">
				<h1>Already have an account?<a href="go_to_login_page" title="" class="show_signup"> Login here.</a></h1>
			</div><!--fr-ps end-->
		</div><!--login end--->

	</section><!--form_popup end-->


</div><!--wrapper end-->
