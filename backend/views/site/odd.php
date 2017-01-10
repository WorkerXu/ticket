<?php

/* @var $this yii\web\View */

use yii\grid\GridView;
use common\helpers\Mobile;

$this->title = 'Odd';
?>
<div class="site-index">
    <div class="box-body">
        <table class="table table-striped table-bordered">
            <tr>
                <td>日期</td>
                <td>澳门</td>
                <td>易胜博</td>
                <td>12BET</td>
            </tr>
            <?php foreach ($odds as $odd)
            {
                $time = isset($odd['bet']['time']) ? $odd['bet']['time'] : (isset($odd['aom']['time']) ? $odd['aom']['time'] : $odd['ysb']['time']);
            ?>
            <tr>
                <td width="200px"><?= date("m-d H:i", strtotime($time));?></td>
                <td width="200px"><?= isset($odd['aom']) ? "<label class = '". $odd['aom']['home_text'] ."'>". $odd['aom']['home']. "</label>&nbsp&nbsp&nbsp". $odd['aom']['odd'] ."&nbsp&nbsp&nbsp<label class = '". $odd['aom']['away_text'] ."'>". $odd['aom']['away'] ."</label>" : "- - - -";?></td>
                <td width="200px"><?= isset($odd['ysb']) ? "<label class = '". $odd['ysb']['home_text'] ."'>". $odd['ysb']['home']. "</label>&nbsp&nbsp&nbsp". $odd['ysb']['odd'] ."&nbsp&nbsp&nbsp<label class = '". $odd['ysb']['away_text'] ."'>". $odd['ysb']['away'] ."</label>" : "- - - -";?></td>
                <td width="200px"><?= isset($odd['bet']) ? "<label class = '". $odd['bet']['home_text'] ."'>". $odd['bet']['home']. "</label>&nbsp&nbsp&nbsp". $odd['bet']['odd'] ."&nbsp&nbsp&nbsp<label class = '". $odd['bet']['away_text'] ."'>". $odd['bet']['away'] ."</label>" : "- - - -";?></td>
            </tr>
            <?php } ?>
        </table>
    </div>
</div>

<div class="site-index">
    <div class="box-body">
        <?php
        echo GridView::widget([
            'dataProvider' => $provider,
            'columns' => [
                [
                    'label' => '类别',
                    'value' => function($model){
                        return (new \common\models\Scores())->getText($model->tag);
                    },
                    'headerOptions' => [
                        'width' => '200'
                    ],
                ],
                [
                    'label' => '胜',
                    'value' => function($model){
                        return $model->win;
                    },
                    'headerOptions' => [
                        'width' => '200'
                    ],
                ],
                [
                    'label' => '平',
                    'value' => function($model){
                        return $model->draw;
                    },
                    'headerOptions' => [
                        'width' => '200'
                    ],
                ],
                [
                    'label' => '负',
                    'value' => function($model){
                        return $model->lost;
                    },
                    'headerOptions' => [
                        'width' => '200'
                    ],
                ],

            ],
            'summary' => false,
            'headerRowOptions' => ['class' => 'bg-success'],
        ]);
        ?>
    </div>
</div>




