<?php

use yii\widgets\LinkPager;
use yii\helpers\Html;
use app\widgets\ModalForm;

/** @var yii\web\View $this */

$this->title = 'My Yii Application';

?>
<!-- <div class="site-index"> -->
<?php
// print('username:');
// print('<br>');
// print($user->username);
// print('<br>');
// print('email:');
// print('<br>');
// print($user->email);
// print('<br>');
// print('<br>');
// print_r($summaryItems);
// print('<br>');
// print('user status:');
// print('<br>');
// print(Yii::$app->user->isGuest);

// foreach ($summaryItems as $item) {
//     print_r($item->summaryStatus->status_title);
//     print('<br>');
//     print('<br>');
// }
// print_r($models);
?>
<div class="row">
    <table class="summary-table table table-hover">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Название записи</th>
                <th scope="col">Статус</th>
                <th scope="col">Подробное описание</th>
                <th scope="col">Краткое резюме</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($models as $key => $model) : ?>
                <tr class="summary-item" id='<?= Html::encode($model->id); ?>'>
                    <td scope="row"><?= Html::encode($model->number); ?></td>
                    <td><?= Html::encode($model->title); ?></td>
                    <td class="status" data-status="<?= Html::encode($model->summary_status); ?>"><?= Html::encode($model->summaryStatus->status_title); ?></td>
                    <td class="detail-edit"><i class="bi bi-pencil-square"></i></td>
                    <td class="summary-edit"><i class="bi bi-pencil-square"></i></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php if (!count($models)) : ?>
    <div class="row">
        <p>Создайте свою первую запись.</p>
    </div>
<?php endif; ?>

<div class="row">
    <?=
    LinkPager::widget([
        'pagination' => $pages,
        'nextPageLabel' => 'Следующая',
        'prevPageLabel' => 'Предыдущая',
        'options' => [
            'class' => 'pagination justify-content-center' //fixed-bottom
        ],
        'linkOptions' => ['class' => 'page-link'],
        'linkContainerOptions' => ['class' => 'page-item'],
    ]);
    ?>
</div>

<?= ModalForm::widget(['formType' => 'DetailForm', 'formModel' => $detailModel]) ?>


<!-- <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal" data-bs-whatever="@mdo">Открыть модальное окно для @mdo</button> -->
<!-- <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal" data-bs-whatever="@fat">Открыть модальное окно для @fat</button>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal" data-bs-whatever="@getbootstrap">Открыть модальное окно для @getbootstrap</button> -->

<!-- <div class="modal fade" id="summaryModal" tabindex="-1" aria-labelledby="summaryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Новое сообщение</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="mb-3">
                        <label for="recipient-name" class="col-form-label">Получатель:</label>
                        <input type="text" class="form-control" id="recipient-name">
                    </div>
                    <div class="mb-3">
                        <label for="message-text" class="col-form-label">Сообщение:</label>
                        <textarea class="form-control" id="message-text"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                <button type="button" class="btn btn-primary">Отправить сообщение</button>
            </div>
        </div>
    </div>
</div> -->

<!-- <div class="jumbotron text-center bg-transparent">
        <h1 class="display-4">Congratulations!</h1>

        <p class="lead">You have successfully created your Yii-powered application.</p>

        <p><a class="btn btn-lg btn-success" href="http://www.yiiframework.com">Get started with Yii</a></p>
    </div>

    <div class="body-content">

        <div class="row">
            <div class="col-lg-4">
                <h2>Heading</h2>

                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et
                    dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip
                    ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu
                    fugiat nulla pariatur.</p>

                <p><a class="btn btn-outline-secondary" href="http://www.yiiframework.com/doc/">Yii Documentation &raquo;</a></p>
            </div>
            <div class="col-lg-4">
                <h2>Heading</h2>

                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et
                    dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip
                    ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu
                    fugiat nulla pariatur.</p>

                <p><a class="btn btn-outline-secondary" href="http://www.yiiframework.com/forum/">Yii Forum &raquo;</a></p>
            </div>
            <div class="col-lg-4">
                <h2>Heading</h2>

                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et
                    dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip
                    ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu
                    fugiat nulla pariatur.</p>

                <p><a class="btn btn-outline-secondary" href="http://www.yiiframework.com/extensions/">Yii Extensions &raquo;</a></p>
            </div>
        </div>

    </div>
</div> -->