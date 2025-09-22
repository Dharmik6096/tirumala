<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use webvimark\modules\UserManagement\components\GhostHtml;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

$user = \Yii::$app->session->get('UserCode');
?>

<div class="grid-search">
    <?php // $this->render('_search', ['model' => $searchModel]);   ?>
</div>

<?php
$attribute = [
        ['attribute' => 'union_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
        }, 'filter' => false, 'visible' => FALSE],
        ['label' => Yii::t('app', 'Soc. Code'), 'visible' => TRUE, 'attribute' => 'dcs_code', 'filter' => false],
//    ['label' => Yii::t('app', 'Old Soc. Code'), 'attribute' => 'dcs_code',
//        'value' => function($model) {
//            return Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_code_ex');
//        }, 'filter' => false, 'visible' => FALSE],
    ['label' => Yii::t('app', 'Ref. Code'), 'attribute' => 'ref_code',
        'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->dcsCode, 'ref_code');
        },],
        ['attribute' => 'dcs_code', 'label' => Yii::t('app', 'Society Name'),
        'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->dcsCode, 'dcs_name');
        }, 'filter' => false],
        ['attribute' => 'contact_person'],
        ['attribute' => 'contact_person_no'],
        ['attribute' => 'created_by', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->createdBy, 'name');
        }, 'filter' => false],
        ['attribute' => 'complaint_date',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->complaint_date);
        }],
        ['attribute' => 'product_code'],
        ['attribute' => 'product_name'],
        ['attribute' => 'service_call_no'],
        ['attribute' => 'complaint_type',
        'filter' => Yii::$app->dropdown->dropdownfilterStatic('complaint_type', $searchModel, 'complaint_type'),
        'value' => function ($model) {
            return isset($model->complaint_type) ? Yii::$app->dropdown->getRecords('complaint_type')['data'][$model->complaint_type] : '';
        },],
        ['attribute' => 'priority',
        'filter' => Yii::$app->dropdown->dropdownfilterStatic('priority', $searchModel, 'priority'),
        'value' => function ($model) {
            return isset($model->priority) ? Yii::$app->dropdown->getRecords('priority')['data'][$model->priority] : '';
        },],
        ['attribute' => 'complaint_desc'],
        ['attribute' => 'remarks'],
        ['attribute' => 'assign_to', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->assignTo, 'name');
        }, 'filter' => false],
        ['attribute' => 'assign_date',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->assign_date);
        }],
        ['attribute' => 'assign_time'],
        ['attribute' => 'resolution_type',
        'filter' => Yii::$app->dropdown->dropdownfilterStatic('resolution_type', $searchModel, 'resolution_type'),
        'value' => function ($model) {
            return isset($model->resolution_type) ? Yii::$app->dropdown->getRecords('resolution_type')['data'][$model->resolution_type] : '';
        },],
        ['attribute' => 'resolve_date',
        'filterType' => GridView::FILTER_DATE,
        'filterWidgetOptions' => [
            'pluginOptions' => ['format' => 'dd-mm-yyyy',
                'autoclose' => true]
        ],
        'value' => function($model) {
            return Yii::$app->controls->view_date($model->resolve_date);
        }],
        ['attribute' => 'is_chargeable',
        'filter' => Yii::$app->dropdown->dropdownfilterStatic('boolean_value', $searchModel, 'is_chargeable'),
        'value' => function ($model) {
            return isset($model->is_chargeable) ? Yii::$app->dropdown->getRecords('boolean_value')['data'][$model->is_chargeable] : '';
        },],
        ['attribute' => 'amount'],
        ['attribute' => 'km'],
        ['attribute' => 'complaint_status',
        'filter' => Yii::$app->dropdown->dropdownfilterStatic('complaint_status', $searchModel, 'complaint_status'),
        'value' => function ($model) {
            return isset($model->complaint_status) ? Yii::$app->dropdown->getRecords('complaint_status')['data'][$model->complaint_status] : '';
        },],
        [
        'attribute' => 'attachement',
        'format' => 'raw',
        'value' => function($model) {
            if (!empty($model->attachment)) {
                $absoluteBaseUrl = Url::base(true);
                $path = $absoluteBaseUrl . '/web/uploads/software-complaint-docs/';
                return Html::a('<i class="fa fa-download"><i/>', $path . $model->attachment, ['target' => '_blank']);
            }
        }],
];

$grid_option = [
    'id' => 'service-complaint',
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'view' => true,
        'edit' => function ($url, $model)use($user) {
            $addClass = '';
            $parent = !empty($model->engineerMap) ? ArrayHelper::map($model->engineerMap, 'user_id', 'user_id') : [];
            if (!empty($model->assign_to) && $model->assign_to != $user && !in_array($user, $parent)) {
                $addClass = 'link-disable';
            }
            $url = Url::to(['tbl-software-complaint/update', 'id' => $model->complaint_code]);
            return GhostHtml::a('<i class="fa fa-pencil"></i>', $url, ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Edit', 'class' => '' . $addClass]);
        },
        'assign' => function ($url, $model)use($user) {
            $addClass = '';
            $parent = !empty($model->engineerMap) ? ArrayHelper::map($model->engineerMap, 'user_id', 'user_id') : [];
            if (!empty($model->assign_to) && $model->assign_to != $user && !in_array($user, $parent)) {
                $addClass = 'link-disable';
            }
            $url = Url::to(['tbl-software-complaint/assign-complaint', 'id' => $model->complaint_code]);
            $status = $model->complaint_status;
            $class = ($status != 4) ? '' : 'link-disable';
            $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Assign', 'class' => '' . $class . ' ' . $addClass, 'data-val' => $model->complaint_code, 'data-name' => ''];
            return GhostHtml::a('<i class="fa fa-user"></i>', $url, $options);
        },
        'resolve' => function ($url, $model)use($user) {
            $addClass = '';
            $parent = !empty($model->engineerMap) ? ArrayHelper::map($model->engineerMap, 'user_id', 'user_id') : [];
            if (!empty($model->assign_to) && $model->assign_to != $user && !in_array($user, $parent)) {
                $addClass = 'link-disable';
            }
            $url = Url::to(['tbl-software-complaint/resolve-complaint', 'id' => $model->complaint_code]);
            $status = $model->complaint_status;
            $class = ($status == 2 || $status == 3) ? '' : 'link-disable';
            $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Resolve', 'class' => '' . $class . ' ' . $addClass, 'data-val' => $model->complaint_code, 'data-name' => ''];
            return GhostHtml::a('<i class="fa fa-check-square"></i>', $url, $options);
        },
        'service-bill' => function ($url, $model) {
            $url = Url::to(['tbl-software-complaint/service-bill', 'id' => $model->complaint_code]);
            $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Service Bill', 'target' => '_blank'];
            return GhostHtml::a('<i class="fa fa-money"></i>', $url, $options);
        },
    ]
];

Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>