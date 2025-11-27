<?php

namespace Drupal\empty_module_fixed\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Login form for the Facebook-style page.
 */
class LoginForm extends FormBase {

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

    $form['email'] = [
      '#type' => 'email',
      '#title' => $this->t('Email'),
      '#title_display' => 'hidden',
      '#placeholder' => $this->t('Email address'),
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
      '#url' => \Drupal\Core\Url::fromRoute('<none>'),
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

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $email = $form_state->getValue('email');
    $password = $form_state->getValue('password');

    // For demonstration purposes - in a real application you would
    // implement proper authentication logic here
    $this->messenger()->addStatus($this->t('Login attempt with email: @email', ['@email' => $email]));
  }

}
