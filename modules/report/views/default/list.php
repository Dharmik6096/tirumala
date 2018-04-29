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
$this->title = Yii::t('app', Yii::$app->label->title('list', '202 - Society Collection Data Report'));
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
                'union_name',
                'district_name',
                'society_code',
                'dcs_code_ex',
                'society_name',
                'first_date_of_data_received',
                'last_date_of_data_received',
                'total_shift',
                'no_of_shift',
                ['attribute'=>'shift_per','value'=>function($model){ return $model['shift_per'].'%';}]
            ];

            $grid_option = [
                'id' => 'report-list',
                'attributes' => $attribute,
                'active_column' => false,
                    //'actions' => []
            ];

            Yii::$app->grid->bind($dataProvider, $model, $grid_option);
            ?>
        </div>
    </div>
</div>

