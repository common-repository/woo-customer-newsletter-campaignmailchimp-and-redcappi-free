<?php
Class WPS_WCNC_Functions{
	public function __construct()
	{
		require 'wps-wcnc-admin.php';
		require 'wps-wcnc-api-ajax.php';

		add_action( 'woocommerce_order_status_completed', array($this,'get_order_data') ,10,1);
		add_action('admin_init',array($this,'get_user_data'));
		if(isset($_GET['wps_s']) && $_GET['wps_s']==1){
			add_action('admin_notices',array($this,'show_message'));
		}
	}
	public static function show_message(){
		$msg  = 'Data <b>Successfully</b> Imported, please check your';
		$msg .=' Newsletter Campaign Account\'s <b>Contact List</b>.'
		?>
		<div class="notice notice-success is-dismissible"> 
			<p><?php echo $msg; ?></p>
			<button type="button" class="notice-dismiss">
				<span class="screen-reader-text">Dismiss this notice.</span>
			</button>
		</div>
		<?php
	}
	public static function get_user_data(){
		
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
	        return;
	    if(!isset($_POST['action']))
	    	return;
	    if(sanitize_text_field($_POST['action'])=='wps_wcnc_camp_all'){
			WPS_WCNC_Functions::process_all_cust_data();
		}
		if((sanitize_text_field($_POST['action'])!='wps_wcnc_user_export'))
			return;
		
		if(!(sanitize_text_field($_POST['wps_wcnc_user_email'])))
			return;
		$order_data['firstname'] 	= sanitize_text_field($_POST['wps_wcnc_user_first_name']);
		$order_data['lastname'] 	= sanitize_text_field($_POST['wps_wcnc_user_last_name']);
		$order_data['email'] 		= sanitize_text_field($_POST['wps_wcnc_user_email']);
		WPS_WCNC_Functions::process_cust_data($order_data);
	}
	public static function get_order_data($order_id){
		$order = new WC_Order( $order_id );
		$order_data['firstname'] = $order->billing_first_name;
		$order_data['lastname'] = $order->billing_last_name;
		$order_data['email'] = $order->billing_email;
		WPS_WCNC_Functions::process_cust_data($order_data);
	}

	public static function process_cust_data($user_data)
	{
		//echo "<pre>"; print_r($order_data); echo "</pre>";
		$campaigns = array('mailchimp','redcappi');
		for($i=0;$i<count($campaigns);$i++){
			WPS_WCNC_Functions::export_to_campaigns($campaigns[$i],$user_data);
		}
		wp_safe_redirect( admin_url( '?page=woo-cust-news-camp-mc-rc&wps_s=1' ) );
	}
	public static function process_all_cust_data(){
		if(!isset($_POST['wps_wcnc_all_camp_name']))
	    	return;
		$camp = sanitize_text_field($_POST['wps_wcnc_all_camp_name']);
		$args = array(
	        'role' => 'customer',
	        'orderby' => 'user_nicename',
	        'order' => 'ASC',
	      );
       	$users = get_users($args);
       	foreach ( $users as $user ) {
       		if($user->user_email!=''){
	       		$user_data['firstname'] = $user->first_name;
				$user_data['lastname'] 	= $user->last_name;
				$user_data['email'] 	= $user->user_email;
				WPS_WCNC_Functions::export_to_campaigns($camp,$user_data);
			}
       	}
       	wp_safe_redirect( admin_url( '?page=woo-cust-news-camp-mc-rc&wps_s=1' ) );
	}

	public static function export_to_campaigns($campaigns,$user_data){
		$key = 'wps_wcnc_'.$campaigns.'_status';
		$wps_wcnc_campigns_status = (get_option($key)) ? get_option($key) : 'disable';
		if($wps_wcnc_campigns_status!='enable')
			return;
		$email = $user_data['email'];
		$fname = $user_data['firstname'];
		$lname = $user_data['lastname'];
		//echo "------<br>----- ".$campaigns.'  '.$key.'<br />'; 
		switch($campaigns){
	        case 'mailchimp':
	        	$wps_wcnc_mailchimp_list_id  = (get_option('wps_wcnc_mailchimp_list_id')) ? get_option('wps_wcnc_mailchimp_list_id') : '';
				$wps_wcnc_mailchimp_key 	 = (get_option('wps_wcnc_mailchimp_key')) ? get_option('wps_wcnc_mailchimp_key') : '';
		        require_once 'library/mailchimp/MCAPI.class.php';
		        $api = new MCAPI($wps_wcnc_mailchimp_key);

		        $merge_vars = array('FNAME'=>$fname, 'LNAME'=>$lname);

		        // For parameters doc, refer to: http://apidocs.mailchimp.com/api/1.3/listsubscribe.func.php
		        $retval = $api->listSubscribe( $wps_wcnc_mailchimp_list_id, $email, $merge_vars, 'html', false, true );
		        //echo "<pre>"; print_r($retval); echo "</pre>";
		        if ($api->errorCode){
		          	return false;
		        } else {
		          	return true;
		        }
	      	break;
	      	default:
	      	break;
	    }
	}

}new WPS_WCNC_Functions();

?>