<?php

namespace Drupal\dependency_injection_exercise\Services;

use Drupal\Core\Mail\MailManager as OriginalMailManager;

/**
 * Provides a Mail plugin manager that redirects all outgoing emails to a single address.
 *
 * This is useful for capturing outgoing emails in a development or testing environment,
 * preventing them from being sent to actual recipients.
 */
class MailManager extends OriginalMailManager {

  /**
   * {@inheritdoc}
   *
   * Redirects all outgoing emails to a predefined address.
   */
  public function mail($module, $key, $to, $langcode, $params = [], $reply = NULL, $send = TRUE) {
    // Consider retrieving this email from a configuration or environment variable for flexibility.
    $to = 'nope@doesntexist.com';

    // Call the parent mail method with the modified arguments.
    return parent::mail($module, $key, $to, $langcode, $params, $reply, $send);
  }

}
