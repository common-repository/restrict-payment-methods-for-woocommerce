<?php

/**
 * This class is loaded on the front-end since its main job is 
 * to display the Admin to box.
 */

class GMWRPM_Frontend {

	

	public function __construct () {

		
		add_filter( 'init', array( $this, 'initcut' ));
		add_filter( 'wp', array( $this, 'wpcut' ));
		 if ( ! is_admin() ) {
		 	add_filter('woocommerce_available_payment_gateways',array( $this, 'gmwrpm_ipayment_gateways' ), 9999, 1 );
		 }
		
	}

	public function wpcut(){
		
		
	}

	public function initcut(){

		
		add_action( 'wp_enqueue_scripts',  array( $this, 'gmwrpm_insta_scritps' ) );

	}

	public function gmwrpm_insta_scritps () {
		wp_enqueue_style('gmwrpm-stylee', GMWRPM_PLUGIN_URL . '/assets/css/style.css', array(), '1.0.0', 'all');
		wp_enqueue_script('gmwrpm-script', GMWRPM_PLUGIN_URL . '/assets/js/script.js', array(), '1.0.0', true );
		

	}

	public function gmwrpm_ipayment_gateways( $allowed_gateways ){

 		/*echo "<pre>";
 		print_r($allowed_gateways);
 		echo "</pre>";*/

 		$all_gateways = WC()->payment_gateways->payment_gateways();

 		$args = array(
		  'numberposts' => -1,
		  'post_type'   => 'payment_gateway'
		);
		 
		$payment_gateway = get_posts( $args );

		/*echo "<pre>";
 		print_r($payment_gateway);
 		echo "</pre>";*/
 		if(!empty($payment_gateway)){
 			foreach ($payment_gateway as $key_payment_gateway => $value_payment_gateway) {
 				 $gmwrpm_condintal_data = get_post_meta( $value_payment_gateway->ID,'gmwrpm_condintal_data', true);
 				 $gmwrpm_payment_data = get_post_meta( $value_payment_gateway->ID,'gmwrpm_payment_data', true);
 				 $is_condition = false;
 				 $countequ = 0;
 				 $count_total_e = count($gmwrpm_condintal_data['type']);
 				 if(!empty($gmwrpm_condintal_data)){
 					/*echo "<pre>";
			 		print_r($gmwrpm_condintal_data);
			 		echo "</pre>";*/
			 		
 				 	foreach ($gmwrpm_condintal_data['type'] as $key_gmwrpm_condintal_data => $value_gmwrpm_condintal_data) {
 				 		//for subtotal
 				 		if($gmwrpm_condintal_data['type'][$key_gmwrpm_condintal_data]=='subtotal'){
 				 			$cartsubtotal = WC()->cart->subtotal;
 				 			if($gmwrpm_condintal_data['equation'][$key_gmwrpm_condintal_data]=='gt'){
 				 				if($cartsubtotal > $gmwrpm_condintal_data['subtotal'][$key_gmwrpm_condintal_data]){
 				 					$countequ += 1;
 				 				}
 				 			}
 				 			if($gmwrpm_condintal_data['equation'][$key_gmwrpm_condintal_data]=='gte'){
 				 				if($cartsubtotal >= $gmwrpm_condintal_data['subtotal'][$key_gmwrpm_condintal_data]){
 				 					$countequ += 1;
 				 				}
 				 			}
 				 			if($gmwrpm_condintal_data['equation'][$key_gmwrpm_condintal_data]=='lt'){
 				 				if($cartsubtotal < $gmwrpm_condintal_data['subtotal'][$key_gmwrpm_condintal_data]){
 				 					$countequ += 1;
 				 				}
 				 			}
 				 			if($gmwrpm_condintal_data['equation'][$key_gmwrpm_condintal_data]=='lte'){
 				 				if($cartsubtotal <= $gmwrpm_condintal_data['subtotal'][$key_gmwrpm_condintal_data]){
 				 					$countequ += 1;
 				 				}
 				 			}
 				 		}
 				 		//for qty
 				 		if($gmwrpm_condintal_data['type'][$key_gmwrpm_condintal_data]=='qty'){
 				 			$cart_qty = WC()->cart->get_cart_contents_count();
 				 			if($gmwrpm_condintal_data['equation'][$key_gmwrpm_condintal_data]=='gt'){
 				 				if($cart_qty > $gmwrpm_condintal_data['qty'][$key_gmwrpm_condintal_data]){
 				 					$countequ += 1;
 				 				}
 				 			}
 				 			if($gmwrpm_condintal_data['equation'][$key_gmwrpm_condintal_data]=='gte'){
 				 				if($cart_qty >= $gmwrpm_condintal_data['qty'][$key_gmwrpm_condintal_data]){
 				 					$countequ += 1;
 				 				}
 				 			}
 				 			if($gmwrpm_condintal_data['equation'][$key_gmwrpm_condintal_data]=='lt'){
 				 				if($cart_qty < $gmwrpm_condintal_data['qty'][$key_gmwrpm_condintal_data]){
 				 					$countequ += 1;
 				 				}
 				 			}
 				 			if($gmwrpm_condintal_data['equation'][$key_gmwrpm_condintal_data]=='lte'){
 				 				if($cart_qty <= $gmwrpm_condintal_data['qty'][$key_gmwrpm_condintal_data]){
 				 					$countequ += 1;
 				 				}
 				 			}
 				 		}
 				 		//for product
 				 		if($gmwrpm_condintal_data['type'][$key_gmwrpm_condintal_data]=='products'){
 				 			$product_conis = explode(",",$gmwrpm_condintal_data['products'][$key_gmwrpm_condintal_data]);
 				 			if(!empty($product_conis)){

 				 				if($gmwrpm_condintal_data['equation'][$key_gmwrpm_condintal_data]=='is'){
 				 					if($this->gmwrpm_matched_cart_items( $product_conis ) > 0){
	 				 					$countequ += 1;
	 				 				}
 				 				}
 				 				if($gmwrpm_condintal_data['equation'][$key_gmwrpm_condintal_data]=='isnot'){
 				 					if($this->gmwrpm_matched_cart_items( $product_conis ) == 0){
	 				 					$countequ += 1;
	 				 				}
 				 				}
 				 				
 				 			}
 				 		}
 				 		//for shipping class
 				 		if($gmwrpm_condintal_data['type'][$key_gmwrpm_condintal_data]=='shipping_class'){
 				 			$product_conis = explode(",",$gmwrpm_condintal_data['shipping_class'][$key_gmwrpm_condintal_data]);
 				 			//print_r($this->gmwrpm_matched_cart_shipping_class( $product_conis ) );
 				 			if(!empty($product_conis)){

 				 				if($gmwrpm_condintal_data['equation'][$key_gmwrpm_condintal_data]=='is'){
 				 					if($this->gmwrpm_matched_cart_shipping_class( $product_conis ) > 0){
	 				 					$countequ += 1;
	 				 				}
 				 				}
 				 				if($gmwrpm_condintal_data['equation'][$key_gmwrpm_condintal_data]=='isnot'){
 				 					if($this->gmwrpm_matched_cart_shipping_class( $product_conis ) == 0){
	 				 					$countequ += 1;
	 				 				}
 				 				}
 				 				
 				 			}
 				 		}
 				 		
 				 		//for Shipping
 				 		if($gmwrpm_condintal_data['type'][$key_gmwrpm_condintal_data]=='shipping_method'){

 				 			$shipping_method_conis = explode(",",$gmwrpm_condintal_data['shipping_method'][$key_gmwrpm_condintal_data]);
 				 			if(!empty($shipping_method_conis)){

 				 				if(isset(WC()->session->get( 'chosen_shipping_methods' )[0])){
 				 					if($gmwrpm_condintal_data['equation'][$key_gmwrpm_condintal_data]=='is'){
 				 						
	 				 					if(in_array(WC()->session->get( 'chosen_shipping_methods' )[0], $shipping_method_conis)){
		 				 					$countequ += 1;
		 				 				}
	 				 				}
	 				 				if($gmwrpm_condintal_data['equation'][$key_gmwrpm_condintal_data]=='isnot'){
	 				 					if(in_array(WC()->session->get( 'chosen_shipping_methods' )[0], $shipping_method_conis)){
		 				 					$countequ += 1;
		 				 				}
	 				 				}
	 				 				
 				 				}
 				 				
 				 			}
 				 		}
 				 	}
 				 } 
 				 if(!empty($gmwrpm_payment_data) && $countequ == $count_total_e){
 				 	foreach ($gmwrpm_payment_data['type'] as $key_gmwrpm_payment_data => $value_gmwrpm_payment_data) {
 				 		/*echo "<pre>";
				 		print_r($gmwrpm_payment_data);
				 		echo "</pre>";*/

 				 		$methods_act = explode(",",$gmwrpm_payment_data['method'][$key_gmwrpm_payment_data]);
 				 		if(!empty($methods_act)){

 				 			foreach ($methods_act as $key_methods_act => $value_methods_act) {
 				 				if($gmwrpm_payment_data['type'][$key_gmwrpm_payment_data]=='enable'){
 				 					if(!isset( $allowed_gateways[$value_methods_act])){
 				 						$allowed_gateways[$value_methods_act] = $all_gateways[$value_methods_act];
 				 					}
 				 				}
 				 				if($gmwrpm_payment_data['type'][$key_gmwrpm_payment_data]=='disable'){
 				 					unset($allowed_gateways[$value_methods_act]);
 				 				}
 				 			}
 				 		}
 				 	}
 				 }
 			}
 		}

		return $allowed_gateways;
	 
	}

	public function gmwrpm_matched_cart_shipping_class( $shipping_class_ids_to_check ) {
	    $count = 0; // Initializing

	    if ( ! WC()->cart->is_empty() ) {
	        // Loop though cart items
	        foreach(WC()->cart->get_cart() as $cart_item ) {
	           /*echo $cart_item['data']->get_shipping_class_id();*/
	            if (in_array($cart_item['data']->get_shipping_class_id(), $shipping_class_ids_to_check)) {
	                $count++; // incrementing items count
	            }
	        }
	    }
	    return $count; // returning matched items count 
	}
	
	public function gmwrpm_matched_cart_items( $search_products ) {
	    $count = 0; // Initializing

	    if ( ! WC()->cart->is_empty() ) {
	        // Loop though cart items
	        foreach(WC()->cart->get_cart() as $cart_item ) {
	            // Handling also variable products and their products variations
	            $cart_item_ids = array($cart_item['product_id'], $cart_item['variation_id']);

	            // Handle a simple product Id (int or string) or an array of product Ids 
	            if( ( is_array($search_products) && array_intersect($search_products, $cart_item_ids) ) || ( !is_array($search_products) && in_array($search_products, $cart_item_ids))){
	                $count++; // incrementing items count
	            }
	        }
	    }
	    return $count; // returning matched items count 
	}
	
	
}


 

?>