<?php

use yii\helpers\Html;
// use yii\helpers\Url;
// use TaskForce\utils\NounPluralConverter;
?>

<tr class="summary-item" id='<?= Html::encode($model->id); ?>'>
  <th scope="row"></th>
  <td class="status" data-status="<?= Html::encode($model->summary_status); ?>"><?= Html::encode($model->summaryStatus->status_title); ?></td>
  <td><?= Html::encode($model->title); ?></td>
  <td class="detail"><i class="bi bi-pencil-square"></i></td>
  <td class="summary"><i class="bi bi-pencil-square"></i></td>
</tr>