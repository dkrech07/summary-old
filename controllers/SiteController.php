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

        $query = $summaryService->getSummaryItems();
        $countQuery = clone $query;
        $pages = new Pagination(['totalCount' => $countQuery->count()]);
        $models = $query->offset($pages->offset)
            ->limit(15)
            ->all();
        $data = null;

        if (\Yii::$app->request->isAjax && \Yii::$app->request->post()) {
            $request = Yii::$app->request;
            $data = $request->post();

            if (key($data) == 'item_id_detail') {
                return json_encode((new SummaryService())->getEditSummaryItem($data['item_id_detail']), JSON_UNESCAPED_UNICODE);
            }

            if (key($data) == 'item_id_summary') {
                return json_encode((new SummaryService())->getEditSummaryItem($data['item_id_summary']), JSON_UNESCAPED_UNICODE);
            }
        }

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

        return $this->render(
            'index',
            [
                'user' => $user,
                'models' => $models,
                'pages' => $pages,
                'data' => $data,
                'itemFormModel' => $itemFormModel,
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
        $sharedConfig = [
            'credentials' => [
                // keys
            ],
            'version' => 'latest',
            'endpoint' => 'https://storage.yandexcloud.net',
            'region' => 'ru-central1',
        ];

        $s3Client = new S3Client($sharedConfig);

        // Use multipart upload
        $source = './upload/Sound_19509.mp3';
        $uploader = new MultipartUploader($s3Client, $source, [
            'bucket' => 'summary',
            'key' => 'Sound_19509.mp3',
        ]);

        try {
            $result = $uploader->upload();
            echo "Upload complete: {$result['ObjectURL']}\n";
        } catch (MultipartUploadException $e) {
            echo $e->getMessage() . "\n";
        }
    }
}
