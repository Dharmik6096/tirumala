<?php

use yii\bootstrap5\ActiveForm;
use yii\helpers\Url;
use yii\web\View;
use yii\helpers\Html;
use kartik\grid\GridView;
use webvimark\modules\UserManagement\components\GhostHtml;

//$action = Url::to(['create-transaction']);
$action = !empty($action) ? 'inward-asset-transaction' : 'outward-asset-transaction';
?>
<div class="grid-search no-effect" >

    <?php
    $form = ActiveForm::begin([
                'id' => 'create-asset-transaction',
                'action' => [$action],
    ]);
    ?>
    <div class="">
        <?php if ($action == 'inward-asset-transaction') { ?>
            <div class="col-sm-2">
                <?= Yii::$app->controls->date($txnModel, $form, 'received_date', '', false, false, false)->label(false); ?>
            </div>  
            <div class="col-sm-2">
                <?= $form->field($txnModel, 'received_by')->textInput(['placeholder' => 'Received By', 'readonly' => false])->label(FALSE) ?>
            </div>  <?php } else { ?>
            <div class="col-sm-2">
                <?= Yii::$app->controls->date($txnModel, $form, 'transaction_date', '', false, false, false)->label(false); ?>
            </div>
            <div class="col-sm-2">
                <?= Yii::$app->dropdown->dropdown('store_location_type', $txnModel, $form, 'form-group col-sm-2', FALSE, false, 'to_type'); ?>
            </div>
            <div class="col-sm-2">
                <?= Yii::$app->dropdown->depend_dropdown('slc_type', $txnModel, $form, 'tblassettransaction-to_type', '', false, 'to_dest', false); ?>
            </div>   <?php } ?>
        <div class="clearfix"></div>
        <?php
        $attribute = [
            ['class' => 'kartik\grid\CheckboxColumn',
                'rowSelectedClass' => GridView::TYPE_SUCCESS,
                'headerOptions' => ['class' => 'skip-export'], 'contentOptions' => ['class' => 'skip-export'],
                'checkboxOptions' => function($model) {
            return ['class' => 'checkbox', 'value' => $model['asset_transaction_code']];
        }],
            ['attribute' => 'asset_code',
                'value' => function($model) {
                    return Yii::$app->general->getforeignkey($model->assetCode, 'asset_name');
                }, 'filter' => false],
            ['attribute' => 'serial_number', 'filter' => false],
            ['attribute' => 'qty', 'filter' => false],
            ['attribute' => 'from_type', 'value' => function($model) {
                    return ($model->from_type == 'VEN') ? $model->from_type : Yii::$app->general->getmultiforeignkey($model->fromStoreLocCode, ['storeLocType'], 'slt_name');
                }, 'filter' => false],
                    ['attribute' => 'from_dest', 'value' => function($model) {
                            return ($model->from_type == 'VEN') ? Yii::$app->general->getmultiforeignkey($model->assetDetail, ['manufacturerCode'], 'manufacturer_name') : Yii::$app->general->getforeignkey($model->fromStoreLocCode, 'store_location_name');
                        }, 'filter' => false],
                            ['attribute' => 'to_type', 'value' => function($model) {
                                    return Yii::$app->general->getmultiforeignkey($model->toStoreLocCode, ['storeLocType'], 'slt_name');
                                }, 'filter' => false],
                                    ['attribute' => 'to_dest', 'value' => function($model) {
                                            return Yii::$app->general->getforeignkey($model->toStoreLocCode, 'store_location_name');
                                        }, 'filter' => false],
                                ];

                                $grid_option = [
                                    'id' => 'create-asset-transaction',
                                    'attributes' => $attribute,
                                    'active_column' => false,
                                    'showPageSummary' => false,
                                ];

                                Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option, ['#'], false);
                                ?>

                                <div class="panel-footer" >
                                    <?= Html::activeHiddenInput($txnModel, 'is_outward', ['id' => 'is_outward']) ?>
                                    <?php
                                    $defaultUrl = Url::to([$action]);
                                    if ($action == 'inward-asset-transaction') {
                                        ?>
                                        <?php
                                        if (!empty($dataProvider->getModels())) {
                                            echo Html::button(Yii::t('app', 'Accept'), ['class' => 'submit_btn btn btn-primary', 'data-url' => $defaultUrl, 'data-val' => 2, 'id' => 'adjust']);
                                        }
                                        ?>
                                    <?php } else { ?>
                                        <?php
                                        if (!empty($dataProvider->getModels())) {
                                            echo Html::button(Yii::t('app', 'Outward'), ['class' => 'submit_btn btn btn-primary', 'data-url' => $defaultUrl, 'data-val' => 1, 'id' => 'adjust',]);
                                            $defaultUrl = Url::to(['outward-use-transaction']);
                                            echo Html::button(Yii::t('app', 'in-use'), ['class' => 'submit_btn btn btn-primary', 'data-url' => $defaultUrl, 'data-val' => 0, 'id' => 'in-use']);
                                        }
                                        ?>
                                    <?php } ?>

                                    <?= Yii::$app->controls->custombutton('Cancel', 'index'); ?> 
                                </div>

                                <?php ActiveForm::end(); ?>
                            </div>   
                        </div>

                        <?php
                        $script = '
                    $(".kv-panel-before").hide();
                    $(".submit_btn").click(function() {
                         var len = $("input[class=\"checkbox kv-row-checkbox\"]:checked").length;
                         if(len == 0){
                            bootbox.alert("<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span> Please select at least one Asset.</span></div></div>");
                            return false;
                         } else {
                            var value = $(this).attr("data-val");
                            $("#is_outward").val(value);
                             var url = $(this).attr("data-url");
                            $("form#create-asset-transaction").attr("action", url);
                            $("#create-asset-transaction").submit();
                         }
                   });
                 ';
                        $this->registerJs($script, View::POS_END, 'create-detail-transaction');
                        ?>