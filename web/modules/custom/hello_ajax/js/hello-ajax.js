/**
 * @file
 * Getting the server time via AJAX.
 */

(function ($, Drupal) {

  'use strict';

  /**
   * Replaces content of hello Ajax block with response from Ajax call.
   */
  Drupal.behaviors.helloAjaxGetTime = {
    attach: function () {

      $.ajax({
        url: Drupal.url('hello-ajax-response'),
        type: 'POST',
        dataType: 'json',
        success: function (response) {
          if (response.hasOwnProperty('time')) {
            $(".block-hello-ajax .content").text(response.time);
          }
        }
      });
    }
  };

})(jQuery, Drupal);
