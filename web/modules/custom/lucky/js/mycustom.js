(function ($, Drupal, drupalSettings) {

  var Request = {
    List: [],
    AbortAll: function () {
        var _self = this;
        $.each(_self.List, (i, v) => {
            v.abort();
        });
    }
  }
  // alert(Request);

Drupal.behaviors.mybehavior = {
  attach: function (context, settings) {
    
    // $('#some_element', context).once('mybehavior', function () {
    //   // Code here will only be applied to $('#some_element')
    //   // a single time.
    // });

		//getcategorymaster code
    jQuery("#edit-field-list-fo-scheme-details-0-subform-field-getcategorymaster").change(function () {
      var getcategorymaster = jQuery('#edit-field-list-fo-scheme-details-0-subform-field-getcategorymaster option:selected').text();
      
      var base_path = drupalSettings.path.baseUrl;
      // alert(base_path);
      jQuery.ajax({
      type:'POST',
      url: base_path + "getschememaster/ajax/" + getcategorymaster,
      data: {getcategorymaster:getcategorymaster},
      
      success:function(result) {
      jQuery('#edit-field-list-fo-scheme-details-0-subform-field-getschememaster').empty();
      console.log("Getschememaster: "+result);
      var jsonData = jQuery.parseJSON(result);
      var select = jQuery('#edit-field-list-fo-scheme-details-0-subform-field-getschememaster');
      var None_option = jQuery("<option/>").attr("value", '').text('--None--');
      select.append(None_option);
      jQuery(jsonData).each(function (index, o) {    
      var option = jQuery("<option/>").attr("value", o).text(o);
      select.append(option);
      });
      }
    });

      // jQuery('#edit-field-list-fo-scheme-details-0-subform-field-getcategorymaster').val('');
      jQuery('#edit-field-list-fo-scheme-details-0-subform-field-getschememaster').val('');
      jQuery('#edit-field-list-fo-scheme-details-0-subform-field-getplanoptions').val('');
    

  });

  		// getschememaster code
      jQuery("#edit-field-list-fo-scheme-details-0-subform-field-getschememaster").change(function () {
				var base_path = drupalSettings.path.baseUrl;
        var getplanoption = jQuery('#edit-field-list-fo-scheme-details-0-subform-field-getschememaster option:selected').text();
				jQuery.ajax({
				type:'POST',
        url: base_path + "getplanoptions/ajax/" + getplanoption,
        data: {getplanoption:getplanoption},
				success:function(result) {
				jQuery('#edit-field-list-fo-scheme-details-0-subform-field-getplanoptions').empty();
				console.log("getplanoptions : "+result);
				var getplanoptionData = jQuery.parseJSON(result);

        var select = jQuery('#edit-field-list-fo-scheme-details-0-subform-field-getplanoptions');
        var None_option = jQuery("<option/>").attr("value", '').text('--None--');
        select.append(None_option);
        jQuery(getplanoptionData).each(function (index, o) {    
        var option = jQuery("<option/>").attr("value", o).text(o);
        select.append(option);
        });
				
				}
			});

		});
		
		jQuery('#edit-field-list-fo-scheme-details-0-subform-field-getschememaster').keyup(function() {
			//alert(jQuery(this).val()); 
			jQuery('#edit-field-list-fo-scheme-details-0-subform-field-getplanoptions').val(jQuery(this).val());
		    });  
  }
};

})(jQuery, Drupal, drupalSettings);

