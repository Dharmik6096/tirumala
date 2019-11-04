<?php

use yii\widgets\ActiveForm;
use yii\web\View;
use yii\helpers\Html;

$request = Yii::$app->request->queryParams;
$min_date = empty($request['min_date']) ? '' : $request['min_date'];
$max_date = empty($request['max_date']) ? '' : $request['max_date'];
$model->min_date = empty($model->min_date) ? date('d-m-Y') : $model->min_date;
$model->max_date = empty($model->max_date) ? date('d-m-Y') : $model->max_date;
$model->shift = empty($model->shift) ? '3' : $model->shift;
$title = isset($this->title) ? $this->title : Yii::t('app', 'Search');
?>

<div class="modal modal-default fade" id="report_search_filter" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close close-import" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo $title; ?></h4>
            </div>
            <?php
            $form = ActiveForm::begin([
                        'method' => 'get',
                        'id' => 'village-form',
                        'validateOnSubmit' => true,
            ]);
            ?>
            <div class="row margin_0">

                <div class="modal-body">
                    <div class="col-sm-6 padding-right-5">
                        <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', FALSE); ?>
                    </div>
                    <?php if (!empty($orgFilter)) { ?>
                        <div class="col-sm-6 padding-right-5">
                            <?= Yii::$app->dropdown->union_plant($model, $form, 'tblmilkcollectionsearch-union_code', 'plant_code'); ?>
                        </div>
                        <div class="col-sm-6 padding-right-5">
                            <?= Yii::$app->dropdown->plant_mcc($model, $form, 'tblmilkcollectionsearch-plant_code', 'mcc_code'); ?>
                        </div>      
                        <div class="col-sm-6 padding-right-5">
                            <?= Yii::$app->dropdown->mcc_bmc($model, $form, 'tblmilkcollectionsearch-mcc_code', 'bmc_code'); ?>
                        </div>
                        <div class="col-sm-6 padding-right-5">
                            <?= Yii::$app->dropdown->bmc_society($model, $form, 'tblmilkcollectionsearch-bmc_code', 'dcs_code'); ?> 
                        </div>
                    <?php } else { ?>
                        <div class="col-sm-6 padding-left-0 padding-right-5">
                            <?= Yii::$app->dropdown->depend_dropdown('dcs', $model, $form, 'tblmilkcollectionsearch-union_code'); ?>
                        </div>
                    <?php } ?>
                    <div class="col-sm-6 padding-left-0 height_65 padding-right-5">
                        <div class="form-group">
                            <?= Yii::$app->controls->active_min_max_date($form, $model, 'min_date', 'max_date', $min_date, $max_date); ?>
                        </div>
                    </div>
                    <?php if (!empty($shiftFilter)) { ?>
                        <div class="col-sm-6 padding-left-0 padding-right-5">
                            <?= Yii::$app->dropdown->dropdown('shift_applicability', $model, $form, '', false, false, 'shift'); ?>
                        </div>   
                    <?php } ?>
                </div>

                <div class="modal-footer mt10 col-sm-12">
                    <?= Yii::$app->controls->search(); ?>
                    <button type="button" class="btn btn-danger close-import" data-dismiss="modal"><?= Yii::t('app', 'Cancel') ?></button>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

<div class="grid-search search-filter searchBtnReport text-right">
    <div class="btn-group btn btn-default report_modal_toggle"><i class="glyphicon glyphicon-search"></i></div>
</div>

<?php
/* $script = "
  $('body').on('submit', '#village-form', function(e) {
  var dcs_code = $('#tblmilkcollectionsearch-dcs_code').val();
  var min_date = $('#tblmilkcollectionsearch-min_date').val();
  var min_date = $('#w0').val();
  var max_date = $('#tblmilkcollectionsearch-max_date').val();

  if(min_date == ''){
  alert ('Please Select From Date');
  preventEncryption($('#village-form'));
  return false;
  }
  else if(max_date == ''){
  alert ('Please Select To Date');
  return false;
  }

  });"; */
$script = '
        $(".report_modal_toggle").on("click", function(){
            $("#report_search_filter").modal("toggle");
        });
';
$this->registerJs($script, View::POS_END, 'report-popup');
