<?php


use yii\helpers\Url;
use yii\web\View;
use webvimark\modules\UserManagement\components\GhostHtml;
?>

<?php

$attribute = [
    ['attribute' => 'union_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
        }, 'vAlign' => 'middle', 'filter' => false, 'visible' => FALSE],
    ['attribute' => 'party_type', 'filter' => FALSE, 'visible' => TRUE],
    ['attribute' => 'party_name'],
    ['attribute' => 'sap_vendor_code', 'filter' => TRUE, 'visible' => TRUE],
    ['attribute' => 'party_contact_no'],
    ['attribute' => 'owner_name'],
    ['attribute' => 'owner_contact_no'],
    ['attribute' => 'owner_email'],
    ['attribute' => 'pan_no', 'filter' => false],
    ['attribute' => 'adhar_no', 'filter' => false],
    ['attribute' => 'bank_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->bankCode, 'bank_name');
        }, 'vAlign' => 'middle', 'filter' => false, 'visible' => false],
    ['attribute' => 'branch_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->branchCode, 'branch_name');
        }, 'vAlign' => 'middle', 'filter' => false, 'visible' => false],
    ['attribute' => 'bank_account_no', 'visible' => false, 'filter' => false],
    ['attribute' => 'ifsc', 'visible' => false, 'filter' => false],
    ['attribute' => 'beneficiary_name', 'visible' => false, 'filter' => false],
];

$grid_option = [
    'id' => 'party-master-list',
    'attributes' => $attribute,
    'active_column' => true,
    'actions' => [
        'view' => TRUE,
        'update' => function ($url, $model) {
            $name = $model->party_name;
            $class = $model->is_active == 0 ? 'disabled' : '';
            $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Edit', 'class' => '' . $class, 'data-val' => $model->party_master_code, 'data-name' => $name];
            return GhostHtml::a('<i class="fa fa-pencil"></i>', $url, $options);
        },
        'deactive' => function ($url, $model) {
            $name = $model->party_name;
            $class = $model->is_active == 0 ? 'disabled' : '';
            $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Deactivate', 'class' => 'deact-party ' . $class, 'data-val' => $model->party_master_code, 'data-name' => $name];
            return GhostHtml::a_alert('<i class="fa fa-close"></i>', ['/tankermovement/tbl-party-master/deactivate-party'], $options);
        },
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
<?php

$script = "
$(document).ready(function(){
    $(document).on('click','.deact-party',function(e){
    var id= $(this).attr('data-val');
    var name = $(this).attr('data-name');
    bootbox.confirm({
        message: '<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-question\'></i></div><span>Are you sure you want to deactivate \"'+name+'\"?</span></div></div>',
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
                 $.ajax({
                        type: 'get',
                        url: '" . Url::to(['deactivate-party']) . "',
                        data:{'id':id},
                        success: function(data) {
                            var obj1 = $.parseJSON(data);
                            if (obj1.status == 'success')
                            {
                                $.pjax.reload({container: '#party-master-list'});
                                bootbox.alert(\"<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-info\'><i class=\'fa fa-info\'></i></div><span>\"+obj1.msg+\"</span></div></div>\");
                                //$.snackbar({content: 'Record successfully deleted.', timeout: 8000, style: 'successbar'});
                            }
                            else if (obj1.status == 'error'){
                                bootbox.alert(\"<div class=\'row\'><div class=\'col-sm-12\'><div class=\'bg-danger\'><i class=\'fa fa-times\'></i></div><span>\"+obj1.msg+\"</span></div></div>\");
                                //$.snackbar({content: 'Record is not deleted.', timeout: 8000, style: 'errorbar'});
                            }
                        },
                        error:function(data){
                                    //alert('Your data has not been submitted..Please try again');
                                }
            });
            }
        }
    });
    });
});";
$this->registerJs($script, View::POS_END, 'party-master-list-index');
?>