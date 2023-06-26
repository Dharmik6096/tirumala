<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use app\components\GeneralFunctions;
use kartik\grid\GridView;
use app\modules\usermanagement\components\GhostHtml;

//$this->title = Yii::$app->label->title('view', 'Product Rate History');
$this->title = Yii::t('app', 'Product Purchase Rate History') . ' (' . $searchModel->product_code . '-' . Yii::t('app', $searchModel->productCode->product_name) . ')';
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
                        ['attribute' => 'product_rate_code_val', 'visible' => false, 'label' => Yii::t('app', 'Rate Code'), 'value' => 'product_purchase_rate_code'],
                        [
                        'attribute' => 'union_code', 'filter' => false,
                        'value' => function($model) {
                            return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
                        }],
                        [
                        'attribute' => 'product_name',
                        'value' => function($model) {
                            return Yii::$app->general->getforeignkey($model->productCode, 'product_name');
                        },
                        'label' => Yii::t('app', 'Product'),
                    ],
//                    ['attribute' => 'product_rate_code'],
//                    ['attribute' => 'product_code'],
                    ['attribute' => 'purchase_rate'],
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
                ];

                $grid_option = [
                    'id' => 'purchase-rate-history-grid',
                    'attributes' => $attribute,
                    'active_column' => false,
                    'actions' => [
                        'applicabilty' => function ($url, $model) {
                            $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Applicability'];
                            return GhostHtml::a('<i class="fa fa-plus"></i>', ['/product/tbl-product-purchase-rate/product-purchase-rate-applicability', 'id' => $model->product_purchase_rate_code], $options);
                        }
                    ]
                ];

                Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
                ?>
            </div>
        </div>
    </div>
</div>