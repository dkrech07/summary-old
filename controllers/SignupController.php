<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\SignupForm;
use yii\filters\AccessControl;

// use yii\web\Response;
// use yii\filters\VerbFilter;
// use app\models\LoginForm;
// use app\models\ContactForm;
// use app\models\User;

class SignupController extends Controller
{
  public function behaviors()
  {
    return [
      'access' => [
        'class' => AccessControl::class,
        'only' => ['index'],
        'rules' => [
          [
            'allow' => true,
            'actions' => ['index'],
            'roles' => ['?']
          ]
        ]
      ]
    ];
  }

  public function actionIndex()
  {
    $model = new SignupForm();

    if ($model->load(Yii::$app->request->post())) {
      if ($user = $model->signup()) {
        if (Yii::$app->getUser()->login($user)) {
          $this->redirect('/site');
        }
      }
    }

    return $this->render('index', [
      'model' => $model,
    ]);
  }

  public function beforeAction($action)
  {
    if (!Yii::$app->user->isGuest) {
      $this->redirect('/site/index');
      return false;
    }
    return true;
  }
}
