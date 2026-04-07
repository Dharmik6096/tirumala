<?php

use app\components\ActiveForm;
use yii\web\View;
use yii\helpers\Html;
use demogorgorn\ajax\AjaxSubmitButton;
use yii\helpers\Url;
use yii\web\JsExpression;
?>
<div class="grid-search no-effect padding_10_0" >
    <?php
    $form = ActiveForm::begin([
                'id' => 'transfer-stock-medicine',
                'action' => '#',
                'method' => 'post',
                'enableClientValidation' => false,
                'enableAjaxValidation' => false,
    ]);
    ?>

    <?= Html::hiddenInput('TblMedicineStockTransferTxnSearch[union_code]', $searchModel->union_code) ?>
    <?= Html::hiddenInput('TblMedicineStockTransferTxnSearch[transaction_date]', $searchModel->transaction_date) ?>
    <?= Html::hiddenInput('TblMedicineStockTransferTxnSearch[remarks]', $searchModel->remarks) ?>
    <?= Html::hiddenInput('TblMedicineStockTransferTxnSearch[from_user_code]', $searchModel->from_user_code) ?>
    <?= Html::hiddenInput('TblMedicineStockTransferTxnSearch[to_user_code]', $searchModel->to_user_code) ?>
    <?= Html::hiddenInput('TblMedicineStockTransferTxnSearch[medicine_wise]', $searchModel->medicine_wise) ?>

    <?php
    $ids = array_column($bulkDataProvider->getModels(), 'medicine_stock_id');
    ?>

    <?= \yii\helpers\Html::hiddenInput('bulk_ids', implode(',', $ids)) ?>

    <?php
    $attribute = [
        ['attribute' => 'medicine_id', 'label' => Yii::t('app', 'Medicine Name'), 'value' => function ($model) {
                return Yii::$app->general->getforeignkey($model->medicineMasterCode, 'medicine_name');
            }, 'filter' => false],
        ['attribute' => 'batch_no', 'filter' => true],
        [
            'attribute' => 'expire_date',
            'value' => function ($model) {
                return Yii::$app->controls->view_date($model->expire_date);
            }
        ],
        ['attribute' => 'stock', 'filter' => true],
        ['attribute' => 'rate', 'format' => Yii::$app->general->CurrencyFormat(), 'visible' => true, 'filter' => false],
    ];

    $grid_option = [
        'id' => 'transfer-stock-medicine-grid',
        'attributes' => $attribute,
        'active_column' => false,
        'showPageSummary' => false,
        'export' => false,
        'toolbar' => false,
    ];

    Yii::$app->grid->bind($bulkDataProvider, $searchModel, $grid_option, ['#'], false);
    ?>

</div>
<div class="panel-footer" >
    <?php
    AjaxSubmitButton::begin([
        'label' => Yii::t('app', 'Transfer All'),
        'ajaxOptions' => [
            'type' => 'POST',
            'url' => Url::to(['create']),
            'data' => new JsExpression("$('#transfer-stock-medicine').serialize()"),
            'beforeSend' => new JsExpression("function(data){
                $('#loadercontent').show();
                $('#pageloader').show();
            }"),
            'success' => new JsExpression("function(data){
                var data=$.parseJSON(data);
                $('#loadercontent').hide();
                $('#pageloader').hide();
                if (data.status == 'success'){
                    $('.help-block').text('');
                    $('.form-group').removeClass('has-error');
                    $('.error-summary').hide();
                    $('.error-summary li').remove();
                    bootbox.alert('<div class=\"row\"><div class=\"col-sm-12\"><div class=\"bg-info\"><i class=\"fa fa-info\"></i></div><span>'+data.msg+'</span></div></div>', function(result){
                        window.location.href = '" . Url::to(['index']) . "';
                    });
                }else{
                    $('#loadercontent').hide();
                    $('#pageloader').hide();
                }
            }"),
        ],
        'options' => [
            'class' => 'btn btn-default btn-raised',
            'type' => 'button'
        ],
    ]);
    AjaxSubmitButton::end();
    ?>
    <?= Yii::$app->controls->custombutton('Cancel', 'index'); ?> 
</div>

<?php ActiveForm::end(); ?>
<?php
$script = '
    $(".kv-panel-before").hide();
';
$this->registerJs($script, View::POS_END, 'approved-attachment-details-list');
?>