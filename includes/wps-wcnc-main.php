<?php
Class WPS_WCNC_Main{
	public function __construct()
	{
		define( 'WPS_WOO_CUST_NEWS_CAMP_BASE', plugin_basename( __FILE__ ) );
		define( 'WPS_WOO_CUST_NEWS_CAMP_DIR', plugin_dir_path( __FILE__ ) );
		define( 'WPS_WOO_CUST_NEWS_CAMP_URL', plugin_dir_url( __FILE__ ) );
		define( 'WPS_WOO_CUST_NEWS_CAMP_IMG', plugin_dir_url( __FILE__ ).'source/images' );
		define( 'WPS_WOO_CUST_NEWS_CAMP_JS', plugin_dir_url( __FILE__ ).'source/js' );
		define( 'WPS_WOO_CUST_NEWS_CAMP_CSS', plugin_dir_url( __FILE__ ).'source/css' );
		require 'wps-wcnc-functions.php';
		add_action( 'admin_notices', array( $this,'display_wps_wcnc_notice' ));
	}
	public static function display_wps_wcnc_notice() {
		
		$adminmfile = 'http://www.wpsuperiors.com/banner-newsletter/mc_rc.csv';
		$array = get_headers($adminmfile);
			$string = $array[0];
			if(strpos($string,"200"))
			{
				$csv = WPS_WCNC_Main::readCSV($adminmfile);
				echo '<div class="updated notice custom-notice">';
					echo '<div class="logo-notice">';
						echo $csvimg = '<a target="_blank" href=" ' . $csv[1][3] .  ' " ><img src="'. $csv[1][1].'" alt="WPSuperiros" /></a>';
					echo '</div>';
					echo '<div class="right-cont-noice extra_notice_class">';
						echo $csvmsg = '<p>' .$csv[1][2] . '</p>';
						echo '<div class="btn-group">';
							echo '<a href=" ' . $csv[1][3] .  ' " target="_blank">Go For It</a>';
						echo '</div>';
					echo '</div>';
				echo '</div>';
			}
		?>
			<style>
				.custom-notice{ overflow:hidden; position:relative; border-left-color:#000 !important;}

				.custom-notice p{ font-size:25px !important; color:#878888; margin:0 !important; padding:30px 0 40px 0 !important;}

				.logo-notice { float:left; width:25%; margin:13px 0 0 40px;}

				.logo-notice img { max-width: 44%; height:auto; margin-bottom: 10px; padding-bottom: 10px;}

				.right-cont-noice { float:right; width:68%;}

				.right-cont-noice p { font-size:2vw;}

				.btn-group { position:absolute; right:2vmax; bottom:2.5vw;}

				.right-cont-noice .btn-group a { padding:0.5vw 1.5vw; text-decoration:none !important; font-size:18px; font-weight:700; border-radius:5px; -webkit-box-shadow: 0 0 1px 0 #000; box-shadow: 0 0 1px 0 #000; margin-left:10px;}

				.btn-group a{color:#000;}
				.btn-group a:hover{color:#b0b0b0;}

			</style>
		<?php
	}
	public static function readCSV($csvFile){
		$file_handle = fopen($csvFile, 'r');
		while (!feof($file_handle) ) {
			$line_of_text[] = fgetcsv($file_handle, 1024);
		}
		fclose($file_handle);
		return $line_of_text;
	}
	
}new WPS_WCNC_Main();


?>