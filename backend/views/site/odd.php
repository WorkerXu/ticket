<?php

/* @var $this yii\web\View */

$this->title = 'Odd';
?>
<div class="site-index">
    <div class="box-body">
        <table class="table table-striped table-bordered">
            <tr>
                <td>日期</td>
                <td>变动</td>
                <td>利记</td>
                <td>10bet</td>
                <td>变动</td>
            </tr>
            <?php foreach ($odds as $odd)
            {
                $time = isset($odd['bet']['time']) ? $odd['bet']['time'] : $odd['lji']['time'];
            ?>
            <tr>
                <td><?= date("m-d H:i", strtotime($time));?></td>
                <td><?= isset($odd['lji']['bet_home']) ? sprintf("%.2f", abs($odd['lji']['bet_home'])). "&nbsp- -&nbsp" .sprintf("%.2f", abs($odd['lji']['bet_away'])) : "- - - - - -";?></td>
                <td><?= isset($odd['lji']) ? "<label class = '". $odd['lji']['home_text'] ."'>". $odd['lji']['home']. "</label>&nbsp&nbsp&nbsp". $odd['lji']['odd'] ."&nbsp&nbsp&nbsp<label class = '". $odd['lji']['away_text'] ."'>". $odd['lji']['away'] ."</label>" : "- - - - - - - -";?></td>
                <td><?= isset($odd['bet']) ? "<label class = '". $odd['bet']['home_text'] ."'>". $odd['bet']['home']. "</label>&nbsp&nbsp&nbsp". $odd['bet']['odd'] ."&nbsp&nbsp&nbsp<label class = '". $odd['bet']['away_text'] ."'>". $odd['bet']['away'] ."</label>" : "- - - - - - - -";?></td>
                <td><?= isset($odd['bet']['lji_home']) ? sprintf("%.2f", abs($odd['bet']['lji_home'])). "&nbsp- -&nbsp" .sprintf("%.2f", abs($odd['bet']['lji_away'])) : "- - - - - -";?></td>
            </tr>
            <?php } ?>
        </table>
    </div>
</div>
