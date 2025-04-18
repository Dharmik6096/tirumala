<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>

<?php

$attribute = [
    ['attribute' => 'process_name'],
    ['attribute' => 'process_code', 'value' => function($model){
        if(strtolower($model->process_name) == 'member provisional'){
            return Yii::$app->general->getforeignkey($model->memberProvisionalCode, 'member_name');
        } else {
            return Yii::$app->general->getforeignkey($model->memberProvisionalFamilyDetailCode, 'family_member_name');
        }
    }],
    ['attribute' => 'resp_param_1'],
    ['attribute' => 'resp_param_2'],
    ['attribute' => 'resp_param_3'],
    ['attribute' => 'resp_param_4'],
    ['attribute' => 'resp_param_5'],
    ['attribute' => 'resp_param_6'],
    ['attribute' => 'update_key'],
    ['attribute' => 'data_post_status'],
    ['attribute' => 'picked_datetime'],
    ['attribute' => 'resp_status'],
    ['attribute' => 'resp_desc'],
    ['attribute' => 'response_datetime'],
    ['attribute' => 'data_post_status', 'filter' => array('SUCCESS'=>'SUCCESS','ERROR'=>'ERROR'),],
];

$grid_option = [
    'id' => 'data-exchange-grid',
    'attributes' => $attribute,
    'active_column' => false,
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>
