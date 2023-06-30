<?php

use yii\web\View;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use demogorgorn\ajax\AjaxSubmitButton;
use yii\web\JsExpression;
use yii\helpers\Html;

$society_name = !empty($dataProvider->getModels()) ?
        ' of ' . Yii::$app->general->getforeignkey($searchModel->memberCode, 'member_name') . ' (' .
        $searchModel->member_code . ')' : '';
$update_count = 0;
?>
<div class="modal modal-default fade" id="BillHeadModal" role="dialog">
    <div class="modal-dialog width_100-50_per">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-bs-dismiss="modal" aria-hidden="true">×  </button>
                <h4 class="modal-title" id="myModalLabel"><?= Yii::t('app', 'Bill Head Detail') . $society_name ?></h4>
            </div>
            <div class='panel-body row pad-10'>
                <div class="col-md-12">           
                    <?php
                    $form = ActiveForm::begin(['options' => [
                                    'class' => 'form-group popup-form',
                                    'id' => 'member-head-skip',
                                ],
                                'action' => Url::to(['/payment/tbl-member-payment/skip-head'])
                    ]);
                    echo $form->errorSummary($searchModel);
                    ?>
                    <div class="row">

                        <?php
                        $attribute = [
                                ['attribute' => 'bill_head_code', 'value' => function($model) {
                                    return Yii::$app->general->getforeignkey($model->billHeadCode, 'bill_head_name');
                                }
                            ],
                                ['attribute' => 'bill_head_type',
                                'value' => function($model) {
                                    return isset($model->billHeadCode->bill_head_type) ? Yii::$app->dropdown->getRecords('bill_head_type')['data'][$model->billHeadCode->bill_head_type] : 'N/A';
                                },],
                                ['attribute' => 'amount'],
                                ['attribute' => 'is_hold',
                                'value' => function($model) {
                                    return $model->is_hold == 1 ? 'Yes' : 'No';
                                },],
                                ['attribute' => 'current_cycle',
                                'value' => function($model) {
                                    return $model->is_skippable == 1 ? $model->payment_cycle_type : 'N/A';
                                }, 'visible' => $allow_update],
                                ['attribute' => 'payment_cycle_type',
                                'format' => 'raw',
                                'contentOptions' => ['class' => 'no_padding_input hide_help_block'],
                                'value' => function ($model, $key, $index) use ($form, $allow_update, &$update_count) {
                                    if ($allow_update && $model->is_skippable == 1) {
                                        $update_count++;
                                        if ($model->payment_cycle_type == 'first') {
                                            $items = ['first' => 'first', 'second' => 'second', 'third' => 'third', 'first-next' => 'first-NextMonth'];
                                        } else if ($model->payment_cycle_type == 'second') {
                                            $items = ['second' => 'second', 'third' => 'third', 'first-next' => 'first-NextMonth'];
                                        } else if ($model->payment_cycle_type == 'third') {
                                            $items = ['third' => 'third', 'first-next' => 'first-NextMonth'];
                                        } else {
                                            $items = ['consecutive' => 'consecutive', 'consecutive-next' => 'consecutive-Next', 'first-next' => 'first-NextMonth'];
                                        }
                                        return Html::activeHiddenInput($model, '[' . $index . ']member_payment_head_code', ['value' => $model->member_payment_head_code]) . Html::activeHiddenInput($model, '[' . $index . ']current_cycle', ['value' => $model->payment_cycle_type]) . $form->field($model, '[' . $index . ']payment_cycle_type')->dropDownList($items, ['class' => ''])->label(FALSE);
                                    }
                                }, 'visible' => $allow_update],
                        ];
                        $grid_option = [
                            'id' => 'bill-head-detail-list',
                            'attributes' => $attribute,
                            'active_column' => FALSE,
                        ];
                        Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option, ['#'], FALSE);
                        ?>
                    </div>
                    <div class="modal-footer mt10 col-sm-12">
                        <div class="col-md-12 top-bottom-15 padding-50">
                            <?php
                            if ($update_count > 0) {
                                AjaxSubmitButton::begin([
                                    'label' => Yii::t('app', 'Save'),
                                    'id' => 'skipHeadBtn',
                                    'ajaxOptions' => [
                                        'type' => 'POST',
                                        'url' => Url::to(['/payment/tbl-member-payment/skip-head']),
                                        'data' => new JsExpression('$("#member-head-skip").serializeArray()'),
                                        'dataType' => 'json',
                                        'success' => new JsExpression('function(data){
                                                                var data=$.parseJSON(data);
                                                                if (data.status == "success"){ 
                                                                    bootbox.alert("<div class=\"row\"><div class=\"col-sm-12\"><div class=\"bg-info\"><i class=\"fa fa-info\"></i></div><span>"+data.msg+" </span></div></div>", function(){
                                                                          location.reload(); 
                                                                    });
                                                                 }else{
                                                                    $(".form-group").removeClass("has-error");
                                                                    $(".error-summary").hide();
                                                                    $(".error-summary li").remove();
                                                                    $.each(data, function(key, val) {
                                                                        if(key=="msg" && key != null){   
                                                                        $(".error-summary ul").append("<li>"+val+"</li>");
                                                                        }
                                                                    });
                                                                    $(".error-summary").show();
                                                                   
                                                                }
                                                 }'),
                                    ],
                                    'options' => ['class' => 'btn btn-default btn-raised',
                                        'type' => 'submit'],
                                ]);
                                AjaxSubmitButton::end();
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
<?php
$script = "$('#BillHeadModal .kv-panel-before').hide();$('#BillHeadModal .filters').hide();";
$this->registerJs($script, View::POS_END, 'panel-before-hide');
?>
