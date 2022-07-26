<?php

use yii\helpers\Url;
use yii\web\View;
use kartik\grid\GridView;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
?>
<?php
$form = ActiveForm::begin(['options' => [
                'id' => 'responsibility-mapping-form',
            ],
        ]);
?>   
<div class="grid-search no-effect" >
    <?php
    $attribute = [
            ['attribute' => 'mcc_plant_code', 'label' => Yii::t('app', 'MCC Code'), 'filter' => false],
            ['attribute' => 'bmc_code', 'label' => Yii::t('app', 'BMC Code'), 'filter' => false],
            ['attribute' => 'bmc_ref_code', 'label' => Yii::t('app', 'BMC Ref Code'), 'filter' => false],
            ['attribute' => 'bmc_name', 'label' => Yii::t('app', 'BMC Name'), 'filter' => false],
            ['attribute' => 'dcs_code', 'label' => Yii::t('app', 'DCS Code'), 'filter' => false],
            ['attribute' => 'dcs_ref_code', 'label' => Yii::t('app', 'Society Ref Code'), 'filter' => false],
            ['attribute' => 'dcs_name', 'label' => Yii::t('app', 'DCS Name'), 'filter' => false],
            ['attribute' => 'date_time_of_collection', 'label' => Yii::t('app', 'Collection Date'),
            'value' => function($model) {
                return Yii::$app->controls->view_date($model['date_time_of_collection']);
            },
            'filter' => FALSE],
            ['attribute' => 'shift', 'filter' => false],
            ['attribute' => 'milk_quality_type', 'filter' => false],
            ['attribute' => 'qty', 'filter' => FALSE],
            ['attribute' => 'fat', 'filter' => FALSE],
            ['attribute' => 'snf', 'filter' => FALSE],
            ['attribute' => 'amount', 'filter' => FALSE],
            ['attribute' => 'penalty_amount', 'format' => 'decimal', 'filter' => FALSE],
            ['attribute' => 'rejection_responsibility_code',
            'label' => Yii::t('app', 'Responsibility'),
            'format' => 'raw',
            'contentOptions' => function($model) {
                return ['class' => 'hide_help_block div_margin_0'];
            },
            'value' => function ($model, $key, $index) use ($form, $searchModel) {
                $disable = FALSE;
                $searchModel->rejection_responsibility_code = $model['rejection_responsibility_code_auto'];
                if (!empty($model['rejection_responsibility_code'])) {
                    $searchModel->rejection_responsibility_code = $model['rejection_responsibility_code'];
                    $disable = TRUE;
                }
                return Html::activeHiddenInput($searchModel, '[' . $index . ']collection_code', ['value' => $model['collection_code']]) . Html::activeHiddenInput($searchModel, '[' . $index . ']collection_type', ['value' => $model['collection_type']]) .
                        Yii::$app->dropdown->dropdown('rejection_responsibility', $searchModel, $form, 'col-sm-3 form-group', FALSE, $disable, '[' . $index . ']rejection_responsibility_code');
            },
            'filter' => FALSE],
    ];

    $grid_option = [
        'id' => 'responsibility-mapping-list',
        'attributes' => $attribute,
        'active_column' => false,
    ];

    Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option, ['#'], false);
    ?>
</div>


<div class="col-sm-12 margin-top-10 form-group" >
    <?php if (!empty($dataProvider->getModels())) { ?>
        <?= Html::button(Yii::t('app', 'SAVE'), ['class' => 'btn btn-primary save', 'name' => 'save-data']); ?>
        <?= Yii::$app->controls->custombutton('Cancel', 'responsibility-mapping'); ?> 
    <?php } ?>
</div>

<?php ActiveForm::end(); ?>


<?php
$script = "$('.kv-panel-before').hide();";
$script .= "$('.save').on('click',function(){
           var msg = 'Are you sure you want to Save Data ?';        
       bootbox.confirm({
        message: '<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-question\'></i></div><span>' + msg + '</span></div></div>',
        buttons: {
            'cancel': {
                            label: 'Cancel',
                            className: 'btn btn-danger'
              },
            'confirm': {
                            label: 'Ok',
                            className: 'btn btn-primary'
             }
        },
        callback: function(result) {
            if (result) {
              $('#loader').show();              
              $('form#responsibility-mapping-form').submit();
            }
        }
    });
 });";
$this->registerJs($script, View::POS_END, 'responsibility-mapping');
?>
