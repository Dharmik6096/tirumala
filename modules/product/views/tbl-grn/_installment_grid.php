<?php

use kartik\grid\GridView;
use webvimark\modules\UserManagement\components\GhostHtml;

$this->title = Yii::t('app', Yii::$app->label->title('list', 'Grn installments'));
?>

<div class="tbl-product-sale-index">
    <div class="panel panel-default panel-grid panel-main">
        <div class="panel-heading">
            <?= $this->title; ?>
        </div>
        <div class="panel-body">
            <?php
            $attribute = [
                    ['attribute' => 'main_amount', 'filter' => false, 'format' => Yii::$app->general->CurrencyFormat(),],
                    ['attribute' => 'installment_amount', 'filter' => false, 'format' => Yii::$app->general->CurrencyFormat(),],
                    ['attribute' => 'installment_status', 'filter' => false],
            ];

            $grid_option = [
                'id' => 'installment-grid',
                'attributes' => $attribute,
                'active_column' => false,
                'actions' => [
                    'view' => false,
                ]
            ];

            Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
            ?>
        </div>
    </div>
</div>