<?php

use yii\widgets\ActiveForm;
use yii\web\View;
use yii\helpers\Html;
use webvimark\modules\UserManagement\components\GhostHtml;

$request = Yii::$app->request->queryParams;
$min_date = empty($request['min_date']) ? date('d-m-Y') : $request['min_date'];
$max_date = empty($request['max_date']) ? date('d-m-Y') : $request['max_date'];
$model->min_date = empty($model->min_date) ? date('d-m-Y') : $model->min_date;
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
    <?php if (!empty($vondorFilter)) { ?>
        <div class="col-sm-2">
            <?php echo Yii::$app->dropdown->dropdownStatic('vendor', $model, $form, 'form-group', false); ?>
        </div>   
    <?php } ?>
    <?php if (!isset($minMaxFilter)) { ?>
        <div class="col-sm-4 padding-left-0 padding-right-5">
            <div class="form-group">
                <?= Yii::$app->controls->min_max_date('min_date', 'max_date', $min_date, $max_date); ?>
            </div>
        </div>           
    <?php } else { ?>
        <div class="col-sm-3 padding-left-0 padding-right-5">
            <div class="form-group">
                <?= Yii::$app->controls->date($model, $form, 'min_date')->label(false); ?>
            </div>
        </div>  
    <?php } ?>

    <?php if (!empty($shiftFilter)) { ?>
        <div class="col-sm-2">
            <?= Yii::$app->dropdown->dropdown('shift_applicability', $model, $form, '', false, false, 'shift'); ?>
        </div>   
    <?php } ?>
    <?php if (!empty($milkFilter)) { ?>
        <div class="col-sm-2">
            <?= Yii::$app->dropdown->dropdown('milk_type_code', $model, $form, '', false, false, 'milk_type_code'); ?>
        </div>   
    <?php } ?>
    <div class="col-sm-3 padding-left-0">
        <?= Yii::$app->controls->search(); ?>
        <?php
        if (isset($detailUrl)) {
            $qry_param = isset($_GET['q']) ? '?q=' . $_GET['q'] : '';
            $detailUrl = \yii\helpers\Url::to([$detailUrl]);
            echo GhostHtml::a('Detail Report', $detailUrl . $qry_param, ['class' => 'btn btn-default']);
        }
        ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>

<?php /*
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

$this->registerJs($script, View::POS_END, 'date-validate'); */
