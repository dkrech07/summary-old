<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<div class="modal fade" id="summaryModal" tabindex="-1" aria-labelledby="summaryModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Новое сообщение</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
      </div>

      <?php $form = ActiveForm::begin(['id' => 'custom-edit']); ?>

      <?= $form->field($formModel, 'number')->textInput(['readonly' => true]) ?>
      <?= $form->field($formModel, 'summary_status')->textInput() ?>
      <?= $form->field($formModel, 'title')->textInput() ?>
      <?= $form->field($formModel, 'detail')->textInput() ?>
      <?= $form->field($formModel, 'summary')->textInput() ?>
      <?= $form->field($formModel, 'created_user')->textInput() ?>
      <?= $form->field($formModel, 'created_at')->textInput() ?>
      <?= $form->field($formModel, 'updated_at')->textInput() ?>

      <div class="submit-btn form-group">
        <button type="submit" class="modal-button accept-button">Сохранить</button>
        <button type="button" class="modal-button cancel-button">Отмена</button>
      </div>

      <button type="button" class="modal-button delete-button">Удалить пост</button>

      <?php ActiveForm::end(); ?>
      <!-- <div class="modal-body"> -->
      <!-- <form>
          <div class="mb-3">
            <label for="recipient-name" class="col-form-label">Получатель:</label>
            <input type="text" class="form-control" id="recipient-name">
          </div>
          <div class="mb-3">
            <label for="message-text" class="col-form-label">Сообщение:</label>
            <textarea class="form-control" id="message-text"></textarea>
          </div>
        </form> -->
      <!-- </div> -->
      <!-- <div class="modal-footer"> -->
      <!-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
        <button type="button" class="btn btn-primary">Отправить сообщение</button> -->
      <!-- </div> -->
    </div>
  </div>
</div>

<section class="custom-edit modal modal-hide form-modal">

  <div class="form-header">
    <?= Html::tag('h3', 'Редактирование данных таможенного поста:') ?>
    <i class="close-btn bi bi-x-square"></i>
  </div>

  <?php $form = ActiveForm::begin(['id' => 'custom-edit']); ?>

  <?= $form->field($formModel, 'number')->textInput(['readonly' => true]) ?>
  <?= $form->field($formModel, 'summary_status')->textInput() ?>
  <?= $form->field($formModel, 'title')->textInput() ?>
  <?= $form->field($formModel, 'detail')->textInput() ?>
  <?= $form->field($formModel, 'summary')->textInput() ?>
  <?= $form->field($formModel, 'created_user')->textInput() ?>
  <?= $form->field($formModel, 'created_at')->textInput() ?>
  <?= $form->field($formModel, 'updated_at')->textInput() ?>

  <div class="submit-btn form-group">
    <button type="submit" class="modal-button accept-button">Сохранить</button>
    <button type="button" class="modal-button cancel-button">Отмена</button>
  </div>

  <button type="button" class="modal-button delete-button">Удалить пост</button>


  <?php ActiveForm::end(); ?>

</section>