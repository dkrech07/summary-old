<?php

use yii\widgets\LinkPager;
use yii\helpers\Html;
use app\widgets\ModalForm;

/** @var yii\web\View $this */

$this->title = 'My Yii Application';

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

<?= ModalForm::widget(['formType' => 'DetailForm', 'formModel' => $detailFormModel]) ?>