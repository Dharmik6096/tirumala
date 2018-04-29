<?php

use yii\grid\GridView;
use yii\web\View;
use yii\widgets\Pjax;

$this->title = Yii::t('app', Yii::$app->label->title('list', 'Society Wise Milk Collection'));
?>

<div class="tbl-banks-index">
    <div class="panel panel-default panel-grid panel-main">
        <div class="panel-heading">
            <?= $this->title; ?>
        </div>
        <div class="panel-body">
            <?=
            GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $model,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    'dcs_name',
                    'total_shift',
                    'get_shift_data',
                ],
            ]);
            ?>
        </div>
    </div>
</div>