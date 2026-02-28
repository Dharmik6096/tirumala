<?php

use app\components\ActiveForm;
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
<div class="grid-search no-effect modal_product_sale_lock" >

    <?php
    echo Html::activeHiddenInput($searchModel, 'union_code', ['id' => 'set_union_code']);
    echo Html::hiddenInput('submitType', '', ['id' => 'submitType']);
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
                echo Html::hiddenInput('sale_detail_code[' . $index . ']', $model['sale_detail_code'], ['id' => '[' . $index . ']sale_detail_code']);
                echo Html::hiddenInput('data[' . $index . ']', $model['data'], ['id' => '[' . $index . ']data']);
                return $model['bmc_code'];
            },
            'filter' => FALSE],
        ['attribute' => 'bmc', 'filter' => FALSE],
        ['attribute' => 'customer_code', 'filter' => FALSE],
        ['attribute' => 'customer_name', 'filter' => FALSE],
        ['attribute' => 'invoice_date', 'filter' => FALSE],
        ['attribute' => 'product', 'filter' => FALSE],
        ['attribute' => 'amount', 'filter' => false, 'format' => Yii::$app->general->CurrencyFormat(),],
        ['attribute' => 'created_at', 'value' => function($model) {
                return Yii::$app->controls->view_datetime($model['created_at']);
            }, 'filter' => false],
        ['attribute' => 'created_by', 'filter' => false],
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
        echo Html::submitButton(Yii::t('app', 'Lock'), ['class' => 'btn-login btn btn-primary submit me-1', 'id' => 'lock', 'value' => 'lock', 'name' => 'lock']);
    }
    if (!empty($dataProvider->getModels())) {
        echo Html::submitButton(Yii::t('app', 'DOWNLOAD'), ['class' => 'btn-login btn btn-primary submit', 'id' => 'download', 'value' => 'download', 'name' => 'lock']);
    }
    ?>
    <?= Yii::$app->controls->custombutton('Cancel', 'index','','btn-login'); ?> 
</div>

<?php ActiveForm::end(); ?>

<?php
$script = '
    $(".kv-panel-before").hide();
    $(".submit").click(function() {
    
        $("#submitType").val($(this).val());
        $("#product-sale-lock-grid").submit();
            
    });
      ';
$this->registerJs($script, View::POS_END, 'product-sale-lock-grid-list');
