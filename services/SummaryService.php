<?php

namespace app\services;

use app\models\Summary;
use app\models\DetailForm;

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


  public function DetailEdit(DetailForm $detailModel)
  {
    //   $editCustom = Customs::find()
    //     ->where(['ID' => $customEditFormModel->ID])
    //     ->one();

    //   $editCustom->CODE = $customEditFormModel->CODE;
    //   $editCustom->NAMT = $customEditFormModel->NAMT;
    //   $editCustom->OKPO = $customEditFormModel->OKPO;
    //   $editCustom->OGRN = $customEditFormModel->OGRN;
    //   $editCustom->INN = $customEditFormModel->INN;
    //   $editCustom->NAME_ALL = $customEditFormModel->NAME_ALL;
    //   $editCustom->ADRTAM = $customEditFormModel->ADRTAM;
    //   $editCustom->PROSF = $customEditFormModel->PROSF;
    //   $editCustom->TELEFON = $customEditFormModel->TELEFON;
    //   $editCustom->FAX = $customEditFormModel->FAX;
    //   $editCustom->EMAIL = $customEditFormModel->EMAIL;
    //   $editCustom->COORDS_LATITUDE = $customEditFormModel->COORDS_LATITUDE;
    //   $editCustom->COORDS_LONGITUDE = $customEditFormModel->COORDS_LONGITUDE;

    //   $transaction = Yii::$app->db->beginTransaction();
    //   try {
    //     $editCustom->save();
    //     $transaction->commit();
    //   } catch (\Exception $e) {
    //     $transaction->rollBack();
    //     throw $e;
    //   } catch (\Throwable $e) {
    //     $transaction->rollBack();
    //   }
  }
}
