<?php
/**
 * Created by PhpStorm.
 * User: xutao
 * Date: 16/11/22
 * Time: 12:18
 */
use yii\grid\GridView;
use common\helpers\Mobile;

$this->title = 'Match';
?>
<?= $this->render('_search');?>

<?php if(!Mobile::isMobile()){ ?>
    <div class="site-index">
        <div class="box-body">
            <?php
                echo GridView::widget([
                    'dataProvider' => $provider,
                    'columns' => [
                        [
                            'label' => '比赛时间',
                            'attribute' => 'vsdate',
                            'format' => ['date', 'php:m-d H:i'],
                        ],
                        [
                            'label' => '联赛',
                            'attribute' => 'lname',
                        ],
                        [
                            'label' => '主队',
                            'attribute' => 'hname',
                        ],
                        [
                            'label' => '比分',
                            'value' => function($data){
                                return "<label class = 'text-danger'>". $data->hscore ."</label> : <label class = 'text-danger'>". $data->ascore ."</label>";
                            },
                            'format' => 'raw',
                            'visible' => Yii::$app->request->get('visible', true),
                        ],
                        [
                            'label' => '客队',
                            'attribute' => 'aname',
                        ],
                        [
                            'label' => '主',
                            'attribute' => 'w',
                            'format' => ['decimal'],
                        ],
                        [
                            'label' => '让球',
                            'value' => function($data){
                                if((float)$data->p <= 0)
                                {
                                    return $data->pshow;
                                }
                                else
                                {
                                    return str_replace("-", "", $data->pshow);
                                }
                            },
                        ],
                        [
                            'label' => '客',
                            'attribute' => 'l',
                            'format' => ['decimal'],
                        ],
                        [
                            'label' => '操作',
                            'value' => function($data){
                                $tip = is_null(\common\models\Match::findOne(["fid" => $data->fid])) ? 'btn-warning' : 'btn-primary';
                                return(
                                    \yii\helpers\Html::a('盘路走势', ['odd', 'fid' => $data->fid, 'date' => $data->vsdate], ['class' => 'btn-sm btn-primary', 'style' => 'text-decoration:none', 'target' => '_blank'])."&nbsp&nbsp".
                                    \yii\helpers\Html::a('数据库存储', ['mysql-odd', 'fid' => $data->fid], ['class' => 'btn-sm btn-primary', 'style' => 'text-decoration:none', 'target' => '_blank'])."&nbsp&nbsp".
                                    \yii\helpers\Html::a('相似盘路', ['similar', 'fid' => $data->fid], ['class' => 'btn-sm btn-primary', 'style' => 'text-decoration:none', 'target' => '_blank'])."&nbsp&nbsp".
                                    \yii\helpers\Html::a('加入数据库', [
                                        'store',
                                        'data'   => $data
                                    ], ['class'  => 'btn-sm '.$tip, 'style' => 'text-decoration:none', 'target' => '_blank'])
                                );
                            },
                            'format' => 'raw',
                        ],
                    ],
                    'summary' => false,
                    'rowOptions' => ['class' => 'text-center'],
                    'headerRowOptions' => ['class' => 'text-center', 'align' => 'center'],
                ]);
            ?>
        </div>
    </div>
<?php }else{ ?>
    <div class="site-index">
        <div class="box-body">
            <?php
            echo GridView::widget([
                'dataProvider' => $provider,
                'columns' => [
                    [
                        'label' => '比赛',
                        'attribute' => 'vsdate',
                        'value' => function($data){
                            if (!Yii::$app->request->get('day')){
                                $fm = 'h:i';
                            }else{
                                $fm = 'm-d';
                            }
                            return date($fm, strtotime($data->vsdate))."&nbsp:&nbsp".mb_substr($data->lname, 0 ,2);
                        },
                        'format' => 'raw',
                    ],
                    [
                        'label' => '对阵',
                        'value' => function($data){
                            return mb_substr($data->hname, 0 ,2)."<label class = 'text-danger'>$data->hscore:$data->ascore</label>".mb_substr($data->aname, 0 ,2);
                        },
                        'format' => 'raw',
                        'visible' => Yii::$app->request->get('visible', true),
                    ],
                    [
                        'label' => '让球',
                        'value' => function($data){
                            if((float)$data->p <= 0)
                            {
                                $odd = $data->pshow;
                            }
                            else
                            {
                                $odd = str_replace("-", "", $data->pshow);
                            }
                            return "<label class = 'text-info'>$odd</label>";
                        },
                        'format' => 'raw',
                    ],
                    [
                        'label' => '操作',
                        'value' => function($data){
                            $tip = is_null(\common\models\Match::findOne(["fid" => $data->fid])) ? 'btn-warning' : 'btn-primary';
                            return(
                                \yii\helpers\Html::a('盘', ['odd', 'fid' => $data->fid, 'date' => $data->vsdate], ['class' => 'btn-xs btn-primary', 'style' => 'text-decoration:none', 'target' => '_blank'])." ".
                                \yii\helpers\Html::a('同', ['similar', 'fid' => $data->fid], ['class' => 'btn-xs btn-primary', 'style' => 'text-decoration:none', 'target' => '_blank'])." ".
                                \yii\helpers\Html::a('存', [
                                    'store',
                                    'data'   => $data
                                ], ['class'  => 'btn-xs '.$tip, 'style' => 'text-decoration:none', 'target' => '_blank'])
                            );
                        },
                        'format' => 'raw',
                    ],
                ],
                'summary' => false,
                'rowOptions' => ['class' => 'text-center'],
                'headerRowOptions' => ['class' => 'text-center', 'align' => 'center'],
            ]);
            ?>
        </div>
    </div>
<?php } ?>
