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
            <?= $form->field($model, 'hsocre')->radioList(['3' => '很优', '2' => '较优', '1' => '略优', '0' => '持平', '-1' => '略劣', '-2' => '较劣', '-3' => '很劣'])?>
        </div>
        <div class="col-md-4 text-center">
            <?= $form->field($model, 'asocre')->radioList(['3' => '很优', '2' => '较优', '1' => '略优', '0' => '持平', '-1' => '略劣', '-2' => '较劣', '-3' => '很劣'])?>
        </div>
        <div class="col-md-4 text-center">
            <?= $form->field($model, 'hmatch')->radioList(['3' => '很优', '2' => '较优', '1' => '略优', '0' => '持平', '-1' => '略劣', '-2' => '较劣', '-3' => '很劣'])?>
        </div>
    </div>
    <div class="form-group">
        <div class="col-md-4 text-center">
            <?= $form->field($model, 'ihsocre')->radioList(['3' => '很好', '2' => '较好', '1' => '略好', '0' => '一般', '-1' => '略差', '-2' => '较差', '-3' => '很差'])?>
        </div>
        <div class="col-md-4 text-center">
            <?= $form->field($model, 'amatch')->radioList(['3' => '很优', '2' => '较优', '1' => '略优', '0' => '持平', '-1' => '略劣', '-2' => '较劣', '-3' => '很劣'])?>
        </div>
        <div class="col-md-4 text-center">
            <?= $form->field($model, 'iasocre')->radioList(['3' => '很好', '2' => '较好', '1' => '略好', '0' => '一般', '-1' => '略差', '-2' => '较差', '-3' => '很差'])?>
        </div>
    </div>
    <div class="form-group">
        <div class="col-md-4 text-center">
            <?= $form->field($model, 'fmatch')->radioList(['3' => '很优', '2' => '较优', '1' => '略优', '0' => '持平', '-1' => '略劣', '-2' => '较劣', '-3' => '很劣'])?>
        </div>
        <div class="col-md-4 text-center">
            <?= $form->field($model, 'famcth')->radioList(['3' => '很优', '2' => '较优', '1' => '略优', '0' => '持平', '-1' => '略劣', '-2' => '较劣', '-3' => '很劣'])?>
        </div>
        <div class="col-md-4 text-center">
            <?= $form->field($model, 'ftmacth')->radioList(['3' => '很优', '2' => '较优', '1' => '略优', '0' => '持平', '-1' => '略劣', '-2' => '较劣', '-3' => '很劣'])?>
        </div>
    </div>

    <div class="form-group">
        <div class="col-md-4 text-center">
            <?= $form->field($model, 'shmatch')->radioList(['3' => '很优', '2' => '较优', '1' => '略优', '0' => '持平', '-1' => '略劣', '-2' => '较劣', '-3' => '很劣'])?>
        </div>
        <div class="col-md-4 text-center">
            <?= $form->field($model, 'ishmatch')->radioList(['3' => '很好', '2' => '较好', '1' => '略好', '0' => '一般', '-1' => '略差', '-2' => '较差', '-3' => '很差'])?>
        </div>
        <div class="col-md-4 text-center">
            <?= $form->field($model, 'thmatch')->radioList(['3' => '很优', '2' => '较优', '1' => '略优', '0' => '持平', '-1' => '略劣', '-2' => '较劣', '-3' => '很劣'])?>
        </div>
    </div>

    <div class="form-group">
        <div class="col-md-4 text-center">
            <?= $form->field($model, 'ithmatch')->radioList(['3' => '很好', '2' => '较好', '1' => '略好', '0' => '一般', '-1' => '略差', '-2' => '较差', '-3' => '很差'])?>
        </div>
        <div class="col-md-4 text-center">
            <?= $form->field($model, 'samatch')->radioList(['3' => '很优', '2' => '较优', '1' => '略优', '0' => '持平', '-1' => '略劣', '-2' => '较劣', '-3' => '很劣'])?>
        </div>
        <div class="col-md-4 text-center">
            <?= $form->field($model, 'isamatch')->radioList(['3' => '很好', '2' => '较好', '1' => '略好', '0' => '一般', '-1' => '略差', '-2' => '较差', '-3' => '很差'])?>
        </div>
    </div>

    <div class="form-group">
        <div class="col-md-4 text-center">
            <?= $form->field($model, 'tamatch')->radioList(['3' => '很优', '2' => '较优', '1' => '略优', '0' => '持平', '-1' => '略劣', '-2' => '较劣', '-3' => '很劣'])?>
        </div>
        <div class="col-md-4 text-center">
            <?= $form->field($model, 'itamatch')->radioList(['3' => '很好', '2' => '较好', '1' => '略好', '0' => '一般', '-1' => '略差', '-2' => '较差', '-3' => '很差'])?>
        </div>
        <div class="col-md-4 text-center">
            <?= $form->field($model, 'power')->radioList(['3' => '很优', '2' => '较优', '1' => '略优', '0' => '持平', '-1' => '略劣', '-2' => '较劣', '-3' => '很劣'])?>
        </div>
    </div>

    <div class="form-group">
        <div class="col-md-12 text-center">
            <?= $form->field($model, 'text')->textarea(['rows' => 15])?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('添加', ['class' => 'btn btn-success']) ?>
        <?= Html::a('自动生成', ['cal-socre', 'id' => $id], ['class' => 'btn btn-primary', 'style' => 'text-decoration:none'])?>
    </div>

    <?php ActiveForm::end(); ?>
</div>