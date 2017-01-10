<?php
/**
 * Created by PhpStorm.
 * User: xutao
 * Date: 16/11/22
 * Time: 12:18
 */
use yii\grid\GridView;
use common\helpers\Mobile;

$this->title = 'Similar';
?>
<?php if(!Mobile::isMobile()){ ?>
<div class="site-index">
    <div class="box-body">
        <?php
        echo GridView::widget([
            'dataProvider' => $provider,
            'columns' => [
                [
                    'label' => '比赛时间',
                    'value' => function($data){
                        return $data->mdate;
                    },
                ],
                [
                    'label' => '主队',
                    'value' => function($data){
                        return $data->hname;
                    },
                ],
                [
                    'label' => '比分',
                    'value' => function($data){
                        return "<label class = 'text-danger'>". $data->score ."</label>";
                    },
                    'format' => 'raw',
                ],
                [
                    'label' => '客队',
                    'value' => function($data){
                        return $data->aname;
                    },
                ],
                [
                    'label' => '操作',
                    'value' => function($data){
                        return(
                            \yii\helpers\Html::a('盘路对比', ['odd', 'fid' => $data->fid, 'date' => $data->mdate], ['class' => 'btn-sm btn-primary', 'style' => 'text-decoration:none', 'target' => '_blank'])."&nbsp&nbsp"
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
                    'label' => '时间',
                    'value' => function($data){
                        return date('m-d',strtotime($data->mdate));
                    },
                ],
                [
                    'label' => '主队',
                    'value' => function($data){
                        return mb_substr($data->hname, 0, 2);
                    },
                ],
                [
                    'label' => '比分',
                    'value' => function($data){
                        return "<label class = 'text-danger'>". $data->score ."</label>";
                    },
                    'format' => 'raw',
                ],
                [
                    'label' => '客队',
                    'value' => function($data){
                        return mb_substr($data->aname, 0, 2);
                    },
                ],
                [
                    'label' => '操作',
                    'value' => function($data){
                        return(
                            \yii\helpers\Html::a('盘路', ['odd', 'fid' => $data->fid, 'date' => $data->mdate], ['class' => 'btn-xs btn-primary', 'style' => 'text-decoration:none', 'target' => '_blank'])
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
