<?php

namespace app\controllers;

use yii\filters\AccessControl;
use yii\web\Controller;
// use TaskForce\utils\CustomHelpers;

abstract class SecuredController extends Controller
{
  public function behaviors()
  {
    return [
      'access' => [
        'class' => AccessControl::class,
        'only'  => ['secret'],
        'rules' => [
          [
            'allow'   => true,
            'actions' => ['secret'],
            'roles'   => ['@'],
          ],
        ],
      ],
    ];
  }

  // public function behaviors()
  // {
  //   return [
  //     'access' => [
  //       'class' => AccessControl::class,
  //       'rules' => [
  //         [
  //           'allow' => true,
  //           'roles' => ['@']
  //         ]
  //       ]
  //     ]
  //   ];
  // }

  // public function beforeAction($action)
  // {
  //   if (CustomHelpers::checkAuthorization() === null) {
  //     $this->redirect('/landing');
  //     return false;
  //   }

  //   if ($action->id === 'add') {
  //     if (\Yii::$app->user->identity->role !== 0) {
  //       $this->redirect('/tasks/index');
  //       return false;
  //     }
  //   }
  //   return true;
  // }
}
