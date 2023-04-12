<?php

namespace app\services;

use Yii;
use app\models\AccountForm;
use app\models\Summary;
use app\models\ItemForm;
use app\models\Account;
use yii\db\Expression;

use Aws\S3\S3Client;
use Aws\Exception\AwsException;
use Aws\S3\MultipartUploader;
use Aws\Exception\MultipartUploadException;
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
  /**
   * @return string
   */
  public static function getCurrentDate(): string
  {
    $expression = new Expression('NOW()');
    $now = (new \yii\db\Query)->select($expression)->scalar();
    return $now;
  }

  public function getSummaryItems()
  // public function getSummaryItems(TasksSearchForm $model): object
  {
    $query = Summary::find()
      ->orderBy('id DESC')
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




  // Создание и редактирование элемента
  public function editItem(ItemForm $itemFormModel)
  {

    $editSummaryItem = Summary::find()
      ->where(['created_user' => Yii::$app->user->identity->id])
      ->count();

    $newItem = new Summary;

    $newItem->file = $itemFormModel->file;
    $newItem->number = $editSummaryItem + 1;
    $newItem->summary_status = 1;
    $newItem->title = $itemFormModel->title;
    $newItem->detail = $itemFormModel->detail;
    $newItem->summary = $itemFormModel->summary;
    $newItem->created_user = Yii::$app->user->identity->id;
    $newItem->created_at = $this->getCurrentDate();
    $newItem->updated_at = $this->getCurrentDate();

    $transaction = Yii::$app->db->beginTransaction();
    try {
      $newItem->save();
      $transaction->commit();
    } catch (\Exception $e) {
      $transaction->rollBack();
      throw $e;
    } catch (\Throwable $e) {
      $transaction->rollBack();
    }
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

  // public function editItem(ItemForm $itemtFormModel)
  // {
  //   $editItem = Summary::find()
  //     ->where(['id' => $itemtFormModel->id])
  //     ->one();

  //   $item = new ItemForm();
  // }

  public function getEditSummaryItem($data)
  {
    $detailFormModel = new ItemForm();

    $editSummaryItem = Summary::find()
      ->where(['ID' => $data])
      ->one();

    $detailFormModel->number = $editSummaryItem->number;
    $detailFormModel->summary_status = $editSummaryItem->summary_status;
    $detailFormModel->title = $editSummaryItem->title;
    $detailFormModel->detail = $editSummaryItem->detail;
    $detailFormModel->summary = $editSummaryItem->summary;
    $detailFormModel->created_user = $editSummaryItem->created_user;
    $detailFormModel->created_at = $editSummaryItem->created_at;
    $detailFormModel->updated_at = $editSummaryItem->updated_at;

    return $detailFormModel;
  }


  public function DetailEdit(ItemForm $detailModel)
  {

    $editSummaryItem = Summary::find()
      ->where(['id' => $detailModel->id])
      ->one();
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

  // public function getEditPage($id)
  // {
  //   $pageEditFormModel = new PageEditFormModel();

  //   $editPage = Pages::find()
  //     ->where(['page_url' => $id])
  //     ->one();

  //   $pageEditFormModel->id = $editPage->id;
  //   $pageEditFormModel->page_dt_add = $editPage->page_dt_add;
  //   $pageEditFormModel->page_name = $editPage->page_name;
  //   $pageEditFormModel->page_meta_description = $editPage->page_meta_description;
  //   $pageEditFormModel->page_content = $editPage->page_content;
  //   $pageEditFormModel->page_user_change = $editPage->page_user_change;
  //   $pageEditFormModel->page_url = $editPage->page_url;

  //   return $pageEditFormModel;
  // }

  public function editAccount(AccountForm $accountFormModel)
  {
    $editAccount = Account::find()
      ->where(['user_id' => Yii::$app->user->identity->id])
      ->one();

    if (!$editAccount) {
      $editAccount = new Account;
      $editAccount->user_id = Yii::$app->user->identity->id;
    }

    $editAccount->y_key_id = $accountFormModel->y_key_id;
    $editAccount->y_secret_key = $accountFormModel->y_secret_key;
    $editAccount->bucket_name = $accountFormModel->bucket_name;

    $transaction = Yii::$app->db->beginTransaction();
    try {
      $editAccount->save();
      $transaction->commit();
    } catch (\Exception $e) {
      $transaction->rollBack();
      throw $e;
    } catch (\Throwable $e) {
      $transaction->rollBack();
    }
  }

  public function uploadFile()
  {
    $fileName = 'file';
    $uploadPath = './upload';
    if (isset($_FILES[$fileName])) {
      $file = \yii\web\UploadedFile::getInstanceByName($fileName);

      // $fileName = uniqid('file_') . '.' . $file->extension;
      $fileName = substr(md5(microtime() . rand(0, 9999)), 0, 8) . '.' . $file->extension;
      $uploadPath = $uploadPath . '/' . $fileName;
      //Print file data
      //print_r($file);
      if ($file->saveAs($uploadPath)) {
        //Now save file data to database
        echo \yii\helpers\Json::encode($file);

        // Отпрвляем файл в Яндекс Object Storage
        $user = Yii::$app->user->identity;
        $account = Account::find()
          ->where(['id' => $user->id])
          ->one();

        $sharedConfig = [
          'credentials' => [
            'key' => $account->y_key_id,
            'secret' => $account->y_secret_key,
          ],
          'version' => 'latest',
          'endpoint' => 'https://storage.yandexcloud.net',
          'region' => 'ru-central1',
        ];

        $s3Client = new S3Client($sharedConfig);

        // Use multipart upload
        $uploader = new MultipartUploader($s3Client, $uploadPath, [
          'bucket' => $account->bucket_name,
          'key' => $fileName,
        ]);

        try {
          $result = $uploader->upload();
          echo "Upload complete: {$result['ObjectURL']}\n";
        } catch (MultipartUploadException $e) {
          echo $e->getMessage() . "\n";
        }
      }
    }

    return false;
  }
}
