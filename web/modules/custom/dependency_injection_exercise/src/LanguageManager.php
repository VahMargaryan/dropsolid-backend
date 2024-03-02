<?php

namespace Drupal\dependency_injection_exercise;

use Drupal\Core\Language\LanguageInterface;
use Drupal\Core\Language\LanguageManager as OriginalLanguageManager;

/**
 * LanguageManager service.
 */
class LanguageManager extends OriginalLanguageManager {

  public function getCurrentLanguage($type = LanguageInterface::TYPE_INTERFACE) {
    // TODO: Add your code here
    return $this->getDefaultLanguage();
  }

}
