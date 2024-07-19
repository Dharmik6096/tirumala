<?php

use kartik\grid\GridView;
use webvimark\modules\UserManagement\components\GhostHtml;

$attribute = [
    ['attribute' => 'member_code', 'filter' => false],
    ['attribute' => 'member_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->memberCode, 'member_name');
        }, 'label' => Yii::t('app', 'Member Name'), 'vAlign' => 'middle', 'filter' => false],
    ['attribute' => 'concern', 'filter' => false],
    ['attribute' => 'discussions', 'filter' => false],
    ['attribute' => 'month',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->month);
        }, 'filter' => false],
    ['attribute' => 'process_type', 'filter' => false],
    ['attribute' => 'status', 'filter' => false],
    ['attribute' => 'remarks_actions', 'filter' => false],
];

$grid_option = [
    'id' => 'vcg-meeting-mom-grid',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'update' => function ($url, $model) {
            $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-val' => $model->VCG_MOM_id];
            return GhostHtml::a('<i class="fa fa-pencil"></i>', ['/feedback/tbl-vcg-meeting-mom/update', 'id' => $model->VCG_MOM_id], $options);
        },
    ]
];
?>
<div class="hideToggleBtn">
    <?php
    Yii::$app->grid->bind($momDataProvider, $momSearchModel, $grid_option);
    ?>
</div>
