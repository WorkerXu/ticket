<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Cal */
/* @var $form ActiveForm */
?>
<div class="cal-socre">

    <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'hscore') ?>
        <?= $form->field($model, 'ascore') ?>
        <?= $form->field($model, 'hsum') ?>
        <?= $form->field($model, 'hasocre') ?>
        <?= $form->field($model, 'aasocre') ?>
        <?= $form->field($model, 'asum') ?>
        <?= $form->field($model, 'hmatchw') ?>
        <?= $form->field($model, 'hmatchd') ?>
        <?= $form->field($model, 'hmatchl') ?>
        <?= $form->field($model, 'amatchw') ?>
        <?= $form->field($model, 'amatchd') ?>
        <?= $form->field($model, 'amatchl') ?>
        <?= $form->field($model, 'hamatchw') ?>
        <?= $form->field($model, 'hamatchd') ?>
        <?= $form->field($model, 'hamatchl') ?>
        <?= $form->field($model, 'aamatchw') ?>
        <?= $form->field($model, 'aamatchd') ?>
        <?= $form->field($model, 'aamatchl') ?>
        <?= $form->field($model, 'fw') ?>
        <?= $form->field($model, 'fd') ?>
        <?= $form->field($model, 'fl') ?>
        <?= $form->field($model, 'fhw') ?>
        <?= $form->field($model, 'fhd') ?>
        <?= $form->field($model, 'fhl') ?>
        <?= $form->field($model, 'fsw') ?>
        <?= $form->field($model, 'fsd') ?>
        <?= $form->field($model, 'fsl') ?>
        <?= $form->field($model, 'hjw') ?>
        <?= $form->field($model, 'hjd') ?>
        <?= $form->field($model, 'hjl') ?>
        <?= $form->field($model, 'ajw') ?>
        <?= $form->field($model, 'ajd') ?>
        <?= $form->field($model, 'ajl') ?>
        <?= $form->field($model, 'hjsw') ?>
        <?= $form->field($model, 'hjsd') ?>
        <?= $form->field($model, 'hjsl') ?>
        <?= $form->field($model, 'ajsw') ?>
        <?= $form->field($model, 'ajsd') ?>
        <?= $form->field($model, 'ajsl') ?>
        <?= $form->field($model, 'hajw') ?>
        <?= $form->field($model, 'hajd') ?>
        <?= $form->field($model, 'hajl') ?>
        <?= $form->field($model, 'aajw') ?>
        <?= $form->field($model, 'aajd') ?>
        <?= $form->field($model, 'aajl') ?>
        <?= $form->field($model, 'hajsw') ?>
        <?= $form->field($model, 'hajsd') ?>
        <?= $form->field($model, 'hajsl') ?>
        <?= $form->field($model, 'aajsw') ?>
        <?= $form->field($model, 'aajsd') ?>
        <?= $form->field($model, 'aajsl') ?>
    
        <div class="form-group">
            <?= Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?>
        </div>
    <?php ActiveForm::end(); ?>

</div><!-- cal-socre -->
