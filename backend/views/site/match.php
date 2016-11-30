<?php
/**
 * Created by PhpStorm.
 * User: xutao
 * Date: 16/11/22
 * Time: 12:18
 */
use yii\grid\GridView;

$this->title = 'Match';
?>
<?= $this->render('_search');?>

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