<?php
/**
 * Login / Register — single card with Sign In / Create Account tabs.
 * @package Unitourk
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
do_action( 'woocommerce_before_customer_login_form' );
$registration = 'yes' === get_option( 'woocommerce_enable_myaccount_registration' );
$gen_pass     = 'yes' === get_option( 'woocommerce_registration_generate_password' );
$gen_user     = 'yes' === get_option( 'woocommerce_registration_generate_username' );
$show_register = $registration; // tab is only shown if registration is on
?>
<div class="ut-auth" id="customer_login">
	<input type="radio" name="ut_auth_tab" id="ut_tab_login" class="ut-auth-radio" checked>
	<?php if ( $show_register ) : ?><input type="radio" name="ut_auth_tab" id="ut_tab_register" class="ut-auth-radio"><?php endif; ?>

	<div class="ut-auth-card">
		<div class="ut-auth-head">
			<h2>Welcome to Unitourk</h2>
			<p>Sign in to track orders and manage your requests, or create an account in seconds.</p>
		</div>

		<?php if ( $show_register ) : ?>
			<div class="ut-auth-tabs">
				<label for="ut_tab_login" class="ut-auth-tab ut-tab-login">Sign In</label>
				<label for="ut_tab_register" class="ut-auth-tab ut-tab-register">Create Account</label>
				<span class="ut-auth-slider"></span>
			</div>
		<?php endif; ?>

		<!-- LOGIN -->
		<div class="ut-auth-panel ut-panel-login">
			<form class="woocommerce-form woocommerce-form-login login" method="post">
				<?php do_action( 'woocommerce_login_form_start' ); ?>
				<p class="form-row">
					<label for="username">Username or email address&nbsp;<span class="required">*</span></label>
					<input type="text" class="input-text" name="username" id="username" autocomplete="username" value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>" />
				</p>
				<p class="form-row">
					<label for="password">Password&nbsp;<span class="required">*</span></label>
					<span class="password-input"><input class="input-text" type="password" name="password" id="password" autocomplete="current-password" /></span>
				</p>
				<?php do_action( 'woocommerce_login_form' ); ?>
				<p class="form-row ut-auth-actions">
					<?php wp_nonce_field( 'woocommerce-login', 'woocommerce-login-nonce' ); ?>
					<button type="submit" class="woocommerce-button button woocommerce-form-login__submit btn btn-primary" name="login" value="Log in">Sign In</button>
					<label class="woocommerce-form__label woocommerce-form__label-for-checkbox woocommerce-form-login__rememberme">
						<input class="woocommerce-form__input woocommerce-form__input-checkbox" name="rememberme" type="checkbox" id="rememberme" value="forever" /> <span>Remember me</span>
					</label>
				</p>
				<p class="woocommerce-LostPassword lost_password"><a href="<?php echo esc_url( wp_lostpassword_url() ); ?>">Forgot your password?</a></p>
				<?php do_action( 'woocommerce_login_form_end' ); ?>
			</form>
		</div>

		<?php if ( $show_register ) : ?>
		<!-- REGISTER -->
		<div class="ut-auth-panel ut-panel-register">
			<form method="post" class="woocommerce-form woocommerce-form-register register">
				<?php do_action( 'woocommerce_register_form_start' ); ?>
				<?php if ( ! $gen_user ) : ?>
					<p class="form-row">
						<label for="reg_username">Username&nbsp;<span class="required">*</span></label>
						<input type="text" class="input-text" name="username" id="reg_username" autocomplete="username" value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>" />
					</p>
				<?php endif; ?>
				<p class="form-row">
					<label for="reg_email">Email address&nbsp;<span class="required">*</span></label>
					<input type="email" class="input-text" name="email" id="reg_email" autocomplete="email" value="<?php echo ( ! empty( $_POST['email'] ) ) ? esc_attr( wp_unslash( $_POST['email'] ) ) : ''; ?>" />
				</p>
				<?php if ( ! $gen_pass ) : ?>
					<p class="form-row">
						<label for="reg_password">Password&nbsp;<span class="required">*</span></label>
						<input type="password" class="input-text" name="password" id="reg_password" autocomplete="new-password" />
					</p>
				<?php else : ?>
					<p class="ut-auth-note">A secure password will be emailed to you, along with your order details on your first request.</p>
				<?php endif; ?>
				<?php do_action( 'woocommerce_register_form' ); ?>
				<p class="woocommerce-form-row form-row ut-auth-actions">
					<?php wp_nonce_field( 'woocommerce-register', 'woocommerce-register-nonce' ); ?>
					<button type="submit" class="woocommerce-Button woocommerce-button button woocommerce-form-register__submit btn btn-primary" name="register" value="Register">Create Account</button>
				</p>
				<?php do_action( 'woocommerce_register_form_end' ); ?>
			</form>
		</div>
		<?php endif; ?>
	</div>
</div>
<?php do_action( 'woocommerce_after_customer_login_form' );
