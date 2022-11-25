jQuery(document).ready(function () {
  jQuery("#edit-username").blur(function() {
        var username = jQuery("#edit-username").val();
	target = jQuery("#username_response");
	targetUrl = Drupal.settings.basePath + "js/example/somefunction/" + username;
	 jQuery.ajax({
	  url: targetUrl,
	  type: "GET",
	  success: function(data) {
		target.html(data);
	  }
	 });
	 return false;
  });
});