<?php

class Security
{
      public static function cleanInput(string $input): string
      {
            return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
      }

      public static function validatePseudo(string $pseudo): bool
      {
            return preg_match('/^[a-zA-Z0-9]{3,10}$/', $pseudo);
      }
}
