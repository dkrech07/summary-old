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
        return $this->render(
            'index',
            [
                'user' => $user,
            ]
        );
    }

    public function actionLogout()
    {
        \Yii::$app->user->logout();
        return $this->goHome();
    }
}
