<?php

use kartik\grid\GridView;
use webvimark\modules\UserManagement\components\GhostHtml;

$this->title = Yii::t('app', Yii::$app->label->title('list', 'Grn installments'));
?>

<div class="tbl-product-sale-index">
    <div class="panel panel-default panel-grid panel-main hide-grid-settings">
        <div class="panel-heading">
            <?= $this->title; ?>
        </div>
        <div class="panel-body">
            <?php
            $attribute = [
                    ['attribute' => 'bmc_code', 'visible' => true, 'filter' => false],
                    ['attribute' => 'bmc_code', 'label' => (Yii::t('app', 'BMC Ref.Code')), 'value' => function($model) {
                        return Yii::$app->general->getforeignkey($model->bmcCode, 'ref_code');
                    }, 'visible' => true, 'filter' => false],
                    ['attribute' => 'bmc_code', 'label' => (Yii::t('app', 'BMC Name')), 'value' => function($model) {
                        return Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_name');
                    }, 'visible' => true, 'filter' => false],
                    ['attribute' => 'main_amount', 'filter' => false, 'format' => Yii::$app->general->CurrencyFormat()],
                    ['attribute' => 'installment_amount', 'filter' => false, 'format' => Yii::$app->general->CurrencyFormat()],
                    ['attribute' => 'installment_status',
                    'value' => function($model) {
                        return ($model->installment_status == 1) ? 'Yes' : 'No';
                    },
                    'filter' => false],
            ];

            $grid_option = [
                'id' => 'installment-grid',
                'attributes' => $attribute,
                'active_column' => false,
            ];

            Yii::$app->grid->bind($grnInstallmentdataProvider, $grnInstallmentSearchModel, $grid_option);
            ?>
        </div>
    </div>
</div>