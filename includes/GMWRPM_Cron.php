<?php

class GMWRPM_Cron {
	
	public function __construct () {

		add_action( 'init', array( $this, 'GMWRPM_default' ) );
		
	}

	public function GMWRPM_default(){
		/*$defalarr = array(
			'gmwrpm_enable' => 'yes',
			'gmwrpm_hide_unpurchase' => 'yes',
			'gmwrpm_showimg' => 'yes',
			'gmwrpm_showdesc' => 'yes',
		);
		
		foreach ($defalarr as $keya => $valuea) {
			if (get_option( $keya )=='') {
				update_option( $keya, sanitize_text_field($valuea) );
			}
			
		}*/
		
	}
}

?>