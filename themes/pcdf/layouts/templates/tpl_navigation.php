<?php

use webvimark\modules\UserManagement\components\GhostMenu;
use webvimark\modules\UserManagement\models\User;

$cntrl = Yii::$app->controller->id;
$action = Yii::$app->controller->action->id;
$reportUser = User::canRoute('report/*');
$url_action[] = '/organisation/tbl-unions/index';
if (Yii::$app->session->get('Unions') != '' && count(explode(',', Yii::$app->session->get('Unions'))) == 1) {
    $url_action[] = '/organisation/tbl-unions/view';
    $url_action['id'] = Yii::$app->session->get('Unions');
}
$logout_url[] = '/user-management/auth/logout';

if (Yii::$app->session->get('Login-sess') == 'User') {
    $logout_url[] = '/user-management/auth/logout';
} else if (Yii::$app->session->get('Login-sess') == 'Rail') {
    $logout_url[] = '/site/rail-logout';
}
?>
<?php

echo GhostMenu::widget([
    'encodeLabels' => false,
    'activateParents' => true,
    'linkTemplate' => '<a href="{url}">{label}</a>',
    'options' => ['class' => 'nav navbar-nav navbar-right', 'id' => 'menu-content'],
    'submenuTemplate' => "\n<ul class='dropdown-menu'>\n{items}\n</ul>\n",
    'items' => [
        [
            'options' => ['class' => 'dropdown'],
            'template' => '<a href="#" data-target="#" data-toggle="dropdown" class="dropdown-toggle apply-shortcut" shortcut_key="shift+alt+c" >Master <b class="caret"></b></a>',
            'items' => [
                ['label' => Yii::t('app', 'PCDF Info'), 'url' => ['/organisation/tbl-federations/view', 'id' => Yii::$app->session->get('Federations')], 'active' => ($cntrl == 'tbl-federations')],
                ['label' => Yii::t('app', 'Union'), 'url' => $url_action, 'active' => ($cntrl == 'tbl-unions')],
                ['label' => Yii::t('app', 'Plant'), 'url' => ['/organisation/tbl-plant/index'], 'active' => ($cntrl == 'tbl-plant')],
                ['label' => Yii::t('app', 'MCC'), 'url' => ['/organisation/tbl-mcc-plant/index'], 'active' => ($cntrl == 'tbl-mcc-plant')],
                ['label' => Yii::t('app', 'BMC'), 'url' => ['/organisation/tbl-dcs-bmc/index'], 'active' => ($cntrl == 'tbl-dcs-bmc')],
                //['label' => Yii::t('app', 'Route'), 'url' => ['/organisation/tbl-routes/index'], 'active' => ($cntrl == 'tbl-routes')],
                ['label' => Yii::t('app', 'Route Mapping'), 'url' => ['/organisation/tbl-route-mapping/index'], 'active' => ($cntrl == 'tbl-route-mapping')],
                ['label' => Yii::t('app', 'Society'), 'url' => ['/organisation/tbl-dcs/index'], 'active' => ($cntrl == 'tbl-dcs' || (Yii::$app->request->get('type') == 'dcs' || Yii::$app->request->get('type') == 'DCS'))],
                ['label' => Yii::t('app', 'Member'), 'url' => ['/dcsoperation/tbl-member/index'], 'active' => ($cntrl == 'tbl-member')],
            ],
        ],
        [
            'options' => ['class' => 'dropdown'],
            'template' => '<a href="#" data-target="#" data-toggle="dropdown" class="dropdown-toggle apply-shortcut" shortcut_key="shift+alt+c" >Transporter <b class="caret"></b></a>',
            'items' => [
                ['label' => Yii::t('app', 'Transporter'), 'url' => ['/transporter/tbl-transporter/index'], 'active' => ($cntrl == 'tbl-transporter')],
                ['label' => Yii::t('app', 'Vehicle Master'), 'url' => ['/transporter/tbl-vehicle-master/index'], 'active' => ($cntrl == 'tbl-vehicle-master')],
                ['label' => Yii::t('app', 'Vehicle Km Information'), 'url' => ['/transporter/tbl-vehicle-km-info/index'], 'active' => ($cntrl == 'tbl-vehicle-km-info')],
                ['label' => Yii::t('app', 'Km Wise Rate'), 'url' => ['/transporter/tbl-km-wise-rate/index'], 'active' => ($cntrl == 'tbl-km-wise-rate')],
                ['label' => Yii::t('app', 'Mobile Oil Rate'), 'url' => ['/transporter/tbl-mobile-oil-rate-master/index'], 'active' => ($cntrl == 'tbl-mobile-oil-rate-master')],
                ['label' => Yii::t('app', 'Fuel Rate'), 'url' => ['/transporter/tbl-fuel-rate-master/index'], 'active' => ($cntrl == 'tbl-fuel-rate-master')],
                ['label' => Yii::t('app', 'Transporter Payment Head'), 'url' => ['/transporter/tbl-transporter-payment-head/index'], 'active' => ($cntrl == 'tbl-transporter-payment-head')],
                ['label' => Yii::t('app', 'Vehicle Transporter Payment Head'), 'url' => ['/transporter/tbl-vehicle-transporter-head-mapping/index'], 'active' => ($cntrl == 'tbl-vehicle-transporter-payment-head')],
            ],
        ],
        [
            'options' => ['class' => 'dropdown'],
            'template' => '<a href="#" data-target="#" data-toggle="dropdown" class="dropdown-toggle apply-shortcut" shortcut_key="shift+alt+c" >Milk Collection <b class="caret"></b></a>',
            'items' => [
                ['label' => 'Milk Collection', 'url' => ['/collection/tbl-milk-collection/index'], 'active' => ($cntrl == 'tbl-milk-collection')],
                ['label' => 'Milk Dispatch', 'url' => ['/collection/tbl-milk-dispatch/index'], 'active' => ($cntrl == 'tbl-milk-dispatch')],
                ['label' => 'BMC Collection', 'url' => ['/collection/tbl-bmc-collection/index'], 'active' => ($cntrl == 'tbl-bmc-collection')],
            ],
        ],
        [
            'options' => ['class' => 'dropdown'],
            'template' => '<a href="#" data-target="#" data-toggle="dropdown" class="dropdown-toggle apply-shortcut" shortcut_key="shift+alt+c" >Milk Rate <b class="caret"></b></a>',
            'items' => [
                ['label' => 'Rate Formula', 'url' => ['/dcsoperation/formula-master/index'], 'active' => ($cntrl == 'formula-master')],
                ['label' => 'Milk Purcahse Rate', 'url' => ['/dcsoperation/tbl-purchase-rate/index'], 'active' => ($cntrl == 'tbl-purchase-rate')],
            ],
        ],
        [
            'options' => ['class' => 'dropdown'],
            'template' => '<a href="#" data-target="#" data-toggle="dropdown" class="dropdown-toggle apply-shortcut" shortcut_key="shift+alt+c" >Product <b class="caret"></b></a>',
            'items' => [
                ['label' => 'Product Group', 'url' => ['/product/tbl-product-group/index'], 'active' => ($cntrl == 'tbl-product-group')],
                ['label' => 'Product', 'url' => ['/product/tbl-product/index'], 'active' => ($cntrl == 'tbl-product')],
                ['label' => 'Product Rate', 'url' => ['/product/tbl-product-rate/index'], 'active' => ($cntrl == 'tbl-product-rate')],
                ['label' => Yii::t('app', 'Product Sale'), 'url' => ['/payment/tbl-product-sale/index'], 'active' => ($cntrl == 'tbl-product-sale')],
            ],
        ],
        [
            'options' => ['class' => 'dropdown'],
            'template' => '<a href="#" data-target="#" data-toggle="dropdown" class="dropdown-toggle apply-shortcut" shortcut_key="shift+alt+c" >Payment <b class="caret"></b></a>',
            'items' => [
                ['label' => Yii::t('app', 'Payment Cycle'), 'url' => ['/payment/tbl-dcs-payment-cycle/index'], 'active' => ($cntrl == 'tbl-dcs-payment-cycle')],
                ['label' => Yii::t('app', 'Member Payment Process'), 'url' => ['/payment/tbl-member-payment/create'], 'active' => ($cntrl == 'tbl-member-payment' && $action == 'create')],
                ['label' => Yii::t('app', 'Member Payment Disburse'), 'url' => ['/payment/tbl-member-payment/export-payment-list'], 'active' => ($cntrl == 'tbl-member-payment' && $action == 'export-payment-list')],
                ['label' => Yii::t('app', 'Transporter Payment Process'), 'url' => ['/payment/tbl-transporter-payment/index'], 'active' => ($cntrl == 'tbl-transporter-payment')],
                ['label' => Yii::t('app', 'Transporter Payment Disburse'), 'url' => ['/payment/tbl-transporter-payment/payment-disburse'], 'active' => ($cntrl == 'tbl-transporter-payment-disburse')],
            //  ['label' => Yii::t('app', 'Disburse Payment'), 'url' => ['/payment/tbl-member-payment/disburse-payment'], 'active' => ($cntrl == 'tbl-member-payment1')],
            ],
        ],
//        ['label' => Yii::t('app', 'Update IMEI'), 'url' => ['/organisation/tbl-dcs/multi-imei-number'], 'active' => ($cntrl == 'tbl-dcs')],
        [
            'options' => ['class' => 'dropdown'],
            'template' => '<a href="#" data-target="#" data-toggle="dropdown" class="dropdown-toggle apply-shortcut" shortcut_key="shift+alt+c" >Reports <b class="caret"></b></a>',
            'items' => [
                [
                    'options' => ['class' => 'dropdown-submenu'],
                    'template' => '<a href="javascript:void(0)" class="dropdown-toggle">Milk Collection <b class="caret"></b></a>',
                    'items' => [
                        ['label' => '101-' . Yii::t('app', 'Member Milk Collection Summary'), 'url' => ['/jasperreports/default/member-milk-collection-summary']],
                        ['label' => '102-' . Yii::t('app', 'Consolidated Milk Collection'), 'url' => ['/jasperreports/default/consolidated-milk-collection']],
                        ['label' => '103-' . Yii::t('app', 'Shift Report (Name Wise)'), 'url' => ['/jasperreports/default/shift-report-name-wise']],
                        ['label' => '104-' . Yii::t('app', 'Member Milk Collection Register'), 'url' => ['/jasperreports/default/member-milk-collection-register']],
                        ['label' => '105-' . Yii::t('app', 'Consolited Milk Collection Union Wise – Table'), 'url' => ['/jasperreports/default/consolidated-dcs-milk-collection']],
                        ['label' => '106-' . Yii::t('app', 'Consolited Milk Collection Union Wise – Graph'), 'url' => ['/jasperreports/default/consolidated-union-milk-collection']],
                        ['label' => '107-' . Yii::t('app', 'Society Wise Collection and Dispatch Difference Report'), 'url' => ['/jasperreports/default/dcs-collection-dispatch-difference-report']],
                        ['label' => '108-' . Yii::t('app', 'Union Wise Collection and Dispatch Difference Report'), 'url' => ['/jasperreports/default/union-collection-dispatch-diff-report']],
                        ['label' => '109-' . Yii::t('app', 'Society Wise Payment Register'), 'url' => ['/jasperreports/default/society-wise-member-register']],
                        ['label' => '110-' . Yii::t('app', 'Union Wise Payment Register'), 'url' => ['/jasperreports/default/union-wise-member-register']],
                        ['label' => '111-' . Yii::t('app', 'Member Wise Payment register'), 'url' => ['/jasperreports/default/member-wise-payment-register']],
                        ['label' => '112-' . Yii::t('app', 'Member Classification Register'), 'url' => ['/jasperreports/default/member-classification-register']],
                        ['label' => '113-' . Yii::t('app', 'Member Payment Held Up'), 'url' => ['/jasperreports/default/member-payment-heldup']],
                        ['label' => '114-' . Yii::t('app', 'Consolidated Milk Collection Block Wise'), 'url' => ['/jasperreports/default/block-wise-collection']],
                        ['label' => '115-' . Yii::t('app', 'Payment Authorization'), 'url' => ['/jasperreports/default/payment-authorization']],
                        ['label' => '116-' . Yii::t('app', 'Society Wise Collection vs Dispatch - Graph'), 'url' => ['/jasperreports/default/dcs-collection-vs-dispatch-graph']],
                        ['label' => '117-' . Yii::t('app', 'Society Details'), 'url' => ['/jasperreports/default/society-details']],
                    ]
                ],
                [
                    'options' => ['class' => 'dropdown-submenu'],
                    'template' => '<a href="javascript:void(0)" class="dropdown-toggle">MIS <b class="caret"></b></a>',
                    'items' => [
                        ['label' => '201-' . Yii::t('app', 'Union Collection Report'), 'url' => ['/report/default/union-count']],
                        ['label' => '202-' . Yii::t('app', 'Society Collection Data Report'), 'url' => ['/report/default/list']],
                        ['label' => '203-' . Yii::t('app', 'Society Collection Summary Report'), 'url' => ['/report/default/index']],
                        ['label' => '204-' . Yii::t('app', 'No Collection Summary Report'), 'url' => ['/report/default/no-collection-society']],
                        ['label' => '205-' . Yii::t('app', 'No Collection Society Report'), 'url' => ['/report/default/no-collection-shifts']],
                        ['label' => '206-' . Yii::t('app', 'No Network Summary Report'), 'url' => ['/report/default/dpu-request-summary']],
                        ['label' => '207-' . Yii::t('app', 'No Network Detail Report'), 'url' => ['/report/default/dpu-request']],
                        ['label' => '208-' . Yii::t('app', 'Society-Shift Crosstab'), 'url' => ['/report/default/shift-report']],
                        ['label' => '209-' . Yii::t('app', 'Society-Day Crosstab'), 'url' => ['/report/default/daily-report']],
                        ['label' => '210-' . Yii::t('app', 'Union-Day Crosstab'), 'url' => ['/report/default/union-report']],
                        ['label' => '211-' . Yii::t('app', 'Society Shift Report'), 'url' => ['/report/default/dcs-shift-report']],
                        ['label' => '212-' . Yii::t('app', 'Union Shift Report'), 'url' => ['/report/default/union-shift-report']],
                        ['label' => '213-' . Yii::t('app', 'Union Shift Summary Report'), 'url' => ['/report/default/union-shift-summary-report']],
                        ['label' => '214-' . Yii::t('app', 'DPMCU Working Status'), 'url' => ['/report/default/dpmcu-working-status']],
                    //  ['label' => Yii::t('app', 'Cleaning Not Done'), 'url' => ['/report/default/cleaning-not-done']],
                    // ['label' => Yii::t('app', 'Calibration Change Report'), 'url' => ['/report/default/calibration-change-report']],
                    ]
                ],
                ['label' => '', 'url' => 'javascript:void(0)', 'visible' => $reportUser],
            ],
        ],
        [
            'options' => ['class' => 'dropdown'],
            'template' => '<a href="#" data-target="#" data-toggle="dropdown" class="dropdown-toggle apply-shortcut" shortcut_key="shift+alt+c" >Authorization <b class="caret"></b></a>',
            'items' => [
                ['label' => 'User', 'url' => ['/user-management/user/index'], 'active' => ($cntrl == 'user'),],
                ['label' => 'Role', 'url' => ['/user-management/role/index'], 'active' => ($cntrl == 'role'),],
//                ['label' => 'Set Originating Location', 'url' => ['/user-management/permission/set-originate-action'], 'active' => ($cntrl == 'permission'),],
                ['label' => 'Permission', 'url' => ['/user-management/permission/index'], 'active' => ($cntrl == 'permission'),],
                ['label' => 'Group', 'url' => ['/user-management/auth-item-group/index'], 'active' => ($cntrl == 'auth-item-group'),],
            ],
        ],
        [
            'options' => ['class' => 'dropdown'],
            'template' => '<a href="#" data-target="#" data-toggle="dropdown" class="dropdown-toggle apply-shortcut" shortcut_key="shift+alt+c" >System <b class="caret"></b></a>',
            'items' => [
                ['label' => '', 'url' => 'javascript:void(0)', 'visible' => true],
                [
                    'options' => ['class' => 'dropdown-submenu'],
                    'template' => '<a href="javascript:void(0)" class="dropdown-toggle">Geo Graphical <b class="caret"></b></a>',
                    'items' => [
                        ['label' => Yii::t('app', 'District'), 'url' => ['/geo/tbl-districts/index'], 'active' => ($cntrl == 'tbl-districts')],
                        ['label' => Yii::t('app', 'Sub District'), 'url' => ['/geo/tbl-sub-districts/index'], 'active' => ($cntrl == 'tbl-sub-districts')],
                        ['label' => Yii::t('app', 'Village'), 'url' => ['/geo/tbl-villages/index'], 'active' => ($cntrl == 'tbl-villages' || $cntrl == 'tbl-village-miscellaneous')],
                        ['label' => Yii::t('app', 'Hamlet'), 'url' => ['/geo/tbl-hamlets/index'], 'active' => ($cntrl == 'tbl-hamlets')],
                        ['label' => Yii::t('app', 'Blocks'), 'url' => ['/geo/tbl-blocks/index'], 'active' => ($cntrl == 'tbl-blocks')],
                    ],
                ],
                [
                    'options' => ['class' => 'dropdown-submenu'],
                    'template' => '<a href="javascript:void(0)" class="dropdown-toggle">Global <b class="caret"></b></a>',
                    'items' => [
                        ['label' => Yii::t('app', 'Society Type'), 'url' => ['/globalmaster/tbl-dcs-types/index'], 'active' => ($cntrl == 'tbl-dcs-types')],
                        ['label' => Yii::t('app', 'Caste Category Master'), 'url' => ['/globalmaster/tbl-caste-category/index'], 'active' => ($cntrl == 'tbl-caste-category')],
                        ['label' => Yii::t('app', 'Unit Master'), 'url' => ['/globalmaster/tbl-units/index'], 'active' => ($cntrl == 'tbl-units')],
                        ['label' => Yii::t('app', 'Land Unit'), 'url' => ['/globalmaster/tbl-land-unit/index'], 'active' => ($cntrl == 'tbl-land-unit')],
                        ['label' => Yii::t('app', 'Animal Type'), 'url' => ['/globalmaster/tbl-animal-type/index'], 'active' => ($cntrl == 'tbl-animal-type')],
                        ['label' => Yii::t('app', 'Milk Quality Type'), 'url' => ['/globalmaster/tbl-milk-quality-type/index'], 'active' => ($cntrl == 'tbl-milk-quality-type')],
                        ['label' => Yii::t('app', 'Miscellaneous'), 'url' => ['/globalmaster/tbl-miscellaneous/index'], 'active' => ($cntrl == 'tbl-miscellaneous')],
                        ['label' => Yii::t('app', 'Member Classification'), 'url' => ['/dcsoperation/tbl-member-classification/index'], 'active' => ($cntrl == 'tbl-member-classification')],
                        ['label' => Yii::t('app', 'Capacity'), 'url' => ['/globalmaster/tbl-capacity/index'], 'active' => ($cntrl == 'tbl-capacity')],
                        ['label' => Yii::t('app', 'Vehicle Type'), 'url' => ['/globalmaster/tbl-vehicle-type/index'], 'active' => ($cntrl == 'tbl-vehicle-type')],
                        ['label' => Yii::t('app', 'Backend Data'), 'url' => ['/general/default/backend-data'], 'active' => ($cntrl == 'backend-data')],
                    ]
                ],
                ['label' => Yii::t('app', 'Bank'), 'url' => ['/organisation/tbl-banks/index'], 'active' => ($cntrl == 'tbl-banks')],
                ['label' => Yii::t('app', 'Branch'), 'url' => ['/organisation/tbl-branch/index'], 'active' => ($cntrl == 'tbl-branch')],
                [
                    'options' => ['class' => 'dropdown-submenu'],
                    'template' => '<a href="javascript:void(0)" class="dropdown-toggle">Utility <b class="caret"></b></a>',
                    'items' => [
                        ['label' => 'FAT/SNF Threshold', 'url' => ['/general/tbl-fat-snf-threshold/index'], 'active' => ($cntrl == 'tbl-fat-snf-threshold')],
                        ['label' => 'Shift Time', 'url' => ['/general/tbl-shift-time/index'], 'active' => ($cntrl == 'tbl-shift-time')],
                        ['label' => 'Notification', 'url' => ['/notification/tbl-notifications/index'], 'active' => ($cntrl == 'tbl-notifications')],
                        ['label' => 'Complaint', 'url' => ['/complaint/tbl-complaint/index'], 'active' => ($cntrl == 'tbl-complaint')],
                        ['label' => 'General Configuration', 'url' => ['/setting/tbl-general-config/index'], 'active' => ($cntrl == 'tbl-general-config')],
                        ['label' => 'Custom Import (Create)', 'url' => ['/customimport/default/create-by-import'], 'active' => ($cntrl == 'customimport-create')],
                        ['label' => 'Custom Import (Update)', 'url' => ['/customimport/default/update-by-import'], 'active' => ($cntrl == 'customimport-update')],
                        ['label' => 'Escalation', 'url' => ['/email/tbl-email-rule-master/index']],
                        ['label' => 'Union Credit', 'url' => ['/payment/tbl-union-credit-limit/index']],
//                        ['label' => 'Member Credit', 'url' => ['/payment/tbl-member-credit-limit/index']],
                    ]
                ],
                [
                    'options' => ['class' => 'dropdown-submenu'],
                    'template' => '<a href="javascript:void(0)" class="dropdown-toggle">DPU Log <b class="caret"></b></a>',
                    'items' => [
                        ['label' => 'Dpu Calibration', 'url' => ['/tbl-dpu-calibration/index']],
                        ['label' => 'Dpu Cleaning', 'url' => ['/tbl-cleaning-dpu/index']],
                        ['label' => 'Member Acknowledgement', 'url' => ['/dcsoperation/tbl-member-download/index']],
                    ]
                ],
//                ['label' => Yii::t('app', 'Vendors'), 'url' => ['/general/tbl-society-vendor/index'], 'active' => ($cntrl == 'tbl-society-vendor')],
//                [
//                    'options' => ['class' => 'dropdown-submenu'],
//                    'template' => '<a href="javascript:void(0)" class="dropdown-toggle">BIPL Ack <b class="caret"></b></a>',
//                    'items' => [
//                        ['label' => 'Rate Downloaded', 'url' => ['/bipl/bipl-change-acknowledgement/index', 'flag' => 'rate']],
//                        ['label' => 'Member Downloaded', 'url' => ['/bipl/bipl-change-acknowledgement/index', 'flag' => 'member']],
//                    ]
//                ],
                [
                    'options' => ['class' => 'dropdown-submenu'],
                    'template' => '<a href="javascript:void(0)" class="dropdown-toggle">Collection Files<b class="caret"></b></a>',
                    'items' => [
//                        ['label' => 'BIPL Files Process', 'url' => ['/collection/tbl-processed-files/index']],
                        ['label' => 'EIPL Files Process', 'url' => ['/eipl-packet/create']],
                    ]
                ],
            ],
        ],
        [
            'options' => ['class' => 'dropdown user'],
            'template' => '<a href="#" data-target="#" data-toggle="dropdown" class="dropdown-toggle apply-shortcut" shortcut_key="shift+alt+c" ><i class="fa fa-user"></i> <b class="caret"></b></a>',
            'items' => [
                ['label' => "<span class='user'><b>" . Yii::t('app', 'User: ') . "</b>" . explode('#', Yii::$app->session->get('UserName'))[1] . "</span>"],
                ['label' => "<span class='user'><b>" . Yii::t('app', 'Org.Type: ') . "</b>" . Yii::$app->session->get('organizations_type') . "</span>"],
                ['label' => 'Change password', 'url' => ['/user-management/auth/change-own-password']],
                ['label' => 'Logout', 'url' => $logout_url],
            ],
        ],
    ],
]);
?>