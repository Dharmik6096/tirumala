<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\View;

$request = Yii::$app->request->queryParams;
$searchModel->from_date = !empty($searchModel->from_date) ? $searchModel->from_date : date('d-m-Y');
$searchModel->to_date = !empty($searchModel->to_date) ? $searchModel->to_date : date('d-m-Y');
$remove = $rtype == 'forced' ? TRUE : FALSE;
//if (!empty($searchModel->dcs_code)) {
//    $dcs = new \app\modules\organisation\models\TblDcs();
//    $sel = array_map('strval', array_keys($dcs->getBMCDCSList($searchModel->bmc_code)));
//    $arr = array_combine(range(1, count($sel)), $sel);
//    $searchModel->dcs_code = $searchModel->dcs_code + $arr;
//}
//var_dump($searchModel->dcs_code); exit;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\collection\models\TblMilkDispatchSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<?php
$form = ActiveForm::begin([
            'action' => $rtype == 'forced' ? ['create'] : ['create-recalc'],
            'method' => 'get',
            'validateOnBlur' => true,
            'validateOnChange' => FALSE,
            'enableClientValidation' => true,
            'validateOnSubmit' => true,
        ]);
?>
<div class="col-sm-2">
    <?= Yii::$app->dropdown->federation_union($searchModel, $form, 'union_code', FALSE); ?>
</div>
<div class="col-sm-2">
    <?= Yii::$app->dropdown->union_plant($searchModel, $form, 'tblraterecalculationsearch-union_code', 'plant_code', false); ?>
</div> 
<div class="col-sm-2">
    <?= Yii::$app->dropdown->plant_mcc($searchModel, $form, 'tblraterecalculationsearch-plant_code', 'mcc_plant_code', false); ?>
</div>      
<div class="col-sm-2">
    <?= Yii::$app->dropdown->mcc_bmc($searchModel, $form, 'tblraterecalculationsearch-mcc_plant_code', 'bmc_code', false); ?>
</div>
<?= Yii::$app->dropdown->dropdownStatic('rate_cal_for', $searchModel, $form, 'form-group col-sm-2 padding-left-5 padding-right-5', FALSE, FALSE, 'recalc_for', false) ?> 
<?php if ($rtype == 'forced') { ?>
    <div class="col-sm-2 show_on_memebr form-group_mb0">
        <?= Yii::$app->dropdown->bmc_society($searchModel, $form, 'tblraterecalculationsearch-bmc_code', 'dcs_code', false, FALSE, '', FALSE, false, true); ?>         
    </div>  
    <div class="col-sm-2 show_on_bmc show_hide_customer_type ">
        <?= Yii::$app->dropdown->customer_type($searchModel, $form, 'tblraterecalculationsearch-bmc_code', 'customer_type', FALSE, FALSE); ?>
    </div>
    <div class="col-sm-2 show_on_bmc">
        <?= Yii::$app->dropdown->customer_code($searchModel, $form, 'tblraterecalculationsearch-bmc_code,tblraterecalculationsearch-customer_type', 'customer_code', FALSE, FALSE); ?>
    </div>

<?php } else if ($rtype == 'custom') { ?>
    <div class="col-sm-2">
        <?= Yii::$app->dropdown->customer_type($searchModel, $form, 'tblraterecalculationsearch-bmc_code', 'customer_type'); ?>
    </div>
<?php }
?> 
<div class="clearfix"></div>
<div class="col-sm-2">
    <?= Yii::$app->controls->date($searchModel, $form, 'from_date', 'form-group col-sm-2 padding-left-5 padding-right-5', false, false, false, false); ?>
</div>
<div class="col-sm-2 shift">
    <?= Yii::$app->dropdown->dropdown('shift_applicability', $searchModel, $form, '', false, false, 'from_shift'); ?>
</div>   
<div class="col-sm-2">
    <?= Yii::$app->controls->date($searchModel, $form, 'to_date', 'form-group col-sm-2 padding-left-5 padding-right-5', false, false, false, false); ?>
</div>
<div class="col-sm-2 shift">
    <?= Yii::$app->dropdown->dropdown('shift_applicability', $searchModel, $form, '', false, false, 'to_shift'); ?>
</div>
<div class="col-sm-3">
    <?= Yii::$app->controls->search('recalc_search'); ?>
</div>
<?php ActiveForm::end(); ?>
<?php
$script = "
    $('#tblraterecalculationsearch-dcs_code').on('change', function(){
        var length = $('#tblraterecalculationsearch-dcs_code > option').length;
        var vl=$('#tblraterecalculationsearch-dcs_code').val();
        if(vl!=null && length>vl.length && $.inArray('multiselect-all',vl)>=0)
        {
            //console.log('yes');
            //$('.multiselect-all > label > input').attr('checked','false');
            $('.multiselect-all').find('input').attr('checked',false);
        }        
    });
    
    $('.recalc_search').on('click', function(){
    var v=$('#tblraterecalculationsearch-dcs_code').val();
    //console.log(v);
    //console.log($.inArray('multiselect-all',v));
    if(v.length>50 && ($.inArray('multiselect-all',v)<0))
    {
        alert('You can only select 50 invidual DCS or all DCS');
        return false;
    }
    else if($.inArray('multiselect-all',v)>=0)
    {
        //alert('here');
        var newval='multiselect-all';
        $('#tblraterecalculationsearch-dcs_code').val(newval);
        //alert($('#tblraterecalculationsearch-dcs_code').val());
        return true;
    }
    return true;
    
        //$('from#recalculation-form').submit();
    });
";
$this->registerJs($script, View::POS_END, 'rate-recalculation-search-script');
?>
