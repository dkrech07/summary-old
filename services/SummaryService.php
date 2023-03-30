<?php

namespace app\services;

use app\models\Summary;

// use Yii;
// use app\models\Replies;
// use app\models\AddTaskForm;
// use app\models\TasksFiles;
// use app\models\Cities;
// use yii\web\Response;
// use yii\widgets\ActiveForm;
// use TaskForce\utils\CustomHelpers;
// use yii\db\Expression;

class SummaryService
{
  public function getSummaryItems()
  // public function getSummaryItems(TasksSearchForm $model): object
  {
    $query = Summary::find()
      ->joinWith('summaryStatus');
    // ->where(['tasks.status' => 'new'])
    // ->orderBy('dt_add DESC');

    // if ($model->categories) {
    //   $query->andWhere(['in', 'category_id', $model->categories]);
    // }

    // if ($model->without_executor) {
    //   $query->andWhere(['executor_id' => null]);
    // }

    // settype($model->period, 'integer');
    // if ($model->period > 0) {
    //   $exp = new Expression("DATE_SUB(NOW(), INTERVAL {$model->period} HOUR)");
    //   $query->andWhere(['>', 'dt_add', $exp]);
    // }

    return $query;
  }

  // /**
  //  * @param int $id
  //  * 
  //  * @return Summary|null
  //  */
  // public function getSummary(int $id): ?Summary
  // {
  //   return Summary::find()
  //     ->joinWith('city', 'category')
  //     ->where(['tasks.id' => $id])
  //     ->one();
  // }
}
