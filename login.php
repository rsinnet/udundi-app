<?php
  $is_login_page = true;
  //initilize the page
require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

// $page_title = "Login";

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
// $page_css[] = "your_style.css";
$no_main_header = true;
// $page_body_prop = array("id"=>"login", "class"=>"animated fadeInDown");
include("inc/header.php");

?>

<body id="signup">
	<div class="container">
		<div class="row header">
			<div class="col-md-12">
				<h3 class="logo">
					<a href="./" style="font-family: 'Ubuntu', sans-serif; font-weight: 500; font-size: 1.8em; text-shadow: none">udundi</a>
				</h3>
				<h4>Sign in to your account.</h4>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="wrapper clearfix">
					<div class="formy">
						<div class="row">
							<div class="col-md-12">
								<form action="authenticate.php" method="POST" id="login-form" role="form">
							  		<div class="form-group">
							    		<label for="email">Email address</label>
							    		<input type="email" name="email" class="form-control" id="email" />
							  		</div>
							  		<div class="form-group">
							    		<label for="password">Password</label>
							    		<input type="password" name="password" class="form-control" id="password" />
							  		</div>
							  		<div class="checkbox">
							    		<label>
							      			<input type="checkbox"> Remember me
							    		</label>
							  		</div>
							  		<div class="submit">
							  			<a href="#" onclick="$(this).closest('form').submit()" class="button-clear">
								  			<span>Sign in</span>
								  		</a>
							  		</div>
								</form>
							</div>
						</div>						
					</div>
				</div>
				<div class="already-account">
					Don't have an account?
					<a href="signup.php">Create one here</a>
				</div>
			</div>
		</div>
	</div>
</body>
</html>

<!-- 
			<div class="col-xs-12 col-sm-12 col-md-5 col-lg-4">
				<div class="well no-padding">
					<form action="authenticate.php" method="POST" id="login-form" class="smart-form client-form">
						<header>
							Sign In
						</header>

						<fieldset>
							
							<section>
								<label class="label">E-mail</label>
								<label class="input"> <i class="icon-append fa fa-user"></i>
									<input type="email" name="email">
									<b class="tooltip tooltip-top-right"><i class="fa fa-user txt-color-teal"></i> Please enter email address/username</b></label>
							</section>

							<section>
								<label class="label">Password</label>
								<label class="input"> <i class="icon-append fa fa-lock"></i>
									<input type="password" name="password">
									<b class="tooltip tooltip-top-right"><i class="fa fa-lock txt-color-teal"></i> Enter your password</b> </label>
								<div class="note">
									<a href="<?php echo APP_URL; ?>/forgotpassword.php">Forgot password?</a>
								</div>
							</section>

							<section>
								<label class="checkbox">
									<input type="checkbox" name="remember" checked="">
									<i></i>Stay signed in</label>
							</section>
						</fieldset>
						<footer>
							<button type="submit" class="btn btn-primary">
								Sign in
							</button>
						</footer>
					</form>

				</div> -->
				


</div>
<!-- END MAIN PANEL -->
<!-- ==========================CONTENT ENDS HERE ========================== -->

<?php 
	//include required scripts
	include("inc/scripts.php"); 
?>

<!-- PAGE RELATED PLUGIN(S) 
<script src="..."></script>-->

<script type="text/javascript">
	runAllForms();

	$(function() {
		// Validation
		$("#login-form").validate({
			// Rules for form validation
			rules : {
				email : {
					required : true,
					email : true
				},
				password : {
					required : true,
					minlength : 3,
					maxlength : 20
				}
			},

			// Messages for form validation
			messages : {
				email : {
					required : 'Please enter your email address',
					email : 'Please enter a VALID email address'
				},
				password : {
					required : 'Please enter your password'
				}
			},

			// Do not change code below
			errorPlacement : function(error, element) {
				error.insertAfter(element.parent());
			}
		});
	});
</script>

<?php 
	//include footer
	include("inc/footer.php"); 
?>
