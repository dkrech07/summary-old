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
    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {

        print_r(Yii::$app->getUser());
        return $this->render('index');
    }
}
