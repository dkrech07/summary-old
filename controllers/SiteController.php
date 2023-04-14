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

        // // Загрузка аудиозаписи
        // $itemFormModel->file = $summaryService->uploadFile();

        // $model = new UploadForm();

        // if (Yii::$app->request->isPost) {
        //     $model->file = UploadedFile::getInstance($model, 'file');

        //     if ($model->file && $model->validate()) {
        //         $model->file->saveAs('uploads/' . $model->file->baseName . '.' . $model->file->extension);
        //     }
        // }

        // return $this->render('upload', ['model' => $model]);

        // Создание и редактирование элемента
        if ($itemFormModel->load(Yii::$app->request->post())) {

            $itemFormModel->load(Yii::$app->request->post());
            $itemFormModel->file = UploadedFile::getInstance($itemFormModel, 'file');

            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($itemFormModel);
            }

            if ($itemFormModel->validate()) {
                $summaryService->editItem($itemFormModel);
                // exit;
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
        $user = Yii::$app->user->identity;
        $account = Account::find()
            ->where(['user_id' => $user->id])
            ->one();

        $token = 'ajef9nnkandea5a1k7gd'; # IAM-токен
        $folderId = 'b1gpblg4vkajavqdadqh'; # Идентификатор каталога
        $audioFileName = "2a6c5b1b.mp3";

        $file = fopen($audioFileName, 'rb');

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://stt.api.cloud.yandex.net/speech/v1/stt:recognize?lang=ru-RU&folderId=${folderId}&format=oggopus");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $token, 'Transfer-Encoding: chunked'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);

        curl_setopt($ch, CURLOPT_INFILE, $file);
        curl_setopt($ch, CURLOPT_INFILESIZE, filesize($audioFileName));
        $res = curl_exec($ch);
        curl_close($ch);
        $decodedResponse = json_decode($res, true);
        if (isset($decodedResponse["result"])) {
            echo $decodedResponse["result"];
        } else {
            echo "Error code: " . $decodedResponse["error_code"] . "\r\n";
            echo "Error message: " . $decodedResponse["error_message"] . "\r\n";
        }

        fclose($file);
    }
}
