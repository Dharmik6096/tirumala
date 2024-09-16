<div class="clearfix"></div>
<div class="hide_toolbar_only hide_filters_only">
    <?php

    $attribute = [
        ['attribute' => 'union_code', 'value' => function ($model) {
            return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
        }, 'visible' => false, 'filter' => false],
        ['attribute' => 'plant_code', 'value' => function ($model) {
            return Yii::$app->general->getforeignkey($model->plantCode, 'name');
        }, 'visible' => false, 'filter' => false],
        ['attribute' => 'mcc_plant_code', 'value' => function ($model) {
            return Yii::$app->general->getforeignkey($model->mccPlantCode, 'name');
        }, 'visible' => false, 'filter' => false],
        ['attribute' => 'bmc_code', 'value' => function ($model) {
            return Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_name');
        }, 'visible' => false, 'filter' => false],
        ['attribute' => 'end_point', 'filter' => false],
        ['attribute' => 'request_url', 'filter' => false],
        ['attribute' => 'request_desc', 'filter' => false],
        ['attribute' => 'txn_type', 'filter' => false],
        ['attribute' => 'date1', 'filter' => FALSE],
        ['attribute' => 'date2', 'filter' => false],
        ['attribute' => 'desc1', 'filter' => false],
        ['attribute' => 'desc2', 'filter' => false],
        ['attribute' => 'request_header', 'filter' => false],
        ['attribute' => 'request_payload', 'filter' => false],
        ['attribute' => 'response_payload', 'filter' => false],
        ['attribute' => 'request_timestamp', 'filter' => false],
        ['attribute' => 'response_timestamp', 'filter' => false],
        ['attribute' => 'status_code', 'filter' => false],
        ['attribute' => 'status_response', 'filter' => false],
        ['attribute' => 'status_message', 'filter' => false],
    ];

    $grid_option = [
        'id' => 'log-grid',
        'attributes' => $attribute,
        'active_column' => false,
        'default_sorting' => false
    ];

    Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
    ?>
</div>