<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use webvimark\modules\UserManagement\components\GhostHtml;
use yii\web\View;

//$this->title = Yii::$app->label->title('view', 'Reports');
$this->title = Yii::t('app', isset($data['title']) ? $data['title'] : '');
$inclass = !empty($result) ? '' : 'in';
$model->date1 = empty($model->date1) ? date('d-m-Y') : $model->date1;
$model->date2 = empty($model->date2) ? date('d-m-Y') : $model->date2;
?>
<div class="panel panel-default panel-grid panel-main">

    <div class="panel-heading"><?= Html::encode($this->title) ?></div>
    <div class="panel-body">
        <div class="report-area">
            <div class="table-responsive mt10 panel-collapse collapse <?= $inclass ?>" id="panel1">
                <?php
                $form = ActiveForm::begin(['options' => [
                                'id' => 'report-form',
                                'field-class' => 'form-group col-sm-3'
                            ], 'validateOnBlur' => FALSE,
                            'validateOnEnter' => TRUE,
                            'validateOnChange' => FALSE,
                            'enableClientValidation' => true,
                            'validateOnSubmit' => true,
                ]);
                ?>    
                <?php //Yii::$app->dropdown->federation($model, $form, 'federation_code', false); ?>  
                <?php
                $param = isset($data['param']) ? explode(',', $data['param']) : [];
                foreach ($param as $key => $value) {
                    $value_array = explode(':', $value);
                    $value = $value_array[0];

                    if (isset($value_array[1]) && $value_array[1] == 'string') {
                        ?>
                        <div class="col-sm-3">
                            <?php
                            echo Yii::$app->controls->date($model, $form, $value, 'form-group col-sm-2 padding-left-5 padding-right-5', false);
                            ?>
                        </div>    
                        <?php
                        if (isset($value_array[2])) {
                            ?>
                            <div class="col-sm-3">
                                <?php
                                echo Yii::$app->dropdown->dropdown('shift_applicability', $model, $form, 'col-sm-3 form-group shift', $model->getAttributeLabel($value_array[2]), false, $value_array[2]);
                                ?>
                            </div>    
                            <?php
                        }
                    }
                    if (in_array($value, array('mccid', 'MCCId'))) {
                        ?>
                        <div class="col-sm-3">
                            <?= Yii::$app->dropdown->union_plant($model, $form, 'reportsmodel-union_code', 'plant_code', 'Plant code'); ?>
                        </div>
                        <div class="col-sm-3">
                            <?= Yii::$app->dropdown->plant_mcc($model, $form, 'reportsmodel-plant_code', $value, 'MCC'); ?>
                        </div>
                        <?php
                    }
                    if (in_array($value, array('union_code'))) {
                        ?>
                        <div class="col-sm-3">
                            <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', 'Union'); ?>
                        </div>   <?php
                    }
                    if (in_array($value, array('bmcid'))) {
                        ?>
                        <div class="col-sm-3">
                            <?= Yii::$app->dropdown->mcc_bmc($model, $form, 'reportsmodel-mccid', 'bmcid', 'BMC'); ?>
                        </div>
                        <?php
                    }
                    if (in_array($value, array('vlccid'))) {
                        ?>
                        <div class="col-sm-3">
                            <?= Yii::$app->dropdown->bmc_society($model, $form, 'reportsmodel-bmcid', 'vlccid', Yii::t('app', 'Society')); ?>
                        </div>
                        <?php
                    }
                    if (in_array($value, array('routeid'))) {
                        ?>
                        <div class="col-sm-3">
                            <?= Yii::$app->dropdown->depend_dropdown('routemapping', $model, $form, 'reportsmodel-union_code', '', 'Route', 'routeid'); ?>
                        </div>
                        <?php
                    }
                    if (in_array($value, array('CattleType'))) {
                        ?>
                        <div class="col-sm-3">
                            <?= Yii::$app->dropdown->dropdown('milk_type_code', $model, $form, '', 'Milk Type', false, 'CattleType'); ?>
                        </div>
                        <?php
                    }
                    if (in_array($value, array('MilkQualityType'))) {
                        ?>
                        <div class="col-sm-3">
                            <?= Yii::$app->dropdown->dropdown('milk_quality_type_code', $model, $form, 'form-group col-sm-3', 'Milk Quality Type', false, 'MilkQualityType'); ?>
                        </div>
                        <?php
                    }

                    if (in_array($value, array('VLCTotal'))) {
                        echo $form->field($model, 'VLCTotal')->hiddenInput(['value' => '0'])->label(false);
                    }
                }

                $model->file_name = !empty($model->file_name) ? $model->file_name : $data['file_name'] . '_' . time();
                echo Html::activeHiddenInput($model, 'file_name');
                if (isset($data['report_type'])) {
                    echo $form->field($model, 'report_type', [ 'options' => ['class' => 'form-group col-sm-3']])->dropDownList($data['report_type'], ['prompt' => Yii::t('app', 'Select Type')]);
                }
                ?>

                <!--            <div class="clearfix"></div>-->
                <div class="col-sm-3 mt25">
                    <?php
                    if ($param) {
                        echo GhostHtml::submitButton(Yii::t('app', 'Generate'), ['class' => 'btn btn-default apply-shortcut', 'name' => 'submit', 'value' => 'html', 'id' => 'html']);
                    }
                    ?>
                </div>
            </div>
            <?php if ($result != '') { ?>

                <div class="panel-footer shortcut-main report-actions" shortcut="true" display_shortcut="false" hilight_shortcut="false">
                    <?= GhostHtml::submitButton('<i class="text-danger fa fa-file-pdf-o"></i>', ['class' => 'btn btn-default apply-shortcut', 'name' => 'submit', 'value' => 'pdf', 'id' => 'pdf', 'title' => Yii::t('app', 'pdf')]); ?>
                    <?php // GhostHtml::submitButton('<i class="text-primary fa fa-file-code-o"></i>', ['class' => 'btn btn-default apply-shortcut', 'name' => 'submit', 'value' => 'csv', 'id' => 'csv', 'title' => Yii::t('app', 'csv')]); ?>
                    <?= GhostHtml::submitButton('<i class="text-success fa fa-file-excel-o"></i>', ['class' => 'btn btn-default apply-shortcut', 'name' => 'submit', 'value' => 'xls', 'id' => 'xls', 'title' => Yii::t('app', 'xls')]); ?>
                    <a href="javascript:void(0)" data-toggle="collapse"  data-target="#panel1" class="btn btn-default apply-shortcut" title="<?= Yii::t('app', 'search') ?>"><i class="text-success fa fa-search"></i></a>
                </div>
            <?php } ?>
        </div>
        <?php if ($result != '') { ?>
            <?php echo $this->render('@app/modules/crystalreports/html/' . $result . '.htm', []); ?>
        <?php } ?>
    </div>
</div>       
<?php ActiveForm::end(); ?>
<?php
$script = "
   $(document).ready(function() {
        if('" . Yii::$app->session->get('Federations') . "' != ''){
         var id='" . strtolower((new ReflectionClass($model))->getShortName() . '-federation_code') . "';   
         $('#'+id+' option:selected').val('" . Yii::$app->session->get('Federations') . "');
         $('#'+id).parent('div').hide(); 
         }
         
    });
    if('" . $report . "'=='BmcCollection' || '" . $report . "'=='RmrdMilkCollection' || '" . $report . "'=='BmcSummaryReport'){
        $('#reportsmodel-cattletype option:first').after($('<option/>', { 'value': 'ALL', text: '" . Yii::t('app', 'ALL') . "'}));      
        if('" . $model->CattleType . "'=='ALL'){
            $('#reportsmodel-cattletype').val('ALL');
        }
    }

    if('" . $report . "'=='RmrdMilkCollection' || '" . $report . "'=='BmcSummaryReport'){
        $('#reportsmodel-milkqualitytype option:first').after($('<option/>', { 'value': 'ALL', text: '" . Yii::t('app', 'ALL') . "'}));      
        if('" . $model->MilkQualityType . "'=='ALL'){
            $('#reportsmodel-milkqualitytype').val('ALL');
        }
    }
    if('" . $report . "'=='BmcCollection' || '" . $report . "'=='RmrdMilkCollection' || '" . $report . "'=='BmcSummaryReport' || '" . $report . "'=='VariationMilkTypeDateWise' || '" . $report . "'=='VariationMilkTypeVillageWise' || '" . $report . "'=='VariationDateWise' || '" . $report . "'=='VariationVillageWise' || '" . $report . "'=='VariationPercentageWise' || '" . $report . "'=='DifferenceReport' || '" . $report . "'=='DifferenceReportDateWise' || '" . $report . "'=='DifferenceReportVillageWise' || '" . $report . "'=='ActualBmcCollection' || '" . $report . "'=='GprsDataReconciliation'){
        $('#reportsmodel-bmcid').on('depdrop.afterChange', function(event, id, value, jqXHR, textStatus) {
            $('#reportsmodel-bmcid option:first').after($('<option/>', { 'value': '0', text: '" . Yii::t('app', 'ALL') . "'}));      
            if('" . $model->bmcid . "'=='0'){
                $('#reportsmodel-bmcid').val(0);
            }
            if($('#reportsmodel-mccid').val()=='0'){ 
                $('#reportsmodel-bmcid').prop('disabled',false);
                $('#reportsmodel-bmcid').val(0);
            }
        });
        $('#reportsmodel-routeid').on('depdrop.afterChange', function(event, id, value, jqXHR, textStatus) {
            $('#reportsmodel-routeid option:first').after($('<option/>', { 'value': '0', text: '" . Yii::t('app', 'ALL') . "'}));      
            if('" . $model->routeid . "'=='0'){
                $('#reportsmodel-routeid').val(0);      
            }
            if($('#reportsmodel-bmcid').val()=='0'){ 
                $('#reportsmodel-routeid').prop('disabled',false);
                $('#reportsmodel-routeid').val(0);
            }
        });
    }
    
    if('" . $report . "'=='BmcCollection' || '" . $report . "'=='RmrdMilkCollection' || '" . $report . "'=='BmcSummaryReport' || '" . $report . "'=='VariationMilkTypeDateWise' || '" . $report . "'=='VariationMilkTypeVillageWise' || '" . $report . "'=='VariationDateWise' || '" . $report . "'=='VariationVillageWise' || '" . $report . "'=='VariationPercentageWise' || '" . $report . "'=='DifferenceReport' || '" . $report . "'=='DifferenceReportDateWise' || '" . $report . "'=='DifferenceReportVillageWise' || '" . $report . "'=='GprsDataReconciliation'){
        $('#reportsmodel-mccid').on('depdrop.afterChange', function(event, id, value, jqXHR, textStatus) {
            $('#reportsmodel-mccid option:first').after($('<option/>', { 'value': '0', text: '" . Yii::t('app', 'ALL') . "'}));      
            if('" . $model->mccid . "'=='0'){
                $('#reportsmodel-mccid').val(0);      
            }
        });
    }
    
    if('" . $report . "'=='ActualBmcCollection'){
        $('#reportsmodel-mccid').on('depdrop.afterChange', function(event, id, value, jqXHR, textStatus) {
            $('#reportsmodel-mccid option:first').after($('<option/>', { 'value': '0', text: '" . Yii::t('app', 'ALL') . "'}));      
            if('" . $model->MCCId . "'=='0'){
                $('#reportsmodel-mccid').val(0);      
            }
        });
    }
    
    if('" . $report . "'=='BmcCollection' || '" . $report . "'=='RmrdMilkCollection' || '" . $report . "'=='BmcSummaryReport' || '" . $report . "'=='VariationMilkTypeDateWise' || '" . $report . "'=='VariationMilkTypeVillageWise' || '" . $report . "'=='VariationDateWise' || '" . $report . "'=='VariationVillageWise' || '" . $report . "'=='VariationPercentageWise' || '" . $report . "'=='DifferenceReport' || '" . $report . "'=='DifferenceReportDateWise' || '" . $report . "'=='DifferenceReportVillageWise'){
        $('#reportsmodel-vlccid').on('depdrop.afterChange', function(event, id, value, jqXHR, textStatus) {
            $('#reportsmodel-vlccid option:first').after($('<option/>', { 'value': '0', text: '" . Yii::t('app', 'ALL') . "'}));      
            if('" . $model->vlccid . "'=='0'){
                $('#reportsmodel-vlccid').val(0);
            }
            if($('#reportsmodel-bmcid').val()=='0'){ 
                $('#reportsmodel-vlccid').prop('disabled',false);
                $('#reportsmodel-vlccid').val(0);
            }
        });
    }
    
    if($('div.crystalstyle').length){
        var report= '" . $data['report_name'] . "';
        var image = $('div.crystalstyle img').attr('src');
        $('div.crystalstyle img').attr('src','../../modules/crystalreports/html/'+report+'/'+image);        
    }
//    $('div.crystalstyle').removeAttr('style');
";
?>

<?php
$this->registerJs($script, View::POS_READY, 'crystal-report');
?>