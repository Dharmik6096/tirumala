<?php

use yii\helpers\Html;
use app\components\ActiveForm;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $model app\modules\payment\models\TblTransporterPaymentSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tbl-transporter-payment-search">

    <?php 
    $form = ActiveForm::begin([
        'action' => ['disburse-payment'],
        'method' => 'get',
        'id' => 'search-disburse-payment',
        'options' => [
            'class' => 'row'
        ]
    ]); ?>
    <div class="col-sm-2 mt5">
        <?= Yii::$app->dropdown->dropdownStatic('transporter_type', $model, $form, '', $model->getAttributeLabel('transporter_type'), false, 'transporter_type', false); ?>
    </div> 
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', 'Union'); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->controls->date($model, $form, 'from_date', '', '', false, false); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->controls->date($model, $form, 'to_date', '', '', false, false); ?>
    </div>
    <div class="col-sm-2 primary">
        <?= Yii::$app->dropdown->union_plant($model, $form, 'tbltransporterpaymentsearch-union_code', 'plant_code', $model->getAttributeLabel('plant_code')); ?>
    </div> 
    <div class="col-sm-2 primary">
        <?= Yii::$app->dropdown->plant_mcc($model, $form, 'tbltransporterpaymentsearch-plant_code', 'mcc_plant_code', $model->getAttributeLabel('mcc_plant_code'), TRUE); ?>
    </div>      
    <div class="col-sm-2 primary">
        <?php echo Html::hiddenInput('check_condition', 'No', ['id' => 'tbltransporterpaymentsearch-check_condition']); ?>
        <?= Yii::$app->dropdown->datewise_bmc_list($model, $form, 'tbltransporterpaymentsearch-mcc_plant_code,tbltransporterpaymentsearch-plant_code,tbltransporterpaymentsearch-union_code,tbltransporterpaymentsearch-from_date,tbltransporterpaymentsearch-to_date,tbltransporterpaymentsearch-check_condition', 'bmc_code', $model->getAttributeLabel('bmc_code'), TRUE, '', FALSE, TRUE, TRUE); ?>
    </div>
    <div class="col-sm-2 primary">
        <?= Yii::$app->dropdown->all_route_transporter($model, $form, 'tbltransporterpaymentsearch-plant_code,tbltransporterpaymentsearch-mcc_plant_code,tbltransporterpaymentsearch-bmc_code', 'transporter_code', $model->getAttributeLabel('transporter_code'), FALSE, '', FALSE, TRUE); ?>
    </div>
    <div class="col-sm-2 secondary">
        <?= Yii::$app->dropdown->datewise_transporter_list($model, $form, 'tbltransporterpaymentsearch-union_code,tbltransporterpaymentsearch-from_date,tbltransporterpaymentsearch-to_date', 'secondory_transporter_code', $model->getAttributeLabel('transporter_code'), FALSE, '', FALSE, TRUE); ?>
    </div>
    <div class="col-sm-1 mt23">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
<?php
$script = "
    $(document).ready(function(){
        var transporter_type = '$model->transporter_type';
        $('.primary, .secondary').hide();
        $('#tbltransporterpaymentsearch-transporter_type').on('change', function(){
            var type = $(this).val();
            $('.primary, .secondary').hide();
            if(type == 1) {
                $('.secondary').show();
                $('#tbltransporterpaymentsearch-plant_code').val(null).trigger('select2:select');
                $('#select2-tbltransporterpaymentsearch-plant_code-container').html('');
            } else if(type == '0') {
                $('.primary').show();
                $('#tbltransporterpaymentsearch-secondory_transporter_code').val(null).trigger('select2:select');
                $('#select2-tbltransporterpaymentsearch-secondory_transporter_code-container').html('');
            }
        });
        if(transporter_type == '1' || transporter_type == '0'){
            $('#tbltransporterpaymentsearch-transporter_type').val(transporter_type).trigger('change');
        }
    });
";
$this->registerJs($script, View::POS_END, 'transportar-payment-search-script');
