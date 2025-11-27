<?php

namespace Drupal\empty_module_fixed\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Form\FormBuilderInterface;
use Drupal\empty_module_fixed\Form\LoginForm;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Controller for the login page.
 */
class EmptyPageController extends ControllerBase {

  /**
   * The form builder service.
   *
   * @var \Drupal\Core\Form\FormBuilderInterface
   */
  protected $formBuilder;

  /**
   * Constructs a new EmptyPageController object.
   *
   * @param \Drupal\Core\Form\FormBuilderInterface $form_builder
   *   The form builder service.
   */
  public function __construct(FormBuilderInterface $form_builder) {
    $this->formBuilder = $form_builder;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('form_builder')
    );
  }

  /**
   * Display login form only.
   */
  public function content() {
    return [
      '#type' => 'container',
      '#attributes' => [
        'class' => ['login-page-content'],
      ],
      'login_form' => $this->formBuilder->getForm(LoginForm::class),
    ];
  }

}
