<?php

use yii\helpers\Url;
use yii\web\View;
use kartik\grid\GridView;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use webvimark\modules\UserManagement\components\GhostHtml;
?>
<?php
$form = ActiveForm::begin(['options' => [
                'id' => 'member-data-lock-form',
            ],
        ]);
?>   
<?= Html::hiddenInput('flag', '', ['id' => 'flag']); ?>
<div class="grid-search no-effect" >
    <?php
    $attribute = [
            ['class' => 'kartik\grid\CheckboxColumn',
            'rowSelectedClass' => GridView::TYPE_SUCCESS,
            'headerOptions' => ['class' => 'skip-export'], 'contentOptions' => ['class' => 'skip-export'],
            'checkboxOptions' => function($model) {
                $value = $model['shift_lock_code'] . '###' . $model['member_lock'] . '###' . $model['union_code'] . '###' . $model['plant_code'] . '###' . $model['mcc_plant_code'] . '###' . $model['date_time_of_collection'] . '###' . $model['shift_code'] . '###' . $model['qty'] . '###' . $model['avgFAT'] . '###' . $model['avgSNF'] . '###' . $model['amount'];
                return ['class' => 'checkbox-collection', 'value' => $value];
            }],
            ['attribute' => 'mcc_plant_code', 'label' => Yii::t('app', 'MCC Code'), 'filter' => false],
            ['attribute' => 'ref_code', 'label' => Yii::t('app', 'Ref Code'), 'filter' => false],
            ['attribute' => 'mcc', 'label' => Yii::t('app', 'MCC Name'), 'filter' => false],
            ['attribute' => 'date_time_of_collection', 'label' => Yii::t('app', 'Collection Date'),
            'value' => function($model) {
                return Yii::$app->controls->view_date($model['date_time_of_collection']);
            },
            'filter' => FALSE],
            ['attribute' => 'shift', 'filter' => false],
            ['attribute' => 'qty', 'filter' => FALSE],
            ['attribute' => 'avgFAT', 'filter' => FALSE],
            ['attribute' => 'avgSNF', 'filter' => FALSE],
            ['attribute' => 'amount', 'filter' => FALSE],
            ['attribute' => 'member_lock', 'label' => Yii::t('app', 'Data Locked?'), 'filter' => FALSE,
            'value' => function ($model) {
                $data = Yii::$app->dropdown->getRecords('boolean_value')['data'];
                return isset($data[$model['member_lock']]) ? $data[$model['member_lock']] : '';
            }
        ],
            ['attribute' => 'file_status', 'label' => Yii::t('app', 'File Status'), 'filter' => FALSE,
            'value' => function ($model) {
                $data = Yii::$app->dropdown->getRecords('file_status')['data'];
                return isset($data[$model['file_status']]) ? $data[$model['file_status']] : '';
            }
        ],
    ];

    $grid_option = [
        'id' => 'shift-lock-member-list',
        'attributes' => $attribute,
        'active_column' => false,
    ];

    Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option, ['#'], false);
    ?>
</div>


<div class="col-sm-12 margin-top-10 form-group" >
    <?php if (!empty($dataProvider->getModels())) { ?>
        <?= GhostHtml::a_alert(Yii::t('app', 'LOCK'), ['/collection/tbl-mcc-shift-lock/member-data-lock'], ['class' => 'btn btn-primary save', 'name' => 'lock-data']); ?>
        <?= GhostHtml::a_alert(Yii::t('app', 'UN-LOCK'), ['/collection/tbl-mcc-shift-lock/member-data-unlock'], ['class' => 'btn btn-primary save', 'name' => 'unlock-data']); ?>
        <?= Yii::$app->controls->custombutton('Cancel', 'index-member'); ?> 
    <?php } ?>
</div>

<?php ActiveForm::end(); ?>


<?php
$script = "$('.kv-panel-before').hide();";
$script .= "$('.save').on('click',function(){
          var len = $('input[class=\'checkbox-collection kv-row-checkbox\']:checked').length;
            if(len == 0){
                bootbox.alert('<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-danger\'><i class=\'fa fa-times\'></i></div><span>Please select at least one record.</span></div></div>');
                return false;
            } else { 
            var type = $(this).prop('name');
            $('#flag').val(type);
            var msg = '';        
            if(type=='lock-data'){
                msg = 'Are you sure you want to LOCK Data ?'
            }else{
                msg = 'Are you sure you want to UN-LOCK Data ?'        
            }
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
              $('form#member-data-lock-form').submit();
            }
        }
    });
 }  
 });";
$this->registerJs($script, View::POS_END, 'shift-lock-member');
?>
