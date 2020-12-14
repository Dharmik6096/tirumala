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
                    <div class="<?= $date_range_class ?>">
                        <?= Yii::$app->controls->active_min_max_date($form, $model, 'from_date6', 'to_date6', $range_1_id_from, $range_1_id_to); ?>
                    </div>
                    <div class="col-sm-2">
                        <?= $form->field($model, 'widget_for')->widget(Select2::classname(), [
                            'data' => $widget_for]
                            )->label(false);?>
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
            var id= '".$id."';
            var union= '" . $unionCode . "';
            var mcc= '" . $mccCode . "';
            var from_date = $('#colc_range_1_from').val();
            var to_date = $('#colc_range_1_to').val();
            var widget_for = $('#dashboard-widget_for').val();
            $.ajax({
                type: 'post',
                url:'" . Url::to(['set-collection-count-summary']) . "',
                data: blockDataString+'&sp='+id+'&union='+union+'&mcc='+mcc+'&from_date='+from_date+'&to_date='+to_date+'&widget_for='+widget_for,
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