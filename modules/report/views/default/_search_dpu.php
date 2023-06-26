<?php

use yii\widgets\ActiveForm;
use yii\web\View;
use yii\helpers\Html;
use app\modules\usermanagement\components\GhostHtml;

$request = Yii::$app->request->queryParams;
$min_date = empty($request['min_date']) ? date('d-m-Y') : $request['min_date'];
$max_date = empty($request['max_date']) ? date('d-m-Y') : $request['max_date'];
$model->min_date = empty($model->min_date) ? date('d-m-Y') : $model->min_date;
$model->shift = empty($model->shift) ? '3' : $model->shift;
$title = isset($this->title) ? $this->title : Yii::t('app', 'Search');
?>

<div class="modal modal-default fade" id="report_search_filter_three" role="dialog">
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
                    <div class="col-sm-3 padding-right-5">
                        <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', FALSE); ?>
                    </div>
                    <?php if (!empty($vondorFilter)) { ?>
                        <div class="col-sm-3">
                            <?php echo Yii::$app->dropdown->dropdownStatic('vendor', $model, $form, 'form-group', false); ?>
                        </div>   
                    <?php } ?>
                    <?php if (!isset($minMaxFilter)) { ?>
                        <div class="col-sm-6 padding-left-0 padding-right-5">
                            <div class="form-group">
                                <?= Yii::$app->controls->min_max_date('min_date', 'max_date', $min_date, $max_date); ?>
                            </div>
                        </div>           
                    <?php } else { ?>
                        <div class="col-sm-6 padding-left-0 padding-right-5">
                            <div class="form-group">
                                <?= Yii::$app->controls->date($model, $form, 'min_date')->label(false); ?>
                            </div>
                        </div>  
                    <?php } ?>

                    <?php if (!empty($shiftFilter)) { ?>
                        <div class="col-sm-3">
                            <?= Yii::$app->dropdown->dropdown('shift_applicability', $model, $form, '', false, false, 'shift'); ?>
                        </div>   
                    <?php } ?>
                    <?php if (!empty($milkFilter)) { ?>
                        <div class="col-sm-3">
                            <?= Yii::$app->dropdown->dropdown('milk_type_code', $model, $form, '', false, false, 'milk_type_code'); ?>
                        </div>   
                    <?php } ?>
                </div>

                <div class="modal-footer mt10 col-sm-12">
                    <?= Yii::$app->controls->search(); ?>
                    <?php
                    if (isset($detailUrl)) {
                        $qry_param = isset($_GET['q']) ? '?q=' . $_GET['q'] : '';
                        $detailUrl = \yii\helpers\Url::to([$detailUrl]);
                        echo GhostHtml::a('Detail Report', $detailUrl . $qry_param, ['class' => 'btn btn-default']);
                    }
                    ?>
                    <button type="button" class="btn btn-danger close-import" data-dismiss="modal"><?= Yii::t('app', 'Cancel') ?></button>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

<div class="grid-search search-filter searchBtnReport text-right">
    <div class="btn-group btn btn-default report_modal_toggle_three"><i class="glyphicon glyphicon-search"></i></div>
</div>
<?php
/*
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

  });"; */
$script = "
        $('.report_modal_toggle_three').on('click', function(){
            $('#report_search_filter_three').modal('toggle');
        });
";

$this->registerJs($script, View::POS_END, 'date-validate');
