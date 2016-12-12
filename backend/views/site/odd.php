<?php

/* @var $this yii\web\View */

use yii\grid\GridView;

$this->title = 'Odd';
?>
<div class="site-index">
    <div class="box-body">
        <table class="table table-striped table-bordered">
            <tr>
                <td>日期</td>
                <td>澳门</td>
                <td>易胜博</td>
            </tr>
            <?php foreach ($odds as $odd)
            {
                $time = isset($odd['bet']['time']) ? $odd['bet']['time'] : (isset($odd['aom']['time']) ? $odd['aom']['time'] : $odd['lji']['time']);
            ?>
            <tr>
                <td><?= date("m-d H:i", strtotime($time));?></td>
                <td><?= isset($odd['aom']) ? "<label class = '". $odd['aom']['home_text'] ."'>". $odd['aom']['home']. "</label>&nbsp&nbsp&nbsp". $odd['aom']['odd'] ."&nbsp&nbsp&nbsp<label class = '". $odd['aom']['away_text'] ."'>". $odd['aom']['away'] ."</label>" : "- - - -";?></td>
                <td><?= isset($odd['lji']) ? "<label class = '". $odd['lji']['home_text'] ."'>". $odd['lji']['home']. "</label>&nbsp&nbsp&nbsp". $odd['lji']['odd'] ."&nbsp&nbsp&nbsp<label class = '". $odd['lji']['away_text'] ."'>". $odd['lji']['away'] ."</label>" : "- - - -";?></td>
            </tr>
            <?php } ?>
        </table>
    </div>
</div>

<?php if(isset($rank_provider)){ ?>
<div class="site-index">
    <div class="box-body">
        <?php
        echo GridView::widget([
            'dataProvider' => $rank_provider,
            'columns' => [
                [
                    'label' => '球队',
                    'value' => function($model){
                        return $model->name;
                    },
                    'headerOptions' => [
                        'width' => '200'
                    ],
                ],
                [
                    'label' => '积分排名',
                    'value' => function($model){
                        return $model->score. "&nbsp($model->standing)";
                    },
                    'format' => 'raw',
                    'headerOptions' => [
                        'width' => '200'
                    ],
                ],
                [
                    'label' => '胜平负',
                    'value' => function($model){
                        return $model->win." / ".$model->draw." / ".$model->lost;
                    },
                    'headerOptions' => [
                        'width' => '200'
                    ],
                ],
                [
                    'label' => '进失球',
                    'value' => function($model){
                        return $model->innum." / ".$model->lostnum;
                    },
                    'headerOptions' => [
                        'width' => '200'
                    ],
                ],
                [
                    'label' => '主客积分',
                    'value' => function($model){
                        return $model->cscore. "&nbsp($model->cstanding)";
                    },
                    'format' => 'raw',
                    'headerOptions' => [
                        'width' => '200'
                    ],
                ],
                [
                    'label' => '主客胜平负',
                    'value' => function($model){
                        return $model->cwin." / ".$model->cdraw." / ".$model->clost;
                    },
                    'headerOptions' => [
                        'width' => '200'
                    ],
                ],
                [
                    'label' => '主客进失球',
                    'value' => function($model){
                        return $model->cinnum." / ".$model->clostnum;
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


<div class="site-index">
        <div class="box-body">
            <?php
            echo GridView::widget([
                'dataProvider' => $power_provider,
                'columns' => [
                    [
                        'label' => '球队',
                        'value' => function ($model) {
                            return $model->type == "h" ? "主队" : "客队";
                        },
                        'headerOptions' => [
                            'width' => '200'
                        ],
                    ],
                    [
                        'label' => '价值分',
                        'value' => function ($model) {
                            return $model->worth_score;
                        },
                        'headerOptions' => [
                            'width' => '200'
                        ],
                    ],
                    [
                        'label' => '攻击力',
                        'value' => function ($model) {
                            return $model->attack_score;
                        },
                        'headerOptions' => [
                            'width' => '200'
                        ],
                    ],
                    [
                        'label' => '防守力',
                        'value' => function ($model) {
                            return $model->defend_score;
                        },
                        'headerOptions' => [
                            'width' => '200'
                        ],
                    ],
                    [
                        'label' => '教练能力',
                        'value' => function ($model) {
                            return $model->tech_score;
                        },
                        'headerOptions' => [
                            'width' => '200'
                        ],
                    ],
                    [
                        'label' => '地区能力',
                        'value' => function ($model) {
                            return $model->state_score;
                        },
                        'headerOptions' => [
                            'width' => '200'
                        ],
                    ],
                    [
                        'label' => '总分',
                        'value' => function ($model) {
                            return $model->total_score . " - - -($model->grade)";
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

<div class="site-index">
    <div class="box-body">
        <?php
        echo GridView::widget([
            'dataProvider' => $ft_provider,
            'columns' => [
                [
                    'label' => '胜率',
                    'value' => function($model){
                        return $model->winrate;
                    },
                    'headerOptions' => [
                        'width' => '200'
                    ],
                ],
                [
                    'label' => '平率',
                    'value' => function($model){
                        return $model->drawrate;
                    },
                    'headerOptions' => [
                        'width' => '200'
                    ],
                ],
                [
                    'label' => '输率',
                    'value' => function($model){
                        return $model->lostrate;
                    },
                    'headerOptions' => [
                        'width' => '200'
                    ],
                ],
                [
                    'label' => '胜平负',
                    'value' => function($model){
                        return $model->win." / ".$model->draw." / ".$model->lost;
                    },
                    'headerOptions' => [
                        'width' => '200'
                    ],
                ],
                [
                    'label' => '进球',
                    'value' => function($model){
                        return $model->innum;
                    },
                    'headerOptions' => [
                        'width' => '200'
                    ],
                ],
                [
                    'label' => '失球',
                    'value' => function($model){
                        return $model->lostnum;
                    },
                    'headerOptions' => [
                        'width' => '200'
                    ],
                ],
            ],
            'summary' => false,
            'headerRowOptions' => ['class' => 'bg-danger'],
        ]);
        ?>
    </div>
</div>

<div class="site-index">
    <div class="box-body">
        <?php
        echo GridView::widget([
            'dataProvider' => $fuck_provider,
            'columns' => [
                [
                    'label' => '时间',
                    'value' => function($model){
                        return date("Y-m-d", strtotime($model->date));
                    },
                    'headerOptions' => [
                        'width' => '200'
                    ],
                ],
                [
                    'label' => '联赛',
                    'value' => function($model){
                        return $model->league;
                    },
                    'headerOptions' => [
                        'width' => '200'
                    ],
                ],
                [
                    'label' => '主队',
                    'value' => function($model){
                        return $model->home;
                    },
                    'headerOptions' => [
                        'width' => '200'
                    ],
                ],
                [
                    'label' => '比分',
                    'value' => function($model){
                        return $model->score;
                    },
                    'headerOptions' => [
                        'width' => '200'
                    ],
                ],
                [
                    'label' => '客队',
                    'value' => function($model){
                        return $model->away;
                    },
                    'headerOptions' => [
                        'width' => '200'
                    ],
                ],
                [
                    'label' => '盘口',
                    'value' => function($model){
                        return $model->handi;
                    },
                    'headerOptions' => [
                        'width' => '200'
                    ],
                ],
            ],
            'summary' => false,
            'headerRowOptions' => ['class' => 'bg-primary'],
        ]);
        ?>
    </div>
</div>


<div class="site-index">
    <div class="box-body">
        <?php
        echo GridView::widget([
            'dataProvider' => $ht_provider,
            'columns' => [
                [
                    'label' => '胜率',
                    'value' => function($model){
                        return $model->winrate;
                    },
                    'headerOptions' => [
                        'width' => '200'
                    ],
                ],
                [
                    'label' => '平率',
                    'value' => function($model){
                        return $model->drawrate;
                    },
                    'headerOptions' => [
                        'width' => '200'
                    ],
                ],
                [
                    'label' => '输率',
                    'value' => function($model){
                        return $model->lostrate;
                    },
                    'headerOptions' => [
                        'width' => '200'
                    ],
                ],
                [
                    'label' => '胜平负',
                    'value' => function($model){
                        return $model->win." / ".$model->draw." / ".$model->lost;
                    },
                    'headerOptions' => [
                        'width' => '200'
                    ],
                ],
                [
                    'label' => '进球',
                    'value' => function($model){
                        return $model->innum;
                    },
                    'headerOptions' => [
                        'width' => '200'
                    ],
                ],
                [
                    'label' => '失球',
                    'value' => function($model){
                        return $model->lostnum;
                    },
                    'headerOptions' => [
                        'width' => '200'
                    ],
                ],
            ],
            'summary' => false,
            'headerRowOptions' => ['class' => 'bg-danger'],
        ]);
        ?>
    </div>
</div>


<div class="site-index">
    <div class="box-body">
        <?php
        echo GridView::widget([
            'dataProvider' => $home_provider,
            'columns' => [
                [
                    'label' => '时间',
                    'value' => function($model){
                        return date("Y-m-d", strtotime($model->date));
                    },
                    'headerOptions' => [
                        'width' => '200'
                    ],
                ],
                [
                    'label' => '联赛',
                    'value' => function($model){
                        return $model->league;
                    },
                    'headerOptions' => [
                        'width' => '200'
                    ],
                ],
                [
                    'label' => '主队',
                    'value' => function($model){
                        return (mb_substr($model->match->hname, 0, 2) == mb_substr(str_replace(" ", "", $model->home), 0, 2)) ? "<label class='text-danger'>$model->home</label>" : $model->home;
                    },
                    'format' => 'raw',
                    'headerOptions' => [
                        'width' => '200'
                    ],
                ],
                [
                    'label' => '比分',
                    'value' => function($model){
                        return $model->score;
                    },
                    'headerOptions' => [
                        'width' => '200'
                    ],
                ],
                [
                    'label' => '客队',
                    'value' => function($model){
                        return (mb_substr($model->match->hname, 0, 2) == mb_substr(str_replace(" ", "", $model->away), 0, 2)) ? "<label class='text-danger'>$model->away</label>" : $model->away;
                    },
                    'format' => 'raw',
                    'headerOptions' => [
                        'width' => '200'
                    ],
                ],
                [
                    'label' => '盘口',
                    'value' => function($model){
                        return $model->handi;
                    },
                    'headerOptions' => [
                        'width' => '200'
                    ],
                ],
            ],
            'summary' => false,
            'headerRowOptions' => ['class' => 'bg-primary'],
        ]);
        ?>
    </div>
</div>


<div class="site-index">
    <div class="box-body">
        <?php
        echo GridView::widget([
            'dataProvider' => $at_provider,
            'columns' => [
                [
                    'label' => '胜率',
                    'value' => function($model){
                        return $model->winrate;
                    },
                    'headerOptions' => [
                        'width' => '200'
                    ],
                ],
                [
                    'label' => '平率',
                    'value' => function($model){
                        return $model->drawrate;
                    },
                    'headerOptions' => [
                        'width' => '200'
                    ],
                ],
                [
                    'label' => '输率',
                    'value' => function($model){
                        return $model->lostrate;
                    },
                    'headerOptions' => [
                        'width' => '200'
                    ],
                ],
                [
                    'label' => '胜平负',
                    'value' => function($model){
                        return $model->win." / ".$model->draw." / ".$model->lost;
                    },
                    'headerOptions' => [
                        'width' => '200'
                    ],
                ],
                [
                    'label' => '进球',
                    'value' => function($model){
                        return $model->innum;
                    },
                    'headerOptions' => [
                        'width' => '200'
                    ],
                ],
                [
                    'label' => '失球',
                    'value' => function($model){
                        return $model->lostnum;
                    },
                    'headerOptions' => [
                        'width' => '200'
                    ],
                ],
            ],
            'summary' => false,
            'headerRowOptions' => ['class' => 'bg-danger'],
        ]);
        ?>
    </div>
</div>

<div class="site-index">
    <div class="box-body">
        <?php
        echo GridView::widget([
            'dataProvider' => $away_provider,
            'columns' => [
                [
                    'label' => '时间',
                    'value' => function($model){
                        return date("Y-m-d", strtotime($model->date));
                    },
                    'headerOptions' => [
                        'width' => '200'
                    ],
                ],
                [
                    'label' => '联赛',
                    'value' => function($model){
                        return $model->league;
                    },
                    'headerOptions' => [
                        'width' => '200'
                    ],
                ],
                [
                    'label' => '主队',
                    'value' => function($model){
                        return mb_substr($model->match->aname, 0, 2) == mb_substr(str_replace(" ", "", $model->home), 0, 2) ? "<label class='text-danger'>$model->home</label>" : $model->home;
                    },
                    'format' => 'raw',
                    'headerOptions' => [
                        'width' => '200'
                    ],
                ],
                [
                    'label' => '比分',
                    'value' => function($model){
                        return $model->score;
                    },
                    'headerOptions' => [
                        'width' => '200'
                    ],
                ],
                [
                    'label' => '客队',
                    'value' => function($model){
                        return mb_substr($model->match->aname, 0, 2) == mb_substr(str_replace(" ", "", $model->away), 0, 2) ? "<label class='text-danger'>$model->away</label>" : $model->away;
                    },
                    'format' => 'raw',
                    'headerOptions' => [
                        'width' => '200'
                    ],
                ],
                [
                    'label' => '盘口',
                    'value' => function($model){
                        return $model->handi;
                    },
                    'headerOptions' => [
                        'width' => '200'
                    ],
                ],
            ],
            'summary' => false,
            'headerRowOptions' => ['class' => 'bg-primary'],
        ]);
        ?>
    </div>
</div>
<?php }?>

