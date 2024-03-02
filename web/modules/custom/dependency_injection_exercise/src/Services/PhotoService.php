<?php

namespace Drupal\dependency_injection_exercise\Services;

use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;

/**
 * Fetch photo service.
 */
class PhotoService {

  use StringTranslationTrait;

  /**
   * The HTTP client.
   *
   * @var \GuzzleHttp\ClientInterface
   */
  protected $httpClient;

  /**
   * The logger channel.
   *
   * @var \Drupal\Core\Logger\LoggerChannelInterface
   */
  protected $logger;

  /**
   * Constructs a new FetchPhotoService object.
   *
   * @param \GuzzleHttp\ClientInterface $httpClient
   *   The HTTP client.
   * @param \Drupal\Core\Logger\LoggerChannelFactoryInterface $loggerChannelFactory
   *   The logger channel factory.
   */
  public function __construct(
    ClientInterface $httpClient,
    LoggerChannelFactoryInterface $loggerChannelFactory
  ) {
    $this->httpClient = $httpClient;
    $this->logger = $loggerChannelFactory->get('dependency_injection_exercise');
  }

  /**
   * Gets photos from an external API.
   *
   * @param int|null $albumId
   *   The album ID.
   *
   * @return array
   *   An array of photo data.
   */
  public function getPhotos($albumId = NULL): array {
    if ($albumId === NULL) {
      try {
        $albumId = random_int(1, 20);
      } catch (\Exception $e) {
        $this->logger->error($e->getMessage());
        return [];
      }
    }

    try {
      $response = $this->httpClient->request('GET', "https://jsonplaceholder.typicode.com/albums/$albumId/photos");
      $raw_data = $response->getBody()->getContents();
      return json_decode($raw_data, TRUE) ?: [];
    } catch (GuzzleException $e) {
      $this->logger->error($e->getMessage());
      return [];
    }
  }
}
