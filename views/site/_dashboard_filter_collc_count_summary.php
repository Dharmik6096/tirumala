<?php

use kartik\widgets\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\View;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\modules\organisation\models\TblDcsSearch */
/* @var $form yii\widgets\ActiveForm */

//Yii::$app->controls->view_date($date);
$widget_for = ['union' => 'Union Wise', 'mcc' => 'MCC Wise'];
$model->from_date6 = empty($model->from_date4) ? (empty($date) ? Yii::$app->controls->view_date(date('Y-m-d')) : Yii::$app->controls->view_date($date)) : $model->from_date4;
$model->to_date6 = empty($model->to_date4) ? (empty($date) ? Yii::$app->controls->view_date(date('Y-m-d')) : Yii::$app->controls->view_date($date)) : $model->to_date4;


$range_1_id_from = !empty($colc_range_1_from) ? $colc_range_1_from : false;
$range_1_id_to = !empty($colc_range_1_to) ? $colc_range_1_to : false;

$date_range_class = !empty($date_range_class) ? $date_range_class : 'col-sm-3';


$unionCode = !empty($model->union_code) ? $model->union_code : '';
$mccCode = !empty($model->mcc_code) ? $model->mcc_code : '';
$common_class = 'padding-left-5 padding-right-5';


$model->from_date = Yii::$app->controls->view_date(date('Y-m-d'));
$model->to_date = Yii::$app->controls->view_date(date('Y-m-d'));
$model->ccs_from_shift = empty($model->ccs_from_shift) ? 1 : $model->ccs_from_shift;
$model->ccs_to_shift = empty($model->ccs_to_shift) ? 2 : $model->ccs_to_shift;

//Yii::$app->controls->view_date($date);
?>
<div class="modal fade" id="modal_<?= $table_class ?>" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h4 class="modal-title"><?= $popup_title ?></h4>
            </div>
            <div class="modal-body dashboard_controls">
                <?php
                $form = ActiveForm::begin([
                            'action' => ['index'],
                            'id' => $id
                ]);
                ?>
<!--                <div class="<?= $date_range_class ?>">
                <?= Yii::$app->controls->active_min_max_date($form, $model, 'from_date6', 'to_date6', $range_1_id_from, $range_1_id_to); ?>
                </div>-->
                <?php if (isset($from_date) && $from_date) { ?>
                    <div class="<?= $date_picker_class . ' ' . $common_class ?> ">
                        <?php
                        echo Yii::$app->controls->date($model, $form, 'from_date', 'form-group ' . $date_picker_class . ' ', false, false, false, false, $from_date_id);
                        ?>
                    </div> 
                <?php } ?>
                <!--<div class="clearfix"></div>-->
                <?php if (isset($from_shift) && $from_shift) { ?>
                    <div class="<?= $shift_class ?> shift  <?= $common_class ?>" id="vetical_milk">
                        <?php
                        echo Yii::$app->dropdown->dropdown('shift_applicability', $model, $form, 'col-sm-3 form-group shift', false, false, 'ccs_from_shift');
                        ?>
                    </div> 
                <?php } ?>
                <?php if (isset($to_date) && $to_date) { ?>
                    <div class="<?= $date_picker_class . ' ' . $common_class ?> ">
                        <?php
                        echo Yii::$app->controls->date($model, $form, 'to_date', 'form-group ' . $date_picker_class . ' ', false, false, false, false, $to_date_id);
                        ?>
                    </div>
                <?php } ?>
                <?php if (isset($to_shift) && $to_shift) { ?>
                    <div class="<?= $shift_class ?> shift <?= $common_class ?>">
                        <?php
                        echo Yii::$app->dropdown->dropdown('shift_applicability', $model, $form, 'col-sm-3 form-group shift', false, false, 'ccs_to_shift');
                        ?>
                    </div>  
                <?php } ?>
                <div class="col-sm-2">
                    <?=
                    $form->field($model, 'widget_for')->widget(Select2::classname(), [
                        'data' => $widget_for]
                    )->label(false);
                    ?>
                </div>
                <?= Html::activeHiddenInput($model, 'union_code'); ?>
                <?php //$form->field($model, 'federation_code', ['options' => ['class' => 'form-group col-sm-2 padding-right-0']])->dropDownList(\app\components\GeneralFunctions::getActiveFederation(), ['prompt' => 'Select Federation'])->label(false);    ?>
                <div class="col-sm-3 pt5 dashboard_modal_footer">
                    <?= Yii::$app->controls->custombutton('Apply', 'javascript:void(0)', false, $id . ' dashboardSearchButton'); ?>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
<div onclick="exportThisWithParameter('custom_report_coll_count_summary', '<?= $this->title ?>')" class="widget_table_search_btn downloadDashboardExcel right_30 mis_custom_report"><i class="fa fa-file-excel-o"></i></div>
<button type="button" class="widget_table_search_btn" data-toggle="modal" data-target="#modal_<?= $table_class ?>"><i class="fa fa-search"></i></button>
<?php
$script = "
    $(document).ready(function () {
        setCrossTab();
        $('.{$id}').on('click',function(e) {
            e.preventDefault(); 
            setCrossTab();
        });
        
        $('.dashboardSearchButton').on('click',function(e) {
            $('#modal_$table_class').modal('hide');
        });

        function setCrossTab(){  
            var blockDataString = $('#collapse1 form').serialize();
            var id= '" . $id . "';
            var union= '" . $unionCode . "';
            var mcc= '" . $mccCode . "';
            var from_date = $('#collec_count_summary_from_date').val();
            var to_date = $('#collec_count_summary_to_date').val();
            var from_shift = $('#dashboard-ccs_from_shift').val();
            var to_shift = $('#dashboard-ccs_to_shift').val();
            var widget_for = $('#dashboard-widget_for').val();
            $.ajax({
                type: 'post',
                url:'" . Url::to(['set-collection-count-summary']) . "',
                data: blockDataString+'&sp='+id+'&union='+union+'&mcc='+mcc+'&from_date='+from_date+'&to_date='+to_date+'&from_shift='+from_shift+'&to_shift='+to_shift+'&widget_for='+widget_for,
                success: function(data) {                                        
                    $('#" . $id . "_container').html(data);
                },
                error:function(data){

                }
            });   
            return false;
        }
    });
    
";
$this->registerJs($script, View::POS_READY, $id);
?>