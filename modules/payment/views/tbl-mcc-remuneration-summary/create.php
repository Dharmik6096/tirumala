<?php

use yii\bootstrap5\ActiveForm;
use yii\helpers\Url;
use yii\web\View;
use yii\helpers\Html;

$this->title = 'MCC Payment Process : Step 1';
$nameWarning = 0;
$codeWarning = 0;
if (!empty($_POST)) {
    $nameWarning = $_POST['warning'];
    $codeWarning = $_POST['code_warning'];
}
$scriptForAutoSelect = ' let mccChange = true; let bmcChange = true;';
?>
<div class="panel panel-default panel-main">
    <div class="panel-heading"><?= $this->title ?></div>
    <div class="panel-body">
        <?php
        $form = ActiveForm::begin([
                    'validateOnBlur' => false,
                    'validateOnChange' => FALSE,
                    'enableClientValidation' => true,
                    'validateOnSubmit' => true,
        ]);
        echo $form->errorSummary($model);
        ?>
        <?php Yii::$app->warning->hiddenfields($nameWarning, $codeWarning); ?>

        <div class="row">
            <div class="col-sm-2" id="union">
                <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', 'Union'); ?>
            </div>
            <div class="col-sm-2">
                <?= Yii::$app->dropdown->union_plant($model, $form, 'tblmccremunerationsummary-union_code', 'plant_code', TRUE); ?>
            </div> 
            <div class="col-sm-2">
                <?=
                Yii::$app->dropdown->plant_mcc($model, $form, 'tblmccremunerationsummary-plant_code', 'mcc_plant_code', Yii::t('app', 'MCC'), TRUE);
                $scriptForAutoSelect .= "
                                        $('#tblmccremunerationsummary-mcc_plant_code').on('depdrop.afterChange', function(event, id, value, jqXHR, textStatus) { 
                                        var mcc = $('#tblmccremunerationsummary-mcc_plant_code').val();
                                            if(mccChange && mcc != null && mcc != undefined) {
                                                setTimeout(() => {
                                                    $('#tblmccremunerationsummary-mcc_plant_code').trigger('select2:select');
                                                    mccChange = false;
                                                }, 1000);
                                            }
                                        });
                                    ";
                ?>
            </div>      
            <div id="multiple-bmc" class="col-sm-2">
                <?=
                Yii::$app->dropdown->mcc_bmc($model, $form, 'tblmccremunerationsummary-mcc_plant_code', 'bmc_code', 'BMC *', TRUE);
                $scriptForAutoSelect .= "
                                        $('#tblmccremunerationsummary-bmc_code').on('depdrop.afterChange', function(event, id, value, jqXHR, textStatus) { 
                                        var bmc = $('#tblmccremunerationsummary-bmc_code').val();
                                        console.log(bmc);
                                            if(bmcChange && bmc != null && bmc != undefined) {
                                                setTimeout(() => {
                                                    $('#tblmccremunerationsummary-bmc_code').trigger('select2:select');
                                                    bmcChange = false;
                                                }, 1000);
                                            }
                                        });
                                    ";
                ?>
            </div>
            <div class="col-sm-2">
                <?= Yii::$app->controls->date($model, $form, 'from_datetime', '', '', false, false); ?>
            </div>
            <div class="col-sm-2">
                <?= Yii::$app->controls->date($model, $form, 'to_datetime', '', '', false, false); ?>
            </div>
            <div class="col-sm-2 padding_top_20 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
                <div class="form-group">
                    <?= Yii::$app->controls->save('Next', $model); ?>   
                    <?= Yii::$app->controls->cancel(); ?>
                </div>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
<?php
if (!empty($scriptForAutoSelect)) {
    $this->registerJs($scriptForAutoSelect, View::POS_READY, 'mcc-payment-multiSelect');
}
?>

