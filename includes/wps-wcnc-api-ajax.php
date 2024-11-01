<?php
Class WPS_WCNC_Api_Ajax{
	public function __construct()
	{
		add_action( 'wp_ajax_wps_wcnc_get_mc_list', array($this,'wps_wcnc_get_mc_list' ));
		add_action( 'wp_ajax_wps_wcnc_rc_get_list_id', array($this,'wps_wcnc_rc_get_list_id' ));
	}
	public static function wps_wcnc_get_mc_list(){
		$wps_wcnc_mailchimp_list_id  = (get_option('wps_wcnc_mailchimp_list_id')) ? get_option('wps_wcnc_mailchimp_list_id') : '';

		$api_key = $_POST['api_key'];
		$data = array(
			'fields' => 'lists',
		);
		$url = 'https://' . substr($api_key,strpos($api_key,'-')+1) . '.api.mailchimp.com/3.0/lists/';
		$result = json_decode( WPS_WCNC_Api_Ajax::mailchimp_curl_connect( $url, 'GET', $api_key, $data) );
		if( !empty($result->lists) ) {
			?>
			<select name="wps_wcnc_mailchimp_list_id" id="wps_wcnc_mailchimp_list_id">
			<?php
				foreach( $result->lists as $list ){
					//echo '<option value="' . $list->id . '">' . $list->name . ' (' . $list->stats->member_count . ')</option>';
					?>
						<option value="<?php echo $list->id; ?>"<?php if($list->id==$wps_wcnc_mailchimp_list_id){echo 'selected';}?>><?php echo $list->name; ?></option>
					<?php
				}
			?>
			</select>
			<?php
		} elseif ( is_int( $result->status ) ) {
			echo '<strong>' . $result->title . ':</strong> ' . $result->detail;
		}
		exit;
	}
	public static function mailchimp_curl_connect( $url, $request_type, $api_key, $data = array() ) {
		if( $request_type == 'GET' )
			$url .= '?' . http_build_query($data);

		$mch = curl_init();
		$headers = array(
			'Content-Type: application/json',
			'Authorization: Basic '.base64_encode( 'user:'. $api_key )
		);
		curl_setopt($mch, CURLOPT_URL, $url );
		curl_setopt($mch, CURLOPT_HTTPHEADER, $headers);
		//curl_setopt($mch, CURLOPT_USERAGENT, 'PHP-MCAPI/2.0');
		curl_setopt($mch, CURLOPT_RETURNTRANSFER, true); // do not echo the result, write it into variable
		curl_setopt($mch, CURLOPT_CUSTOMREQUEST, $request_type); // according to MailChimp API: POST/GET/PATCH/PUT/DELETE
		curl_setopt($mch, CURLOPT_TIMEOUT, 10);
		curl_setopt($mch, CURLOPT_SSL_VERIFYPEER, false); // certificate verification for TLS/SSL connection

		if( $request_type != 'GET' ) {
			curl_setopt($mch, CURLOPT_POST, true);
			curl_setopt($mch, CURLOPT_POSTFIELDS, json_encode($data) ); // send data in json
		}

		return curl_exec($mch);
	}

	public static function wps_wcnc_rc_get_list_id(){
		require_once('library/rcapi.class.php');
		$result 	= array();

		$public_key = $_POST['pub_key'] ;
		$private_key = $_POST['pri_key'] ;

		$api_domain = 'http://api.redcappi.com';
		$api_folder = '/v1';
 

		$rcapi = new RedCappiClientApi($public_key, $private_key, $api_domain, $api_folder);
		$results  = json_decode($rcapi->showList());

		$wps_wcnc_redcappi_list_id  = (get_option('wps_wcnc_redcappi_list_id')) ? get_option('wps_wcnc_redcappi_list_id') : '';
		if( !empty($results) ) {
			?>
			<select name="wps_wcnc_redcappi_list_id" id="wps_wcnc_redcappi_list_id">
			<?php
				//echo "<pre>>>"; print_r($datas); echo "</pre>"; exit;
				foreach($results as $result){
					if($result->id>0){
						if($wps_wcnc_redcappi_list_id==$result->id){
							echo "<option value='".$result->id."' selected>".$result->name."</option>";
						}
						else{
							echo "<option value='".$result->id."'>".$result->name."</option>";
						}
					}
				}
				?>
			</select>
				<?php
		}
		//echo "<pre>"; print_r($result); echo "</pre>";
		exit;
	}
	
}new WPS_WCNC_Api_Ajax();

?>