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
            </tr>
            <?php foreach ($odds as $odd)
            {
                $time = isset($odd['bet']['time']) ? $odd['bet']['time'] : $odd['lji']['time'];
            ?>
            <tr>
                <td><?= date("m-d H:i", strtotime($time));?></td>
                <td><?= isset($odd['lji']) ? "<label class = '". $odd['lji']['home_text'] ."'>". $odd['lji']['home']. "</label>&nbsp&nbsp&nbsp". $odd['lji']['odd'] ."&nbsp&nbsp&nbsp<label class = '". $odd['lji']['away_text'] ."'>". $odd['lji']['away'] ."</label>" : "- - - -";?></td>
                <td><?= isset($odd['bet']) ? "<label class = '". $odd['bet']['home_text'] ."'>". $odd['bet']['home']. "</label>&nbsp&nbsp&nbsp". $odd['bet']['odd'] ."&nbsp&nbsp&nbsp<label class = '". $odd['bet']['away_text'] ."'>". $odd['bet']['away'] ."</label>" : "- - - -";?></td>
            </tr>
            <?php } ?>
        </table>
    </div>
</div>

<?php if(!Mobile::isMobile()){ ?>
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

<?php }else{ ?>

<?php if(isset($rank_provider)){ ?>
<div class="site-index">
    <div class="box-body">
        <?php
        echo GridView::widget([
            'dataProvider' => $rank_provider,
            'columns' => [
                [
                    'label' => '赛季',
                    'value' => function($model){
                        return mb_substr($model->name, 0, 2);
                    },
                    'headerOptions' => [
                        'width' => '200'
                    ],
                ],
                [
                    'label' => '排名',
                    'value' => function($model){
                        return $model->standing;
                    },
                    'format' => 'raw',
                    'headerOptions' => [
                        'width' => '200'
                    ],
                ],
                [
                    'label' => '积分',
                    'value' => function($model){
                        return $model->score;
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
                    'dataProvider' => $rank_provider,
                    'columns' => [
                        [
                            'label' => '主客',
                            'value' => function($model){
                                return mb_substr($model->name, 0, 2);
                            },
                            'headerOptions' => [
                                'width' => '200'
                            ],
                        ],
                        [
                            'label' => '排名',
                            'value' => function($model){
                                return $model->cstanding;
                            },
                            'format' => 'raw',
                            'headerOptions' => [
                                'width' => '200'
                            ],
                        ],
                        [
                            'label' => '积分',
                            'value' => function($model){
                                return $model->cscore;
                            },
                            'format' => 'raw',
                            'headerOptions' => [
                                'width' => '200'
                            ],
                        ],
                        [
                            'label' => '胜平负',
                            'value' => function($model){
                                return $model->cwin." / ".$model->cdraw." / ".$model->clost;
                            },
                            'headerOptions' => [
                                'width' => '200'
                            ],
                        ],
                        [
                            'label' => '进失球',
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
                        return $model->type == "h" ? "主" : "客";
                    },
                    'headerOptions' => [
                        'width' => '200'
                    ],
                ],
                [
                    'label' => '身价',
                    'value' => function ($model) {
                        return $model->worth_score;
                    },
                    'headerOptions' => [
                        'width' => '200'
                    ],
                ],
                [
                    'label' => '攻击',
                    'value' => function ($model) {
                        return $model->attack_score;
                    },
                    'headerOptions' => [
                        'width' => '200'
                    ],
                ],
                [
                    'label' => '防守',
                    'value' => function ($model) {
                        return $model->defend_score;
                    },
                    'headerOptions' => [
                        'width' => '200'
                    ],
                ],
                [
                    'label' => '技术',
                    'value' => function ($model) {
                        return $model->tech_score;
                    },
                    'headerOptions' => [
                        'width' => '200'
                    ],
                ],
                [
                    'label' => '状态',
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
                        return date('y-m-d', strtotime($model->date)).":".mb_substr($model->league, 0 ,2);
                    },
                    'headerOptions' => [
                        'width' => '200'
                    ],
                ],
                [
                    'label' => '主队',
                    'value' => function($model){
                        return mb_substr($model->home, 0, 2);
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
                        return mb_substr($model->away, 0, 2);
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
                        return date('y-m-d', strtotime($model->date)).":".mb_substr($model->league, 0 ,2);
                    },
                    'headerOptions' => [
                        'width' => '200'
                    ],
                ],
                [
                    'label' => '主队',
                    'value' => function($model){
                        return (mb_substr($model->match->hname, 0, 2) == mb_substr(str_replace(" ", "", $model->home), 0, 2)) ? "<label class='text-danger'>".mb_substr($model->home, 0, 2)."</label>" : mb_substr($model->home, 0, 2);
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
                        return (mb_substr($model->match->hname, 0, 2) == mb_substr(str_replace(" ", "", $model->away), 0, 2)) ? "<label class='text-danger'>".mb_substr($model->away, 0, 2)."</label>" : mb_substr($model->away, 0, 2);
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
                    'value' => function($data){
                        return date('y-m-d', strtotime($data->date)).":".mb_substr($data->league, 0 ,2);
                    },
                    'headerOptions' => [
                        'width' => '200'
                    ],
                ],
                [
                    'label' => '主队',
                    'value' => function($model){
                        return mb_substr($model->match->aname, 0, 2) == mb_substr(str_replace(" ", "", $model->home), 0, 2) ? "<label class='text-danger'>".mb_substr($model->home, 0, 2)."</label>" : mb_substr($model->home, 0, 2);
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
                        return mb_substr($model->match->aname, 0, 2) == mb_substr(str_replace(" ", "", $model->away), 0, 2) ? "<label class='text-danger'>".mb_substr($model->away, 0, 2)."</label>" : mb_substr($model->away, 0, 2);
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
<?php } ?>

<?php } ?>
