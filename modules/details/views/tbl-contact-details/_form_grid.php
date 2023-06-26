<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use app\modules\usermanagement\components\GhostHtml;

Url::remember();
if ($searchModel->module_name == 'society') {
    $contact_person_lable = Yii::t('app', 'Society Secretory');
    $local_contact_person_lable = Yii::t('app', 'Society Secretory Hindi Name');
} else {
    $contact_person_lable = 'Contact Person';
    $local_contact_person_lable = Yii::t('app', 'Contact Person Hindi Name');
}
$attribute = [
//    'contact_person',
//    'local_contact_person',
    ['label' => $contact_person_lable, 'value' => 'fullname', 'filter' => false],
    ['label' => $local_contact_person_lable, 'value' => 'localfullname', 'filter' => false],
//    'fullname',
//    'localfullname',
    'email:email',
    'mobile_no',
    'department',
    ['attribute' => 'is_default', 'value' => function($model) {
            return $model->is_default == 1 ? 'Yes' : 'No';
        }, 'filter' => false],
    ['attribute' => 'is_contact_verified', 'value' => function($model) {
            return $model->is_contact_verified == 1 ? 'Verified' : ( $model->is_contact_verified == 2 ? 'Reject' : 'Pending');
        }, 'filter' => false],
    ['attribute' => 'remarks', 'filter' => false],
    ['attribute' => 'email_to', 'filter' => false],
    ['attribute' => 'email_cc', 'filter' => false],
    ['attribute' => 'email_bcc', 'filter' => false],
];

$grid_option = [
    'id' => 'contact-list',
    'attributes' => $attribute,
    'active_column' => true,
    'actions' => [
//      'view' => true,
//      'update' => true,
//      'delete' => ['option' => 'contact_person,detail_code,/details/tbl-contact-details/delete'],
        'disable' => function ($url, $model) {
            $class = $model->is_active == 1 ? '' : 'disabled';
            $options = ['data-name' => $model->contact_person, 'data-val' => $model->detail_code, 'data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Deactivate', 'class' => 'deactive ' . $class, 'data-is-default' => $model->is_default];
//            die('here');
            return Html::a('<i class="fa fa-times"></i>', ['/details/tbl-contact-details/deactivate', 'id' => $model->detail_code], $options);
        },
        'default' => function ($url, $model) {
            $class = $model->is_default == 0 && $model->is_active == 1 && !empty($model->mobile_no) ? '' : 'disabled';
            $options = ['data-name' => $model->contact_person, 'data-val' => $model->detail_code, 'data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Set as Default', 'class' => 'set-default ' . $class];
            return Html::a('<i class="fa fa-check"></i>', ['/details/tbl-contact-details/set-default', 'id' => $model->detail_code], $options);
        },
        'edit' => function ($url, $model) {
            $class = $model->is_active == 1 ? '' : 'disabled';
            $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Edit', 'class' => 'edit-record ' . $class, 'data-val' => $model->detail_code, 'data-name' => $model->detail_code, 'title' => Yii::t('app', 'Edit')];
            return GhostHtml::a_alert('<i class="fa fa-pencil"></i>', ['/details/tbl-contact-details/update-contact'], $options);
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
            if(df==1){ var msg = 'This is a Default Contact, Are you sure you want to deactivate Contact Person'; }
            else { var msg = 'This can not be reactivate, Are you sure you want to deactivate Contact Person'; }
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
