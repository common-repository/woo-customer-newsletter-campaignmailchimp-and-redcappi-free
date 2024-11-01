<?php
class WPS_WCNC_Settings
{
	
	public function display_settings_MailChimp(){
		$wps_wcnc_mailchimp_key 	  = (get_option('wps_wcnc_mailchimp_key')) ? get_option('wps_wcnc_mailchimp_key') : '';
	    $wps_wcnc_mailchimp_status 	  = (get_option('wps_wcnc_mailchimp_status')) ? get_option('wps_wcnc_mailchimp_status') : 'disable';
		?>
			<article class="ac-large wps-wcnc-settings">
				<span class="wps-label">Status</span>
				<select name="wps_wcnc_mailchimp_status">
					<option value="enable" <?php if($wps_wcnc_mailchimp_status=='enable'){echo 'selected';}?>>Enable</option>
					<option value="disable" <?php if($wps_wcnc_mailchimp_status=='disable'){echo 'selected';}?>>Disable</option>
				</select>
		    	<span class="wps-label">API Key</span>
		    	<input type="text" autocomplete="off" name="wps_wcnc_mailchimp_key" id="wps_wcnc_mailchimp_key" value="<?php echo $wps_wcnc_mailchimp_key; ?>"/>
		    	
		    	<span class="wps-label">Lists</span>
		    	<p class="wpcWcncloaderDiv">
		    		Please wait,List is loading <img src="<?php echo WPS_WOO_CUST_NEWS_CAMP_IMG; ?>/loading.gif" title="List is loading" />
		    	</p>
		    	<div id="wps_wcnc_mailchimp_list_id">Fill the API Key, Your MailChimp account lists will be automatically show here.</div>
			</article>
		<?php
	}

	public function display_settings_RedCappi(){
		$wps_wcnc_redcappi_pub_key 	= (get_option('wps_wcnc_redcappi_pub_key')) ? get_option('wps_wcnc_redcappi_pub_key') : '';
	    $wps_wcnc_redcappi_status 	= (get_option('wps_wcnc_redcappi_status')) ? get_option('wps_wcnc_redcappi_status') : 'disable';
	    $wps_wcnc_redcappi_pri_key 	= (get_option('wps_wcnc_redcappi_pri_key')) ? get_option('wps_wcnc_redcappi_pri_key') : '';
		?>
			<article class="ac-extra-large wps-wcnc-settings">
				<p class="wps-wcnc-notice">*This Feature Is Available With Premium Version*</p>
				<span class="wps-label">Status</span>
				<select name="wps_wcnc_redcappi_status">
					<option value="enable" <?php if($wps_wcnc_redcappi_status=='enable'){echo 'selected';}?>>Enable</option>
					<option value="disable" <?php if($wps_wcnc_redcappi_status=='disable'){echo 'selected';}?>>Disable</option>
				</select>
				<span class="wps-label">Public Key</span>
		    	<input type="text" autocomplete="off" name="wps_wcnc_redcappi_pub_key" id="wps_wcnc_redcappi_pub_key" value="<?php echo $wps_wcnc_redcappi_pub_key; ?>"/>
		    	<span class="wps-label">Private Key</span>
		    	<input type="text" autocomplete="off" name="wps_wcnc_redcappi_pri_key" id="wps_wcnc_redcappi_pri_key" value="<?php echo $wps_wcnc_redcappi_pri_key; ?>"/>
		    	<span class="wps-label">Lists</span>
		    	<p class="wpcWcncloaderDiv">
		    		Please wait,List is loading <img src="<?php echo WPS_WOO_CUST_NEWS_CAMP_IMG; ?>/loading.gif" title="List is loading" />
		    	</p>
		    	<div id="wps_wcnc_redcappi_list_id">Fill the Public Key and Private Key, Your RedCappi account lists will be automatically show here.</div>
			</article>
		<?php
	}
}

?>