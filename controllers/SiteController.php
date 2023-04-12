<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\User;
use app\models\SignupForm;
use app\services\SummaryService;
use yii\data\ActiveDataProvider;
use yii\data\Pagination;
use app\models\ItemForm;
use app\models\Account;
use app\models\AccountForm;
use yii\widgets\ActiveForm;
use yii\web\UploadedFile;
use app\models\Summary;

use Aws\S3\S3Client;
use Aws\Exception\AwsException;
use Aws\S3\MultipartUploader;
use Aws\Exception\MultipartUploadException;

class SiteController extends SecuredController
{
    public $layout = 'summary';

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $user = Yii::$app->user->identity;
        $summaryService = new SummaryService;
        $itemFormModel = new ItemForm();
        $accountFormModel = new accountForm();

        // Вывод элементов на странице
        $query = Summary::find()
            ->orderBy('id DESC')
            ->joinWith('summaryStatus');

        $query = $summaryService->getSummaryItems();
        $countQuery = clone $query;
        $pages = new Pagination(['totalCount' => $countQuery->count()]);
        $models = $query->offset($pages->offset)
            ->limit(15)
            ->all();

        // Редактирование учетных данных для Яндекс Storage
        if ($accountFormModel->load(Yii::$app->request->post())) {
            $accountFormModel->load(Yii::$app->request->post());

            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($accountFormModel);
            }

            if ($accountFormModel->validate()) {
                $summaryService->editAccount($accountFormModel);
                $this->refresh();
            }
        }

        // $account = Account::find()
        //     ->where(['user_id' => $user->id])
        //     ->one();

        // print_r($account);

        // Загрузка аудиозаписи
        $itemFormModel->file = $summaryService->uploadFile();

        // Создание и редактирование элемента
        if ($itemFormModel->load(Yii::$app->request->post())) {
            $itemFormModel->load(Yii::$app->request->post());

            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($itemFormModel);
            }

            if ($itemFormModel->validate()) {
                $summaryService->editItem($itemFormModel);
                $this->refresh();
            }
        }








        // $data = null;

        // if (\Yii::$app->request->isAjax && \Yii::$app->request->post()) {
        //     $request = Yii::$app->request;
        //     $data = $request->post();

        //     if (key($data) == 'item_id_detail') {
        //         return json_encode((new SummaryService())->getEditSummaryItem($data['item_id_detail']), JSON_UNESCAPED_UNICODE);
        //     }

        //     if (key($data) == 'item_id_summary') {
        //         return json_encode((new SummaryService())->getEditSummaryItem($data['item_id_summary']), JSON_UNESCAPED_UNICODE);
        //     }
        // }

        // $model1 = new Model1();
        // $model2 = new Model2();

        // if ($model->load(Yii::$app->request->post())) {
        //     // обработка первой модели
        // }
        // if ($model2->load(Yii::$app->request->post())) {
        //     // обработка второй модели
        // }







        // if (Yii::$app->request->post('CustomEditForm')) {
        //     $customEditFormModel->load(Yii::$app->request->post());
        //     if (Yii::$app->request->isAjax) {
        //         Yii::$app->response->format = Response::FORMAT_JSON;
        //         return ActiveForm::validate($customEditFormModel);
        //     }
        //     if ($customEditFormModel->validate()) {
        //         (new GrandmasterService())->editCustom($customEditFormModel);
        //         return $this->refresh();
        //     }
        // }



        //  else {
        //     return false;
        // }

        return $this->render(
            'index',
            [
                'user' => $user,
                'models' => $models,
                'pages' => $pages,
                'itemFormModel' => $itemFormModel,
                'accountFormModel' => $accountFormModel,
            ]
        );
    }

    public function actionLogout()
    {
        \Yii::$app->user->logout();
        return $this->goHome();
    }

    // use Aws\S3\S3Client;
    // use Aws\Exception\AwsException;

    public function actionUpload()
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
