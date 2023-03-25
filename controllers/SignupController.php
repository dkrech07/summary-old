<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\SignupForm;

// use yii\filters\AccessControl;
// use yii\web\Response;
// use yii\filters\VerbFilter;
// use app\models\LoginForm;
// use app\models\ContactForm;
// use app\models\User;

class SignupController extends Controller
{
  public function actionIndex()
  {
    $model = new SignupForm();

    if ($model->load(Yii::$app->request->post())) {
      if ($user = $model->signup()) {
        if (Yii::$app->getUser()->login($user)) {
          return $this->goHome();
        }
      }
    }

    return $this->render('index', [
      'model' => $model,
    ]);
  }
}
