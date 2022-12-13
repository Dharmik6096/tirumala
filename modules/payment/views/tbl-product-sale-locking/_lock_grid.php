<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\web\View;
use yii\helpers\Html;
use kartik\grid\GridView;

$action = Url::to(['create']);
?>
<div class=""></div>

<?php
$form = ActiveForm::begin([
            'id' => 'product-sale-lock-grid',
        ]);
?>
<div class="grid-search no-effect" >

    <?php
    echo Html::activeHiddenInput($searchModel, 'union_code', ['id' => 'set_union_code']);
    echo Html::activeHiddenInput($searchModel, 'plant_code', ['id' => 'set_plant_code']);
    echo Html::activeHiddenInput($searchModel, 'mcc_plant_code', ['id' => 'set_mcc_plant_code']);
    echo Html::activeHiddenInput($searchModel, 'bmc_code', ['id' => 'set_bmc_code']);
    echo Html::activeHiddenInput($searchModel, 'from_date', ['id' => 'set_from_date']);
    echo Html::activeHiddenInput($searchModel, 'to_date', ['id' => 'set_to_date']);
    echo Html::activeHiddenInput($searchModel, 'locking_date', ['id' => 'set_locking_date']);
    echo Html::activeHiddenInput($searchModel, 'type', ['id' => 'set_type']);
    $attribute = [
        ['attribute' => 'bmc_code',
            'value' => function ($model, $key, $index) {
                echo Html::hiddenInput('product_sale_transaction_code[' . $index . ']', $model['product_sale_transaction_code'], ['id' => '[' . $index . ']product_sale_transaction_code']);
                return $model['bmc_code'];
            },
            'filter' => FALSE],
        ['attribute' => 'bmc', 'filter' => FALSE],
        ['attribute' => 'customer_type', 'filter' => FALSE],
        ['attribute' => 'customer_name', 'filter' => FALSE],
        ['attribute' => 'ref_code', 'filter' => FALSE],
        ['attribute' => 'code_ex', 'filter' => FALSE],
        ['attribute' => 'invoice_date', 'filter' => FALSE],
        ['attribute' => 'product', 'filter' => FALSE],
        ['attribute' => 'sap_batch_no', 'filter' => FALSE],
        ['attribute' => 'quantity', 'filter' => FALSE],
        ['attribute' => 'amount', 'filter' => false, 'format' => Yii::$app->general->CurrencyFormat(),],
        ['attribute' => 'rate', 'filter' => false, 'format' => Yii::$app->general->CurrencyFormat(),],
    ];

    $grid_option = [
        'id' => 'lock-list',
        'attributes' => $attribute,
        'active_column' => false,
        'showPageSummary' => false,
    ];

    Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option, ['#'], false);
    ?>
</div>
<div class="col-sm-12 margin-top-10 form-group" >
    <?php
    if (!empty($dataProvider->getModels())) {
        echo Html::button(Yii::t('app', 'Lock'), ['class' => 'btn btn-primary', 'id' => 'lock']);
    }
    ?>
    <?= Yii::$app->controls->custombutton('Cancel', 'index'); ?> 
</div>

<?php ActiveForm::end(); ?>

<?php
$script = '
    $(".kv-panel-before").hide();
    $("#lock").click(function() {
        $("#product-sale-lock-grid").submit();
            
    });
      ';
$this->registerJs($script, View::POS_END, 'product-sale-lock-grid-list');
