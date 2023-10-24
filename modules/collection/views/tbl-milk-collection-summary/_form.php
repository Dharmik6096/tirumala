<?php

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $model app\modules\product\models\TblProduct */
/* @var $form yii\widgets\ActiveForm */

$readonly = $type == 'create' ? FALSE : TRUE;
?>

<?php
$form = ActiveForm::begin([
            'validateOnBlur' => false,
            'validateOnChange' => FALSE,
            'enableClientValidation' => true,
            'validateOnSubmit' => true,
        ]);
?>

<?= $form->errorSummary($model); ?>
<div class="row">
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', 'Union', $readonly); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->union_plant($model, $form, 'tblmilkcollectionsummary-union_code', 'plant_code', Yii::t('app', 'Plant')); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->plant_mcc($model, $form, 'tblmilkcollectionsummary-plant_code', 'mcc_plant_code', Yii::t('app', 'MCC')); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->mcc_bmc($model, $form, 'tblmilkcollectionsummary-mcc_plant_code', 'bmc_code', Yii::t('app', 'BMC')); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->bmc_society($model, $form, 'tblmilkcollectionsummary-bmc_code', 'dcs_code', Yii::t('app', 'Society')); ?>
    </div>
    <div class="col-sm-2">
        <?= Yii::$app->controls->date($model, $form, 'date_time_of_collection', '', date('Y-m-d'), false, $readonly, true); ?>
    </div>
    <div class="col-sm-2 shift">
        <?php echo Yii::$app->dropdown->dropdown('shift_applicability', $model, $form, 'col-sm-6 form-group', $model->getAttributeLabel('shift_code'), false, 'shift_code'); ?>    
    </div>
    <div class="col-sm-1 number-validate">
        <?= $form->field($model, 'total_qty')->textInput() ?>
    </div>
    <div class="col-sm-1 number-validate">
        <?= $form->field($model, 'avg_fat')->textInput() ?>
    </div>
    <div class="col-sm-1 number-validate">
        <?= $form->field($model, 'avg_snf')->textInput() ?>
    </div>
    <div class="col-sm-1 number-validate">
        <?= $form->field($model, 'kg_fat')->textInput(['readOnly' => TRUE]) ?>
    </div>
    <div class="col-sm-1 number-validate">
        <?= $form->field($model, 'kg_snf')->textInput(['readOnly' => TRUE]) ?>
    </div>
    <div class="col-sm-1 number-validate">
        <?= $form->field($model, 'total_amount')->textInput() ?>
    </div>
    <div class="col-sm-1 number-validate">
        <?= $form->field($model, 'avg_rate')->textInput(['readOnly' => TRUE]) ?>
    </div>
    <div class="col-sm-1 number-validate">
        <?= $form->field($model, 'auto_count')->textInput() ?>
    </div>
    <div class="clearfix"></div>
    <div class="col-sm-12 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
        <div class="form-group">
            <?= Yii::$app->controls->save(Yii::$app->label->button($type), $model); ?>
            <?= Yii::$app->controls->reset(); ?>
            <?= Yii::$app->controls->cancel($model); ?>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>

<?php
$script = "
$(document).ready(function() { 

    calculateKgFatAndKgSnf();
    calculateAvgRate();
    
    $('#tblmilkcollectionsummary-total_qty').on('change', function(){
        calculateKgFatAndKgSnf();
    });
    $('#tblmilkcollectionsummary-avg_fat').on('change', function(){
        calculateKgFatAndKgSnf();
    });
    $('#tblmilkcollectionsummary-avg_snf').on('change', function(){
        calculateKgFatAndKgSnf();
    });
    $('#tblmilkcollectionsummary-total_amount').on('change', function(){
        calculateAvgRate();
    });  
    
    function calculateKgFatAndKgSnf(){
        var kgFatField = $('#tblmilkcollectionsummary-kg_fat');
        var kgSnfField = $('#tblmilkcollectionsummary-kg_snf');
        var totalQty = parseFloat($('#tblmilkcollectionsummary-total_qty').val());
        var avgFat = parseFloat($('#tblmilkcollectionsummary-avg_fat').val());
        var avgSnf = parseFloat($('#tblmilkcollectionsummary-avg_snf').val());
        
        if (!isNaN(totalQty) && !isNaN(avgFat)) {
            var kgFat = (totalQty * avgFat) / 100;
            kgFatField.val(kgFat.toFixed(2));
        }

        if (!isNaN(totalQty) && !isNaN(avgSnf)) {
            var kgSnf = (totalQty * avgSnf) / 100;
            kgSnfField.val(kgSnf.toFixed(2));
        }
        calculateAvgRate();
    }
     function calculateAvgRate() {
        var avgRateField = $('#tblmilkcollectionsummary-avg_rate');
        var totalQty = parseFloat($('#tblmilkcollectionsummary-total_qty').val());
        var totalAmount = parseFloat($('#tblmilkcollectionsummary-total_amount').val());

        if (!isNaN(totalQty) && !isNaN(totalAmount) && totalQty !== 0) {
            var avgRate = totalAmount / totalQty;
            avgRateField.val(avgRate.toFixed(2));
        }
    }
});
";
$this->registerJs($script, View::POS_END, 'milk-collection-summary-form');
?>
