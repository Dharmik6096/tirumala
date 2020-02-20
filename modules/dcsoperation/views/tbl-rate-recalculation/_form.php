<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\View;
use kartik\grid\GridView;
?>
<div class="grid-search clearfix large-search">
    <?php echo $this->render('_recalculation_search', ['searchModel' => $searchModel, 'model' => $model, 'rtype' => $rtype]); ?>
</div>
<?php
$rec_data = !empty($dataProvider) ? $dataProvider->allModels : '';
$form = ActiveForm::begin([
            'options' => ['id' => 'recalculation-form'],
            'validateOnBlur' => FALSE,
            'validateOnEnter' => TRUE,
            'validateOnChange' => FALSE,
            'enableClientValidation' => true,
            'validateOnSubmit' => true,
        ]);
?>
<div class="col-sm-12">
    <?php echo $form->errorSummary($model); ?>
</div>
<?php
if (!empty($rec_data) && $rtype == 'forced') {
    ?>
    <div class="col-sm-3">
        <?= Yii::$app->dropdown->dcsRateChart($model, $form, 'tblraterecalculationsearch-union_code', 'rate_code', $model->getAttributeLabel('rate_code')); ?>
    </div>
<?php } ?>
<?= Html::activeHiddenInput($searchModel, 'union_code') ?>
<?= Html::activeHiddenInput($searchModel, 'plant_code') ?>
<?= Html::activeHiddenInput($searchModel, 'mcc_plant_code') ?>
<?= Html::activeHiddenInput($searchModel, 'bmc_code') ?>

<div class="clearfix"></div>
<div class="col-sm-12 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
    <div class="form-group">
        <?php if (!empty($rec_data)) { ?>
            <span class="btn_show">
                <?= Yii::$app->controls->save(Yii::$app->label->button($type), $model); ?>
            </span>
            <?= Yii::$app->controls->reset(); ?>
        <?php } ?>
    </div>
</div>



<?php
if ($rtype == 'forced') {
    $attribute = [
        ['class' => 'kartik\grid\CheckboxColumn',
            'rowSelectedClass' => GridView::TYPE_SUCCESS,
            'headerOptions' => ['class' => 'skip-export'], 'contentOptions' => ['class' => 'skip-export'],
            'visible' => $rtype == 'forced' ? false : true,
            'checkboxOptions' => function($model) {
                return ['value' => $model['dcs_code']];
            }],
        ['attribute' => 'dcs_code', 'value' => 'dcs_code', 'vAlign' => 'middle', 'filter' => false],
        ['attribute' => 'dcs_name', 'value' => 'dcs_name'],
        ['attribute' => 'qty', 'value' => 'qty', 'vAlign' => 'middle', 'filter' => false],
        ['attribute' => 'amount', 'value' => 'amount', 'vAlign' => 'middle', 'filter' => false],
        ['attribute' => 'recalc_for', 'value' => 'recalc_for', 'vAlign' => 'middle', 'filter' => false],
    ];
} else {
    $attribute = [
        ['class' => 'kartik\grid\CheckboxColumn',
            'rowSelectedClass' => GridView::TYPE_SUCCESS,
            'headerOptions' => ['class' => 'skip-export'], 'contentOptions' => ['class' => 'skip-export'],
            'visible' => $rtype == 'forced' ? false : true,
            'checkboxOptions' => function($model) {
                return ['value' => $model['code'] . '###' . $model['purchase_rate_code'] . '###' . $model['from_date'] . '###' . $model['to_date'] . '###' . $model['customer_type'] . '###' . $model['recalc_for']];
            }],
        ['attribute' => 'type', 'value' => 'type', 'vAlign' => 'middle', 'filter' => false],
        ['attribute' => 'code', 'value' => 'code', 'vAlign' => 'middle', 'filter' => false],
        ['attribute' => 'code_ex', 'value' => 'code_ex', 'vAlign' => 'middle', 'filter' => false],
        ['attribute' => 'name', 'value' => 'name'],
        ['label' => 'From Date', 'attribute' => 'from_date', 'value' => function($model) {
                $shift = explode(' ', $model['from_date'])[1] == '06:00:00.000000' ? ' (M)' : ' (E)';
                return Yii::$app->controls->view_date($model['from_date']) . $shift;
            }, 'filter' => false],
        ['label' => 'To Date', 'attribute' => 'to_date', 'value' => function($model) {
                $shift = explode(' ', $model['to_date'])[1] == '06:00:00.000000' ? ' (M)' : ' (E)';
                return !empty($model['to_date']) ? Yii::$app->controls->view_date($model['to_date']) . $shift : '';
            }, 'filter' => false],
        ['attribute' => 'wef_date', 'value' => function($model) {
                $shift = explode(' ', $model['wef_date'])[1] == '06:00:00.000000' ? ' (M)' : ' (E)';
                return Yii::$app->controls->view_date($model['wef_date']) . $shift;
            }, 'filter' => false],
        ['header' => 'Rate Id', 'attribute' => 'purchase_rate_code', 'value' => 'purchase_rate_code', 'vAlign' => 'middle', 'filter' => false],
        ['attribute' => 'qty', 'value' => 'qty', 'vAlign' => 'middle', 'filter' => false],
        ['attribute' => 'amount', 'value' => 'amount', 'vAlign' => 'middle', 'filter' => false],
        ['attribute' => 'recalc_for', 'value' => 'recalc_for', 'vAlign' => 'middle', 'filter' => false],
    ];
}
$grid_option = [
    'id' => 'bmc-rate-recalculation-list',
    'attributes' => $attribute,
    'active_column' => false,
];
if (!empty($dataProvider)) {
    Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
}
?>
<?php ActiveForm::end(); ?>
<?php
$script = "
    $('#tblbmccollectionsearch-union_code').on('change', function(){
        var searchUnion = $(this).val();
        var mianModelUnion = $('#tblraterecalculation-union_code').val();
        if(searchUnion != mianModelUnion){
            $('.btn_show').hide();
        } else {
            $('.btn_show').show();
        }
    });
    
    $('.submit_form').on('click', function(){
        $('from#recalculation-form').submit();
    });
    
     $('#tblbmccollectionsearch-customer_type').change(function(){
          $('#tblbmccollectionsearch-customer_code').val('');
    });
    
    $('#tblbmccollectionsearch-customer_type').on('depdrop.afterChange', function(event, id, value, jqXHR, textStatus) {
        var length = $('#tblbmccollectionsearch-customer_type option[value!=\'\']').length;
            if(length == 0) {
                $('.show_hide_customer_type').hide();
            }else if(length == 1) {
                $('#tblbmccollectionsearch-customer_type').val('DCS');
                $('.show_hide_customer_type').hide();
            } else {
                $('.show_hide_customer_type').show();
            }
    });
";
$this->registerJs($script, View::POS_END, 'rate-recalculation-script');
?>
