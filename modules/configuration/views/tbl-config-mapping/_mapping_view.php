<?php
$this->title = Yii::t('app', Yii::$app->label->title('list', 'Control Mapping View'));
?>
<div class="panel panel-default panel-grid panel-main">
    <div class="panel-heading">
        <?= $this->title; ?>
    </div>
    <div>
    <div class="panel-body">
        <?php
        $attribute = [
//            ['attribute' => 'union_code', 'value' => function($model) {
//                    return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
//                },
//                'filter' => false, 'visible' => FALSE],
            ['attribute' => 'plant_code', 'value' => function($model) {
                    return Yii::$app->general->getforeignkey($model->plantCode, 'name');
                },
                'filter' => false],
            ['attribute' => 'mcc_plant_code', 'value' => function($model) {
                    return Yii::$app->general->getforeignkey($model->mainMccCode, 'name');
                },
                'filter' => false],
            ['attribute' => 'bmc_code', 'value' => function($model) {
                    return Yii::$app->general->getforeignkey($model->mainBmcCode, 'bmc_name');
                },
                'filter' => false],
            ['attribute' => 'config_for', 'value' => function($model) {
                    return Yii::$app->general->getforeignkey($model->configCode, 'config_for');
                },
                'filter' => false],
            ['attribute' => 'process_name', 'value' => function($model) {
                    return Yii::$app->general->getforeignkey($model->configCode, 'process_name');
                },
                'filter' => false],
            ['attribute' => 'config_code', 'value' => function($model) {
                    return Yii::$app->general->getforeignkey($model->configCode, 'config_name');
                },
                'filter' => false],
        ];
        $grid_option = [
            'id' => 'config-mapping-view-list',
            'attributes' => $attribute,
            'active_column' => false,
        ];
        Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
        ?>

    </div>
</div>