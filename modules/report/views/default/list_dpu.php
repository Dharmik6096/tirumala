<?php

use yii\widgets\ActiveForm;
use yii\web\View;
use yii\widgets\Pjax;
use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\export\ExportMenu;

$request = Yii::$app->request->queryParams;
$min_date = empty($request["min_date"]) ? '' : $request["min_date"];
$max_date = empty($request["max_date"]) ? '' : $request["max_date"];
$this->title = Yii::t('app', Yii::$app->label->title('list', '206 - No Network Summary Report'));
?>

<div class="tbl-banks-index">
    <div class="panel panel-default panel-grid panel-main">
        <div class="panel-heading">
            <?= $this->title; ?>           
        </div>
        <div class="panel-body">
            <?php echo $this->render('_search_dpu', ['model' => $model, 'detailUrl' => '/report/default/dpu-request']); ?>
            <div class="report-area">

                <?php
                $attribute = [
                        ['attribute' => 'union_name', 'value' => 'union_name', 'label' => Yii::t('app', 'Union Name'), 'filter' => false],
                    'total_dcs',
                    'dpu_dcs',
                    'col_dcs',
                    'no_col_dcs',
                ];

                $grid_option = [
                    'id' => 'report-list-dpu',
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

