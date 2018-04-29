<?php

use yii\helpers\Html;
use app\components\GeneralFunctions;
use kartik\detail\DetailView;

$this->title = Yii::$app->label->title('view', 'route');
$this->params['menu'][]=Yii::$app->controls->update($model->route_code);
?>
<div class="panel panel-default panel-grid panel-main">
    <div class="panel-heading">
        <?= Yii::$app->controls->cancel($model); ?>
        <?= Html::encode($this->title) ?>
    </div>
    <div class="panel-body">
        <div class="table-responsive">
            <?php
            // DetailView Attributes Configuration
            $attributes = [
                [
                    'attribute' => 'union_code',
                    'label' => 'Union',
                    'value' => isset($model->unionCode)?$model->unionCode->union_name:'',
                    'valueColOptions' => ['style' => 'width:80%'],
                ],
//                    'unionCode.union_name',
                'route_code',
                'route_name',
                'local_name',
                'route_length_kms',
                [
                    'attribute' => 'vehicle_type_code',
                    'value' => isset($model->vehicleType)?$model->vehicleType->vehicle_type_name:'',
                    'valueColOptions' => ['style' => 'width:80%'],
                ],
                [
                    'attribute' => 'bmc_code',
                    'value' => isset($model->tblDcsBmc) ? $model->tblDcsBmc->bmc_name : '',
                    'valueColOptions' => ['style' => 'width:80%'],
                ],
//                    'vehicalType.vehical_type',
                'capacity',
                'start_time',
                'return_time',
                [
                    'attribute' => 'is_active',
                    'label' => 'Status',
                    'format' => 'html',
                    'value' => GeneralFunctions::getRecordStatus($model->is_active)
                ],
            ];

// View file rendering the widget
            echo DetailView::widget([
                'model' => $model,
                'attributes' => $attributes,
                'mode' => 'view',
                'bordered' => true,
                'striped' => false,
                'responsive' => true,
                'hAlign' => 'left',
                'vAlign' => 'top',
                'deleteOptions' => [ // your ajax delete parameters
                    'params' => ['id' => 1000, 'kvdelete' => true],
                ],
                'container' => ['id' => 'kv-demo'],
            ]);
            ?>
        </div>
    </div>
</div>