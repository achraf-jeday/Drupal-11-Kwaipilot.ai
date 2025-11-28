<?php

namespace Drupal\empty_module_fixed\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\Core\Messenger\MessengerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Login form for the Facebook-style page.
 */
class LoginForm extends FormBase {

  /**
   * The messenger service.
   *
   * @var \Drupal\Core\Messenger\MessengerInterface
   */
  protected $messenger;

  /**
   * Constructs a new LoginForm.
   *
   * @param \Drupal\Core\Messenger\MessengerInterface $messenger
   *   The messenger service.
   */
  public function __construct(MessengerInterface $messenger) {
    $this->messenger = $messenger;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('messenger')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'empty_module_fixed_login_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['#attributes']['class'][] = 'empty-module-login-form';

    // Facebook-style form title
    $form['form_title'] = [
      '#type' => 'html_tag',
      '#tag' => 'div',
      '#value' => $this->t('Log in to Facebook'),
      '#attributes' => [
        'class' => ['form-title'],
      ],
    ];

    // Facebook-style form description
    $form['form_description'] = [
      '#type' => 'html_tag',
      '#tag' => 'div',
      '#value' => $this->t('Facebook helps you connect and share with the people in your life.'),
      '#attributes' => [
        'class' => ['form-description'],
      ],
    ];

    $form['username'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Username'),
      '#title_display' => 'hidden',
      '#placeholder' => $this->t('Username'),
      '#required' => TRUE,
      '#attributes' => [
        'class' => ['form-control'],
        'autocomplete' => 'username',
      ],
    ];

    $form['password'] = [
      '#type' => 'password',
      '#title' => $this->t('Password'),
      '#title_display' => 'hidden',
      '#placeholder' => $this->t('Password'),
      '#required' => TRUE,
      '#attributes' => [
        'class' => ['form-control'],
        'autocomplete' => 'current-password',
      ],
    ];

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Log In'),
      '#attributes' => [
        'class' => ['btn', 'btn-primary'],
      ],
    ];

    // Create new account button
    $form['create_account'] = [
      '#type' => 'link',
      '#title' => $this->t('Create new account'),
      '#url' => Url::fromRoute('<none>'),
      '#attributes' => [
        'class' => ['btn', 'btn-secondary'],
      ],
    ];

    // Footer links
    $form['footer'] = [
      '#type' => 'html_tag',
      '#tag' => 'div',
      '#value' => $this->t('Forgot your password? • Privacy Policy • Terms • Help'),
      '#attributes' => [
        'class' => ['login-footer'],
      ],
    ];

    // Add custom validation
    $form['#validate'][] = [$this, 'validateAuthentication'];

    return $form;
  }

  /**
   * Custom validation handler for user authentication.
   */
  public function validateAuthentication(array &$form, FormStateInterface $form_state) {
    $username = $form_state->getValue('username');
    $password = $form_state->getValue('password');

    // Basic validation - check if fields are not empty
    if (empty($username)) {
      $form_state->setErrorByName('username', $this->t('Please enter your username.'));
      return;
    }

    if (empty($password)) {
      $form_state->setErrorByName('password', $this->t('Please enter your password.'));
      return;
    }

    // Drupal 11 user authentication validation
    try {
      // Load user by username
      $account = \Drupal::service('user.auth')->authenticate($username, $password);

      if (!$account) {
        // Invalid username/password combination
        $form_state->setErrorByName('password', $this->t('The username or password you entered is incorrect. Please try again.'));
        return;
      }

      // Check if user account is active
      $user = \Drupal\user\Entity\User::load($account);
      if (!$user || !$user->isActive()) {
        $form_state->setErrorByName('username', $this->t('This account has been disabled.'));
        return;
      }

      // Store user ID for successful authentication handling
      $form_state->set('authenticated_user_id', $account);

    } catch (\Exception $e) {
      // Handle any authentication errors
      $form_state->setErrorByName('password', $this->t('An error occurred during authentication. Please try again later.'));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $authenticated_user_id = $form_state->get('authenticated_user_id');

    if ($authenticated_user_id) {
      // Load the authenticated user
      $user = \Drupal\user\Entity\User::load($authenticated_user_id);

      if ($user) {
        // Check if this is the user's first login
        $is_first_login = empty($user->getLastLoginTime()) || $user->getLastLoginTime() == 0;

        // Use Drupal's user_login_finalize function to properly log in the user
        user_login_finalize($user);

        // Add appropriate success message based on login history
        if ($is_first_login) {
          $this->messenger->addStatus($this->t('Welcome to your account, @username! This is your first login.', ['@username' => $user->getDisplayName()]));
        } else {
          $this->messenger->addStatus($this->t('Welcome back, @username!', ['@username' => $user->getDisplayName()]));
        }

        // Update the user's last login time
        $user->setLastLoginTime(\Drupal::time()->getRequestTime());
        $user->save();

        // Redirect to admin content
        $form_state->setRedirect('system.admin_content');

        return;
      }
    }

    // Fallback redirect if something went wrong
    $form_state->setRedirect('<front>');
  }

}
