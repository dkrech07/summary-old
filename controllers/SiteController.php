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

use Orhanerday\OpenAi\OpenAi;

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

        // Создание и редактирование элемента
        if ($itemFormModel->load(Yii::$app->request->post())) {

            $itemFormModel->load(Yii::$app->request->post());

            if (isset($itemFormModel->file)) {
                $itemFormModel->file = UploadedFile::getInstance($itemFormModel, 'file');
            }

            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($itemFormModel);
            }

            if ($itemFormModel->validate()) {
                $summaryService->createItem($itemFormModel);
                $this->refresh();
            }
        }

        // // Редактирование подробного описания
        // if ($accountFormModel->load(Yii::$app->request->post())) {
        //     $accountFormModel->load(Yii::$app->request->post());

        //     if (Yii::$app->request->isAjax) {
        //         Yii::$app->response->format = Response::FORMAT_JSON;
        //         return ActiveForm::validate($accountFormModel);
        //     }

        //     if ($accountFormModel->validate()) {
        //         $summaryService->editAccount($accountFormModel);
        //         $this->refresh();
        //     }
        // }

        $summaryService->getDescription();

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
                // print_r($summaryService->getDetailItem($data['item_id_detail']));
                // exit;
                return json_encode($summaryService->getDetailItem($data['item_id_detail']), JSON_UNESCAPED_UNICODE);
            }

            // if (key($data) == 'item_id_summary') {
            //     return json_encode((new SummaryService())->getDetailItem($data['item_id_summary']), JSON_UNESCAPED_UNICODE);
            // }

            if (key($data) == 'item_id_summary') {
                // print_r($summaryService->getDetailItem($data['item_id_detail']));
                // exit;
                return json_encode($summaryService->getSummmaryItem($data['item_id_summary']), JSON_UNESCAPED_UNICODE);
            }
        }
    }

    public function actionOpen()
    {
        print('ok');
        // http://localhost/summary/web/site/open

        // require __DIR__ . '/vendor/autoload.php'; // remove this line if you use a PHP Framework.



        $open_ai_key = ''; //getenv('OPENAI_API_KEY');
        $open_ai = new OpenAi($open_ai_key);

        $chat = $open_ai->chat([
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                // [
                //     "role" => "system",
                //     "content" => "You are a helpful assistant."
                // ],
                [
                    "role" => "user",
                    "content" => "Сделай краткое описание из текста: Вагонные споры последнее дело, когда больше нечего пить. Но поезд идет, будет опустен и тянет поговорить. И двое сошлись, и двое сошлись не на страха, на совесть колеса прогнали, со 1 говорил наша жизнь это. Помнит другой, говорил перо, 1 он вержгал на пути нашем чисто другой возражал и дожил. 1 Говорил, мол, мы ваши действия другой говорили. Пассажиры 1 говорил нам, 1 говорил, нам свободна как рано по поиску. То надо весел, надо, поезд уже надо, везет. Другой говорил, но задаваться не надо, как сядем в него, так и сойдем, а 1 кричал. Нам открыта дорога, нам много на много лет 2 плечо, не так уж и много, все дело в. Цене на билете, куда хотим, куда едем. 2 Отличие, что поезд проедет и штат, где проложен путь. Еба сошли, где то под таганрогом среди бескрайних полей, и каждый пошел своей дорогой, а поезд пошел в своей."
                ],
                // [
                //     "role" => "assistant",
                //     "content" => "The Los Angeles Dodgers won the World Series in 2020."
                // ],
                // [
                //     "role" => "user",
                //     "content" => "Where was it played?"
                // ],
            ],
            // 'temperature' => 1.0,
            // 'max_tokens' => 4000,
            // 'frequency_penalty' => 0,
            // 'presence_penalty' => 0,
        ]);


        // var_dump($chat);
        echo "<br>";
        echo "<br>";
        echo "<br>";
        // decode response
        $d = json_decode($chat);

        print_r($d);
        // Get Content
        // echo ($d->choices[0]->message->content);
    }
}
