<?php

declare(strict_types=1);

namespace app\services;

use Yii;
use app\models\Profiles;
use app\models\User;
use yii\db\Expression;

// const ALL_STARS_COUNT = 5;
// const COUNT_BYTES_IN_KILOBYTE = 1024;

class CustomHelpers
{
  /**
   * @return string
   */
  public static function getCurrentDate(): string
  {
    $expression = new Expression('NOW()');
    $now = (new \yii\db\Query)->select($expression)->scalar();
    return $now;
  }
}
