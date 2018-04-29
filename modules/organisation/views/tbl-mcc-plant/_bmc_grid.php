<?php $attribute = [
    ['attribute' => 'bmc_code', 'value' => 'bmc_code','filter'=>false],
    ['attribute' => 'bmc_name', 'value' => 'bmc_name','filter'=>false],
    ['attribute' => 'capacity', 'value' => 'capacity0.value','filter'=>false],
];

$grid_option = [
    'id' => 'sub-center-bmc-list',
    'attributes' => $attribute,
    'active_column' => false,
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option,['index','dcs'=>Yii::$app->request->get('dcs'),'dcsname'=>Yii::$app->request->get('dcsname'),'subcenter'=>Yii::$app->request->get('subcenter'),'subname'=>Yii::$app->request->get('subname'),'type'=>Yii::$app->request->get('type')]);
?>