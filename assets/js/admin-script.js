jQuery( document ).ready(function() {
	
 		jQuery("body").on("change", ".condintal_data_type", function(){
 			
 		//jQuery(".condintal_data_type").change(function(){
			var inpuarray = { 
								gt : 'greater than', 
								gte: 'greater than or equal', 
								lt : 'less than', 
								lte: 'less than or equal',
								in: 'Include',
								notin: 'Exclude',
								is: 'is',
								isnot: 'is not'
							};

			var datatypeget = JSON.parse(jQuery('option:selected', this).attr('data_operator'));
			var att_value = jQuery(this).closest("tr").find(".opt_equation select").attr('att_value');
			var optionhtml = '';
			jQuery.each(datatypeget, function(index, value) {
		     
		        optionhtml += '<option value="' + value + '" ' + (att_value == value ? 'selected' : '') + '>' + inpuarray[value] + '</option>';
		    });
			jQuery(this).closest("tr").find(".opt_equation select").html(optionhtml);
			jQuery(this).closest("tr").find(".commainclas").hide();
			   console.log(".gtcont_"+jQuery(this).val());
			jQuery(this).closest("tr").find(".gtcont_"+jQuery(this).val()).show();
			refersjcselect2();
		  //return false;
		});	
		jQuery("body").on("change", ".gmwrpm_product_mul", function(){
			jQuery(this).closest("tr").find(".gmwrpm_product_mul_hidden").val(jQuery(this).val().join(','));
		});
		jQuery("body").on("change", ".gtcont_type_multiselect select", function(){
			jQuery("."+jQuery(this).attr("target")).val(jQuery(this).val().join(','));
		});
		
		
		jQuery("body").on("change", ".gmwrpm_pmmethod_mul", function(){
			jQuery(this).closest("tr").find(".gmwrpm_pmmethod_mulhidden").val(jQuery(this).val().join(','));
		});
		jQuery("#gmwrpm-add-condition").click(function(){
			jQuery(".gmwrpm_condintal_meta tbody").append(jQuery("#condition_scriphtml").html());
			return false
		});
		jQuery("#gmwrpm-add-method").click(function(){
			jQuery(".gmwrpm_paymentmethod_meta tbody").append(jQuery("#payment_scriphtml").html());
			refersjcselect2();
			return false
		});
		
		jQuery("body").on("click", ".gmwrpm-remove-conditions", function(){
			jQuery(this).closest("tr").remove();
			return false;
		});

		jQuery("body").on("click", ".gmwrpm-remove-payment", function(){
			jQuery(this).closest("tr").remove();
			return false;
		});

		jQuery( ".gmwrpm_condintal_meta tbody tr" ).each(function( index ) {
			jQuery(this).find(".condintal_data_type").change();

		});
			
		refersjcselect2();
		

});
function refersjcselect2(){
	jQuery('.gmwrpm_select_ref').select2({
	});
	
	jQuery('.gmwrpm_product_mul').select2({
        ajax: {
                url: ajaxurl,
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        q: params.term,
                        action: 'GMWRPM_get_product'
                    };
                },
                processResults: function( data ) {
                var options = [];
                if ( data ) {
 
                    jQuery.each( data, function( index, text ) {
                        options.push( { id: text[0], text: text[1]  } );
                    });
 
                }
                return {
                    results: options
                };
            },
            cache: true
        },

        minimumInputLength: 3
    })

}