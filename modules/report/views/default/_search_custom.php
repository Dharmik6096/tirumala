<?php

use yii\widgets\ActiveForm;
use yii\web\View;
use yii\helpers\Html;

$request = Yii::$app->request->queryParams;
$model->max_date = empty($model->max_date) ? date('d-m-Y') : $model->max_date;
$shift_no = empty($request["shift_no"]) ? 5 : $request["shift_no"];
$shifts = ['06:00:00' => 'Morning', '18:00:00' => 'Evening'];
$model_class = (new \ReflectionClass($model))->getShortName();
$field_class = strtolower($model_class);
$title = isset($this->title) ? $this->title : Yii::t('app', 'Search');
?>

<div class="modal modal-default fade" id="report_search_filter_two" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close close-import" data-bs-dismiss="modal">&times;</button>
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
                    <?php if (isset($bmc_filter)) { ?>
                        <div class="col-sm-3 padding-right-5">
                            <?= Yii::$app->dropdown->union_plant($model, $form, $field_class . '-union_code', 'plant_code'); ?>
                        </div>      
                        <div class="col-sm-3 padding-right-5">
                            <?= Yii::$app->dropdown->plant_mcc($model, $form, $field_class . '-plant_code', 'mcc_code'); ?>
                        </div>      
                        <div class="col-sm-3 padding-right-5">
                            <?= Yii::$app->dropdown->mcc_bmc($model, $form, $field_class . '-mcc_code', 'bmc_code'); ?>
                        </div>
                    <?php } ?>

                    <div class="col-sm-6 padding-left-0 padding-right-5">
                        <div class="form-group">
                            <?= Yii::$app->controls->date($model, $form, 'max_date')->label(false); ?>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <?= $form->field($model, 'shift')->dropDownList($shifts)->label(false); ?>
                    </div> 
                    <?php if (!isset($shift_cnt)) { ?>
                        <div class="col-sm-3 padding-left-0 padding-right-5">
                            <div class="form-group">
                                <div class="form-group">
                                    <?= Html::input('number', 'shift_no', $shift_no, ['class' => 'form-control', 'min' => 0]) ?>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>

                <div class="modal-footer mt10 col-sm-12">
                    <?= Yii::$app->controls->search(); ?>
                    <button type="button" class="btn btn-danger close-import" data-bs-dismiss="modal"><?= Yii::t('app', 'Cancel') ?></button>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

<div class="grid-search search-filter searchBtnReport text-right">
    <div class="btn-group btn btn-default report_modal_toggle_two"><i class="glyphicon glyphicon-search"></i></div>
</div>
<?php
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
    
});

        $('.report_modal_toggle_two').on('click', function(){
            $('#report_search_filter_two').modal('toggle');
        });
";

$this->registerJs($script, View::POS_END, 'date-validate');
