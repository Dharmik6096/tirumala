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
                    <?= Yii::$app->controls->custombutton('Apply', 'javascript:void(0)', false, $id . ' dashboardSearchButton'); ?>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
<div onclick="exportThisWithParameter('custom_report_1', '<?= $this->title ?>')" class="widget_table_search_btn downloadDashboardExcel right_30 mis_custom_report"><i class="fa fa-file-excel-o"></i></div>
<button type="button" class="widget_table_search_btn" data-toggle="modal" data-target="#modal_<?= $table_class ?>"><i class="fa fa-search"></i></button>

<?php
$client_code = \Yii::$app->session->get('eiplCode');
$client_code = !empty($client_code) ? $client_code : '';
$client_code = strtolower($client_code);
?>
<div class="dashboard_milk_analysis margin_bottom_10">
    <div class="table-responsive overflow_hidden dashboard_tbl dashboard_table_section">
        <div class="dynamic_report_table overflow_auto dynamic_report_margin">
            <table id="custom_report_1" class="fht-table table table-striped dashboardMilkAnalysis_grid">
                <thead class="dashboardWidgetDetailPortion">
                    <tr>
                        <!-- <th rowspan='2'><?php //Yii::t('app', 'MCC')        ?></th> -->
                        <th rowspan='2'><?= Yii::t('app', 'BMC ') ?></th>
                        <th colspan='7'><?= Yii::t('app', 'CC Collection') ?></th>
                        <th colspan='6'><?= Yii::t('app', 'BMC Receipts') ?></th>
                        <?php if ($client_code != 'gyan') { ?>
                            <th colspan='6'><?= Yii::t('app', 'Bulk Vendor Receipt') ?></th>
                            <th colspan='6'><?= Yii::t('app', 'Total') ?></th>
                        <?php } ?>
                        <th colspan='5'><?= Yii::t('app', 'CC Differences') ?></th>
                    </tr>
                    <tr>
                        <th><?= Yii::t('app', 'Qty') ?></th>
                        <th><?= Yii::t('app', 'FAT') ?></th>
                        <th><?= Yii::t('app', 'SNF') ?></th>
                        <th><?= Yii::t('app', 'Rate') ?></th>
                        <th><?= Yii::t('app', 'Amount') ?></th>
                        <th><?= Yii::t('app', 'No Of Farmers') ?></th>
                        <th><?= Yii::t('app', 'CC Count') ?></th>
                        <!-- <th><?php //Yii::t('app', 'Online')        ?></th>
                        <th><?php //Yii::t('app', 'Pendrive')        ?></th>
                        <th><?php //Yii::t('app', 'Manual')        ?></th> -->

                        <th><?= Yii::t('app', 'Qty') ?></th>
                        <th><?= Yii::t('app', 'FAT') ?></th>
                        <th><?= Yii::t('app', 'SNF') ?></th>
                        <th><?= Yii::t('app', 'Rate') ?></th>
                        <th><?= Yii::t('app', 'Amount') ?></th>
                        <th><?= Yii::t('app', 'Count') ?></th>
                        <?php if ($client_code != 'gyan') { ?>
                            <th><?= Yii::t('app', 'Qty') ?></th>
                            <th><?= Yii::t('app', 'FAT') ?></th>
                            <th><?= Yii::t('app', 'SNF') ?></th>
                            <th><?= Yii::t('app', 'Rate') ?></th>
                            <th><?= Yii::t('app', 'Amount') ?></th>
                            <th><?= Yii::t('app', 'Count') ?></th>

                            <th><?= Yii::t('app', 'Qty') ?></th>
                            <th><?= Yii::t('app', 'FAT') ?></th>
                            <th><?= Yii::t('app', 'SNF') ?></th>
                            <th><?= Yii::t('app', 'Rate') ?></th>
                            <th><?= Yii::t('app', 'Amount') ?></th>
                            <th><?= Yii::t('app', 'Count') ?></th>
                        <?php } ?>

                        <th><?= Yii::t('app', 'Qty') ?></th>
                        <th><?= Yii::t('app', 'FAT') ?></th>
                        <th><?= Yii::t('app', 'SNF') ?></th>
                        <th><?= Yii::t('app', 'Amount') ?></th>
                        <th><?= Yii::t('app', 'Count') ?></th>
                    </tr>
                </thead>
                <tbody class="dashboardMilkAnalysis_tbody">

                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
$script = "
$('.dashboardSearchButton').on('click',function(e) {
    $('#modal_$table_class').modal('hide');
});

$('.{$id}').on('click',function(e) {
    e.preventDefault(); 
    var blockDataString = $('#$id').serialize();
    var union= '" . $unionCode . "';
    var mcc= '" . $mccCode . "';
    var value = 'milk_analysis_grid';
    parseMilkAnalysis(blockDataString,union,mcc,value);
});
";
$this->registerJs($script, View::POS_READY, $id);
?>