<?php
$gmwrpm_enable = get_option( 'gmwrpm_enable' );
$gmwrpm_hide_unpurchase = get_option( 'gmwrpm_hide_unpurchase' );
$gmwrpm_layout = get_option( 'gmwrpm_layout' );
$gmwrpm_showchoose = get_option( 'gmwrpm_showchoose' );
$gmwrpm_showimg = get_option( 'gmwrpm_showimg' );
$gmwrpm_showdesc = get_option( 'gmwrpm_showdesc' );

?>
<form method="post" action="options.php">
	<?php settings_fields( 'gmwrpm_general_options_group' ); ?>
	<table class="form-table">
    <tr>
      <th scope="row"><label><?php _e('Enable', 'gmwrpm'); ?></label></th>
      <td>
        <input type="radio" name="gmwrpm_enable" <?php echo ($gmwrpm_enable=='yes')?'checked':''; ?> value="yes"><?php _e('Yes', 'gmwrpm'); ?>
        <input type="radio" name="gmwrpm_enable" <?php echo ($gmwrpm_enable=='no')?'checked':''; ?> value="no"><?php _e('No', 'gmwrpm'); ?>
       
      </td>
    <!-- </tr>
     <tr>
      <th scope="row"><label><?php _e('Hide unpurchasable variation', 'gmwrpm'); ?></label></th>
      <td>
        <input type="radio" name="gmwrpm_hide_unpurchase" <?php echo ($gmwrpm_hide_unpurchase=='yes')?'checked':''; ?> value="yes"><?php _e('Yes', 'gmwrpm'); ?>
        <input type="radio" name="gmwrpm_hide_unpurchase" <?php echo ($gmwrpm_hide_unpurchase=='no')?'checked':''; ?> value="no"><?php _e('No', 'gmwrpm'); ?>
       
      </td>
    </tr> -->
    <!-- <tr>
      <th scope="row"><label><?php _e('Selection Layout', 'gmwrpm'); ?></label></th>
      <td>
        <input type="radio" name="gmwrpm_layout" <?php echo ($gmwrpm_layout=='radio')?'checked':''; ?> value="radio"><?php _e('Radio', 'gmwrpm'); ?>
        <input type="radio" name="gmwrpm_layout" <?php echo ($gmwrpm_layout=='html_select')?'checked':''; ?> value="html_select"><?php _e('Html Select', 'gmwrpm'); ?>
        <input type="radio" name="gmwrpm_layout" <?php echo ($gmwrpm_layout=='select2')?'checked':''; ?> value="select2"><?php _e('Select2', 'gmwrpm'); ?>
        <input type="radio" name="gmwrpm_layout" <?php echo ($gmwrpm_layout=='ddslick')?'checked':''; ?> value="ddslick"><?php _e('DDSlick', 'gmwrpm'); ?>
       
      </td>
    </tr> -->
     <!-- <tr>
      <th scope="row"><label><?php _e('Show "Choose an option"', 'gmwrpm'); ?></label></th>
      <td>
        <input type="radio" name="gmwrpm_showchoose" <?php echo ($gmwrpm_showchoose=='yes')?'checked':''; ?> value="yes"><?php _e('Yes', 'gmwrpm'); ?>
        <input type="radio" name="gmwrpm_showchoose" <?php echo ($gmwrpm_showchoose=='no')?'checked':''; ?> value="no"><?php _e('No', 'gmwrpm'); ?>
      </td>
    </tr> -->
    <tr>
      <th scope="row"><label><?php _e('Show Image', 'gmwrpm'); ?></label></th>
      <td>
        <input type="radio" name="gmwrpm_showimg" <?php echo ($gmwrpm_showimg=='yes')?'checked':''; ?> value="yes"><?php _e('Yes', 'gmwrpm'); ?>
        <input type="radio" name="gmwrpm_showimg" <?php echo ($gmwrpm_showimg=='no')?'checked':''; ?> value="no"><?php _e('No', 'gmwrpm'); ?>
      </td>
    </tr>
    <tr>
      <th scope="row"><label><?php _e('Show Description', 'gmwrpm'); ?></label></th>
      <td>
        <input type="radio" name="gmwrpm_showdesc" <?php echo ($gmwrpm_showdesc=='yes')?'checked':''; ?> value="yes"><?php _e('Yes', 'gmwrpm'); ?>
        <input type="radio" name="gmwrpm_showdesc" <?php echo ($gmwrpm_showdesc=='no')?'checked':''; ?> value="no"><?php _e('No', 'gmwrpm'); ?>
      </td>
    </tr>
  </table>
	<?php  submit_button(); ?>
</form>