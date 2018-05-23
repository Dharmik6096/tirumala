<?php

use yii\widgets\ActiveForm;
use yii\web\View;
use yii\helpers\Html;

$request = Yii::$app->request->queryParams;
$model->max_date = empty($model->max_date) ? date('d-m-Y') : $model->max_date;
$shift_no = empty($request["shift_no"]) ? 5 : $request["shift_no"];
$shifts = ['06:00:00' => 'Morning', '18:00:00' => 'Evening'];
$model_class = (new \ReflectionClass($model))->getShortName();
$field_class = strtolower($model_class);
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
    <?php if (isset($bmc_filter)) { ?>
        <div class="col-sm-2 padding-right-5">
            <?= Yii::$app->dropdown->union_plant($model, $form, $field_class . '-union_code', 'plant_code'); ?>
        </div>      
        <div class="col-sm-2 padding-right-5">
            <?= Yii::$app->dropdown->plant_mcc($model, $form, $field_class . '-plant_code', 'mcc_code'); ?>
        </div>      
        <div class="col-sm-2 padding-right-5">
            <?= Yii::$app->dropdown->mcc_bmc($model, $form, $field_class . '-mcc_code', 'bmc_code'); ?>
        </div>
    <?php } ?>

    <div class="col-sm-3 padding-left-0 padding-right-5">
        <div class="form-group">
            <?= Yii::$app->controls->date($model, $form, 'max_date')->label(false); ?>
        </div>
    </div>
    <div class="col-sm-2">
        <?= $form->field($model, 'shift')->dropDownList($shifts)->label(false); ?>
    </div> 
    <?php if (!isset($shift_cnt)) { ?>
        <div class="col-sm-2 padding-left-0 padding-right-5">
            <div class="form-group">
                <div class="form-group">
                    <?= Html::input('number', 'shift_no', $shift_no, ['class' => 'form-control', 'min' => 0]) ?>
                </div>
            </div>
        </div>
    <?php } ?>
    <div class="col-sm-2 padding-left-0">
        <?= Yii::$app->controls->search(); ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>

<?php
$script = "
    $('body').on('submit', '#village-form', function() {    
    var dcs_code = $('#tblmilkcollectionsearch-dcs_code').val();
    var min_date = $('#w0').val();
    var min_date = $('#w0').val();
    var max_date = $('#w0-2').val();
    
       if(min_date == ''){
         alert ('Please Select From Date');
         return false;
        }
        else if(max_date == ''){
          alert ('Please Select To Date');
          return false;
       }
    
});";

$this->registerJs($script, View::POS_END, 'date-validate');
