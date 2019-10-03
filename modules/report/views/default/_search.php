<?php

use yii\widgets\ActiveForm;
use yii\web\View;
use yii\helpers\Html;

$request = Yii::$app->request->queryParams;
$min_date = empty($request['min_date']) ? '' : $request['min_date'];
$max_date = empty($request['max_date']) ? '' : $request['max_date'];
$model->min_date = empty($model->min_date) ? date('d-m-Y') : $model->min_date;
$model->max_date = empty($model->max_date) ? date('d-m-Y') : $model->max_date;
$model->shift = empty($model->shift) ? '3' : $model->shift;
?>

<div class="grid-search large-search hidden-print">
    <?php
    $form = ActiveForm::begin([
                'method' => 'get',
                'id' => 'village-form',
                'validateOnSubmit' => true,
    ]);
    ?>
    <div class="col-sm-2 padding-right-5">
        <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', FALSE); ?>
    </div>
    <?php if (!empty($orgFilter)) { ?>
        <div class="col-sm-2 padding-right-5">
            <?= Yii::$app->dropdown->union_plant($model, $form, 'tblmilkcollectionsearch-union_code', 'plant_code'); ?>
        </div>
        <div class="col-sm-2 padding-right-5">
            <?= Yii::$app->dropdown->plant_mcc($model, $form, 'tblmilkcollectionsearch-plant_code', 'mcc_code'); ?>
        </div>      
        <div class="col-sm-2 padding-right-5">
            <?= Yii::$app->dropdown->mcc_bmc($model, $form, 'tblmilkcollectionsearch-mcc_code', 'bmc_code'); ?>
        </div>
        <div class="col-sm-2 padding-left-0 padding-right-5">
            <?= Yii::$app->dropdown->bmc_society($model, $form, 'tblmilkcollectionsearch-bmc_code', 'dcs_code'); ?> 
        </div>
    <?php } else { ?>
        <div class="col-sm-2 padding-left-0 padding-right-5">
            <?= Yii::$app->dropdown->depend_dropdown('dcs', $model, $form, 'tblmilkcollectionsearch-union_code'); ?>
        </div>
    <?php } ?>
    <div class="col-sm-4 padding-left-0 padding-right-5">
        <div class="form-group">
            <?= Yii::$app->controls->active_min_max_date($form, $model, 'min_date', 'max_date', $min_date, $max_date); ?>
        </div>
    </div>
    <?php if (!empty($shiftFilter)) { ?>
        <div class="col-sm-2">
            <?= Yii::$app->dropdown->dropdown('shift_applicability', $model, $form, '', false, false, 'shift'); ?>
        </div>   
    <?php } ?>
    <div class="col-sm-2 padding-left-0">
        <?= Yii::$app->controls->search(); ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>

<?php
/*$script = "
    $('body').on('submit', '#village-form', function(e) {    
    var dcs_code = $('#tblmilkcollectionsearch-dcs_code').val();
    var min_date = $('#tblmilkcollectionsearch-min_date').val();
    var min_date = $('#w0').val();
    var max_date = $('#tblmilkcollectionsearch-max_date').val();
    
       if(min_date == ''){
        alert ('Please Select From Date');
         preventEncryption($('#village-form'));
        return false;
        }
        else if(max_date == ''){
          alert ('Please Select To Date');
          return false;
       }
    
});";

$this->registerJs($script, View::POS_END, 'date-validate');*/
