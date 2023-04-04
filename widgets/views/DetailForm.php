<?php

use mihaildev\ckeditor\CKEditor;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalLabel">Подробное описание</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
      </div>
      <?php $form = ActiveForm::begin(['id' => 'detail']); ?>
      <div class="modal-body">
        <?= $form->field($formModel, 'detail')->textarea(['autofocus' => true, 'rows' => '18', 'placeholder' => "Добавьте сюда текст"])->label(false) ?>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Сохранить изменения</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
      </div>
      <?php ActiveForm::end(); ?>

    </div>
  </div>
</div>