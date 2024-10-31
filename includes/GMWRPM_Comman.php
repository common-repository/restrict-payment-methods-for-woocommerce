<?php

class GMWRPM_Comman {
	
	public function __construct () {
		add_action( 'init', array($this,'GMWRPM_default') );

		add_action( 'wp_ajax_nopriv_GMWRPM_get_product',array($this, 'GMWRPM_get_product') );
        add_action( 'wp_ajax_GMWRPM_get_product', array($this, 'GMWRPM_get_product') );


        add_action( 'edit_post', array($this, 'GMWRPM_meta_save'), 10, 2);
	}

	public function GMWRPM_default(){
		
	}

	public function GMWRPM_get_product(){
		$return = array();
		$search_results = new WP_Query( array( 
            'post_type' => 'product',
            's'=> sanitize_text_field($_GET['q']),
            'post_status' => 'publish',
            'ignore_sticky_posts' => 1,
            'posts_per_page' => 50
        ) );
        if( $search_results->have_posts() ) :
            while( $search_results->have_posts() ) : $search_results->the_post();   
                $title = ( mb_strlen( $search_results->post->post_title ) > 50 ) ? mb_substr( $search_results->post->post_title, 0, 49 ) . '...' : $search_results->post->post_title;
                $return[] = array( $search_results->post->ID, $title );
            endwhile;
        endif;
        echo json_encode( $return );
        die;
	}
    public function GMWRPM_meta_save( $post_id, $post ) {
         
            if ($post->post_type != 'payment_gateway') { return; }

            if ( !current_user_can( 'edit_post', $post_id )) return;

            $gmwrpm_condintal_data = $this->GMWRPM_recursive( $_REQUEST['gmwrpm_condintal_data'] );
            update_post_meta( $post_id, 'gmwrpm_condintal_data', $gmwrpm_condintal_data);
            $gmwrpm_payment_data = $this->GMWRPM_recursive( $_REQUEST['gmwrpm_payment_data'] );
            update_post_meta( $post_id, 'gmwrpm_payment_data', $gmwrpm_payment_data);
           

          
    }
    public function GMWRPM_recursive($array) {
            if(!empty($array)) {
                foreach ( $array as $key => &$value ) {
                    if ( is_array( $value ) ) {
                        $value = $this->GMWRPM_recursive($value);
                    } else {
                        $value = sanitize_text_field( $value );
                    }
                }
            }
            return $array;
    }
}

?>