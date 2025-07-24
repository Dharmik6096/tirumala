<?php

use yii\helpers\Html;
use webvimark\modules\UserManagement\components\GhostHtml;
use yii\helpers\Url;
use yii\web\View;

?>
<?php

$attribute = [
        ['attribute' => 'union_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->unionCode, 'union_name');
        }, 'filter' => FALSE, 'visible' => FALSE],
        ['attribute' => 'plant_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->plantCode, 'name');
        }, 'filter' => FALSE, 'visible' => FALSE],
        ['attribute' => 'mcc_plant_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->mccPlantCode, 'name');
        }, 'filter' => FALSE],
        ['attribute' => 'bmc_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_name');
        }, 'filter' => FALSE],
        ['attribute' => 'route_code', 'filter' => false, 'label' => Yii::t('app', 'Route Code')],
        ['attribute' => 'route_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->routeCode, 'route_name');
        }, 'filter' => FALSE],
        ['attribute' => 'route_code', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->routeCode, 'ref_code');
        }, 'filter' => FALSE, 'label' => 'Ref - Route Code'],
        ['attribute' => 'customer_type', 'value' => function($model) {
            return Yii::$app->general->getforeignkey($model->customerType, 'customer_desc');
        }, 'filter' => Yii::$app->dropdown->dropdownfilter('customer_type', $searchModel, 'customer_type', Yii::t('app', 'Select'))],
        ['attribute' => 'customer_code'],
        ['attribute' => 'customer_code_ex'],
        ['attribute' => 'ref_code'],
        ['attribute' => 'customer_name'],
        ['attribute' => 'local_name', 'filter' => FALSE],
        [
        'attribute' => 'gst_no',
        'headerOptions' => ['class' => 'hidden-for-specific-client'],
        'contentOptions' => ['class' => 'hidden-for-specific-client'],
        'filterOptions' => ['class' => 'hidden-for-specific-client'],
    ],
        [
        'attribute' => 'customer_category',
        'headerOptions' => ['class' => 'd-none-for-specific-client'],
        'contentOptions' => ['class' => 'd-none-for-specific-client'],
        'filterOptions' => ['class' => 'd-none-for-specific-client'],
    ],
        ['attribute' => 'address', 'filter' => FALSE, 'visible' => FALSE],
        ['attribute' => 'local_address', 'filter' => FALSE, 'visible' => FALSE],
        ['label' => Yii::t('app', 'Contact Person'), 'visible' => false, 'filter' => false,
        'value' => function($model) {
            $detail = Yii::$app->general->getDefaultContactDetail($model->customer_code, 'customer');
            isset($detail->firstname) ? $detail = $detail->firstname . ' ' . $detail->lastname . ' ' . $detail->surname : $detail = '';
            return $detail;
        }
    ],
        ['label' => 'Email', 'visible' => false, 'filter' => false,
        'value' => function($model) {
            $detail = Yii::$app->general->getDefaultContactDetail($model->customer_code, 'customer');
            isset($detail->email) ? $detail = $detail->email : $detail = '';
            return $detail;
        }
    ],
        ['label' => 'Department', 'visible' => false, 'filter' => false,
        'value' => function($model) {
            $detail = Yii::$app->general->getDefaultContactDetail($model->customer_code, 'customer');
            isset($detail->department) ? $detail = $detail->department : $detail = '';
            return $detail;
        }
    ],
// Bank Detail
    ['label' => 'Bank', 'visible' => false, 'filter' => false,
        'value' => function($model) {
            $detail = Yii::$app->general->getDefaultBankDetail($model->customer_code, 'customer');
            isset($detail->bankCode) ? $detail = $detail->bankCode->bank_name : $detail = '';
            return $detail;
        }
    ],
        ['label' => 'Branch', 'visible' => false, 'filter' => false,
        'value' => function($model) {
            $detail = Yii::$app->general->getDefaultBankDetail($model->customer_code, 'customer');
            isset($detail->branchCode) ? $detail = $detail->branchCode->branch_name : $detail = '';
            return $detail;
        }
    ],
        ['label' => 'Bank Account No', 'visible' => false, 'filter' => false,
        'value' => function($model) {
            $detail = Yii::$app->general->getDefaultBankDetail($model->customer_code, 'customer');
            isset($detail->bank_account_no) ? $detail = $detail->bank_account_no : $detail = '';
            return $detail;
        }
    ],
        ['label' => 'IFSC', 'visible' => false, 'filter' => false,
        'value' => function($model) {
            $detail = Yii::$app->general->getDefaultBankDetail($model->customer_code, 'customer');
            isset($detail->ifsc) ? $detail = $detail->ifsc : $detail = '';
            return $detail;
        }
    ],
        ['attribute' => 'sap_vendor_code', 'filter' => FALSE, 'visible' => FALSE],
        ['attribute' => 'x_col2', 'filter' => FALSE, 'visible' => FALSE],
        ['attribute' => 'ts_code_m', 'visible' => FALSE],
        ['attribute' => 'ts_code_e', 'visible' => FALSE],
        ['attribute' => 'status',
        'filter' => (!$pending_approval) ? Yii::$app->dropdown->dropdownfilterStatic('provisional_status', $searchModel, 'status') : false,
        'value' => function($model) {
            return isset(Yii::$app->dropdown->getRecords('provisional_status')['data'][$model->status]) ? Yii::$app->dropdown->getRecords('provisional_status')['data'][$model->status] : '';
        }],
        ['attribute' => 'data_post_status',
        'value' => function($model) {
            return isset(Yii::$app->dropdown->getRecords('send_status')['data'][$model->data_post_status]) ? Yii::$app->dropdown->getRecords('send_status')['data'][$model->data_post_status] : 'Pending';
        }, 'filter' => false, 'visible' => false],
        ['attribute' => 'picked_datetime',
        'value' => function($model) {
            return Yii::$app->controls->view_datetime($model->picked_datetime, 'php:d-m-Y H:i:s');
        }, 'filter' => FALSE, 'visible' => false],
        ['attribute' => 'response_datetime',
        'value' => function($model) {
            return Yii::$app->controls->view_datetime($model->response_datetime, 'php:d-m-Y H:i:s');
        }, 'filter' => FALSE, 'visible' => false],
        ['attribute' => 'resp_desc', 'filter' => FALSE, 'visible' => false],
        ['attribute' => 'pan_no', 'visible' => false, 'filter' => false],
];
$gridId = 'customer-master-list';
$grid_option = [
    'id' => $gridId,
    'attributes' => $attribute,
    'active_column' => false,
    'actions' => [
        'views' => function($url, $model) use ($pending_approval) {
            $icon = '<i class="fa fa-eye"></i>';
            $url = ['/organisation/tbl-customer-master-provisional/view', 'id' => $model->customer_provisional_code];
            if ($pending_approval) {
                $icon = '<i class="fa fa-check"></i>';
                $url = ['/organisation/tbl-customer-master-provisional/approve-customer-master', 'id' => $model->process_approval_code];
            }
            $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'View'];
            return Html::a($icon, $url, $options);
        },
        'update' => function ($url, $model) use ($pending_approval) {
            $class = '';
            if (!$pending_approval) {
                $class = ($model->status != 'Pending' && $model->status != 'Reroute') ? 'link-disable' : '';
            }
            $name = $model->customer_name;
            $options = ['data-toggle' => 'tooltip', 'data-placement' => 'top', 'data-original-title' => 'Edit', 'class' => '' . $class, 'data-val' => $model->customer_provisional_code, 'data-name' => $name];
            return GhostHtml::a('<i class="fa fa-pencil"></i>', $url, $options);
        },
        'add-document' => function ($url, $model) use ($pending_approval) {
            if ($pending_approval) {
                return false;
            }
            $disable = ($model->status == 'Pending' || $model->status == 'Reroute') ? '' : 'disabled';
            $options = ['title' => Yii::t('app', 'Add Document'), 'class' => $disable];
            return GhostHtml::a('<i class="fa fa-file"></i>', ['/organisation/tbl-customer-master-provisional/document-upload', 'id' => $model->customer_provisional_code], $options);
        },
        'repush' => function ($url, $model) use ($gridId) {
            return Yii::$app->general->createRePushLink($url, $model, $gridId, 'customer_provisional_code');
        },
    ]
];
Yii::$app->grid->bind($dataProvider, $searchModel, $grid_option);
?>