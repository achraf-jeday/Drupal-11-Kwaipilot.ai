/**
 * @file
 * JavaScript for login page.
 */

(function ($, Drupal) {
  'use strict';

  Drupal.behaviors.loginPage = {
    attach: function (context, settings) {
      // Auto-focus on the username field when page loads
      $(context).find('#edit-username').once('login-page-focus').focus();

      // Add form validation and visual feedback
      const $form = $(context).find('.empty-module-login-form');
      const $username = $(context).find('#edit-username');
      const $password = $(context).find('#edit-password');
      const $submitBtn = $(context).find('#edit-submit');
      const $createAccountBtn = $(context).find('#edit-create-account');

      // Fix ID collision by ensuring unique IDs
      if ($createAccountBtn.length && $submitBtn.length) {
        // The create account link and submit button have different IDs now
        // but we need to ensure no conflicts in event handling
        $createAccountBtn.attr('data-btn-type', 'create-account');
        $submitBtn.attr('data-btn-type', 'submit-login');
      }

      // Form validation on submit
      $form.once('login-page-validation').on('submit', function(e) {
        let isValid = true;

        // Clear previous error states
        $username.removeClass('error');
        $password.removeClass('error');

        // Validate username
        if (!$username.val().trim()) {
          $username.addClass('error');
          isValid = false;
        }

        // Validate password
        if (!$password.val().trim()) {
          $password.addClass('error');
          isValid = false;
        }

        if (!isValid) {
          e.preventDefault();

          // Add visual feedback for validation errors
          $form.addClass('has-validation-error');

          // Remove error class after a short delay
          setTimeout(() => {
            $form.removeClass('has-validation-error');
          }, 3000);
        } else {
          // Add loading state
          $submitBtn.addClass('loading').prop('disabled', true);
          $submitBtn.val('Logging in...');
        }
      });

      // Real-time validation feedback
      $username.on('input', function() {
        if ($(this).val().trim()) {
          $(this).removeClass('error');
        }
      });

      $password.on('input', function() {
        if ($(this).val().trim()) {
          $(this).removeClass('error');
        }
      });

      // Keyboard navigation
      $form.on('keydown', function(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
          e.preventDefault();
          $submitBtn.trigger('click');
        }
      });

      // Create account button functionality
      $createAccountBtn.on('click', function(e) {
        e.preventDefault();
        // Simulate account creation redirect or modal
        alert('Create account functionality would redirect to registration page');
      });

      // Add Facebook logo dynamically if not present
      if ($form.length && !$form.find('.facebook-logo').length) {
        $form.prepend('<div class="facebook-logo" style="text-align: center; margin-bottom: 20px; font-size: 48px; font-weight: bold; color: #1877f2; font-family: Arial, sans-serif;">facebook</div>');
      }

      // Ensure proper body class for CSS targeting
      if ($('body').hasClass('path-empty-page')) {
        $('body').addClass('facebook-login-page');
      }
    }
  };

})(jQuery, Drupal);
