<?php

use yii\helpers\Html;
use yii\helpers\Url;
use TaskForce\utils\NounPluralConverter;
?>

<tr id='<?= Html::encode($model->id); ?>'>
  <th scope="row"></th>
  <td><?= Html::encode($model->status); ?></td>
  <td><?= Html::encode($model->title); ?></td>
  <td class="detail"><i class="bi bi-pencil-square"></i></td>
  <td class="summary"><i class="bi bi-pencil-square"></i></td>
</tr>


<?= ListView::widget([
  'dataProvider' => $dataProvider,
  'itemView' => '_summaryItem',

  'options' => [
    'tag' => 'div',
    'class' => 'news-list',
    'id' => 'news-list',
  ],

  'layout' => "{items}\n{pager}\n{summary}",
  'summary' => 'Показано {count} из {totalCount}',
  'summaryOptions' => [
    'tag' => 'span',
    'class' => 'my-summary'
  ],

  'itemOptions' => [
    'tag' => 'div',
    'class' => 'news-item',
  ],

  'emptyText' => '<p>Список пуст</p>',
  'emptyTextOptions' => [
    'tag' => 'p'
  ],

  'pager' => [
    'nextPageLabel' => '<i class="bi bi-caret-right"></i>',
    'prevPageLabel' => '<i class="bi bi-caret-left"></i>',
    'maxButtonCount' => 5,
    'pageCssClass' => 'page-item',
    'prevPageCssClass' => 'page-item page-control',
    'nextPageCssClass' => 'page-item page-control',
    'activePageCssClass' => 'active',
    'linkOptions' => ['class' => 'page-link'],
    'options' => [
      'class' => 'pagination',
    ],
  ],
]); ?>