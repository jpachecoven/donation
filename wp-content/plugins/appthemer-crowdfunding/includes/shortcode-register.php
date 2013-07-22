<?php
/**
 * Register Shortcode.
 *
 * [appthemer_crowdfunding_register] creates a log in form for users to log in with.
 *
 * @since Appthemer CrowdFunding 1.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Register Shortcode
 *
 * @since CrowdFunding 1.0
 *
 * @return $form
 */
function atcf_shortcode_register() {
	global $post;

	$user = wp_get_current_user();

	ob_start();

	echo '<div class="atcf-register">';
	echo '<form name="registerform" id="registerform" action="" method="post">';
	do_action( 'atcf_shortcode_register', $user, $post );
	echo '</form>';
	echo '</div>';

	$form = ob_get_clean();

	return $form;
}
add_shortcode( 'appthemer_crowdfunding_register', 'atcf_shortcode_register' );

/**
 * Register form
 *
 * @since CrowdFunding 1.0
 *
 * @return $form
 */
function atcf_shortcode_register_form() {
	global $edd_options;
?>
	<p class="atcf-register-first-name">
		<label for="user_nicename"><?php _e( 'First Name', 'atcf' ); ?></label>
		<input type="text" name="first_name" id="first_name" class="input" value="" />
	</p>
	
	<p class="atcf-register-last-name">
		<label for="user_nicename"><?php _e( 'Last Name', 'atcf' ); ?></label>
		<input type="text" name="last_name" id="last_name" class="input" value="" />
	</p>

	<p class="atcf-register-email">
		<label for="user_login"><?php _e( 'Email Address', 'atcf' ); ?></label>
		<input type="text" name="user_email" id="user_email" class="input" value="" />
	</p>

	<p class="atcf-register-username">
		<label for="user_login"><?php _e( 'Username', 'atcf' ); ?></label>
		<input type="text" name="user_login" id="user_login" class="input" value="" />
	</p>

	<p class="atcf-register-password">
		<label for="user_pass"><?php _e( 'Password', 'atcf' ); ?></label>
		<input type="password" name="user_pass" id="user_pass" class="input" value="" />
	</p>
	
	<p class="atcf-register-select">
		<label for="user_joining_as"><?php _e( 'Joining as', 'atcf' ); ?></label>
			<!-- <label for="user_individual"><?php _e( 'Individual', 'atcf' ); ?></label>
			<input checked="true" name="user_joining_as" id="user_individual" type="radio" value="Individual" />
			<label for="user_organization"><?php _e( 'Organization', 'atcf' ); ?></label>
			<input checked="true" name="user_joining_as" id="user_organization" type="radio" value="Organization" /> -->
		<select name="user_joining_as" id="user_joining_as" class="select">
			<option value="Individual" selected="true"><?php _e( 'Individual', 'atcf' ); ?></option>
			<option value="Organization"><?php _e( 'Organization', 'atcf' ); ?></option>
		</select>
	</p>
	
	<p class="atcf-register-rif organization">
		<label for="user_rif"><?php _e( 'RIF', 'atcf' ); ?></label>
		<input type="text" name="user_rif" id="user_rif" class="input" value="" />
	</p>
	
	<p class="atcf-register-orgname organization">
		<label for="user_orgname"><?php _e( 'Org Name', 'atcf' ); ?></label>
		<input type="text" name="user_orgname" id="user_orgname" class="input" value="" />
	</p>
	
	<p class="atcf-register-orgaddress organization">
		<label for="user_orgaddress"><?php _e( 'Address', 'atcf' ); ?></label>
		<input type="text" name="user_address" id="user_address" class="input" value="" />
	</p>
	
	<p class="atcf-register-zip organization">
		<label for="user_zip"><?php _e( 'ZIP', 'atcf' ); ?></label>
		<input type="text" name="user_zip" id="user_zip" class="input" value="" />
	</p>
	
	<p class="atcf-register-phone organization">
		<label for="user_phone"><?php _e( 'Phone', 'atcf' ); ?></label>
		<input type="text" name="user_phone" id="user_phone" class="input" value="" />
	</p>
		
	<p class="atcf-register-submit">
		<input type="submit" name="submit" id="submit" class="<?php echo apply_filters( 'atcf_shortcode_register_button_class', 'button-primary' ); ?>" value="<?php _e( 'Register', 'atcf' ); ?>" />
		<input type="hidden" name="action" value="atcf-register-submit" />
		<?php wp_nonce_field( 'atcf-register-submit' ); ?>
	</p>
	
	<script>
		var $ = jQuery;
		$('.organization').hide();
		$('#user_joining_as').change(function() {		
			user_type = $('#user_joining_as').val();
			if (user_type == "Organization") {
				$(".organization").show ( 800 );
			} else {
				$(".organization").hide ( 800 );
			}		
		});	
	</script>
<?php
	//$crowdfunding = crowdfunding();
	//wp_enqueue_script( 'atcf-scripts', $crowdfunding->plugin_url . '/assets/js/crowdfunding.js', 'jquery');
}
add_action( 'atcf_shortcode_register', 'atcf_shortcode_register_form' );

/**
 * Process registration submission.
 *
 * @since Appthemer CrowdFunding 1.0
 *
 * @return void
 */
function atcf_registration_handle() {
	if ( 'POST' !== strtoupper( $_SERVER['REQUEST_METHOD'] ) )
		return;
	
	if ( empty( $_POST['action' ] ) || ( 'atcf-register-submit' !== $_POST[ 'action' ] ) )
		return;

	if ( ! wp_verify_nonce( $_POST[ '_wpnonce' ], 'atcf-register-submit' ) )
		return;

	$errors   = new WP_Error();
	
	//$nicename = isset( $_POST[ 'displayname' ] ) ? esc_attr( $_POST[ 'displayname' ] ) : null;
	$email    = isset( $_POST[ 'user_email' ] ) ? esc_attr( $_POST[ 'user_email' ] ) : null;
	$username = isset( $_POST[ 'user_login' ] ) ? esc_attr( $_POST[ 'user_login' ] ) : null;
	$password = isset( $_POST[ 'user_pass' ] ) ? esc_attr( $_POST[ 'user_pass' ] ) : null;

	// ADD New fields - Joel Pacheco
	$first_name = isset( $_POST[ 'first_name' ] ) ? esc_attr( $_POST[ 'first_name' ] ) : null;
	$last_name = isset( $_POST[ 'last_name' ] ) ? esc_attr( $_POST[ 'last_name' ] ) : null;
	$joining_as = isset( $_POST[ 'user_joining_as' ] ) ? esc_attr( $_POST[ 'user_joining_as' ] ) : null;
	
	$fields = array(
		'first_name' 		   => $first_name,
		'last_name' 		   => $last_name,
		'type'			   	   => $joining_as,
	);
	
	if ($joining_as == "Organization") {
			
		$rif = isset( $_POST[ 'user_rif' ] ) ? esc_attr( $_POST[ 'user_rif' ] ) : null;
		$orgname = isset( $_POST[ 'user_orgname' ] ) ? esc_attr( $_POST[ 'user_orgname' ] ) : null;
		$address = isset( $_POST[ 'user_address' ] ) ? esc_attr( $_POST[ 'user_address' ] ) : null;
		$zip = isset( $_POST[ 'user_zip' ] ) ? esc_attr( $_POST[ 'user_zip' ] ) : null;
		$phone = isset( $_POST[ 'user_phone' ] ) ? esc_attr( $_POST[ 'user_phone' ] ) : null;
		
		$defaults = array(
		'rif'			       => $rif,
		'orgname'			   => $orgname,
		'address'			   => $address,
		'zip'				   => $zip,
		'phone'				   => $phone,	
		);
		
		$fields = wp_parse_args($fields, $defaults);
	}
	// DDA
	
	$nicename = esc_attr($first_name . " " . $last_name);	

	/** Check Email */
	if ( empty( $email ) || ! is_email( $email ) )
		$errors->add( 'invalid-email', __( 'Please enter a valid email address.', 'atcf' ) );

	if ( email_exists( $email ) )
		$errors->add( 'taken-email', __( 'That contact email address already exists.', 'atcf' ) );

	/** Check Password */
	if ( empty( $password ) )
		$errors->add( 'invalid-password', __( 'Please choose a secure password.', 'atcf' ) );

	$errors = apply_filters( 'atcf_register_validate', $errors, $_POST );

	if ( ! empty ( $errors->errors ) )
		wp_die( $errors );

	if ( '' == $username )
		$username = $email;

	if ( '' == $nicename )
		$nicename = $username;

	$user_id = atcf_register_user( array(
		'user_login'           => $username, 
		'user_pass'            => $password, 
		'user_email'           => $email,
		'display_name'         => $nicename,
	) );

	// ADD New fields - Joel Pacheco
	atcf_register_user_meta( $user_id, $fields );
	
	do_action( 'atcf_register_process_after', $user_id, $_POST );

	$redirect = apply_filters( 'atcf_register_redirect', isset ( $edd_options[ 'profile_page' ] ) ? get_permalink( $edd_options[ 'profile_page' ] ) : home_url() );

	if ( $user_id ) {
		wp_safe_redirect( $redirect );
		exit();
	} else {
		wp_safe_redirect( home_url() );
		exit();
	}
}
add_action( 'template_redirect', 'atcf_registration_handle' );

/**
 * Register a user.
 *
 * Extract a bit that actually creates the user so it can be called elsewhere
 * (such as on the campaign creation process)
 *
 * @since Appthemer CrowdFunding 1.0
 *
 * @return void
 */
function atcf_register_user( $args = array() ) {
	$defaults = array(
		'user_pass'            => wp_generate_password( 12, false ),
		'show_admin_bar_front' => 'false',
		'role'                 => 'campaign_contributor'
	);

	$args = wp_parse_args( $args, $defaults );
	
	$user_id = wp_insert_user($args);

	$secure_cookie = is_ssl() ? true : false;
	wp_set_auth_cookie( $user_id, true, $secure_cookie );
	wp_new_user_notification( $user_id, $args[ 'user_pass' ] );

	return $user_id;
}

/**
 * Register a user meta.
 *
 * Adds extra user and organization data
 * 
 *
 * @since Appthemer CrowdFunding 1.0 (Joel Pacheco)
 *
 * @return void
 */
function atcf_register_user_meta( $user_id, $args = array() ) {
	
	foreach ($args as $user_meta => $value) {
		
		update_user_meta($user_id, $user_meta, $value);
		
	}
	
}