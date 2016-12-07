<?php
/**
 * Created by PhpStorm.
 * User: xutao
 * Date: 16/11/22
 * Time: 12:18
 */
use yii\grid\GridView;

$this->title = 'Similar';
?>

<div class="site-index">
    <div class="box-body">
        <?php
        echo GridView::widget([
            'dataProvider' => $provider,
            'columns' => [
                [
                    'label' => '比赛时间',
                    'value' => function($data){
                        return $data->match->mdate;
                    },
                ],
                [
                    'label' => '主队',
                    'value' => function($data){
                        return $data->match->hname;
                    },
                ],
                [
                    'label' => '比分',
                    'value' => function($data){
                        return "<label class = 'text-danger'>". $data->match->score ."</label>";
                    },
                    'format' => 'raw',
                ],
                [
                    'label' => '客队',
                    'value' => function($data){
                        return $data->match->aname;
                    },
                ],
                [
                    'label' => '操作',
                    'value' => function($data){
                        return(
                            \yii\helpers\Html::a('盘路走势', ['mysql-odd', 'fid' => $data->match->fid], ['class' => 'btn-sm btn-primary', 'style' => 'text-decoration:none', 'target' => '_blank'])."&nbsp&nbsp".
                            \yii\helpers\Html::a('盘路笔记', ['add-socre', 'id' => $data->match->id], ['class' => 'btn-sm btn-primary', 'style' => 'text-decoration:none', 'target' => '_blank'])
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