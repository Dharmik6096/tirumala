<?php
return [
    [
        'columns' => [
            [
                'attribute' => 'union_code',
                'value' => Yii::$app->general->getforeignkey($model->unionCode, 'union_name'),
                'valueColOptions' => ['style' => 'width:30%'],
            ],
            [
                'attribute' => 'plant_code',
                'value' => Yii::$app->general->getforeignkey($model->plantCode, 'name'),
                'valueColOptions' => ['style' => 'width:30%'],
            ]
        ]
    ],
    [
        'columns' => [
            [
                'attribute' => 'mcc_plant_code',
                'value' => Yii::$app->general->getforeignkey($model->mccPlantCode, 'name'),
                'valueColOptions' => ['style' => 'width:30%'],
            ],
            [
                'attribute' => 'bmc_code',
                'value' => Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_name'),
                'valueColOptions' => ['style' => 'width:30%'],
            ]
        ]
    ],
    [
        'columns' => [
            [
                'attribute' => 'trip_code',
                'valueColOptions' => ['style' => 'width:30%'],
            ],
            [
                'attribute' => 'grn_no',
                'valueColOptions' => ['style' => 'width:30%'],
            ]
        ]
    ],
    [
        'columns' => [
            [
                'attribute' => 'dispatch_from',
                'valueColOptions' => ['style' => 'width:30%'],
            ],
            [
                'attribute' => 'receipt_at',
                'valueColOptions' => ['style' => 'width:30%'],
            ],
        ]
    ],
    [
        'columns' => [
            [
                'attribute' => 'dispatch_from_code',
                'value' => function () use ($model) {
                    $rel = Yii::$app->general->getDestRelation($model->dispatch_from);
                    $att = strtolower($model->dispatch_from) == 'bmc' ? 'bmc_name' : (strtolower($model->dispatch_from) == 'vendor' ? 'customer_name' : (strtolower($model->dispatch_from) == 'party' ? 'party_name' : 'name'));
                    if (!empty($rel))
                        return Yii::$app->general->getforeignkey($model->{$rel . 'Source'}, $att);
                },
                'valueColOptions' => ['style' => 'width:30%'],
            ],
            [
                'attribute' => 'receipt_at_code',
                'value' => function () use ($model) {
                    $rel = Yii::$app->general->getDestRelation($model->receipt_at);
                    $att = strtolower($model->receipt_at) == 'bmc' ? 'bmc_name' : (strtolower($model->receipt_at) == 'vendor' ? 'customer_name' : (strtolower($model->receipt_at) == 'party' ? 'party_name' : 'name'));
                    if (!empty($rel))
                        return Yii::$app->general->getforeignkey($model->{$rel . 'Dest'}, $att);
                },
                'valueColOptions' => ['style' => 'width:30%'],
            ]
        ]
    ],
    [
        'columns' => [
            [
                'attribute' => 'vehicle_entry_date',
                'value' => Yii::$app->controls->view_date($model->vehicle_entry_date),
                'valueColOptions' => ['style' => 'width:30%'],
            ], 
            [
                'attribute' => 'receipt_datetime',
                'value' => Yii::$app->controls->view_date($model->receipt_datetime),
                'valueColOptions' => ['style' => 'width:30%'],
            ]
        ]
    ],
    [
        'columns' => [
            [
                'attribute' => 'vehicle_code',
                'valueColOptions' => ['style' => 'width:30%'],
            ],
            [
                'attribute' => 'arrival_time',
                'valueColOptions' => ['style' => 'width:30%'],
            ]
        ]
    ],
    [
        'columns' => [
            [
                'attribute' => 'gross_weight',
                'valueColOptions' => ['style' => 'width:30%'],
            ],
            [
                'attribute' => 'tare_weight',
                'valueColOptions' => ['style' => 'width:30%'],
            ]
        ]
    ],
    [
        'columns' => [
            [
                'attribute' => 'tare_weight_time',
                'valueColOptions' => ['style' => 'width:30%'],
            ],
            [
                'attribute' => 'qty',
                'valueColOptions' => ['style' => 'width:30%'],
            ]
        ]
    ]
];
?>