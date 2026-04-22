<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use app\components\GeneralFunctions;
use kartik\grid\GridView;
use webvimark\modules\UserManagement\components\GhostHtml;

//$this->title = Yii::$app->label->title('view', 'Product Rate History');
$this->title = Yii::t('app', 'Product Rate History') . ' (' . $searchModel->product_code . '-' . Yii::t('app', $searchModel->productCode->product_name) . ')';
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
                    ['attribute' => 'product_sale_rate_code_val', 'label' => Yii::t('app', 'Rate Code'), 'value' => 'product_sale_rate_code'],
                    [
                        'attribute' => 'union_code', 'filter' => false,
                        'value' => function($model) {
                            return (!empty($model->union_code) || isset($model->union_code)) ? $model->unionCode->union_name : '-';
                        }],
                    [
                        'attribute' => 'product_name',
                        'value' => 'productCode.product_name',
                        'label' => Yii::t('app', 'Product'),
                    ],
                    ['attribute' => 'sale_rate'],
                    [
                        'label' => 'Rate for Gyan',
                        'attribute' => 'rate_wharehouse',
                        'value' => 'rate_wharehouse',
                        'visible' => Yii::$app->session->get('eiplCode') == 'GYAN' ? true : false,
                    ],
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
                    ['attribute' => 'is_member_rate', 'filter' => false, 'value' => function($model) {
                            return $model->is_member_rate == 1 ? Yii::t('app', 'Yes') : Yii::t('app', 'No');
                        }],
                    ['attribute' => 'commission', 'value' => 'commission'],
                    ['attribute' => 'rdo_commission', 'value' => 'rdo_commission'],
                ];

                $grid_option = [
                    'id' => 'sale-rate-history-grid',
                    'attributes' => $attribute,
                    'active_column' => false,
                    'actions' => [
                        'applicabilty' => function ($url, $model) {
                            $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Applicability'];
                            return GhostHtml::a('<i class="fa fa-plus"></i>', ['/product/tbl-product-rate/product-rate-applicability', 'id' => $model->product_sale_rate_code], $options);
                        }
                    ]
                ];

                Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
                ?>
            </div>
        </div>
    </div>
</div>