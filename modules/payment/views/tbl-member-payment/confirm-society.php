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
                'dcs_name',
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
                            'validateOnBlur' => false,
                            'validateOnEnter' => TRUE,
                            'validateOnChange' => FALSE,
                            'enableClientValidation' => true,
                            'validateOnSubmit' => true,
                            'action' => Url::to(['confirm-society'])
                ]);
                ?>
                <?= Html::activeHiddenInput($model, 'dcs_payment_cycle_code'); ?>
                <?= Html::activeHiddenInput($model, 'union_code'); ?>
                <?php foreach ($model->dcs_code as $dcs_code) { ?>
                    <?= Html::activeHiddenInput($model, 'dcs_code[]', ['value' => $dcs_code]); ?>
                <?php } ?>
                  <div class="col-md-12" >
                    <?php if (!empty($dataProvider->getModels())) { ?>
                        <?= Yii::$app->controls->save('Next', $model); ?>
                    <?php } ?>
                    <?= Yii::$app->controls->custombutton('Cancel', 'create'); ?>        
                </div>
                <?php ActiveForm::end(); ?>


            </div>
        </div>
    </div>
</div>
<?php 
$script="$('.kv-panel-before').hide();";
$this->registerJs($script, View::POS_END, 'panel-before-hide');
?>