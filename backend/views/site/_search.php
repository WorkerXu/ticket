<?php

use yii\widgets\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="box box-info">
    <!-- /.box-header -->
    <div class="box-body">
        <div class="panel-body">
            <div class="oauth-user-search">

                <?= Html::beginForm('/site/match', 'get', ['class' => 'form-inline'])?>

                <div class="form-group">
                    <?= Html::input("text", "day", Yii::$app->request->get("day", ""),['class' => 'form-control', 'placeholder' => '结束比赛']);?>
                </div>

                <div class="form-group">
                    <input type="text" name="page" placeholder = '页数' class = 'form-control' value=<?= Yii::$app->request->get('page', 1);?>>
                </div>

                <div class="form-group">
                    <?= Html::dropDownList("type", Yii::$app->request->get("type", 2),
                        [
                            2 => "所有",
                            1 => "竞彩",
                            0 => "外围",
                        ],
                        ['class' => 'form-control']);?>
                </div>

                <div class="form-group">
                    <?= Html::dropDownList("visible", Yii::$app->request->get("visible", true),
                        [
                            true => "比分可见",
                            false => "比分隐藏",
                        ],
                        ['class' => 'form-control']);?>
                </div>

                <div class="form-group">
                    <?= Html::submitButton('搜索', ['class' => 'btn btn-sm btn-primary']) ?>
                </div>

                <? Html::endForm();?>

            </div>

        </div>
    </div>
    <!-- /.box-body -->
</div>

