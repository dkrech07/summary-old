<?php

namespace app\widgets;

use yii\base\Widget;

class ModalForm extends Widget
{
  public $model;
  public $type;

  public function run()
  {
    return $this->render("{$this->type}", [
      'model' => $this->model,
    ]);
  }
}
