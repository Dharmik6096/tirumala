<?php

use yii\widgets\ActiveForm;
use yii\web\View;
use yii\widgets\Pjax;
use kartik\grid\GridView;
use kartik\export\ExportMenu;

$this->title = Yii::t('app', Yii::$app->label->title('list', '201 - Union Collection Report'));
?>

<div class="tbl-banks-index">
    <div class="panel panel-default panel-grid panel-main">
        <div class="panel-heading">
            <?= $this->title; ?>           
        </div>
        <div class="panel-body">
            <?php echo $this->render('_search', ['model' => $model]); ?>

            <?php
            $attribute = [
                ['attribute' => 'union_name', 'value' => 'union_name', 'label' => Yii::t('app', 'Union Name'), 'filter' => false],
                ['attribute' => 'total_society', 'value' => 'total_society', 'label' => Yii::t('app', 'Total Society'), 'filter' => false],
                ['attribute' => 'data_received_for_society', 'value' => 'data_received_for_society', 'label' => Yii::t('app', 'Data Received For Society'), 'filter' => false],
            ];

            $grid_option = [
                'id' => 'report-count',
                'attributes' => $attribute,
                'active_column' => false,
                    //'actions' => []
            ];

            Yii::$app->grid->bind($dataProvider, $model, $grid_option);
            ?>
        </div>

    </div>
</div>