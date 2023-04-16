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

use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Psr7\Request;

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

        $summaryService->getDescription();


        // print_r(Summary::find()
        //     ->where(['id' => 39])
        //     ->one());
        // ->joinWith('summaryStatus'))




        // $data = null;

        // if (\Yii::$app->request->isAjax && \Yii::$app->request->post()) {
        //     $request = Yii::$app->request;
        //     $data = $request->post();

        //     if (key($data) == 'item_id_detail') {
        //         // return json_encode($summaryService->getEditSummaryItem($data['item_id_detail']), JSON_UNESCAPED_UNICODE);

        //         print_r($summaryService->getEditSummaryItem($data['item_id_detail']));
        //         return json_encode($summaryService->getEditSummaryItem($data['item_id_detail']));
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

    public function actionEdit()
    {
        $user = Yii::$app->user->identity;
        $summaryService = new SummaryService;
        $itemFormModel = new ItemForm();
        $accountFormModel = new accountForm();

        if (\Yii::$app->request->isAjax && \Yii::$app->request->post()) {
            $request = Yii::$app->request;
            $data = $request->post();

            if (key($data) == 'item_id_detail') {
                // return json_encode($summaryService->getEditSummaryItem($data['item_id_detail']), JSON_UNESCAPED_UNICODE);

                // print_r($summaryService->getEditSummaryItem($data['item_id_detail']));
                return json_encode($summaryService->getEditSummaryItem($data['item_id_detail']), JSON_UNESCAPED_UNICODE);
            }

            if (key($data) == 'item_id_summary') {
                return json_encode((new SummaryService())->getEditSummaryItem($data['item_id_summary']), JSON_UNESCAPED_UNICODE);
            }
        }
    }

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

    public function actionDecode()
    {
        // $geocode = 'Москва';

        // $query = http_build_query([
        //     'format' => 'json',
        //     'q' => $geocode,
        //     'polygon_geojson' => 1,
        // ]);

        $url = "https://transcribe.api.cloud.yandex.net";

        // $url = "http://nominatim.openstreetmap.org/search?$query";
        $client = new Client([
            'base_uri' => $url,
        ]);


        $response = $client->request('POST', '/speech/stt/v2/longRunningRecognize', [
            'headers' => [
                'Authorization' => 'Api-Key AQVNxpT5cvi9T36mk3HbRFMYVMl-HgfwlEHDuZnT'
            ],
            'json' => [
                'config' => [
                    'specification' => [
                        'languageCode' => 'ru-RU',
                        'model' => 'general',
                        // 'profanityFilter' => true,
                        'audioEncoding' => 'MP3',
                        // 'sampleRateHertz' => '48000',
                        // 'audioChannelCount' => '1'
                    ]
                ],
                'audio' => [
                    'uri' => 'https://storage.yandexcloud.net/summary/1659103f.mp3'
                ]
            ]
        ]);

        $body = $response->getBody();
        $arr_body = json_decode($body);
        print_r($arr_body);

        // stdClass Object ( [done] => [id] => e0377jv3i8026r52eva3 [createdAt] => 2023-04-16T08:22:49Z [createdBy] => aje9jg4as25k6r43n4op [modifiedAt] => 2023-04-16T08:22:49Z )

        // $request = new Request('PUT', $url);
        // $response = $client->send($request);
        // $content = $response->getBody()->getContents();
        // $responseData = json_decode($content, false);

        // "config" => [
        //     "specification" => [
        //         "languageCode" => "string",
        //         "model" => "string",
        //         "profanityFilter" => "string",
        //         "audioEncoding" => "string",
        //         "sampleRateHertz" => "integer",
        //         "audioChannelCount" => "integer"
        //     ]
        // ],
        // "audio" => [
        //     "uri" => "string"
        // ]
    }

    public function actionDescription()
    {
        // $geocode = 'Москва';

        // $query = http_build_query([
        //     'format' => 'json',
        //     'q' => $geocode,
        //     'polygon_geojson' => 1,
        // ]);

        $url = "https://operation.api.cloud.yandex.net/operations/";

        // $url = "http://nominatim.openstreetmap.org/search?$query";
        $client = new Client([
            'base_uri' => $url,
        ]);


        $response = $client->request('GET', 'e036ejtlftpso6dlk6pr', [
            'headers' => [
                'Authorization' => 'Api-Key AQVNxpT5cvi9T36mk3HbRFMYVMl-HgfwlEHDuZnT'
            ]
        ]);

        $body = $response->getBody();
        $arr_body = json_decode($body);
        print($arr_body->done);
        // print('<br>');
        // print('<br>');
        // // print_r(count($arr_body->response->chunks));
        // print('<br>');
        // print('<br>');
        // print_r($arr_body->response->chunks[0]->alternatives[0]->text);
        // print('<br>');
        // print('<br>');

        // print_r($arr_body);

        // stdClass Object ( [done] => [id] => e0377jv3i8026r52eva3 [createdAt] => 2023-04-16T08:22:49Z [createdBy] => aje9jg4as25k6r43n4op [modifiedAt] => 2023-04-16T08:22:49Z )

        // $request = new Request('PUT', $url);
        // $response = $client->send($request);
        // $content = $response->getBody()->getContents();
        // $responseData = json_decode($content, false);

        // "config" => [
        //     "specification" => [
        //         "languageCode" => "string",
        //         "model" => "string",
        //         "profanityFilter" => "string",
        //         "audioEncoding" => "string",
        //         "sampleRateHertz" => "integer",
        //         "audioChannelCount" => "integer"
        //     ]
        // ],
        // "audio" => [
        //     "uri" => "string"
        // ]
    }
}
