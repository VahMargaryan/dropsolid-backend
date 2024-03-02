<?php

namespace Drupal\dependency_injection_exercise\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\dependency_injection_exercise\Services\PhotoService;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides REST outputs for the Dependency Injection Exercise module.
 */
class RestOutputController extends ControllerBase {

  /**
   * The photo service.
   *
   * @var \Drupal\dependency_injection_exercise\Services\PhotoService
   */
  protected $photoService;

  /**
   * Constructs a new RestOutputController object.
   *
   * @param \Drupal\dependency_injection_exercise\Services\PhotoService $photoService
   *   The photo service.
   */
  public function __construct(PhotoService $photoService) {
    $this->photoService = $photoService;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('dependency_injection_exercise.photo_service')
    );
  }

  /**
   * Displays the photos.
   *
   * @return array
   *   A render array representing the photos.
   */
  public function showPhotos(): array {
    $build = [
      '#cache' => [
        'max-age' => 60,
        'contexts' => ['url'],
      ],
    ];

    $data = $this->photoService->getPhotos(5);

    if (empty($data)) {
      $build['error'] = [
        '#type' => 'html_tag',
        '#tag' => 'p',
        '#value' => $this->t('No photos available.'),
      ];

      return $build;
    }

    $build['photos'] = array_map(static function ($item) {
      return [
        '#theme' => 'image',
        '#uri' => $item['thumbnailUrl'],
        '#alt' => $item['title'],
        '#title' => $item['title'],
      ];
    }, $data);

    return $build;
  }

  /**
   * Display an empty page.
   *
   * @return array
   *   An empty render array.
   */
  public function emptyPage(): array {
    return [
      '#markup' => '',
    ];
  }

}
