<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

use yii\helpers\Html;

if($searchModel->module_name == 'society') { 
    $contact_person_lable = Yii::t('app', 'Society Secretory');
    $local_contact_person_lable = Yii::t('app', 'Society Secretory Hindi Name');
} else {
    $contact_person_lable = Yii::t('app', 'Contact Person');
    $local_contact_person_lable = Yii::t('app', 'Contact Person Hindi Name');
}
$attribute = [
//    ['attribute' => 'contact_person', 'filter'=>false],
//    ['attribute' => 'local_contact_person', 'filter'=>false],
    ['label' => $contact_person_lable, 'value' => 'fullname','filter'=>false],
    ['label' => $local_contact_person_lable, 'value' => 'localfullname','filter'=>false],
//    ['attribute' => 'fullname', 'filter'=>false],
//    ['attribute' => 'localfullname', 'filter'=>false],
    ['attribute' => 'email', 'filter'=>false],
    ['attribute' => 'mobile_no', 'filter'=>false],
    ['attribute' => 'department', 'filter'=>false],
    ['attribute' => 'is_default','value'=>function($model){return $model->is_default==1?'Yes':'No';}, 'filter'=>false],
];

$grid_option = [
    'id' => 'contact-list',
    'attributes' => $attribute,
    'active_column' => true,
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option,[Yii::$app->controller->action->id,'id'=>Yii::$app->request->get('id')]);
?>
