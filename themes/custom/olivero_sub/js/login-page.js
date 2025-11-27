/**
 * @file
 * JavaScript for login page.
 */

(function ($, Drupal) {
  'use strict';

  Drupal.behaviors.loginPage = {
    attach: function (context, settings) {
      // Add any custom JavaScript for the login page here
      // For example, auto-focus on the email field
      $(context).find('#todo-input, #edit-email').once('login-page-focus').focus();
    }
  };

})(jQuery, Drupal);
