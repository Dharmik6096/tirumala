<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\View;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\modules\organisation\models\TblDcsSearch */
/* @var $form yii\widgets\ActiveForm */
$model->from_date_milk_analysis = empty($model->from_date) ? Yii::$app->controls->view_date(date('Y-m-d')) : $model->from_date;
$model->to_date_milk_analysis = empty($model->to_date) ? Yii::$app->controls->view_date(date('Y-m-d')) : $model->to_date;

$date_range_class = !empty($date_range_class) ? $date_range_class : 'col-sm-3';


$unionCode = !empty($model->union_code) ? $model->union_code : '';
$mccCode = !empty($model->mcc_code) ? $model->mcc_code : '';
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
                <div class="clearfix pt10"></div>
                <div class="col-sm-6">
                    <div class="<?= $date_range_class ?>">
                        <?php if ($date_range) { ?>
                            <?= Yii::$app->controls->active_min_max_date($form, $model, 'from_date_milk_analysis', 'to_date_milk_analysis', $range_id_from, $range_id_to); ?>
                        <?php } ?>
                    </div>
                </div>
                <?php //$form->field($model, 'federation_code', ['options' => ['class' => 'form-group col-sm-2 padding-right-0']])->dropDownList(\app\components\GeneralFunctions::getActiveFederation(), ['prompt' => 'Select Federation'])->label(false);    ?>
                <div class="col-sm-3 pt5 dashboard_modal_footer">
                    <?= Yii::$app->controls->custombutton('Apply', 'javascript:void(0)', false, $id . ' dashboardSearchButton_analysis'); ?>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
<div onclick="exportThisWithParameter('custom_report', '<?= $this->title ?>')" class="widget_table_search_btn downloadDashboardExcel right_30 mis_custom_report"><i class="fa fa-file-excel-o"></i></div>
<button type="button" class="widget_table_search_btn" data-toggle="modal" data-target="#modal_<?= $table_class ?>"><i class="fa fa-search"></i></button>

<div class="dashboard_milk_analysis">
    <div class="table-responsive overflow_hidden dashboard_tbl dashboard_table_section">
        <div class="custom_report_table dynamic_report_table dynamic_report_margin dashboardMilkAnalysis_vertical">
            
        </div>
    </div>
</div>

<?php
$script = "
$('.dashboardSearchButton_analysis').on('click',function(e) {
    $('#modal_$table_class').modal('hide');
});

$('.{$id}').on('click',function(e) {
    e.preventDefault(); 
    var blockDataString = $('#$id').serialize();
    var union= '" . $unionCode . "';
    var mcc= '" . $mccCode . "';
    var value = 'milk_analysis_vertical';
    parseMilkAnalysis(blockDataString,union,mcc,value);
});
";
$this->registerJs($script, View::POS_READY, $id);
?>