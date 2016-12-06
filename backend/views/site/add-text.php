<?php
/**
 * Created by PhpStorm.
 * User: xutao
 * Date: 16/12/6
 * Time: 14:24
 */
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<div class="match-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'text')->textarea(['rows' => 15]) ?>

    <div class="form-group">
        <?= Html::submitButton('添加', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>