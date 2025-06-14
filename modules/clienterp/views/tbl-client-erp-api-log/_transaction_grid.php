<div class="clearfix"></div>
<div class="hide_toolbar_only hide_filters_only">
    <?php

    $attribute = [
        ['attribute' => 'source_org_type', 'filter' => false],
        [
            'attribute' => 'source_org_type',
            'label' => Yii::t('app', 'Source Name'),
            'value' => function ($model) {
                $rel = Yii::$app->general->getDestRelation($model->source_org_type);
                $sourceOrgType = strtolower($model->source_org_type);
                $att = ($sourceOrgType == 'bmc') ? 'bmc_name' : (($sourceOrgType == 'party') ? 'party_name' : (($sourceOrgType == 'vendor') ? 'customer_name' : 'name'));
                if (!empty($rel))
                    return Yii::$app->general->getforeignkey($model->{$rel . 'Source'}, $att);
            }, 'vAlign' => 'middle', 'filter' => false
        ],
        [
            'attribute' => 'source_org_type',
            'label' => Yii::t('app', 'Source SAP Vendor Code'),
            'value' => function ($model) {
                $rel = Yii::$app->general->getDestRelation($model->source_org_type);
                $sourceOrgType = strtolower($model->source_org_type);
                $att = 'sap_vendor_code';
                if (!empty($rel))
                    return Yii::$app->general->getforeignkey($model->{$rel . 'Source'}, $att);
            }, 'vAlign' => 'middle', 'filter' => false
        ],
        ['attribute' => 'destination_type', 'filter' => false],
        [
            'attribute' => 'destination_type',
            'label' => Yii::t('app', 'Dest Name'),
            'value' => function ($model) {
                $rel = Yii::$app->general->getDestRelation($model->destination_type);
                $destType = strtolower($model->destination_type);
                $att = ($destType == 'bmc') ? 'bmc_name' : (($destType == 'party') ? 'party_name' : (($destType == 'vendor') ? 'customer_name' : 'name'));
                if (!empty($rel))
                    return Yii::$app->general->getforeignkey($model->{$rel . 'Dest'}, $att);
            }, 'vAlign' => 'middle', 'filter' => false
        ],
        [
            'attribute' => 'destination_type',
            'label' => Yii::t('app', 'Dest SAP Vendor'),
            'value' => function ($model) {
                $rel = Yii::$app->general->getDestRelation($model->destination_type);
                $att = 'sap_vendor_code';
                if (!empty($rel))
                    return Yii::$app->general->getforeignkey($model->{$rel . 'Dest'}, $att);
            }, 'vAlign' => 'middle', 'filter' => false
        ],
        ['attribute' => 'entry_type', 'filter' => false],
        ['attribute' => 'chamber_quantity', 'filter' => false],
        ['attribute' => 'fat', 'filter' => false],
        ['attribute' => 'snf', 'filter' => false],
        ['attribute' => 'water', 'filter' => FALSE],
        ['attribute' => 'density', 'filter' => false],
        ['attribute' => 'protein', 'filter' => false],
        ['attribute' => 'lactose', 'filter' => false],
        ['attribute' => 'freezing_point', 'filter' => false],
        ['attribute' => 'mbrt', 'filter' => false],
        ['attribute' => 'temp', 'filter' => false],
        ['attribute' => 'acidity', 'filter' => false],
    ];

    $grid_option = [
        'id' => 'transaction-grid',
        'attributes' => $attribute,
        'active_column' => false,
        'default_sorting' => false
    ];

    Yii::$app->grid->bind($dataTransactionProvider, $searchTransactionModel, $grid_option);
    ?>
</div>