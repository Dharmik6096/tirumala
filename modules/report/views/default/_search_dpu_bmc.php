<?php

use yii\widgets\ActiveForm;
use yii\web\View;
use yii\helpers\Html;

$request = Yii::$app->request->queryParams;
$min_date = empty($request['min_date']) ? date('d-m-Y') : $request['min_date'];
$max_date = empty($request['max_date']) ? date('d-m-Y') : $request['max_date'];
$model->shift = empty($model->shift) ? '3' : $model->shift;
$model_class = (new \ReflectionClass($model))->getShortName();
$field_class = strtolower($model_class);
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
                    <div class="col-sm-6 padding-right-5">
                        <?= Yii::$app->dropdown->federation_union($model, $form, 'union_code', FALSE); ?>
                    </div>
                    <div class="col-sm-6 padding-right-5">
                        <?= Yii::$app->dropdown->union_plant($model, $form, $field_class . '-union_code', 'plant_code'); ?>
                    </div> 

                    <div class="col-sm-6 padding-right-5">
                        <?= Yii::$app->dropdown->plant_mcc($model, $form, $field_class . '-plant_code', 'mcc_code'); ?>
                    </div>      
                    <div class="col-sm-6 padding-right-5">
                        <?= Yii::$app->dropdown->mcc_bmc($model, $form, $field_class . '-mcc_code', 'bmc_code'); ?>
                    </div>
                    <div class="col-sm-6 padding-right-5">
                        <?= Yii::$app->dropdown->bmc_society($model, $form, $field_class . '-bmc_code', 'dcs_code'); ?>         
                    </div>
                    <div class="col-sm-6 padding-right-5">
                        <div class="form-group">
                            <?= Yii::$app->controls->min_max_date('min_date', 'max_date', $min_date, $max_date); ?>
                        </div>
                    </div>
                    <?php if (!empty($shiftFilter)) { ?>
                        <div class="col-sm-6">
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
    <div class="btn-group btn btn-default report_modal_toggle_three"><i class="glyphicon glyphicon-search"></i></div>
</div>
<?php
$script = "
        $('.report_modal_toggle_three').on('click', function(){
            $('#report_search_filter_three').modal('toggle');
        });
";

$this->registerJs($script, View::POS_END, 'dpu-bmc-search-popup');


