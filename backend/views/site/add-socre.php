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

    <?php $form = ActiveForm::begin([
        'options' => ['class'=>'form-horizontal'],
    ]); ?>

    <div class="form-group">
        <div class="col-md-4 text-center">
            <?= $form->field($model, 'socre')->radioList(['2' => '优势', '1' => '略优', '0' => '持平', '-1' => '略劣', '-2' => '劣势'])?>
        </div>
        <div class="col-md-4 text-center">
            <?= $form->field($model, 'hmatch')->radioList(['2' => '优势', '1' => '略优', '0' => '持平', '-1' => '略劣', '-2' => '劣势'])?>
        </div>
        <div class="col-md-4 text-center">
            <?= $form->field($model, 'amatch')->radioList(['2' => '优势', '1' => '略优', '0' => '持平', '-1' => '略劣', '-2' => '劣势'])?>
        </div>
    </div>
    <div class="form-group">
        <div class="col-md-4 text-center">
            <?= $form->field($model, 'fmatch')->radioList(['2' => '优势', '1' => '略优', '0' => '持平', '-1' => '略劣', '-2' => '劣势'])?>
        </div>
        <div class="col-md-4 text-center">
            <?= $form->field($model, 'famcth')->radioList(['2' => '优势', '1' => '略优', '0' => '持平', '-1' => '略劣', '-2' => '劣势'])?>
        </div>
        <div class="col-md-4 text-center">
            <?= $form->field($model, 'shmatch')->radioList(['2' => '优势', '1' => '略优', '0' => '持平', '-1' => '略劣', '-2' => '劣势'])?>
        </div>
    </div>
    <div class="form-group">
        <div class="col-md-4 text-center">
            <?= $form->field($model, 'thmatch')->radioList(['2' => '优势', '1' => '略优', '0' => '持平', '-1' => '略劣', '-2' => '劣势'])?>
        </div>
        <div class="col-md-4 text-center">
            <?= $form->field($model, 'samatch')->radioList(['2' => '优势', '1' => '略优', '0' => '持平', '-1' => '略劣', '-2' => '劣势'])?>
        </div>
        <div class="col-md-4 text-center">
            <?= $form->field($model, 'tamatch')->radioList(['2' => '优势', '1' => '略优', '0' => '持平', '-1' => '略劣', '-2' => '劣势'])?>
        </div>
    </div>

    <div class="form-group">
        <div class="col-md-4 text-center">
            <?= $form->field($model, 'isocre')->radioList(['2' => '很好', '1' => '略好', '0' => '一般', '-1' => '略差', '-2' => '很差'])?>
        </div>
        <div class="col-md-4 text-center">
            <?= $form->field($model, 'ihmatch')->radioList(['2' => '很好', '1' => '略好', '0' => '一般', '-1' => '略差', '-2' => '很差'])?>
        </div>
        <div class="col-md-4 text-center">
            <?= $form->field($model, 'iamacth')->radioList(['2' => '很好', '1' => '略好', '0' => '一般', '-1' => '略差', '-2' => '很差'])?>
        </div>
    </div>

    <div class="form-group">
        <div class="col-md-12 text-center">
            <?= $form->field($model, 'text')->textarea(['rows' => 15])?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('添加', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>