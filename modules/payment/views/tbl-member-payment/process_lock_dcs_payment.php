<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\web\View;
use yii\helpers\Html;

$this->title = 'Farmer Payment Process : Step 2';
?>
<div class="tbl-banks-index">
    <div class="panel panel-default panel-grid panel-main">
        <div class="panel-heading">
            <?= $this->title; ?>           
        </div>


        <div class="panel-body">    
            <?php
            $attribute = [
                    ['attribute' => 'dcs_name'],
                    ['attribute' => 'member_count'],
                    ['attribute' => 'qty'],
                    ['attribute' => 'avg_fat'],
                    ['attribute' => 'avg_snf'],
                    ['attribute' => 'avg_rate'],
                    [
                    'attribute' => 'total_amount',
                    'hAlign' => Yii::$app->general->ColoumnAlign(),
                    'format' => Yii::$app->general->CurrencyFormat(),
                    'pageSummary' => true
                ],
                    [
                    'attribute' => 'total_deduction',
                    'hAlign' => Yii::$app->general->ColoumnAlign(),
                    'format' => Yii::$app->general->CurrencyFormat(),
                    'pageSummary' => true
                ],
                    [
                    'attribute' => 'final_amount',
                    'hAlign' => Yii::$app->general->ColoumnAlign(),
                    'format' => Yii::$app->general->CurrencyFormat(),
                    'pageSummary' => true
                ],
            ];

            $grid_option = [
                'id' => 'confirm-society',
                'attributes' => $attribute,
                'active_column' => false,
                'showPageSummary' => true,
                    //'actions' => []
            ];

            Yii::$app->grid->bind($dataProvider, $model, $grid_option, ['create'], false);
            ?>
            <div class="clearfix"></div>
            <div class="grid-search large-search hidden-print">
                <?php
                $form = ActiveForm::begin([
                            'id' => 'member-wise-payment-summary-form',
                            'validateOnBlur' => false,
                            'validateOnEnter' => TRUE,
                            'validateOnChange' => FALSE,
                            'enableClientValidation' => true,
                            'validateOnSubmit' => true,
                            'action' => Url::to(['list-member-payment'])
                ]);
                ?>
                <?= Html::activeHiddenInput($model, 'payment_cycle_code'); ?>
                <?= Html::activeHiddenInput($model, 'union_code'); ?>
                <?= Html::activeHiddenInput($model, 'plant_code'); ?>
                <?= Html::activeHiddenInput($model, 'mcc_plant_code'); ?>
                <?= Html::activeHiddenInput($model, 'bmc_code'); ?>
                <?= Html::activeHiddenInput($model, 'payment_cycle_code'); ?>
                <?= Html::hiddenInput('process_lock_flag', 'Process', ['class' => 'process_lock_flag']); ?>

                <div class="col-md-12" >
                    <?php if (!empty($dataProvider->getModels())) { ?>
                        <?php foreach ($dataProvider->getModels() as $data) { ?>
                            <?= Html::activeHiddenInput($model, 'dcs_code[]', ['value' => $data['dcs_code']]); ?>
                        <?php } ?>
                        <?= Html::button(Yii::t('app', 'Process'), ['class' => 'btn btn-primary ', 'id' => 'adjust']); ?>
                        <?= Html::button(Yii::t('app', 'Process and Lock'), ['class' => 'btn btn-primary', 'id' => 'adjust-lock']); ?>
                        <?php // Yii::$app->controls->save('Next', $model); ?>
                    <?php } ?>
                    <?= Yii::$app->controls->custombutton('Cancel', 'create-payment'); ?>        
                </div>
                <?php ActiveForm::end(); ?>


            </div>
        </div>
    </div>
</div>
<?php
$script = "$('.kv-panel-before').hide(); 
         $('#adjust').click(function() {
    $('.process_lock_flag').val('Process');
    $('#member-wise-payment-summary-form').submit();
});
$('#adjust-lock').click(function() {
    $('.process_lock_flag').val('Lock');
    $('#member-wise-payment-summary-form').submit();
});";
$this->registerJs($script, View::POS_END, 'panel-before-hide');
?>