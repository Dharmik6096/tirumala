<?php

use yii\widgets\ActiveForm;
use yii\web\View;
use yii\widgets\Pjax;
use kartik\grid\GridView;
use kartik\export\ExportMenu;

$this->title = Yii::t('app', Yii::$app->label->title('list', '211 - Society Shift Report'));
?>

<div class="tbl-banks-index">
    <div class="panel panel-default panel-grid panel-main">
        <div class="panel-heading">
            <?= $this->title; ?>           
        </div>
        <div class="panel-body">
            <?php echo $this->render('_search', ['model' => $model, 'shiftFilter' => true]); ?>
            <div class="report-area">

                <?php
                $attribute = [
                        ['attribute' => 'dcs_code', 'filter' => false],
                        ['attribute' => 'dcs_name', 'filter' => false],
                        ['attribute' => 'dtdate', 'filter' => false],
                        ['attribute' => 'qty', 'filter' => false],
                        ['attribute' => 'avg_fat', 'filter' => false],
                        ['attribute' => 'avg_snf', 'filter' => false],
                        ['attribute' => 'kg_fat', 'filter' => false],
                        ['attribute' => 'kg_snf', 'filter' => false],
                        ['attribute' => 'avg_rate', 'filter' => false],
                        ['attribute' => 'total_amount', 'filter' => false,
                        'hAlign' => Yii::$app->general->ColoumnAlign(),
                        'format' => Yii::$app->general->CurrencyFormat(),
                    ],
                ];
                $grid_option = [
                    'id' => 'dcs-report-report',
                    'attributes' => $attribute,
                    'active_column' => false,
                        //'actions' => []
                ];


                Yii::$app->grid->bind($dataProvider, $model, $grid_option);
                ?>
            </div>
        </div>
    </div>
</div>