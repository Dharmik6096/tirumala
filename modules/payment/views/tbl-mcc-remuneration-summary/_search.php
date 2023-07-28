<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $model app\modules\payment\models\tblmccpaymentSearch */
/* @var $form yii\widgets\ActiveForm */
$scriptForAutoSelect = ' let mccChange = true; let bmcChange = true;';
?>

<div class="tbl-vsp-payment-search">

    <?php
    $form = ActiveForm::begin([
                'method' => 'get',
    ]);
    ?>   

    <div class="col-sm-2">
        <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', 'Union'); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->union_plant($model, $form, 'tblmccpayment-union_code', 'plant_code', 'Plant'); ?>
    </div> 
    <div class="col-sm-2">
        <?=
        Yii::$app->dropdown->plant_mcc($model, $form, 'tblmccpayment-plant_code', 'mcc_plant_code', 'MCC', TRUE);
        $scriptForAutoSelect .= "
                                $('#tblmccpayment-mcc_plant_code').on('depdrop.afterChange', function(event, id, value, jqXHR, textStatus) { 
                                var mcc = $('#tblmccpayment-mcc_plant_code').val();
                                    if(mccChange && mcc != null && mcc != undefined) {
                                        setTimeout(() => {
                                            $('#tblmccpayment-mcc_plant_code').trigger('select2:select');
                                            mccChange = false;
                                        }, 1000);
                                    }
                                });
                                ";
        ?>
    </div>      
    <div id="multiple-bmc" class="col-sm-2">
        <?=
        Yii::$app->dropdown->mcc_bmc($model, $form, 'tblmccpayment-mcc_plant_code', 'bmc_code', 'BMC *', TRUE);
        $scriptForAutoSelect .= "
                                $('#tblmccpayment-bmc_code').on('depdrop.afterChange', function(event, id, value, jqXHR, textStatus) { 
                                var bmc = $('#tblmccpayment-bmc_code').val();
                                console.log(bmc);
                                    if(bmcChange && bmc != null && bmc != undefined) {
                                        setTimeout(() => {
                                            $('#tblmccpayment-bmc_code').trigger('select2:select');
                                            bmcChange = false;
                                        }, 1000);
                                    }
                                });
                                    ";
        ?>
    </div>
    <div class="col-sm-2">
<?= Yii::$app->dropdown->mccRemunerationPaymentCycle($model, $form, 'tblmccpayment-union_code,tblmccpayment-bmc_code', 'payment_cycle_code', 'Payment Cycle'); ?>
    </div>

    <div class="form-group padding_top_20">
    <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
    </div>
<?php ActiveForm::end(); ?>

</div>
<?php
if (!empty($scriptForAutoSelect)) {
    $this->registerJs($scriptForAutoSelect, View::POS_READY, 'mcc-payment-multiSelect-search');
}
?>
