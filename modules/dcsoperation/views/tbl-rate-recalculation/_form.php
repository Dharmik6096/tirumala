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
        <?= Yii::$app->dropdown->dcsRateChart($model, $form, 'tblraterecalculationsearch-union_code,tblraterecalculationsearch-recalc_for', 'rate_code', $model->getAttributeLabel('rate_code')); ?>
    </div>
<?php } ?>
<?= Html::activeHiddenInput($searchModel, 'union_code') ?>
<?= Html::activeHiddenInput($searchModel, 'plant_code') ?>
<?= Html::activeHiddenInput($searchModel, 'mcc_plant_code') ?>
<?= Html::activeHiddenInput($searchModel, 'bmc_code') ?>

<!--<span class="hide-grid-settings kv-panel-before"></span>-->
<div class="col-sm-12 shortcut-main" shortcut="true" display_shortcut="false" hilight_shortcut="false">
    <div class="form-group">
        <?php if (!empty($rec_data)) { ?>
            <span class="btn_show">
                <?php
                echo Html::button(Yii::t('app', 'SAVE'), ['class' => 'btn btn-primary', 'id' => 'recalculation']);
                ?>
            </span>
            <?= Yii::$app->controls->reset(); ?>
        <?php } ?>
    </div>



    <?php
    if ($rtype == 'forced') {
        $attribute = [
            ['class' => 'kartik\grid\CheckboxColumn',
                'rowSelectedClass' => GridView::TYPE_SUCCESS,
                'headerOptions' => ['class' => 'skip-export'], 'contentOptions' => ['class' => 'skip-export'],
//                'visible' => $rtype == 'forced' ? false : true,
                'checkboxOptions' => function($model) {
                    return ['class' => 'checkbox-recalculation', 'value' => $model['code'] . '###' . $model['customer_type'] . '###' . $model['recalc_for']];
                }],
            ['attribute' => 'type', 'filter' => false],
            ['attribute' => 'code', 'filter' => false],
            ['attribute' => 'code_ex', 'filter' => false],
            ['attribute' => 'name', 'filter' => false],
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
                    return ['class' => 'checkbox-recalculation', 'value' => $model['code'] . '###' . $model['purchase_rate_code'] . '###' . $model['from_date'] . '###' . $model['to_date'] . '###' . $model['customer_type'] . '###' . $model['recalc_for']];
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
</div>
<?php
$script = "
    $(document).ready(function() {
       $('.btn-toolbar.kv-grid-toolbar').hide();
      $('.show_on_memebr').hide();
      $('.show_on_bmc').hide();
        var recalc_for =$('#tblraterecalculationsearch-recalc_for').val();
            if(recalc_for=='member'){
                $('.show_on_memebr').show();
                $('.show_on_bmc').hide();
            }else if(recalc_for=='bmc'){
                $('.show_on_memebr').hide();
                $('.show_on_bmc').show();
            }
    });
    $('.submit_form').on('click', function(){
        $('from#recalculation-form').submit();
    });
    $('#tblraterecalculationsearch-recalc_for').change(function(){
          $('#tblraterecalculationsearch-dcs_code').val('');
          $('#tblraterecalculationsearch-customer_code').val('');
          var recalc_for =$('#tblraterecalculationsearch-recalc_for').val();
            if(recalc_for=='member'){
               $('.show_on_memebr').show();
               $('#tblraterecalculationsearch-customer_type').val('');
               $('.show_on_bmc').hide();
            }else if(recalc_for=='bmc'){
                $('.show_on_memebr').hide();
                $('.show_on_bmc').show();
                hideType();
            }
    });
    
    $('#tblraterecalculationsearch-customer_type').on('depdrop.afterChange', function(event, id, value, jqXHR, textStatus) {
        var length = $('#tblraterecalculationsearch-customer_type option[value!=\'\']').length;
        var recalc_for =$('#tblraterecalculationsearch-recalc_for').val();
            if(length == 0) {
                $('.show_hide_customer_type').hide();
            }else if(length == 1) {
                $('#tblraterecalculationsearch-customer_type').val('DCS');
                $('.show_hide_customer_type').hide();
                $('#tblraterecalculationsearch-customer_type').trigger('change');
                $('#tblraterecalculationsearch-customer_code').on('depdrop.afterChange', function(event, id, value, jqXHR, textStatus) {
                    $('#tblraterecalculationsearch-customer_code').trigger('change');
                });
            } else if(recalc_for=='bmc'){
                $('.show_hide_customer_type').show();
            }
    });
    
    function hideType(){
        var type =$('#tblraterecalculationsearch-customer_type').val();
        var length = $('#tblraterecalculationsearch-customer_type option[value!=\'\']').length;
        if(length == 1) {
                $('#tblraterecalculationsearch-customer_type').val('DCS');
                $('.show_hide_customer_type').hide();
                $('#tblraterecalculationsearch-customer_type').trigger('change');
                $('#tblraterecalculationsearch-customer_code').on('depdrop.afterChange', function(event, id, value, jqXHR, textStatus) {
                    $('#tblraterecalculationsearch-customer_code').trigger('change');
                });
        }
    }
    
    $('#recalculation').click(function() {
        var len = $('input[class=\"checkbox-recalculation kv-row-checkbox\"]:checked').length;
            if(len == 0){
             bootbox.alert('<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span>Please select at least one Collection.</span></div></div>');
                return false;
            } else {
            $('#recalculation-form').submit();
            }
    });
";
$this->registerJs($script, View::POS_END, 'rate-recalculation-script');
?>