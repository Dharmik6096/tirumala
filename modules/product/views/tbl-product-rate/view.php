<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use app\components\GeneralFunctions;
use kartik\grid\GridView;

//$this->title = Yii::$app->label->title('view', 'Product Rate History');
$this->title = Yii::t('app','Product Rate History').' ('.$searchModel->product_code.'-'.Yii::t('app',$searchModel->productCode->product_name).')';
//$this->params['menu'][] = Yii::$app->controls->update($model->rate_code);
?>
<div class="tbl-purchase-rate-view">
    <div class="panel panel-default panel-grid panel-main">
        <div class="panel-heading">
            <?= Yii::$app->controls->cancel($searchModel); ?>
            <?= Html::encode($this->title) ?>
        </div>
        <div class="panel-body">
            <div class="table-responsive">
                <?php
                $attribute = [
                    ['attribute' => 'product_rate_code_val','label'=>Yii::t('app','Rate Code'),'value'=>'product_rate_code'],
                    [
                        'attribute' => 'product_name',
                        'value' => 'productCode.product_name',
                        'label' => Yii::t('app','Product'),
                    ], 
//                    ['attribute' => 'product_rate_code'],
//                    ['attribute' => 'product_code'],
                    ['attribute' => 'rate'],
                    [
                        'attribute' => 'wef_date', 'width' => '200px',
                        'filterType' => GridView::FILTER_DATE,
                        'filterWidgetOptions' => [
                            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                                'autoclose' => true]
                        ],
                        'value' => function($model) {
                        return Yii::$app->controls->view_date($model->wef_date);
                    }],
                    [
                        'attribute' => 'union_code',
                        'value' => function($model) {
                            return (!empty($model->union_code) || isset($model->union_code)) ? $model->unionCode->union_name : '-';
                    }],
                ];

                $grid_option = [
                    'id' => 'rate-history-grid',
                    'attributes' => $attribute,
                    'active_column' => TRUE,
                ];

                Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
                ?>
            </div>
        </div>
    </div>
</div>