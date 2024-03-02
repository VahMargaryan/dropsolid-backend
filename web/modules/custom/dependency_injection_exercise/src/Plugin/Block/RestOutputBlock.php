<?php

namespace Drupal\dependency_injection_exercise\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\dependency_injection_exercise\Services\PhotoService;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a 'RestOutputBlock' Block.
 *
 * This block displays photos fetched from a service.
 *
 * @Block(
 *   id = "rest_output_block",
 *   admin_label = @Translation("Rest Output Block"),
 * )
 */
class RestOutputBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * The photo service.
   *
   * @var \Drupal\dependency_injection_exercise\Services\PhotoService
   */
  protected $photoService;

  /**
   * Constructs a new RestOutputBlock instance.
   *
   * Initializes the block with a photo service dependency.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin ID for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\dependency_injection_exercise\Services\PhotoService $photoService
   *   The photo service.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, PhotoService $photoService) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->photoService = $photoService;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('dependency_injection_exercise.photo_service')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
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

}
