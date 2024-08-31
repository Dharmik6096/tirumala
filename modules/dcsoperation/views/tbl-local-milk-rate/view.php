<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use app\components\GeneralFunctions;
use kartik\grid\GridView;
use webvimark\modules\UserManagement\components\GhostHtml;

//$this->title = Yii::$app->label->title('view', 'Product Rate History');
$this->title = Yii::t('app', 'Local Milk Rate History');
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
                    ['attribute' => 'local_milk_rate_code', 'label' => Yii::t('app', 'Rate Code'), 'value' => 'local_milk_rate_code'],
                    [
                        'attribute' => 'union_code', 'filter' => false,
                        'value' => function($model) {
                            return (!empty($model->union_code) || isset($model->union_code)) ? $model->unionCode->union_name : '-';
                        }],
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
                ];

                $grid_option = [
                    'id' => 'sale-rate-history-grid',
                    'attributes' => $attribute,
                    'active_column' => false,
                    'actions' => [
                        'applicabilty' => function ($url, $model) {
                            $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Applicability'];
                            return GhostHtml::a('<i class="fa fa-plus"></i>', ['/product/tbl-product-rate/product-rate-applicability', 'id' => $model->local_milk_rate_code], $options);
                        }
                    ]
                ];

                Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
                ?>
            </div>
        </div>
    </div>
</div>