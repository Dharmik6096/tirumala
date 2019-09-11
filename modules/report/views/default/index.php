<?php

use yii\widgets\ActiveForm;
use yii\web\View;
use yii\widgets\Pjax;
use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\export\ExportMenu;

$this->title = Yii::t('app', Yii::$app->label->title('list', '203 - Society Collection Summary Report'));
?>

<div class="tbl-banks-index">
    <div class="panel panel-default panel-grid panel-main">
        <div class="panel-heading">
            <?= $this->title; ?>
            <div class="btn-create">
                <!--<a href="create.html" class="" target="_blank">View Report</a>-->
            </div>
        </div>
        <div class="panel-body">
            <div class="report-area">
                <?php echo $this->render('_search', ['model' => $model]); ?>


                <?php
                $attribute = [
                    ['attribute' => 'dcs_code', 'filter' => false],
                    'dcs_code_ex',
                    'dcs_name',
                    'total_shift',
                    'received_shift_data',
                    'total_qty',
                    'avg_fat',
                    'avg_snf',
                    ['attribute' => 'amount',
                        'hAlign' => Yii::$app->general->ColoumnAlign(),
                        'format' => Yii::$app->general->CurrencyFormat(),
                    ],
                ];


                $grid_option = [
                    'id' => 'report-index',
                    'attributes' => $attribute,
                    'active_column' => false,
                        //'actions' => []
                ];

                Yii::$app->grid->bind($dataProvider, $model, $grid_option, false);
                ?>
            </div>
        </div>
    </div>
</div>
