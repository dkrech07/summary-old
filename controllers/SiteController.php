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

use app\models\DetailForm;

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
        $detailFormModel = new DetailForm();

        $query = $summaryService->getSummaryItems();
        $countQuery = clone $query;
        $pages = new Pagination(['totalCount' => $countQuery->count()]);
        $models = $query->offset($pages->offset)
            ->limit(15)
            ->all();

        if (\Yii::$app->request->isAjax && \Yii::$app->request->post()) {
            $request = Yii::$app->request;
            $data = $request->post();

            if (key($data) == 'item_id') {
                // return json_encode('test', JSON_UNESCAPED_UNICODE);

                return json_encode((new SummaryService())->getEditSummaryItem($data['item_id']), JSON_UNESCAPED_UNICODE);
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
                'detailFormModel' => $detailFormModel,
            ]
        );
    }

    public function actionLogout()
    {
        \Yii::$app->user->logout();
        return $this->goHome();
    }
}
