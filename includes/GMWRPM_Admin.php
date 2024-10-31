<?php

/**
 * This class is loaded on the back-end since its main job is 
 * to display the Admin to box.
 */

class GMWRPM_Admin {
    public $GMWRPM_config = array();
    public $GMWRPM_operation = array(
        "equation"=> array("gt","gte","lt","lte"),
        "ie"=> array("in","notin")
    );
	
	public function __construct () {

		


		
		add_action('admin_enqueue_scripts', array( $this, 'GMWRPM_admin_script' ),1000);
		add_action( 'init', array( $this, 'GMWRPM_init' ) );
		add_action( 'add_meta_boxes', array($this, 'GMWRPM_add_meta_box'));
		
		
	}

	public function GMWRPM_admin_script () {
		wp_enqueue_style('gmwrpm_admin_css', GMWRPM_PLUGIN_URL.'assets/css/admin-style.css');
	  wp_enqueue_style( 'gmwrpm_select2_css' , GMWRPM_PLUGIN_URL.'assets/js/select2/select2.css');
	   wp_enqueue_script('gmwrpm_select2_js', GMWRPM_PLUGIN_URL.'assets/js/select2/select2.js',array('jquery'));

		wp_enqueue_script( 'wp-color-picker' ); 
		wp_enqueue_script('gmwrpm_admin_js', GMWRPM_PLUGIN_URL.'assets/js/admin-script.js');
	}

	public function GMWRPM_init () {
		
		$args = array(
	        'public' => true,
	        'label'  => __( 'Payment Gateway Rules', 'gmwrpm' ),
	        'supports'  => array( 'title' ),
	    );
	    register_post_type( 'payment_gateway', $args );
           
            
            $this->GMWRPM_config = array();
            $this->GMWRPM_config[] = array(
                "label"=>"Order Subtotal",
                "key"=>"subtotal",
                "operation"=>"equation",
                "value_type"=>"number",
            );
            $this->GMWRPM_config[] = array(
                "label"=>"Shipping Method",
                "key"=>"shipping_method",
                "operation"=>"ie",
                "value_type"=>"multiselect",
                "value"=>$this->GMWRPM_shipping(),
            );
            $this->GMWRPM_config[] = array(
                "label"=>"Products",
                "key"=>"products",
                "operation"=>"ie",
                "value"=>"custom",
                "value_type"=>"products",
            );
            $this->GMWRPM_config[] = array(
                "label"=>"Total Qty",
                "key"=>"qty",
                "operation"=>"number",
                "operation"=>"equation",
                "value_type"=>"number",
            );
            $shipping_classes = WC()->shipping->get_shipping_classes();
            $shipping_classes_arr = array();
            if (!empty($shipping_classes)) {
                foreach ($shipping_classes as $shipping_class) {
                    $shipping_classes_arr[]= array(
                        "label"=>$shipping_class->name,
                        "value"=>$shipping_class->term_id,
                        "custom_att"=>$shipping_class
                    );
                }

            }
            $this->GMWRPM_config[] = array(
                "label"=>"Shipping Class",
                "key"=>"shipping_class",
                "operation"=>"ie",
                "value_type"=>"multiselect",
                "value"=>$shipping_classes_arr,
            );

	}

	public function GMWRPM_add_meta_box() {
            add_meta_box(
                'GMWRPM_metabox',
                __( 'Payment Gateway Rules Settings', 'gmwrpm' ),
                array($this, 'GMWRPM_metabox_rule'),
                'payment_gateway',
                'normal'
            );
            add_meta_box(
                'GMWRPM_payment_metabox',
                __( 'Payment Method Settings', 'gmwrpm' ),
                array($this, 'GMWRPM_metabox_paymentmethod'),
                'payment_gateway',
                'normal'
            );
   }

   public function GMWRPM_metabox_rule( $post ) {
      $gmwrpm_condintal_data = get_post_meta( $post->ID,'gmwrpm_condintal_data', true); 
      
   	?>
   		<div class="gmwrpm_condintal_meta">
   			<table>
   				<tbody>
   					    <?php
                  if(!empty($gmwrpm_condintal_data['type'])){
                     foreach ($gmwrpm_condintal_data['type'] as $key => $value) {
                        $this->GMWRPM_comman_html( 'exist' ,$key,$gmwrpm_condintal_data);
                     }
                  }
                  ?>
   				</tbody>
   				<tfoot>
   					<tr>
							<td colspan="4" >
								<button type="button" class="button" id="gmwrpm-add-condition">Add Condition</button>
								
							</td>
						</tr>
   				</tfoot>
   			</table>
   			
   		</div>
         
      <script type="text/html" id="condition_scriphtml">

   			  <?php
            $this->GMWRPM_comman_html( 'fresh' );
            ?>
   		</script>
   	<?php
   }

   public function GMWRPM_metabox_paymentmethod( $post ) {

     $gmwrpm_payment_data = get_post_meta( $post->ID,'gmwrpm_payment_data', true); 
     /*echo "<pre>";
     print_r($gmwrpm_payment_data);
     echo "</pre>";*/
      ?>
      <div class="gmwrpm_paymentmethod_meta">
        <table>
          <tbody>
                  <?php
                  if(!empty($gmwrpm_payment_data['type'])){
                     foreach ($gmwrpm_payment_data['type'] as $key => $value) {
                        $this->GMWRPM_payment_comman_html( 'exist' ,$key,$gmwrpm_payment_data);
                     }
                  }
                  ?>
          </tbody>
          <tfoot>
            <tr>
              <td colspan="4" >
                <button type="button" class="button" id="gmwrpm-add-method">Add Method</button>
                
              </td>
            </tr>
          </tfoot>
        </table>
      </div>
      <script type="text/html" id="payment_scriphtml">
          <?php
            $this->GMWRPM_payment_comman_html( 'fresh' );
            ?>
      </script>
      <?php
   }
	
    public function GMWRPM_comman_html( $type , $key=0,$arr=array()) {
       /* echo "<pre>";
        print_r($arr);
        echo "</pre>";*/

      /*  echo "<pre>";
        print_r($this->GMWRPM_config);
        echo "</pre>";*/
      ?>
      <tr>
         <td class="opt_type">
            <select name="gmwrpm_condintal_data[type][]" class="condintal_data_type">
               <?php 
               foreach($this->GMWRPM_config as $GMWRPM_config_key=>$GMWRPM_config_val){
                ?>
                <option 
                value="<?php echo $GMWRPM_config_val['key']?>" 
                <?php echo (isset($arr['type'][$key]))?$this->GMWRPM_cheselcted($arr['type'][$key],$GMWRPM_config_val['key']):''; ?>
                data_operator='<?php echo json_encode($this->GMWRPM_operation[$GMWRPM_config_val['operation']]);?>'>
                    <?php echo $GMWRPM_config_val['label']?>
                </option>
                <?php
               }
               ?>
               
            </select>
         </td>
         <td class="opt_equation">
            <select name="gmwrpm_condintal_data[equation][]" >
               <option value="gt" <?php echo (isset($arr['equation'][$key]))?$this->GMWRPM_cheselcted($arr['equation'][$key],'gt'):''; ?>>greater than</option>
               <option value="gte" <?php echo (isset($arr['equation'][$key]))?$this->GMWRPM_cheselcted($arr['equation'][$key],'gte'):''; ?>>greater than or equal</option>
               <option value="lt" <?php echo (isset($arr['equation'][$key]))?$this->GMWRPM_cheselcted($arr['equation'][$key],'lt'):''; ?>>less than</option>
               <option value="lte" <?php echo (isset($arr['equation'][$key]))?$this->GMWRPM_cheselcted($arr['equation'][$key],'lte'):''; ?>>less than or equal</option>
            </select>
         </td>
         <td class="opt_value">
            <?php 
            foreach($this->GMWRPM_config as $GMWRPM_config_key=>$GMWRPM_config_val){
                $GMWRPM_key=$GMWRPM_config_val['key'];
            ?>
           <div 
           class="gtcont_<?php echo $GMWRPM_key;?> commainclas gtcont_type_<?php echo $GMWRPM_config_val['value_type'];?>" 
           style="<?php echo ($GMWRPM_key=='subtotal')?'display:block':'';?>"
           >
               <?php
               if($GMWRPM_config_val['value_type']=='number'){
               ?>
               <input 
               class="input-text" 
               type="number" 
               name="gmwrpm_condintal_data[<?php echo $GMWRPM_key;?>][]" 
               value="<?php echo (isset($arr['subtotal'][$key]))?esc_attr($arr[$GMWRPM_key][$key]):'';?>
               "/>
               <?php
               }
               if($GMWRPM_config_val['value_type']=='multiselect'){
               ?>

               <input type="hidden"  name="gmwrpm_condintal_data[<?php echo $GMWRPM_key;?>][]" class="gmwrpm_<?php echo $GMWRPM_key;?>_hidden"  value="<?php echo (isset($arr[$GMWRPM_key][$key]))?esc_attr($arr[$GMWRPM_key][$key]):'';?>"/>
               <select class="gmwrpm_<?php echo $GMWRPM_key;?> gmwrpm_select_ref" target='gmwrpm_<?php echo $GMWRPM_key;?>_hidden' multiple="multiple" style="width:250px;">
                    <?php
                    if(!empty($GMWRPM_config_val['value'])){
                        foreach ($GMWRPM_config_val['value'] as $key_select => $value_select) {
                            if(isset($value_select['is_optgroup']) && $value_select['is_optgroup']=='yes'){
                                echo "<optgroup label='" . esc_attr($value_select['optgroup_name']) . "'>";
                                foreach ($value_select['values'] as $key_option => $value_option) {
                                    echo "<option value='" . esc_attr($value_option['value']) . "' " . ((!empty($arr['shipping_method'][$key])) ? $this->GMWRPM_cheselcted($arr[$GMWRPM_key][$key], $value_option['value']) : '') . ">" . esc_attr($value_option['label']) . "</option>";
                                }
                                echo "</optgroup>";
                            }else{

                                echo "<option value='" . esc_attr($value_select['value']) . "' " . ((!empty($arr['shipping_method'][$key])) ? $this->GMWRPM_cheselcted($arr[$GMWRPM_key][$key], $value_select['value']) : '') . ">" . esc_attr($value_select['label']) . "</option>";
                               
                            }
                        }

                    }
                   
                    ?>
                </select>

            
                <?php
               }
                if($GMWRPM_config_val['value_type']=='products'){
                    ?>
                     <input type="hidden"  name="gmwrpm_condintal_data[products][]" class="gmwrpm_product_mul_hidden" value="<?php echo (isset($arr['products'][$key]))?esc_attr($arr['products'][$key]):'';?>"/>
               <select class="gmwrpm_product_mul"   multiple="multiple" style="width:250px;">
                  <?php
                   if(isset($arr['products'][$key])){
                     $gmwrpm_product_mul = explode(",",$arr['products'][$key]);
                     foreach ($gmwrpm_product_mul as $key_gmwrpm_product_mul => $value_gmwrpm_product_mul) {
                        if($value_gmwrpm_product_mul!=''){
                        $product_gt = get_post($value_gmwrpm_product_mul);
                        echo "<option value='".esc_attr($value_gmwrpm_product_mul)."' selected>".esc_attr($product_gt->post_title)."</option>";
                        }
                     }
                   }
                  ?>
               </select> 
                    <?php
                }
               ?>
            </div>
            <?php
            }
            ?>
            
           

         </td>
         <td>
            <button type="button" class="button gmwrpm-remove-conditions" >Remove Selected</button>
         </td>
      </tr>
      <?php
    }
    public function GMWRPM_payment_comman_html( $type , $key=0,$arr=array()) {
      ?>
      <tr>
         <td class="pmt_opt_type">
            <select name="gmwrpm_payment_data[type][]" class="payment_data_type">
               <option value="enable" <?php echo (isset($arr['type'][$key]))?$this->GMWRPM_cheselcted($arr['type'][$key],'enable'):''; ?> >Enable</option>
               <option value="disable"  <?php echo (isset($arr['type'][$key]))?$this->GMWRPM_cheselcted($arr['type'][$key],'disable'):''; ?>>Disable</option>
           </select>
         </td>
        
         <td class="pmt_opt_value">
            
            <div class="gtcont_method ">
               <input type="hidden"  name="gmwrpm_payment_data[method][]" class="gmwrpm_pmmethod_mulhidden"  value="<?php echo (isset($arr['method'][$key]))?$arr['method'][$key]:'';?>"/>
               <select class="gmwrpm_pmmethod_mul gmwrpm_select_ref" multiple="multiple" style="width:250px;">
                <?php
                // Get the selected payment methods from the saved array
                $selected_methods = (!empty($arr['method']) && is_array($arr['method'])) ? $arr['method'] : array();

                // Loop through each payment gateway
                foreach (WC()->payment_gateways->payment_gateways() as $key_payment => $value_payment) {
                    // Check if the current payment gateway is selected
                    $selected = in_array($key_payment, $selected_methods) ? 'selected="selected"' : '';
                    // Output the option tag
                    echo "<option value='" . esc_attr($key_payment) . "' " . $selected . ">" . esc_attr($value_payment->method_title) . "</option>";
                }
                ?>
                </select>

            </div>
         </td>
         <td>
            <button type="button" class="button gmwrpm-remove-payment" >Remove Selected</button>
         </td>
      </tr>
      <?php
    }
	
	public function GMWRPM_cheselcted($dymic,$static,$isarray = ''){
           // echo $dymic ." " .$static;
         if($isarray =='array'){
            $dymicarr = explode(",",$dymic);
            if (in_array($static, $dymicarr)){
                return 'selected';
            }
         }else{
            if($dymic==$static){ 
                
               return ' selected ';
            }
         }
     
   }

   public function GMWRPM_shipping(){
    $shipping_zones = array( new WC_Shipping_Zone( 0 ) );
    $shipping_zones = array_merge( $shipping_zones, WC_Shipping_Zones::get_zones() );
    $options = array();

    foreach ( $shipping_zones as $shipping_zone ) {
      if ( is_array( $shipping_zone ) && isset( $shipping_zone['zone_id'] ) ) {
        $shipping_zone = WC_Shipping_Zones::get_zone( $shipping_zone['zone_id'] );
      } else if ( ! is_object( $shipping_zone ) ) {
        // Skip
        continue;
      }

      $methods = array();
      foreach ( $shipping_zone->get_shipping_methods() as $shipping_method ) {
        if ( method_exists( $shipping_method, 'get_rate_id' ) ) {
          $methods[] = array(
            'label'=>$shipping_method->title,
            'value'=>$shipping_method->get_rate_id(),
            'custom_att'=> array(
                'title' => $shipping_method->title,
                'rate_id' => $shipping_method->get_rate_id(),
                'instance_id' => $shipping_method->get_instance_id(),
                'combined_id' => implode( '&', array( $shipping_method->get_rate_id(), $shipping_method->get_instance_id()) ),
            )
          );
        }
      }

      if ( ! empty( $methods ) ) {
        $options[$shipping_zone->get_id()] = array(
          'is_optgroup' => 'yes',  
          'optgroup_name' => $shipping_zone->get_zone_name(),
          'values' => $methods,
        );
      }
    }
    return $options;
   }
}

?>