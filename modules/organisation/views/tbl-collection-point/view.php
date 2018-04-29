<?php

use yii\helpers\Html;
use app\components\GeneralFunctions;
use kartik\detail\DetailView;

$this->title = $model->collection_point_no;
$this->params['menu'][] = Yii::$app->controls->update($model->collection_point_no);
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
                'collection_point_no',
//                    'dcsCode.dcs_name',
//                    'milkQualityTypeCode.animal_type_name',
//                    'subCenterCode.sub_center_name',
                [
                    'attribute' => 'is_default',
                    'label' => 'Default',
                    'format' => 'html',
                    'value' => ($model->is_default == 1) ? 'Yes' : 'No'
                ],
                [
                    'attribute' => 'is_active',
                    'label' => 'Status',
                    'format' => 'html',
                    'value' => GeneralFunctions::getRecordStatus($model->is_active)
                ],
                [
                    'attribute' => 'milk_type_code',
                    'value' => $model->milkType(),
                    'valueColOptions' => ['style' => 'width:30%'],
                    'format' => 'html',
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