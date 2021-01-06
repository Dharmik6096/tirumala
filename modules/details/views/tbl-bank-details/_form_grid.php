<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

use yii\helpers\Html;
use webvimark\modules\UserManagement\components\GhostHtml;
use yii\helpers\Url;
use yii\web\View;

Url::remember();
$attribute = [
    ['attribute' => 'bank_code', 'value' => 'bankCode.bank_name', 'filter' => false],
    ['attribute' => 'branch_code', 'value' => 'branchCode.branch_name', 'filter' => false],
    ['attribute' => 'bank_account_no', 'filter' => false],
    ['attribute' => 'ifsc', 'filter' => false],
    ['attribute' => 'beneficiary_name', 'filter' => false],
    ['attribute' => 'adhar_no', 'filter' => false],
    ['attribute' => 'is_default', 'value' => function($model) {
            return $model->is_default == 1 ? 'Yes' : 'No';
        }, 'filter' => false],
];

$grid_option = [
    'id' => 'bank-list',
    'attributes' => $attribute,
    'active_column' => true,
    'actions' => [
        //'view' => true,
        'disable' => function ($url, $model) {
            $options = ['data-name' => $model->bank_account_no, 'data-val' => $model->detail_code, 'data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Deactivate', 'class' => 'deactive', 'data-is-default' => $model->is_default];
            return $model->is_active == 1 ? Html::a('<i class="fa fa-times"></i>', ['/details/tbl-bank-details/deactivate', 'id' => $model->detail_code], $options) : '';
        },
        'default' => function ($url, $model) {
            $options = ['data-name' => $model->bank_account_no, 'data-val' => $model->detail_code, 'data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Set as Default', 'class' => 'set-default'];
            return ($model->is_default == 0 && $model->is_active == 1) ? Html::a('<i class="fa fa-check"></i>', ['/details/tbl-bank-details/set-default', 'id' => $model->detail_code], $options) : '';
        },
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option, [Yii::$app->controller->action->id, 'id' => Yii::$app->request->get('id')]);
?>
<?php

$script = <<< JS
              
        $(".deactive").on('click',function(event){
            event.preventDefault();
            var trg=$(this);
            var id=$(this).parents('tr').find('td:eq(1)').text();
            var df = trg.attr("data-is-default");
            if(df==1){ var msg = 'This is a Default bank account, Are you sure you want to deactivate bank account'; }
            else { var msg = 'This can not be reactivate, Are you sure you want to deactivate bank account'; }
            bootbox.confirm({
                message: '<div class="row"><div class="col-sm-12"><div class="bg-info"><i class="fa fa-question"></i></div><span> '+msg+' "'+id+'"?</span></div></div>',
                buttons: {
                    'cancel': {
                           label: 'No',
                           className: 'btn-danger'
                      },
                    'confirm': {
                           label: 'Yes',
                           className: 'btn-primary'
                     }
                 },
                callback: function(result) {
                   if (result) {
                      window.location = trg.attr('href');
                   }
                    else{
                        //return false;
                    }
                }
             });
        });
        
JS;
$this->registerJs($script, View::POS_READY);
?>