<?php

use yii\widgets\ActiveForm;
use yii\web\View;
use yii\widgets\Pjax;
use kartik\grid\GridView;
use kartik\export\ExportMenu;

$this->title = Yii::t('app', Yii::$app->label->title('list', '208 - Society-Shift Crosstab'));
?>

<div class="tbl-banks-index">
    <div class="panel panel-default panel-grid panel-main">
        <div class="panel-heading">
            <?= $this->title; ?>           
        </div>
        <div class="panel-body">
            <div class="report-area">
                <?php echo $this->render('_search_dpu_bmc', ['model' => $model]); ?>

                <?php
                $attribute = [
                    ['attribute' => 'union_name', 'label' => Yii::t('app', 'Union Name'), 'filter' => false],
                    ['attribute' => 'district_name', 'filter' => false],
                    ['attribute' => 'dcs_code', 'filter' => false],
                    ['attribute' => 'dcs_name', 'filter' => false],
                    ['attribute' => 'MinDate', 'filter' => false],
                ];
                if (!empty($extra))
                    $attribute = array_merge($attribute, $extra);
                $grid_option = [
                    'id' => 'shift-report',
                    'attributes' => $attribute,
                    'active_column' => false,
                        //'actions' => []
                ];


                Yii::$app->grid->bind($dataProvider, $model, $grid_option);
                ?>
            </div>
        </div>
    </div>
</div>

<?php
$script = "
$('#tblmilkcollectionsearch-plant_code').on('depdrop.afterChange', function(event, id, value, jqXHR, textStatus) {
    $('#tblmilkcollectionsearch-plant_code option:first').after($('<option/>', { 'value': '0', text: '" . Yii::t('app', 'All') . "'}));      
        if('" . $model->plant_code . "'=='0'){
        $('#tblmilkcollectionsearch-plant_code').val(0);      
    }
    if($('#tblmilkcollectionsearch-company_code').val()=='0'){ 
         $('#tblmilkcollectionsearch-plant_code').prop('disabled',false);
         $('#tblmilkcollectionsearch-plant_code').val(0);
    }
});

$('#tblmilkcollectionsearch-mcc_code').on('depdrop.afterChange', function(event, id, value, jqXHR, textStatus) {
    $('#tblmilkcollectionsearch-mcc_code option:first').after($('<option/>', { 'value': '0', text: '" . Yii::t('app', 'All') . "'}));      
    if('" . $model->mcc_code . "'=='0'){
        $('#tblmilkcollectionsearch-mcc_code').val(0);      
    }
    if($('#tblmilkcollectionsearch-plant_code').val()=='0'){ 
         $('#tblmilkcollectionsearch-mcc_code').prop('disabled',false);
         $('#tblmilkcollectionsearch-mcc_code').val(0);
    }
});

$('#tblmilkcollectionsearch-bmc_code').on('depdrop.afterChange', function(event, id, value, jqXHR, textStatus) {
    $('#tblmilkcollectionsearch-bmc_code option:first').after($('<option/>', { 'value': '0', text: '" . Yii::t('app', 'All') . "'}));      
    if('" . $model->bmc_code . "'=='0'){
        $('#tblmilkcollectionsearch-bmc_code').val(0);      
    }
    if($('#tblmilkcollectionsearch-mcc_code').val()=='0'){ 
         $('#tblmilkcollectionsearch-bmc_code').prop('disabled',false);
         $('#tblmilkcollectionsearch-bmc_code').val(0);
    }
});


$('#tblmilkcollectionsearch-dcs_code').on('depdrop.afterChange', function(event, id, value, jqXHR, textStatus) {
    $('#tblmilkcollectionsearch-dcs_code option:first').after($('<option/>', { 'value': '0', text: '" . Yii::t('app', 'All') . "'}));      
        if('" . $model->dcs_code . "'=='0'){
        $('#tblmilkcollectionsearch-dcs_code').val(0);      
    }
    if($('#tblmilkcollectionsearch-bmc_code').val()=='0'){ 
         $('#tblmilkcollectionsearch-dcs_code').prop('disabled',false);
         $('#tblmilkcollectionsearch-dcs_code').val(0);
    }
});
    
";
$this->registerJs($script, View::POS_READY, 'dep-drop-dcs');
?>