<?php

use yii\widgets\ActiveForm;
use yii\web\View;
use yii\widgets\Pjax;
use kartik\grid\GridView;
use kartik\export\ExportMenu;

$this->title = Yii::t('app', Yii::$app->label->title('list', '215 - DPMCU Information'));
?>

<div class="tbl-banks-index">
    <div class="panel panel-default panel-grid panel-main">
        <div class="panel-heading">
            <?= $this->title; ?>           
        </div>
        <div class="panel-body">
            <?php echo $this->render('_search_custom', ['model' => $model, 'bmc_filter' => true, 'shift_cnt' => FALSE]); ?>

            <?php
            $attribute = [
                ['attribute' => 'bmc_name', 'filter' => false],
                ['attribute' => 'dcs_code', 'filter' => false],
                ['attribute' => 'dcs_name', 'filter' => false],
                'DPU_SerialNo',
                'DPUVersionNo',
                'MA_Internal_Number',
                'MA_External_Number',
                'Last_Calibration_Date',
                'Last_Cleaning_Date'
            ];
            if (!empty($extra))
                $attribute = array_merge($attribute, $extra);
            $grid_option = [
                'id' => 'shift-a-report',
                'attributes' => $attribute,
                'active_column' => false,
            ];


            Yii::$app->grid->bind($dataProvider, $model, $grid_option);
            ?>
        </div>

    </div>
</div>