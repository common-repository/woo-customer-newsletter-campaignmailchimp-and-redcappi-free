<?php
class WPS_WCNC_Customers
{
	
	public function display_customers(){
		$total_items  = self::record_count();
		echo "<h3>Total Customer: ".$total_items."</h3>";
		$customers = self::get_customers();
		if($total_items>=1){
		?>
			<div class="wps-wcnc-actions">
				Import All to
				<form name="wps-wcnc-cust-all-mc" id="wps-wcnc-cust-all-mc" method="POST">
					<input type="hidden" name="action" value="wps_wcnc_camp_all" />
					<input type="hidden" name="wps_wcnc_all_camp_name" value="mailchimp" />
					<input disabled="disabled" id="wps-wcnc-cust-all-mc-btn" class="button button-primary" name="submit" value="MailChimp" type="submit">
				</form> |
				<form name="wps-wcnc-cust-all-cc" id="wps-wcnc-cust-all-cc" method="POST">
					<input type="hidden" name="action" value="wps_wcnc_camp_all" />
					<input type="hidden" name="wps_wcnc_all_camp_name" value="redcappi" />
					<input disabled="disabled" id="wps-wcnc-cust-all-cc-btn" class="button button-primary" name="submit" value="RedCappi" type="submit">
				</form>
				<p class="wps-wcnc-notice">*This Feature Is Available With Premium Version*</p>
			</div>
		<?php } ?>
			<table class="wp-list-table widefat fixed striped users">
				<thead>
					<tr>
						<th scope="col" class="manage-column column-cb check-column">
							
						</th>
						<th scope="col" id="username" class="manage-column column-username column-primary">Name</th>
						<th scope="col" id="email" class="manage-column column-email">Email</th>
						<th id="wps_wcnc_actions" class="manage-column column-wps_wcnc_actions" scope="col">Actions</th>
					</tr>
				</thead>

				<tbody id="the-list" data-wp-lists="list:user">
					<?php if($customers){ ?>
						<?php $count = 1;?>
						<?php foreach ( $customers as $user ) { ?>
							
							<tr id="user-<?php echo $count; ?>">
								<th class="wps-wcnc-td check-column" scope="row"><?php echo $count; ?>.</th>
								<td class="wps-wcnc-td name column-name" data-colname="Name"><strong><?php echo esc_html( $user->display_name ); ?></strong></td>
								<td class="wps-wcnc-td email column-email" data-colname="Email"><a href="mailto:<?php echo esc_html( $user->user_email ); ?>"><?php echo esc_html( $user->user_email ); ?></a></td>
								<td id="wps_wcnc_actions">
									<form name="wps-wcnc-cust-<?php echo $count; ?>" id="wps-wcnc-cust-<?php echo $count; ?>" method="POST"  >
										<input type="hidden" name="action" value="wps_wcnc_user_export">
										<input type="hidden" name="wps_wcnc_user_email" value="<?php echo esc_html( $user->user_email ); ?>">
										<input type="hidden" name="wps_wcnc_user_first_name" value="<?php echo esc_html( $user->first_name ); ?>">
										<input type="hidden" name="wps_wcnc_user_last_name" value="<?php echo esc_html( $user->last_name ); ?>">
									</form>
									<a href="#" onclick="document.getElementById('wps-wcnc-cust-<?php echo $count; ?>').submit();">
										<img src="<?php echo WPS_WOO_CUST_NEWS_CAMP_IMG; ?>/export.png" width="10%" title="Import To Newsletter Campaigns" />
									</a>
								</td>
							</tr>
						
						<?php $count++; ?>
						<?php } ?>
					<?php }else{ ?>

						<tr><th></th><td>No Customer Found</td><td></td></tr>
					<?php } ?>

				</tbody>

				<tfoot>
					<tr>
						<th scope="col" class="manage-column column-cb check-column"></th>
						<th scope="col" class="manage-column column-username column-primary">Name</th>
						<th scope="col" class="manage-column column-email">Email</th>
						<th id="wps_wcnc_actions" class="manage-column column-wps_wcnc_actions" scope="col">Actions</th>
					</tr>
				</tfoot>
			</table>
		<?php
	}

	public function record_count(){
		 $args = array(
	        'role' => 'customer',
	        'orderby' => 'user_nicename',
	        'order' => 'ASC'
	      );
       $users = get_users($args);

		return count($users);
	}
	public function get_customers(){
		$args = array(
	        'role' => 'customer',
	        'orderby' => 'user_nicename',
	        'order' => 'ASC',
	      );
       $users = get_users($args);
       return $users;
	}

}

?>