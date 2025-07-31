<?php

class HoneypotProtector
{

      private string $honeypotField;

      public function __construct(string $honeypotField = 'fake_email')
      {
            $this->honeypotField = $honeypotField;
      }

      public function isBotSubmission(array $postData): bool
      {
            return !empty($postData[$this->honeypotField]);
      }
}
