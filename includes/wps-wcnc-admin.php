<?php
Class WPS_WCNC_Admin{
	public function __construct()
	{
		add_action('admin_init',array($this, 'reg_style_script'));
		add_action("admin_init", array($this,"register_settings_fields"));
		add_action('admin_menu', array($this,'add_menu_option'));
	}
	public static function reg_style_script(){
		wp_enqueue_style( 'wps-accrodian-css-1',WPS_WOO_CUST_NEWS_CAMP_CSS.'/accrodian.css', array(), '1.1' );
		wp_enqueue_style( 'wps-accrodian-css-1' );

		wp_register_script('wps-accrodian-js-1', WPS_WOO_CUST_NEWS_CAMP_JS.'/accrodian.js', array('jquery'),'1.1', true);
		wp_enqueue_script('wps-accrodian-js-1');

		wp_register_script('wps-api-js-1', WPS_WOO_CUST_NEWS_CAMP_JS.'/wps-wcnc-api.js', array('jquery'),'1.1', true);
		wp_enqueue_script('wps-api-js-1');

		
	}
	public static function add_menu_option(){
	    add_menu_page('Woo Customers Campaign','Woo Customers Campaign','manage_options', 'woo-cust-news-camp-mc-rc', array('WPS_WCNC_Admin', 'customer_list_render_page'),'dashicons-email-alt',55);
	    add_submenu_page('woo-cust-news-camp-mc-rc', 'Settings', 'Settings', 'manage_options','wps-wcnc-settings', array('WPS_WCNC_Admin','settings_render_page'));
	}

	public static function customer_list_render_page(){
		require 'wps-wcnc-customers.php';
		$customers = new WPS_WCNC_Customers();
		?>
		<div class="wrap">
			<h2>Customer's List</h2>
			<div id="poststuff">
				<div id="post-body" class="metabox-holder">
					<div id="post-body-content">
						<div class="meta-box-sortables ui-sortable">
							<?php
								$customers->display_customers(); 
							?>
						</div>
					</div>
				</div>
				<br class="clear">
			</div>
		</div>
		<?php
	}
	
	public static function settings_render_page(){
		require 'wps-wcnc-settings.php';
		$settings = new WPS_WCNC_Settings();
		?>
			<div id="wpbody" role="main">
				<div id="wpbody-content" aria-label="Main content" tabindex="0" style="overflow: hidden;">
					<div class="wrap">
						<form method="post" action="options.php">
							<?php 
								settings_fields("wps-wcnc-api-settings-section");
	    						do_settings_sections("wps-wcnc-api-settings");  
	    					?>
							<h1 class="wp-heading-inline">API Settings<?php submit_button(); ?></h1>
							<hr class="wp-header-end">
							<?php settings_errors(); ?>
							<div class="container">
								<section class="ac-container">
									<div>
										<input id="ac-1" name="accordion-1" type="checkbox" />
										<label for="ac-1">MailChimp</label>
										<?php $settings->display_settings_MailChimp(); ?>
									</div>
									<div>
										<input id="ac-8" name="accordion-8" type="checkbox" />
										<label for="ac-8">RedCappi</label>
										<?php $settings->display_settings_RedCappi(); ?>
									</div>
								</section>
								<?php 
									submit_button();
								?>
							</div>
						</form>
					</div>
				</div>
			</div>
		<?php
	}

	public static function register_settings_fields(){
		add_settings_section("wps-wcnc-api-settings-section", " ", null, "wps-wcnc-api-settings");
		/* MailChimp */
		register_setting("wps-wcnc-api-settings-section", "wps_wcnc_mailchimp_status");
		register_setting("wps-wcnc-api-settings-section", "wps_wcnc_mailchimp_key");
	    register_setting("wps-wcnc-api-settings-section", "wps_wcnc_mailchimp_list_id");

	    /* RedCappi */
	    register_setting("wps-wcnc-api-settings-section", "wps_wcnc_redcappi_pub_key");
	    register_setting("wps-wcnc-api-settings-section", "wps_wcnc_redcappi_pri_key");
	    register_setting("wps-wcnc-api-settings-section", "wps_wcnc_redcappi_list_id");
	    register_setting("wps-wcnc-api-settings-section", "wps_wcnc_redcappi_status");

	}

}new WPS_WCNC_Admin();

?>