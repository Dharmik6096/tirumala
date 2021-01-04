<?php

use webvimark\modules\UserManagement\components\GhostMenu;
use webvimark\modules\UserManagement\models\User;

$cntrl = Yii::$app->controller->id;
$action = Yii::$app->controller->action->id;
$reportUser = User::canRoute('report/*');
if (Yii::$app->session->get('Unions') != '' && count(explode(',', Yii::$app->session->get('Unions'))) == 1) {
    $url_action[] = '/organisation/tbl-unions/view';
    $url_action['id'] = Yii::$app->session->get('Unions');
} else {
    $url_action[] = '/organisation/tbl-unions/index';
}
$logout_url[] = '/user-management/auth/logout';

if (Yii::$app->session->get('Login-sess') == 'User') {
    $logout_url[] = '/user-management/auth/logout';
} else if (Yii::$app->session->get('Login-sess') == 'Rail') {
    $logout_url[] = '/site/rail-logout';
}
$collectionApproval = Yii::$app->general->getUnionConfiguration(explode(',', Yii::$app->session->get('Unions')), 'collection_approval', 'PORTAL') == 1 ? TRUE : FALSE;
$eiplCode = Yii::$app->session->get('eiplCode');
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
//                ['label' => Yii::t('app', 'PCDF Info'), 'url' => ['/organisation/tbl-federations/view', 'id' => Yii::$app->session->get('Federations')], 'active' => ($cntrl == 'tbl-federations')],
                    ['label' => Yii::t('app', 'Union'), 'url' => $url_action, 'active' => ($cntrl == 'tbl-unions')],
                    ['label' => Yii::t('app', 'Plant'), 'url' => ['/organisation/tbl-plant/index'], 'active' => ($cntrl == 'tbl-plant')],
                    ['label' => Yii::t('app', 'Cluster'), 'url' => ['/organisation/tbl-cluster/index'], 'active' => ($cntrl == 'tbl-cluster')],
                    ['label' => Yii::t('app', 'MCC'), 'url' => ['/organisation/tbl-mcc-plant/index'], 'active' => ($cntrl == 'tbl-mcc-plant')],
                    ['label' => Yii::t('app', 'BMC'), 'url' => ['/organisation/tbl-dcs-bmc/index'], 'active' => ($cntrl == 'tbl-dcs-bmc')],
                //['label' => Yii::t('app', 'Route'), 'url' => ['/organisation/tbl-routes/index'], 'active' => ($cntrl == 'tbl-routes')],
                ['label' => Yii::t('app', 'Route Mapping'), 'url' => ['/organisation/tbl-route-mapping/index'], 'active' => ($cntrl == 'tbl-route-mapping')],
                    ['label' => Yii::t('app', 'Society'), 'url' => ['/organisation/tbl-dcs/index'], 'active' => ($cntrl == 'tbl-dcs' || (Yii::$app->request->get('type') == 'dcs' || Yii::$app->request->get('type') == 'DCS'))],
                    ['label' => Yii::t('app', 'Vendor/Customer'), 'url' => ['/organisation/tbl-customer-master/index'], 'active' => ($cntrl == 'tbl-customer-master')],
                    ['label' => Yii::t('app', 'Member'), 'url' => ['/dcsoperation/tbl-member/index'], 'active' => ($cntrl == 'tbl-member')],
                    ['label' => Yii::t('app', 'Provisional Member'), 'url' => ['/dcsoperation/tbl-member-provisional/index'], 'active' => ($cntrl == 'tbl-member-provisional')],
                    ['label' => Yii::t('app', 'Transfer Request'), 'url' => ['/organisation/tbl-master-transfer/index'], 'active' => ($cntrl == 'tbl-master-transfer')],
                    ['label' => Yii::t('app', 'Bulk Notification'), 'url' => ['/sms/tbl-bulk-notification/index'], 'active' => ($cntrl == 'tbl-bulk-notification')],
                    [
                    'options' => ['class' => 'dropdown-submenu toggle_right'],
                    'template' => '<a href="javascript:void(0)" class="dropdown-toggle">Accounting Master <b class="caret"></b></a>',
                    'items' => [
                            ['label' => Yii::t('app', 'Tax Group'), 'url' => ['/dcsaccounting/tbl-tax-group/index'], 'active' => ($cntrl == 'tbl-tax-group')],
                            ['label' => Yii::t('app', 'Basic Tax'), 'url' => ['/dcsaccounting/tbl-basic-tax/index'], 'active' => ($cntrl == 'tbl-basic-tax')],
                            ['label' => Yii::t('app', 'Tax'), 'url' => ['/dcsaccounting/tbl-tax/index'], 'active' => ($cntrl == 'tbl-tax')],
                    ]
                ],
            ],
        ],
            [
            'options' => ['class' => 'dropdown'],
            'template' => '<a href="#" data-target="#" data-toggle="dropdown" class="dropdown-toggle apply-shortcut" shortcut_key="shift+alt+c" >Transporter <b class="caret"></b></a>',
            'items' => [
                    ['label' => Yii::t('app', 'Transporter'), 'url' => ['/transporter/tbl-transporter/index'], 'active' => ($cntrl == 'tbl-transporter')],
                    ['label' => Yii::t('app', 'Vehicle Master'), 'url' => ['/transporter/tbl-vehicle-master/index'], 'active' => ($cntrl == 'tbl-vehicle-master')],
                    ['label' => Yii::t('app', 'Vehicle Km Information'), 'url' => ['/transporter/tbl-vehicle-km-info/index'], 'active' => ($cntrl == 'tbl-vehicle-km-info')],
                    ['label' => Yii::t('app', 'Vehicle Extra Km'), 'url' => ['/transporter/tbl-vehicle-extra-km-daywise/index'], 'active' => ($cntrl == 'tbl-vehicle-extra-km-daywise')],
                    ['label' => Yii::t('app', 'Km Wise Rate'), 'url' => ['/transporter/tbl-km-wise-rate/index'], 'active' => ($cntrl == 'tbl-km-wise-rate')],
//                ['label' => Yii::t('app', 'Mobile Oil Rate'), 'url' => ['/transporter/tbl-mobile-oil-rate-master/index'], 'active' => ($cntrl == 'tbl-mobile-oil-rate-master')],
                ['label' => Yii::t('app', 'Fuel Rate'), 'url' => ['/transporter/tbl-fuel-rate-master/index'], 'active' => ($cntrl == 'tbl-fuel-rate-master')],
                    ['label' => Yii::t('app', 'Transporter Payment Head'), 'url' => ['/transporter/tbl-transporter-payment-head/index'], 'active' => ($cntrl == 'tbl-transporter-payment-head')],
                    ['label' => Yii::t('app', 'Vehicle Transporter Payment Head'), 'url' => ['/transporter/tbl-vehicle-transporter-head-mapping/index'], 'active' => ($cntrl == 'tbl-vehicle-transporter-payment-head')],
            ],
        ],
            [
            'options' => ['class' => 'dropdown'],
            'template' => '<a href="#" data-target="#" data-toggle="dropdown" class="dropdown-toggle apply-shortcut" shortcut_key="shift+alt+c" >Milk Collection <b class="caret"></b></a>',
            'items' => [
                    [
                    'options' => ['class' => 'dropdown-submenu toggle_right'], 'visible' => $collectionApproval,
                    'template' => '<a href="javascript:void(0)" class="dropdown-toggle">Approval Data <b class="caret"></b></a>',
                    'items' => [
                            ['label' => 'Milk Collection Approval', 'url' => ['/collection/tbl-collection-data-alias/milk-collection-approve'], 'active' => ($cntrl == 'tbl-collection-data-alias' && $action == 'milk-collection-approve')],
                            ['label' => 'BMC Collection Approval', 'url' => ['/collection/tbl-collection-data-alias/bmc-collection-approve'], 'active' => ($cntrl == 'tbl-collection-data-alias' && $action == 'bmc-collection-approve')],
                            ['label' => 'Milk Dispatch Approval', 'url' => ['/collection/tbl-collection-data-alias/milk-dispatch-approve'], 'active' => ($cntrl == 'tbl-collection-data-alias' && $action == 'milk-dispatch-approve')],
                    ]
                ],
                    ['label' => 'Milk Collection', 'url' => ['/collection/tbl-milk-collection/index'], 'active' => ($cntrl == 'tbl-milk-collection' && in_array($action, ['index', 'create', 'view', 'update']))],
                    ['label' => 'Milk Dispatch', 'url' => ['/collection/tbl-milk-dispatch/index'], 'active' => ($cntrl == 'tbl-milk-dispatch')],
                    ['label' => 'Milk Dispatch - New', 'url' => ['/collection/tbl-dcs-milk-dispatch/index'], 'active' => ($cntrl == 'tbl-dcs-milk-dispatch')],
                    ['label' => Yii::t('app', 'BMC Collection'), 'url' => ['/collection/tbl-bmc-collection/index'], 'active' => ($cntrl == 'tbl-bmc-collection')],
                    ['label' => Yii::t('app', 'Milk Rejection'), 'url' => ['/collection/tbl-milk-reject/index'], 'active' => ($cntrl == 'tbl-milk-reject')],
                    ['label' => Yii::t('app', 'BMC Dispatch'), 'url' => ['/collection/tbl-bmc-dispatch/index'], 'active' => ($cntrl == 'tbl-bmc-dispatch')], //                ['label' => 'Local Milk Sale', 'url' => ['/collection/tbl-tab-local-sale/index'], 'active' => ($cntrl == 'tbl-tab-local-sale')],
                ['label' => 'Local Milk Sale', 'url' => ['/collection/collection-farmer-local-sale/index'], 'active' => ($cntrl == 'collection-farmer-local-sale')],
                    ['label' => 'DPU Shift End Summary', 'url' => ['/collection/tbl-dpu-shift-end-summary/index'], 'active' => ($cntrl == 'tbl-dpu-shift-end-summary')],
                    ['label' => 'Milk Collection Summary', 'url' => ['/collection/tbl-milk-collection-summary/index'], 'active' => ($cntrl == 'tbl-milk-collection-summary')],
                    ['label' => Yii::t('app', 'BMC Testing Data'), 'url' => ['/collection/tbl-quality-collection/index'], 'active' => ($cntrl == 'tbl-quality-collection')],
                    ['label' => Yii::t('app', 'BMC Weight Data'), 'url' => ['/collection/tbl-weight-collection/index'], 'active' => ($cntrl == 'tbl-weight-collection')],
                //['label' => 'Manual Milk Collection', 'url' => ['/collection/tbl-milk-collection-temp/index'], 'active' => ($cntrl == 'tbl-milk-collection-temp' && $action == 'index')],
// ['label' => 'Milk Collection Approve', 'url' => ['/collection/tbl-milk-collection-temp/get-temp-data'], 'active' => ($cntrl == 'tbl-milk-collection-temp' && $action == 'get-temp-data')],
                ['label' => 'Cleaning', 'url' => ['/collection/tbl-m-a-cleaning/index'], 'active' => ($cntrl == 'tbl-ma-cleaning')],
                    ['label' => 'Calibration', 'url' => ['/collection/tbl-m-a-c-alibration/index'], 'active' => ($cntrl == 'tbl-ma-calibration')],
                    ['label' => 'SAP Data Repost', 'url' => ['/collection/tbl-milk-collection/repost-sap-data'], 'active' => ($cntrl == 'tbl-milk-collection' && $action == 'repost-sap-data')],
                    [
                    'options' => ['class' => 'dropdown-submenu toggle_right'],
                    'template' => '<a href="javascript:void(0)" class="dropdown-toggle">Tanker Movement <b class="caret"></b></a>',
                    'items' => [
                            ['label' => 'Trip Master', 'url' => ['/tankermovement/tbl-vehicle-trip/index'], 'active' => ($cntrl == 'tbl-vehicle-trip')],
                            ['label' => 'Inspection Detail', 'url' => ['/tankermovement/tbl-bmc-dispatch-inspection/index'], 'active' => ($cntrl == 'tbl-bmc-dispatch-inspection')],
                            ['label' => 'BMC Milk Dispatch', 'url' => ['/tankermovement/tbl-bmc-milk-dispatch/index'], 'active' => ($cntrl == 'tbl-bmc-milk-dispatch')],
                            ['label' => 'Sample Bottle Testing', 'url' => ['/tankermovement/tbl-sample-bottle-testing/index'], 'active' => ($cntrl == 'tbl-sample-bottle-testing')],
                    // ['label' => 'Milk Receipt', 'url' => ['/tankermovement/tbl-milk-vehicle-entry/index'], 'active' => ($cntrl == 'tbl-milk-vehicle-entry')],
                    ]
                ],
            ],
        ],
            [
            'options' => ['class' => 'dropdown'],
            'template' => '<a href="#" data-target="#" data-toggle="dropdown" class="dropdown-toggle apply-shortcut" shortcut_key="shift+alt+c" >Milk Rate <b class="caret"></b></a>',
            'items' => [
                    ['label' => 'Rate Formula', 'url' => ['/dcsoperation/formula-master/index'], 'active' => ($cntrl == 'formula-master')],
                    ['label' => 'Milk Purchase Rate', 'url' => ['/dcsoperation/tbl-purchase-rate/index'], 'active' => ($cntrl == 'tbl-purchase-rate')],
                    ['label' => 'Milk Purchase Rate (' . Yii::t('app', 'BMC') . ')', 'url' => ['/dcsoperation/tbl-dcs-purchase-rate/index'], 'active' => ($cntrl == 'tbl-dcs-purchase-rate')],
                    ['label' => 'Rate Recalculation', 'url' => ['/dcsoperation/tbl-rate-recalculation/index'], 'active' => ($cntrl == 'tbl-rate-recalculation')],
            ],
        ],
            [
            'options' => ['class' => 'dropdown'],
            'template' => '<a href="#" data-target="#" data-toggle="dropdown" class="dropdown-toggle apply-shortcut" shortcut_key="shift+alt+c" >Product <b class="caret"></b></a>',
            'items' => [
                    ['label' => 'Product Group', 'url' => ['/product/tbl-product-group/index'], 'active' => ($cntrl == 'tbl-product-group')],
                    ['label' => 'Product', 'url' => ['/product/tbl-product/index'], 'active' => ($cntrl == 'tbl-product')],
                    ['label' => Yii::t('app', 'Product Purchase Rate'), 'url' => ['/product/tbl-product-purchase-rate/index'], 'active' => ($cntrl == 'tbl-product-purchase-rate')],
                    ['label' => 'Product Sale Rate', 'url' => ['/product/tbl-product-rate/index'], 'active' => ($cntrl == 'tbl-product-rate')],
                    ['label' => Yii::t('app', 'Product Sale'), 'url' => ['/payment/tbl-product-sale/index'], 'active' => ($cntrl == 'tbl-product-sale')],
                    ['label' => Yii::t('app', 'DPU Product Demand'), 'url' => ['/product/tbl-dpu-product-demand/index'], 'active' => ($cntrl == 'tbl-dpu-product-demand')],
                    ['label' => Yii::t('app', 'Product Receipt'), 'url' => ['/product/tbl-product-receipt/index'], 'active' => ($cntrl == 'tbl-product-receipt')],
                    ['label' => Yii::t('app', 'Product Requisition'), 'url' => ['/product/tbl-product-requisition/index'], 'active' => ($cntrl == 'tbl-product-requisition')],
                    ['label' => Yii::t('app', 'Product Dispatch With Requisition'), 'url' => ['/product/tbl-product-dispatch/index', 'flag' => 1], 'active' => (($cntrl == 'tbl-product-dispatch' || $cntrl == 'tbl-product-dispatch-transaction') && Yii::$app->getRequest()->getQueryParam('flag') != 0),],
                    ['label' => Yii::t('app', 'Product Dispatch Without Requisition'), 'url' => ['/product/tbl-product-dispatch/index', 'flag' => 0], 'active' => (($cntrl == 'tbl-product-dispatch' || $cntrl == 'tbl-product-dispatch-transaction') && Yii::$app->getRequest()->getQueryParam('flag') == 0),],
            ],
        ],
            [
            'options' => ['class' => 'dropdown'],
            'template' => '<a href="#" data-target="#" data-toggle="dropdown" class="dropdown-toggle apply-shortcut" shortcut_key="shift+alt+c" >Payment <b class="caret"></b></a>',
            'items' => [
                    ['label' => Yii::t('app', 'Payment Cycle'), 'url' => ['/payment/tbl-payment-cycle/index'], 'active' => ($cntrl == 'tbl-dcs-payment-cycle')],
                    ['label' => Yii::t('app', 'Member Payment Restrict'), 'url' => ['/payment/tbl-member-payment-restrict/index'], 'active' => ($cntrl == 'tbl-member-payment-restrict' && $action == 'index')],
                    ['label' => Yii::t('app', 'DCS Wise Billing Config'), 'url' => ['/payment/tbl-vsp-payment-config/index'], 'active' => ($cntrl == 'tbl-vsp-payment-config' && $action == 'index')],
                    ['label' => Yii::t('app', 'Member Payment'), 'url' => ['/payment/tbl-member-payment/index'], 'active' => ($cntrl == 'tbl-member-payment' && $action == 'index')],
                    ['label' => Yii::t('app', 'Vendor Payment'), 'url' => ['/payment/tbl-vsp-payment/index'], 'active' => ($cntrl == 'tbl-vsp-payment' && $action == 'index')],
                    ['label' => Yii::t('app', 'Transporter Payment Process'), 'url' => ['/payment/tbl-transporter-payment/index'], 'active' => ($cntrl == 'tbl-transporter-payment')],
                    ['label' => Yii::t('app', 'Payment Data[Thirumala]'), 'url' => ['/misreports/default/farmer-payment-report'], 'active' => ($cntrl == 'default' && $action == 'farmer-payment-report')],
            //  ['label' => Yii::t('app', 'Disburse Payment'), 'url' => ['/payment/tbl-member-payment/disburse-payment'], 'active' => ($cntrl == 'tbl-member-payment1')],
            ],
        ],
//        ['label' => Yii::t('app', 'Update IMEI'), 'url' => ['/organisation/tbl-dcs/multi-imei-number'], 'active' => ($cntrl == 'tbl-dcs')],
        [
            'options' => ['class' => 'dropdown'],
            'template' => '<a href="#" data-target="#" data-toggle="dropdown" class="dropdown-toggle apply-shortcut" shortcut_key="shift+alt+c" >Staff<b class="caret"></b></a>',
            'items' => [
                    ['label' => Yii::t('app', 'Designation'), 'url' => ['/globalmaster/tbl-designation/index'], 'active' => ($cntrl == 'tbl-designation')],
                    ['label' => Yii::t('app', 'Staff Leave Master'), 'url' => ['/staffmanagement/tbl-staff-leave-master/index'], 'active' => ($cntrl == 'tbl-staff-leave-master')],
                    ['label' => Yii::t('app', 'Staff Salary Heads'), 'url' => ['/globalmaster/tbl-salary-heads/index'], 'active' => ($cntrl == 'tbl-salary-heads')],
                    ['label' => Yii::t('app', 'Staff Member'), 'url' => ['/staffmanagement/tbl-staff-member/index'], 'active' => ($cntrl == 'tbl-staff-member'),],
                    ['label' => Yii::t('app', 'Staff Salary'), 'url' => ['/staffmanagement/tbl-staff-salary/index'], 'active' => ($cntrl == 'tbl-staff-salary'),],
                    ['label' => Yii::t('app', 'Staff Attendance'), 'url' => ['/staffmanagement/tbl-staff-attendance/index'], 'active' => ($cntrl == 'tbl-staff-attendance'),],
                    ['label' => Yii::t('app', 'Staff Add/Deduct.'), 'url' => ['/staffmanagement/tbl-staff-addition-deduction/index'], 'active' => ($cntrl == 'tbl-staff-addition-deduction'),],
                    ['label' => Yii::t('app', 'Salary Processing'), 'url' => ['/staffmanagement/tbl-staff-salary-process/index'], 'active' => ($cntrl == 'tbl-staff-salary-process'),],
            ]
        ],
            [
            'options' => ['class' => 'dropdown'],
            'template' => '<a href="#" data-target="#" data-toggle="dropdown" class="dropdown-toggle apply-shortcut" shortcut_key="shift+alt+c" >Billing<b class="caret"></b></a>',
            'items' => [
                    ['label' => Yii::t('app', 'Formula Master'), 'url' => ['/vsp/tbl-general-formula/index'], 'active' => ($cntrl == 'tbl-general-formula')],
                    ['label' => Yii::t('app', 'Bill Head Master'), 'url' => ['/vsp/tbl-bill-head/index'], 'active' => ($cntrl == 'tbl-bill-head')],
                    ['label' => Yii::t('app', 'Bill Head Transaction'), 'url' => ['/vsp/tbl-bill-head-detail/index'], 'active' => ($cntrl == 'tbl-bill-head-detail')],
                    ['label' => Yii::t('app', 'Head Load'), 'url' => ['/vsp/tbl-head-load/index'], 'active' => ($cntrl == 'tbl-head-load')],
                    ['label' => Yii::t('app', 'Transit Loss'), 'url' => ['/vsp/tbl-bmc-transit-loss/index'], 'active' => ($cntrl == 'tbl-bmc-transit-loss')],
                    ['label' => Yii::t('app', 'VSP Outstanding'), 'url' => ['/payment/tbl-vsp-outstanding/index'], 'active' => ($cntrl == 'tbl-vsp-outstanding')],
            ],
        ],
            [
            'options' => ['class' => 'dropdown'],
            'template' => '<a href="#" data-target="#" data-toggle="dropdown" class="dropdown-toggle apply-shortcut" shortcut_key="shift+alt+c" >Reports <b class="caret"></b></a>',
            'items' => [
                    [
                    'options' => ['class' => 'dropdown-submenu'],
                    'template' => '<a href="javascript:void(0)" class="dropdown-toggle">Master Register <b class="caret"></b></a>',
                    'items' => [
                            ['label' => Yii::t('app', 'DCS Register'), 'url' => ['/misreports/default/dcs-master']],
                            ['label' => Yii::t('app', 'Member Register'), 'url' => ['/misreports/default/member-master']],
                    ]
                ],
                    [
                    'options' => ['class' => 'dropdown-submenu'],
                    'template' => '<a href="javascript:void(0)" class="dropdown-toggle">Mobile Report <b class="caret"></b></a>',
                    'items' => [
                            ['label' => 'M01-' . Yii::t('app', 'Member Collection Passbook'), 'url' => ['/misreports/default/member-collection-passbook']],
                            ['label' => 'M02-' . Yii::t('app', 'Member Collection Day Wise'), 'url' => ['/misreports/default/member-collection-day-wise']],
                            ['label' => 'M03-' . Yii::t('app', 'Member Collection Summary'), 'url' => ['/misreports/default/member-collection-summary']],
                            ['label' => 'M04-' . Yii::t('app', 'Member Collection Payment Cycle Wise'), 'url' => ['/misreports/default/member-collection-paymentcycle-wise']],
                            ['label' => 'M05-' . Yii::t('app', 'Member Collection Month Wise'), 'url' => ['/misreports/default/member-collection-month-wise']],
                            ['label' => 'M06-' . Yii::t('app', 'Member Application Detail'), 'url' => ['/misreports/default/member-mobile-app-detail']],
                    ]
                ],
                    [
                    'options' => ['class' => 'dropdown-submenu'],
                    'template' => '<a href="javascript:void(0)" class="dropdown-toggle">Milk Collection <b class="caret"></b></a>',
                    'items' => [
//                        ['label' => '101-' . Yii::t('app', 'Member Milk Collection Summary'), 'url' => ['/jasperreports/default/member-milk-collection-summary']],
                            ['label' => '101-' . Yii::t('app', 'Member Milk Collection'), 'url' => ['/misreports/default/member-milk-collection']],
//                       ['label' => '102-' . Yii::t('app', 'Consolidated Milk Collection'), 'url' => ['/jasperreports/default/consolidated-milk-collection']],
                        ['label' => '102-' . Yii::t('app', 'Society Wise Milk Collection'), 'url' => ['/misreports/default/dcs-wise-milk-collection']],
//                        ['label' => '103-' . Yii::t('app', 'Shift Report (Name Wise)'), 'url' => ['/jasperreports/default/shift-report-name-wise']],
                        ['label' => '103-' . Yii::t('app', 'Shift Report (Name Wise)'), 'url' => ['/misreports/default/shift-report-name-wise']],
//                        ['label' => '104-' . Yii::t('app', 'Member Milk Collection Register'), 'url' => ['/jasperreports/default/member-milk-collection-register']],
                        ['label' => '104-' . Yii::t('app', 'Member Milk Collection Register'), 'url' => ['/misreports/default/member-milk-collection-register']],
//                        ['label' => '105-' . Yii::t('app', 'Consolited Milk Collection Union Wise – Table'), 'url' => ['/jasperreports/default/consolidated-dcs-milk-collection']],
                        ['label' => '105-' . Yii::t('app', 'Consolited Milk Collection Union Wise – Table'), 'url' => ['/misreports/default/consolidated-union-milk-collection']],
//                        ['label' => '106-' . Yii::t('app', 'Consolited Milk Collection Union Wise – Graph'), 'url' => ['/jasperreports/default/consolidated-union-milk-collection']],
                        ['label' => '107-' . Yii::t('app', 'Society Wise Collection and Dispatch Difference Report'), 'url' => ['/jasperreports/default/dcs-collection-dispatch-difference-report']],
//                        ['label' => '108-' . Yii::t('app', 'Union Wise Collection and Dispatch Difference Report'), 'url' => ['/jasperreports/default/union-collection-dispatch-diff-report']],
                        ['label' => '108-' . Yii::t('app', 'Company Wise Collection vs Dispatch'), 'url' => ['/misreports/default/union-wise-collection-vs-dispatch']],
//                        ['label' => '109-' . Yii::t('app', 'Society Wise Payment Register'), 'url' => ['/jasperreports/default/society-wise-member-register']],
//                        ['label' => '110-' . Yii::t('app', 'Union Wise Payment Register'), 'url' => ['/jasperreports/default/union-wise-member-register']],
//                        ['label' => '111-' . Yii::t('app', 'Member Wise Payment register'), 'url' => ['/jasperreports/default/member-wise-payment-register']],
//                        ['label' => '112-' . Yii::t('app', 'Member Classification Register'), 'url' => ['/jasperreports/default/member-classification-register']],
//                        ['label' => '113-' . Yii::t('app', 'Member Payment Held Up'), 'url' => ['/jasperreports/default/member-payment-heldup']],
//                        ['label' => '114-' . Yii::t('app', 'Consolidated Milk Collection Block Wise'), 'url' => ['/jasperreports/default/block-wise-collection']],
//                        ['label' => '115-' . Yii::t('app', 'Payment Authorization'), 'url' => ['/jasperreports/default/payment-authorization']],
//                        ['label' => '116-' . Yii::t('app', 'Society Wise Collection vs Dispatch - Graph'), 'url' => ['/jasperreports/default/dcs-collection-vs-dispatch-graph']],
                        ['label' => '116-' . Yii::t('app', 'Society Wise Collection vs Dispatch - Graph'), 'url' => ['/misreports/default/dcs-collection-vs-dispatch-graph']],
//                        ['label' => '117-' . Yii::t('app', 'Society Details'), 'url' => ['/jasperreports/default/society-details']],
                        ['label' => '118-' . Yii::t('app', 'MCC Wise Collection Summary'), 'url' => ['/misreports/default/total-milk-collection-date-shift']],
                            ['label' => '119-' . Yii::t('app', 'Collection Data Summary'), 'url' => ['/misreports/default/collection-data-summary']],
                            ['label' => '120-' . Yii::t('app', 'MCC-Shift Collection Count'), 'url' => ['/misreports/default/mcc-shift-cross-tab']],
                            ['label' => '121-' . Yii::t('app', 'CDA Date And Shift Wise'), 'url' => ['/misreports/default/cda-date-and-shift-wise']],
                            ['label' => '122-' . Yii::t('app', 'CDA Date Wise'), 'url' => ['/misreports/default/cda-date-wise']],
                            ['label' => '123-' . Yii::t('app', 'CDA Consolidated'), 'url' => ['/misreports/default/cda-consolidated']],
                            ['label' => '124-' . Yii::t('app', 'BMC Collection Consolidated'), 'url' => ['/misreports/default/bmc-collection-consolidated']],
                    ]
                ],
                    [
                    'options' => ['class' => 'dropdown-submenu'],
                    'template' => '<a href="javascript:void(0)" class="dropdown-toggle">' . Yii::t('app', 'MIS') . '<b class="caret"></b></a>',
                    'items' => [
                            ['label' => '201-' . Yii::t('app', 'Union Collection Report'), 'url' => ['/report/default/union-count']],
                            ['label' => '202-' . Yii::t('app', 'Society Collection Data Report'), 'url' => ['/report/default/list']],
                            ['label' => '203-' . Yii::t('app', 'Society Collection Summary Report'), 'url' => ['/report/default/index']],
//                        ['label' => '204-' . Yii::t('app', 'No Collection Summary Report'), 'url' => ['/report/default/no-collection-society']],
//                        ['label' => '205-' . Yii::t('app', 'No Collection Society Report'), 'url' => ['/report/default/no-collection-shifts']],
//                        ['label' => '206-' . Yii::t('app', 'No Network Summary Report'), 'url' => ['/report/default/dpu-request-summary']],
//                        ['label' => '207-' . Yii::t('app', 'No Network Detail Report'), 'url' => ['/report/default/dpu-request']],
                        ['label' => '208-' . Yii::t('app', 'Society-Shift Collection Completed'), 'url' => ['/report/default/shift-report']],
                            ['label' => '209-' . Yii::t('app', 'Society-Day Crosstab'), 'url' => ['/report/default/daily-report']],
                            ['label' => '210-' . Yii::t('app', 'Union-Day Crosstab'), 'url' => ['/report/default/union-report']],
                            ['label' => '211-' . Yii::t('app', 'Society Shift Report'), 'url' => ['/report/default/dcs-shift-report']],
                            ['label' => '212-' . Yii::t('app', 'Union Shift Report'), 'url' => ['/report/default/union-shift-report']],
                            ['label' => '213-' . Yii::t('app', 'Union Shift Summary Report'), 'url' => ['/report/default/union-shift-summary-report']],
                            ['label' => '214-' . Yii::t('app', 'DPMCU Working Status'), 'url' => ['/report/default/dpmcu-working-status']],
                            ['label' => '215-' . Yii::t('app', 'DPMCU Information'), 'url' => ['/report/default/shift-a-report']],
                            ['label' => '216-' . Yii::t('app', 'BMC Shift Report'), 'url' => ['/misreports/default/bmc-shift-report']],
                            ['label' => '217-' . Yii::t('app', 'BMC Consolidate Report'), 'url' => ['/misreports/default/bmc-consolidate-report']],
//                        ['label' => '218-' . Yii::t('app', 'BMC Summary Report'), 'url' => ['/misreports/default/bmc-summary-report']],
                        ['label' => '219-' . Yii::t('app', 'Society Summary Report'), 'url' => ['/misreports/default/society-summary-report']],
                            ['label' => '220-' . Yii::t('app', 'Date/Shift wise BMC Collection'), 'url' => ['/misreports/default/date-shift-bmc-collection']],
                            ['label' => '221-' . Yii::t('app', 'Shift Wise Auto Manual'), 'url' => ['/misreports/default/shift-wise-auto-manual']],
                        //  ['label' => Yii::t('app', 'Cleaning Not Done'), 'url' => ['/report/default/cleaning-not-done']],
// ['label' => Yii::t('app', 'Calibration Change Report'), 'url' => ['/report/default/calibration-change-report']],
                        ['label' => '222-' . Yii::t('app', 'Rate Applicability Details'), 'url' => ['/misreports/default/rate-applicability-details']],
                            ['label' => '223-' . Yii::t('app', 'Rate Acknowledgement'), 'url' => ['/misreports/default/rate-acknowledgement']],
                            ['label' => '224-' . Yii::t('app', 'AMCS Sync Pending'), 'url' => ['/misreports/default/amcs-sync-pending']],
                    ]
                ],
                    [
                    'options' => ['class' => 'dropdown-submenu'],
                    'template' => '<a href="javascript:void(0)" class="dropdown-toggle">' . Yii::t('app', 'BMC Collection') . '<b class="caret"></b></a>',
                    'items' => [
                        /*                       ['label' => '301-' . Yii::t('app', 'Actual BMC Collection'), 'url' => ['/crystalreports/default/actual-bmc-collection']],
                          ['label' => '302-' . Yii::t('app', 'RMRD Milk Collection'), 'url' => ['/crystalreports/default/rmrd-milk-collection']],
                          ['label' => '303-' . Yii::t('app', 'BMC Summary'), 'url' => ['/crystalreports/default/bmc-summary-report']],
                          ['label' => '304-' . Yii::t('app', 'Milk Type Variation Date Wise'), 'url' => ['/crystalreports/default/variation-milk-type-date-wise']],
                          ['label' => '305-' . Yii::t('app', 'Milk Type Variation Village Wise'), 'url' => ['/crystalreports/default/variation-milk-type-village-wise']],
                          ['label' => '306-' . Yii::t('app', 'Date Wise Variation'), 'url' => ['/crystalreports/default/variation-date-wise']],
                          ['label' => '307-' . Yii::t('app', 'Village Wise Variation'), 'url' => ['/crystalreports/default/variation-village-wise']],
                          ['label' => '308-' . Yii::t('app', 'Percentage Wise Variation'), 'url' => ['/crystalreports/default/variation-percentage-wise']],
                          ['label' => '309-' . Yii::t('app', 'Difference Report'), 'url' => ['/crystalreports/default/difference-report']],
                          ['label' => '310-' . Yii::t('app', 'Date Wise Difference Report'), 'url' => ['/crystalreports/default/difference-report-date-wise']],
                          ['label' => '311-' . Yii::t('app', 'Village Wise Difference Report'), 'url' => ['/crystalreports/default/difference-report-village-wise']],
                          ['label' => '312-' . Yii::t('app', 'BMC Collection'), 'url' => ['/crystalreports/default/bmc-collection']],
                          ['label' => '313-' . Yii::t('app', 'DPU-GPRS Data Reconciliation'), 'url' => ['/crystalreports/default/gprs-data-reconciliation']], */
                            ['label' => '301-' . Yii::t('app', 'Actual BMC Collection'), 'url' => ['/jasperreports/default/actual-bmc-collection']],
//                        ['label' => '302-' . Yii::t('app', 'RMRD Milk Collection'), 'url' => ['/jasperreports/default/rmrd-milk-collection']],
                        ['label' => '303-' . Yii::t('app', 'BMC Summary'), 'url' => ['/jasperreports/default/bmc-summary-report']],
                            ['label' => '304-' . Yii::t('app', 'Milk Type Variation Date Wise'), 'url' => ['/jasperreports/default/variation-milk-type-date-wise']],
                            ['label' => '305-' . Yii::t('app', 'Milk Type Variation Village Wise'), 'url' => ['/jasperreports/default/variation-milk-type-village-wise']],
                            ['label' => '306-' . Yii::t('app', 'Date Wise Variation'), 'url' => ['/jasperreports/default/variation-date-wise']],
//                        ['label' => '307-' . Yii::t('app', 'Village Wise Variation'), 'url' => ['/jasperreports/default/variation-village-wise']],
                        ['label' => '307-' . Yii::t('app', 'Village Wise Variation'), 'url' => ['/misreports/default/variation-village-wise']],
//                        ['label' => '308-' . Yii::t('app', 'Percentage Wise Variation'), 'url' => ['/jasperreports/default/variation-percentage-wise']],
                        ['label' => '308-' . Yii::t('app', 'Percentage Wise Variation'), 'url' => ['/misreports/default/variation-percentage-wise']],
////                        ['label' => '309-' . Yii::t('app', 'Difference Report'), 'url' => ['/jasperreports/default/difference-report']],
//                        ['label' => '310-' . Yii::t('app', 'Date Wise Difference Report'), 'url' => ['/jasperreports/default/difference-report-date-wise']],
//                        ['label' => '311-' . Yii::t('app', 'Village Wise Difference Report'), 'url' => ['/jasperreports/default/difference-report-village-wise']],
                        ['label' => '312-' . Yii::t('app', 'BMC Collection'), 'url' => ['/jasperreports/default/bmc-collection']],
//                        ['label' => '313-' . Yii::t('app', 'DPU-GPRS Data Reconciliation'), 'url' => ['/jasperreports/default/gprs-data-reconciliation']],
                        ['label' => '313-' . Yii::t('app', 'DPU-GPRS Data Reconciliation'), 'url' => ['/misreports/default/gprs-data-reconciliation']],
                    ]
                ],
                    [
                    'options' => ['class' => 'dropdown-submenu'],
                    'template' => '<a href="javascript:void(0)" class="dropdown-toggle">' . Yii::t('app', 'SAP') . '<b class="caret"></b></a>',
                    'items' => [
                            ['label' => '401-' . Yii::t('app', 'Status Report'), 'url' => ['/misreports/default/sap-status-report']],
                            ['label' => '402-' . Yii::t('app', 'Comparision Report'), 'url' => ['/misreports/default/sap-comparision-report']],
                            ['label' => '403-' . Yii::t('app', 'Dispatch vs Receipt Report'), 'url' => ['/misreports/default/dispatch-vs-receipt']],
                            ['label' => '404-' . Yii::t('app', 'SAP Data Export'), 'url' => ['/misreports/default/cpmilk-sap-report']],
                    ]
                ],
                    [
                    'options' => ['class' => 'dropdown-submenu'],
                    'template' => '<a href="javascript:void(0)" class="dropdown-toggle">' . Yii::t('app', 'Cleaning & Calibration') . '<b class="caret"></b></a>',
                    'items' => [
                            ['label' => Yii::t('app', '501 - Analyzer Cleaning Review'), 'url' => ['/misreports/default/analyzer-cleaning-review']],
                            ['label' => Yii::t('app', '502 - Analyzer Cleaning Pending Activity'), 'url' => ['/misreports/default/analyzer-cleaning-pending-activity']],
                            ['label' => Yii::t('app', '503 - Analyzer PCB Replacement'), 'url' => ['/misreports/default/analyzer-pcb-replacement']],
                            ['label' => Yii::t('app', '504 - Cleaning Flag Report'), 'url' => ['/misreports/default/cleaning-flag']],
                            ['label' => Yii::t('app', '505 - Eko Milk Calibration'), 'url' => ['/misreports/default/eko-milk-calibration']],
                            ['label' => Yii::t('app', '506 - Calibration Flag'), 'url' => ['/misreports/default/calibration-flag']],
                            ['label' => Yii::t('app', '507 - Cleaning Flag Bmc'), 'url' => ['/misreports/default/cleaning-flag-bmc']],
                    ]
                ],
                    [
                    'options' => ['class' => 'dropdown-submenu'],
                    'template' => '<a href="javascript:void(0)" class="dropdown-toggle">' . Yii::t('app', 'Payment') . '<b class="caret"></b></a>',
                    'items' => [
                            ['label' => '601-' . Yii::t('app', 'BMC Payment'), 'url' => ['/jasperreports/default/bmc-payment']],
                            ['label' => '602-' . Yii::t('app', 'Vendor Payment'), 'url' => ['/misreports/default/vendor-payment']],
                            ['label' => '603-' . Yii::t('app', 'Member Payment'), 'url' => ['/misreports/default/member-payment']],
                            ['label' => '604-' . Yii::t('app', 'Vendor Milk Payment'), 'url' => ['/jasperreports/default/vendor-milk-payment']],
                            ['label' => '605-' . Yii::t('app', 'Member Milk Payment'), 'url' => ['/jasperreports/default/member-milk-payment']],
                            ['label' => '606-' . Yii::t('app', 'Vendor Bank Payment'), 'url' => ['/misreports/default/vendor-bank-payment']],
                            ['label' => '607-' . Yii::t('app', 'Member Bank Payment'), 'url' => ['/misreports/default/member-bank-payment']],
                            ['label' => '608-' . Yii::t('app', 'Member Outstanding Detail'), 'url' => ['/misreports/default/member-outstanding-detail']],
                            ['label' => '609-' . Yii::t('app', 'Vendor Milk Bill'), 'url' => ['/jasperreports/default/vendor-milk-bill']],
                            ['label' => '610-' . Yii::t('app', 'Member Milk Bill'), 'url' => ['/jasperreports/default/member-milk-bill']],
                    ]
                ],
                    [
                    'options' => ['class' => 'dropdown-submenu'],
                    'template' => '<a href="javascript:void(0)" class="dropdown-toggle">' . Yii::t('app', 'Staff Reports') . '<b class="caret"></b></a>',
                    'items' => [
                            ['label' => '701-' . Yii::t('app', 'Staff Salary'), 'url' => ['/jasperreports/default/staff-salary']],
                    ]
                ],
                    ['label' => '', 'url' => 'javascript:void(0)', 'visible' => true],
            ],
        ],
            [
            'options' => ['class' => 'dropdown'],
            'template' => '<a href="#" data-target="#" data-toggle="dropdown" class="dropdown-toggle apply-shortcut" shortcut_key="shift+alt+c" >Reports - New <b class="caret"></b></a>',
            'items' => [
                    [
                    'options' => ['class' => 'dropdown-submenu'],
                    'template' => '<a href="javascript:void(0)" class="dropdown-toggle">Master Register <b class="caret"></b></a>',
                    'items' => [
                            ['label' => Yii::t('app', 'DCS Register'), 'url' => ['/misreports/reports/dcs-master']],
                            ['label' => Yii::t('app', 'Member Register'), 'url' => ['/misreports/reports/member-master']],
                            ['label' => Yii::t('app', 'Customer Master Register'), 'url' => ['/misreports/reports/customer-master']],
                            ['label' => Yii::t('app', 'Milk Collection Register'), 'url' => ['/misreports/reports/milk-collection-register']],
                            ['label' => Yii::t('app', 'BMC Collection Register'), 'url' => ['/misreports/reports/bmc-collection-register']],
                            ['label' => Yii::t('app', 'Transporter Master Register'), 'url' => ['/misreports/reports/transporter-master']],
                            ['label' => Yii::t('app', 'Vehicle Master Register'), 'url' => ['/misreports/reports/vehicle-master']],
                            ['label' => Yii::t('app', 'Rate Master Register'), 'url' => ['/misreports/reports/rate-master-register']],
                            ['label' => Yii::t('app', 'Product Sale Rate Register'), 'url' => ['/misreports/reports/product-sale-rate-master-register']],
                    ]
                ],
                    [
                    'options' => ['class' => 'dropdown-submenu'],
                    'template' => '<a href="javascript:void(0)" class="dropdown-toggle">Milk Collection <b class="caret"></b></a>',
                    'items' => [
                            [
                            'options' => ['class' => 'dropdown-submenu'],
                            'template' => '<a href="javascript:void(0)" class="dropdown-toggle">Member Collection<b class="caret"></b></a>',
                            'items' => [
                                    ['label' => '101 -' . Yii::t('app', 'Member Collection Detail'), 'url' => ['/misreports/reports/member-daily-collection']],
                                    ['label' => '102 -' . Yii::t('app', 'Society Collection Detail'), 'url' => ['/misreports/reports/dcs-coll-date-shift-summary']],
                                    ['label' => '103 -' . Yii::t('app', 'Member Collection Shift Report'), 'url' => ['/misreports/reports/member-collection-shift-report']],
                                    ['label' => '104 -' . Yii::t('app', 'Member Collection Payment Cycle Wise'), 'url' => ['/misreports/reports/member-collection-payment-cycle-wise']],
                                    ['label' => '105 -' . Yii::t('app', 'Member Wise Monthly Collection'), 'url' => ['/misreports/reports/member-wise-monthly-collection']],
                                    ['label' => '106 -' . Yii::t('app', 'Member Manual Milk Entry'), 'url' => ['/misreports/reports/manual-milk-entry-member-date-shift-wise']],
                                    ['label' => '107 -' . Yii::t('app', 'Society Manual Milk Entry'), 'url' => ['/misreports/reports/manual-milk-entry-society-date-shift-wise']],
                                    ['label' => '108 -' . Yii::t('app', 'Milk Collection Data'), 'url' => ['/misreports/reports/milk-collection-data']],
                            ]
                        ],
                            [
                            'options' => ['class' => 'dropdown-submenu'],
                            'template' => '<a href="javascript:void(0)" class="dropdown-toggle">BMC Collection<b class="caret"></b></a>',
                            'items' => [
                                    ['label' => '201 -' . Yii::t('app', 'BMC Collection Shift Report'), 'url' => ['/misreports/reports/bmc-collection-shift-report']],
                                    ['label' => '202 -' . Yii::t('app', 'BMC Collection Detail'), 'url' => ['/misreports/reports/bmc-coll-date-shift-wise-summary']],
                                    ['label' => '203 -' . Yii::t('app', 'Company Collection Detail'), 'url' => ['/misreports/reports/union-coll-date-shift-wise-summary']],
                                    ['label' => '204 -' . Yii::t('app', 'Company Wise Collection Vs Dispatch'), 'url' => ['/misreports/reports/union-wise-coll-vs-dispatch']],
                                    ['label' => '205 -' . Yii::t('app', 'Company Wise Collection Vs Receipt'), 'url' => ['/misreports/reports/union-wise-coll-vs-recipt']],
                                    ['label' => '206 -' . Yii::t('app', 'Company Wise Dispatch Vs Receipt'), 'url' => ['/misreports/reports/union-wise-dispatch-vs-recipt']],
                                    ['label' => '207 -' . Yii::t('app', 'Society Wise CDA'), 'url' => ['/misreports/reports/society-wise-cda'], 'visible' => $eiplCode == 'GYAN' ? FALSE : TRUE],
                                    ['label' => '207 -' . Yii::t('app', 'Society Wise CDA'), 'url' => ['/misreports/reports/society-wise-cda-two'], 'visible' => $eiplCode == 'GYAN' ? TRUE : FALSE],
                                    ['label' => '208 -' . Yii::t('app', 'BMC Autmation Report'), 'url' => ['/misreports/reports/bmc-automation-report']],
                                    ['label' => '209-' . Yii::t('app', 'Route Wise Collection'), 'url' => ['/misreports/reports/route-wise-collection']],
                                    ['label' => '210-' . Yii::t('app', 'Route Wise Collection Summary'), 'url' => ['/misreports/reports/route-wise-collection-summary']],
                                    ['label' => '211-' . Yii::t('app', 'Vendor Wise Collection Summary'), 'url' => ['/misreports/reports/vendor-wise-collection-summary']],
                                    ['label' => '212 -' . Yii::t('app', 'BMC Collection Data'), 'url' => ['/misreports/reports/bmc-collection-data']],
                            ]
                        ],
                            ['label' => '', 'url' => 'javascript:void(0)', 'visible' => true],
                    ]
                ],
                    [
                    'options' => ['class' => 'dropdown-submenu'],
                    'template' => '<a href="javascript:void(0)" class="dropdown-toggle">' . Yii::t('app', 'Indent') . '<b class="caret"></b></a>',
                    'items' => [
                            ['label' => '301 -' . Yii::t('app', 'Member Indent Detail'), 'url' => ['/misreports/reports/member-wise-summary']],
                            ['label' => '302 -' . Yii::t('app', 'Vendor Indent Detail'), 'url' => ['/misreports/reports/vendor-wise-summary']],
                            ['label' => '303 -' . Yii::t('app', 'BMC Indent Detail'), 'url' => ['/misreports/reports/bmc-wise-summary']],
                            ['label' => '304 -' . Yii::t('app', 'Company Indent Detail'), 'url' => ['/misreports/reports/union-wise-summary']],
                    ]
                ],
                    [
                    'options' => ['class' => 'dropdown-submenu'],
                    'template' => '<a href="javascript:void(0)" class="dropdown-toggle">' . Yii::t('app', 'SAP') . '<b class="caret"></b></a>',
                    'items' => [
                            ['label' => '401-' . Yii::t('app', 'Status Report'), 'url' => ['/misreports/reports/sap-status-report']],
                            ['label' => '402-' . Yii::t('app', 'Comparision Report'), 'url' => ['/misreports/reports/sap-comparision-report']],
                            ['label' => '403-' . Yii::t('app', 'Dispatch vs Receipt Report'), 'url' => ['/misreports/reports/dispatch-vs-receipt']],
                            ['label' => '404-' . Yii::t('app', 'SAP Data Export'), 'url' => ['/misreports/reports/cpmilk-sap-report']],
                    ]
                ],
                    [
                    'options' => ['class' => 'dropdown-submenu'],
                    'template' => '<a href="javascript:void(0)" class="dropdown-toggle">' . Yii::t('app', 'Billing') . '<b class="caret"></b></a>',
                    'items' => [
                            ['label' => '501 -' . Yii::t('app', 'Member Wise No. Of Payment Cycle'), 'url' => ['/misreports/reports/member-wise-no-of-payment-cycle']],
                            ['label' => '502 -' . Yii::t('app', 'BMC Member Payment'), 'url' => ['/misreports/reports/bmc-wise-payment-cycle-wise']],
                            ['label' => '503 -' . Yii::t('app', 'Company Member Payment'), 'url' => ['/misreports/reports/company-wise-payment-cycle-wise']],
                            ['label' => '504 -' . Yii::t('app', 'DCS Wise From Date To Date Summary'), 'url' => ['/misreports/reports/dcs-wise-from-date-to-date-summary']],
                            ['label' => '505 -' . Yii::t('app', 'Vendor Payment Consolidated'), 'url' => ['/misreports/reports/vendor-payment-consolidated']],
                            ['label' => '506 -' . Yii::t('app', 'BMC Vendor Payment'), 'url' => ['/misreports/reports/vendor-payment-cycle-wise-bmc-wise']],
                            ['label' => '507 -' . Yii::t('app', 'Company Vendor Payment'), 'url' => ['/misreports/reports/vendor-payment-cycle-wise-union-wise']],
                            ['label' => '508 -' . Yii::t('app', 'Company Total Payment'), 'url' => ['/misreports/reports/total-payment-company-wise-payment-cycle-wise']],
                    ]
                ],
                    [
                    'options' => ['class' => 'dropdown-submenu'],
                    'template' => '<a href="javascript:void(0)" class="dropdown-toggle">' . Yii::t('app', 'Payment') . '<b class="caret"></b></a>',
                    'items' => [
                            ['label' => '601-' . Yii::t('app', 'BMC Payment'), 'url' => ['/jasperreports/default/bmc-payment']],
                            ['label' => '602-' . Yii::t('app', 'Vendor Payment'), 'url' => ['/misreports/reports/vendor-payment']],
                            ['label' => '603-' . Yii::t('app', 'Member Payment'), 'url' => ['/misreports/reports/member-payment']],
                            ['label' => '604-' . Yii::t('app', 'Vendor Milk Payment'), 'url' => ['/jasperreports/default/vendor-milk-payment']],
                            ['label' => '605-' . Yii::t('app', 'Member Milk Payment'), 'url' => ['/jasperreports/default/member-milk-payment']],
                            ['label' => '606-' . Yii::t('app', 'Vendor Bank Payment'), 'url' => ['/misreports/reports/vendor-bank-payment']],
                            ['label' => '607-' . Yii::t('app', 'Member Bank Payment'), 'url' => ['/misreports/reports/member-bank-payment']],
                            ['label' => '608-' . Yii::t('app', 'Member Outstanding Detail'), 'url' => ['/misreports/reports/member-outstanding-detail']],
                            ['label' => '609-' . Yii::t('app', 'Vendor Milk Bill'), 'url' => ['/jasperreports/default/vendor-milk-bill']],
                            ['label' => '610-' . Yii::t('app', 'Member Milk Bill'), 'url' => ['/jasperreports/default/member-milk-bill']],
                            ['label' => '611-' . Yii::t('app', 'Member Payment(Drafted)'), 'url' => ['/misreports/reports/member-payment-drafted']],
                            ['label' => '612-' . Yii::t('app', 'Vendor Bill'), 'url' => ['/jasperreports/default/vendor-bill']],
                            ['label' => '613-' . Yii::t('app', 'Payment Abstract'), 'url' => ['/misreports/reports/payment-abstract']],
                    ]
                ],
                    [
                    'options' => ['class' => 'dropdown-submenu'],
                    'template' => '<a href="javascript:void(0)" class="dropdown-toggle">' . Yii::t('app', 'Staff Reports') . '<b class="caret"></b></a>',
                    'items' => [
                            ['label' => '701-' . Yii::t('app', 'Staff Salary'), 'url' => ['/jasperreports/default/staff-salary']],
                    ]
                ],
                    [
                    'options' => ['class' => 'dropdown-submenu'],
                    'template' => '<a href="javascript:void(0)" class="dropdown-toggle">' . Yii::t('app', 'Cleaning & Calibration') . '<b class="caret"></b></a>',
                    'items' => [
                            ['label' => Yii::t('app', '801 - Analyzer Cleaning Review'), 'url' => ['/misreports/reports/analyzer-cleaning-review']],
                            ['label' => Yii::t('app', '802 - Analyzer Cleaning Pending Activity'), 'url' => ['/misreports/reports/analyzer-cleaning-pending-activity']],
                            ['label' => Yii::t('app', '803 - Analyzer PCB Replacement'), 'url' => ['/misreports/reports/analyzer-pcb-replacement']],
                            ['label' => Yii::t('app', '804 - Cleaning Flag Report'), 'url' => ['/misreports/reports/cleaning-flag']],
                            ['label' => Yii::t('app', '805 - Eko Milk Calibration'), 'url' => ['/misreports/reports/eko-milk-calibration']],
                            ['label' => Yii::t('app', '806 - Calibration Flag'), 'url' => ['/misreports/reports/calibration-flag']],
                            ['label' => Yii::t('app', '807 - Cleaning Flag Bmc'), 'url' => ['/misreports/reports/cleaning-flag-bmc']],
                    ]
                ],
                    [
                    'options' => ['class' => 'dropdown-submenu'],
                    'template' => '<a href="javascript:void(0)" class="dropdown-toggle">' . Yii::t('app', 'Other MIS') . '<b class="caret"></b></a>',
                    'items' => [
                            ['label' => Yii::t('app', 'All Reports - List'), 'url' => ['/dynamicreport/default/index'], 'active' => ($cntrl == 'default' && $action == 'index')],
                            ['label' => '901-' . Yii::t('app', 'Rate Applicability Details'), 'url' => ['/misreports/reports/rate-applicability-details']],
                            ['label' => '902-' . Yii::t('app', 'Rate Acknowledgement'), 'url' => ['/misreports/reports/rate-acknowledgement']],
                            ['label' => '903-' . Yii::t('app', 'AMCS Sync Pending'), 'url' => ['/misreports/reports/amcs-sync-pending']],
                            ['label' => '904-' . Yii::t('app', 'Location Wise Asset Summary'), 'url' => ['/misreports/reports/location-wise-asset-summary']],
                            ['label' => '905-' . Yii::t('app', 'Location Wise Asset Detail'), 'url' => ['/misreports/reports/location-wise-asset-detail']],
                            ['label' => '906-' . Yii::t('app', 'Location Wise Asset Movement'), 'url' => ['/misreports/reports/location-wise-asset-movement']],
                            ['label' => '907-' . Yii::t('app', 'Society Collection Data'), 'url' => ['/misreports/reports/society-collection-data']],
                            ['label' => '908-' . Yii::t('app', 'DPU-GPRS Data Reconciliation'), 'url' => ['/misreports/reports/gprs-data-reconciliation']],
                            ['label' => '909-' . Yii::t('app', 'Member Data'), 'url' => ['/misreports/reports/member-data']],
                            ['label' => '910-' . Yii::t('app', 'Member Reception Status'), 'url' => ['/misreports/reports/member-reception-status']],
                            ['label' => '911-' . Yii::t('app', 'Shift Wise Auto Manual'), 'url' => ['/misreports/reports/shift-wise-auto-manual']],
                            ['label' => '912-'. Yii::t('app', 'Monthly Comparision Report'), 'url' => ['/misreports/reports/milk-and-bmc-collection-monthly-comparision']],
                    ]
                ],
                    ['label' => '', 'url' => 'javascript:void(0)', 'visible' => true],
            ],
        ],
            [
            'options' => ['class' => 'dropdown'],
            'template' => '<a href="#" data-target="#" data-toggle="dropdown" class="dropdown-toggle apply-shortcut" shortcut_key="shift+alt+c" >System <b class="caret"></b></a>',
            'items' => [
                    ['label' => '', 'url' => 'javascript:void(0)', 'visible' => true],
                    [
                    'options' => ['class' => 'dropdown-submenu mtp10'],
                    'template' => '<a href="javascript:void(0)" class="dropdown-toggle">Authorization <b class="caret"></b></a>',
                    'items' => [
                            ['label' => 'User', 'url' => ['/user-management/user/index'], 'active' => ($cntrl == 'user'),],
                            ['label' => 'Role', 'url' => ['/user-management/role/index'], 'active' => ($cntrl == 'role'),],
//                ['label' => 'Set Originating Location', 'url' => ['/user-management/permission/set-originate-action'], 'active' => ($cntrl == 'permission'),],
                        ['label' => 'Permission', 'url' => ['/user-management/permission/index'], 'active' => ($cntrl == 'permission'),],
                            ['label' => 'Group', 'url' => ['/user-management/auth-item-group/index'], 'active' => ($cntrl == 'auth-item-group'),],
                            ['label' => 'Mobile Dashboard Permission', 'url' => ['/usermanagement/tbl-eipl-app-widget-mapping/app-widget-mapping'], 'active' => ($cntrl == 'tbl-eipl-app-widget-mapping'),],
                            ['label' => 'Mobile Menu Permission', 'url' => ['/usermanagement/tbl-eipl-app-menu-actions/app-menu-mapping'], 'active' => ($cntrl == 'tbl-eipl-app-menu-actions'),],
                    ],
                ],
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
                    'template' => '<a href="javascript:void(0)" class="dropdown-toggle">Asset <b class="caret"></b></a>',
                    'items' => [
                            ['label' => Yii::t('app', 'Asset Group'), 'url' => ['/assetmanagement/tbl-asset-group/index'], 'active' => ($cntrl == 'tbl-asset-group')],
                            ['label' => Yii::t('app', 'Asset Master'), 'url' => ['/assetmanagement/tbl-asset-master/index'], 'active' => ($cntrl == 'tbl-asset-master')],
                            ['label' => Yii::t('app', 'Store Location'), 'url' => ['/assetmanagement/tbl-store-location/index'], 'active' => ($cntrl == 'tbl-store-location')],
                            ['label' => Yii::t('app', 'Asset SAP Code'), 'url' => ['/assetmanagement/tbl-asset-set/index'], 'active' => ($cntrl == 'tbl-asset-set/index')],
                            ['label' => Yii::t('app', 'Asset Detail'), 'url' => ['/assetmanagement/tbl-asset-detail/index'], 'active' => ($cntrl == 'tbl-asset-detail/index')],
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
                            ['label' => Yii::t('app', 'Department'), 'url' => ['/general/tbl-department/index'], 'active' => ($cntrl == 'tbl-department')],
                            ['label' => Yii::t('app', 'Backend Data'), 'url' => ['/general/default/backend-data'], 'active' => ($cntrl == 'backend-data')],
                    ]
                ],
                    ['label' => Yii::t('app', 'Bank'), 'url' => ['/organisation/tbl-banks/index'], 'active' => ($cntrl == 'tbl-banks')],
                    ['label' => Yii::t('app', 'Branch'), 'url' => ['/organisation/tbl-branch/index'], 'active' => ($cntrl == 'tbl-branch')],
                    [
                    'options' => ['class' => 'dropdown-submenu'],
                    'template' => '<a href="javascript:void(0)" class="dropdown-toggle">Utility <b class="caret"></b></a>',
                    'items' => [
                            [
                            'options' => ['class' => 'dropdown-submenu'],
                            'template' => '<a href="javascript:void(0)" class="dropdown-toggle">Manual Alert<b class="caret"></b></a>',
                            'items' => [
                                    ['label' => Yii::t('app', 'RMRD Collection (VSP)'), 'url' => ['/sms/manual-notification/rmrd-collection-vsp']],
                            ]
                        ],
                            ['label' => Yii::t('app', 'Collection Pendrive File'), 'url' => ['/misreports/reports/collection-pendrive-file']],
                            ['label' => Yii::t('app', 'FAT/SNF Threshold'), 'url' => ['/general/tbl-fat-snf-threshold/index'], 'active' => ($cntrl == 'tbl-fat-snf-threshold')],
                            ['label' => Yii::t('app', 'Shift Time'), 'url' => ['/general/tbl-shift-time/index'], 'active' => ($cntrl == 'tbl-shift-time')],
                            ['label' => Yii::t('app', 'Notification'), 'url' => ['/notification/tbl-notifications/index'], 'active' => ($cntrl == 'tbl-notifications')],
                            ['label' => Yii::t('app', 'Complaint'), 'url' => ['/complaint/tbl-complaint/index'], 'active' => ($cntrl == 'tbl-complaint')],
                            ['label' => Yii::t('app', 'General Configuration'), 'url' => ['/setting/tbl-general-config/index'], 'active' => ($cntrl == 'tbl-general-config')],
//                        ['label' => Yii::t('app', 'Custom Import (Create)'), 'url' => ['/customimport/default/create-by-import'], 'active' => ($cntrl == 'customimport-create')],
//                        ['label' => Yii::t('app', 'Custom Import (Update)'), 'url' => ['/customimport/default/update-by-import'], 'active' => ($cntrl == 'customimport-update')],
                        ['label' => Yii::t('app', 'DPU Incentive'), 'url' => ['/general/tbl-dpu-incentive-master/index'], 'active' => ($cntrl == 'tbl-dpu-incentive-master')],
                            ['label' => Yii::t('app', 'Escalation'), 'url' => ['/email/tbl-email-rule-master/index']],
                            ['label' => Yii::t('app', 'Union Credit'), 'url' => ['/payment/tbl-union-credit-limit/index']],
                            ['label' => Yii::t('app', 'DPU Passwords'), 'url' => ['/setting/tbl-dpu-passwords/create'], 'active' => ($cntrl == 'tbl-dpu-passwords')],
                            ['label' => Yii::t('app', 'SAP Data Export'), 'url' => ['/misreports/default/sap-report'], 'active' => ($cntrl == 'default' && $action == 'sap-report')],
//                        ['label' => 'Member Credit', 'url' => ['/payment/tbl-member-credit-limit/index']],
                        ['label' => Yii::t('app', 'Company Configuration '), 'url' => ['/configuration/tbl-milk-collection-config/tabs']],
                            ['label' => Yii::t('app', 'SAP Data'), 'url' => ['/vendorapi/tbl-vendor-api-data/index'], 'active' => ($cntrl == 'tbl-vendor-api-data')],
                            ['label' => Yii::t('app', 'Installation Identity'), 'url' => ['/installation/tbl-android-installation/index'], 'active' => ($cntrl == 'tbl-android-installation')],
                            ['label' => Yii::t('app', 'Pendrive Sync'), 'url' => ['/syncutility/pendrive-sync/index'], 'active' => ($cntrl == 'pendrive-sync')],
                            ['label' => Yii::t('app', 'Prevent BMC Collection Data'), 'url' => ['/vendorapi/tbl-prevent-collection-data/index'], 'active' => ($cntrl == 'tbl-prevent-collection-data')],
                    ]
                ],
                    [
                    'options' => ['class' => 'dropdown-submenu'],
                    'template' => '<a href="javascript:void(0)" class="dropdown-toggle">DPU Log <b class="caret"></b></a>',
                    'items' => [
                            ['label' => Yii::t('app', 'Dpu Calibration'), 'url' => ['/tbl-dpu-calibration/index']],
                            ['label' => Yii::t('app', 'Dpu Cleaning'), 'url' => ['/tbl-cleaning-dpu/index']],
                            ['label' => 'Member Acknowledgement', 'url' => ['/dcsoperation/tbl-member-download/index']],
                            ['label' => 'Rate Acknowledgement', 'url' => ['/dcsoperation/tbl-purchase-rate-applicability/index']],
                            ['label' => Yii::t('app', 'Master Export'), 'url' => ['/eipldpu/pendrive-export/index']],
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
                            ['label' => 'BIPL Files Process', 'url' => ['/bkgprocess/tbl-ftp-txn-log/list']],
                            ['label' => 'EIPL Files Process - New', 'url' => ['/eipldpu/pendrive-import/create']],
                            ['label' => 'AMCS Files Process', 'url' => ['/syncutility/pendrive-import/create']],
                            ['label' => 'Import Files Detail', 'url' => ['/import/tbl-import-file-log/index']],
                            ['label' => 'Pendrive Files Detail', 'url' => ['/syncutility/pendrive-import/index']],
                            ['label' => 'FTP File Upload', 'url' => ['/bkgprocess/ftp-generate/ftp-milk-collection']],
                    ]
                ],
            ],
        ],
            [
            'options' => ['class' => 'dropdown user'],
            'template' => '<a href="#" data-target="#" data-toggle="dropdown" class="dropdown-toggle apply-shortcut" shortcut_key="shift+alt+c" ><i class="fa fa-user"></i> <b class="caret"></b></a>',
            'items' => [
                    ['label' => (Yii::$app->session->get('UserName') != NULL && isset(explode('#', Yii::$app->session->get('UserName'))[1])) ? "<span class='user'><b>" . Yii::t('app', 'User: ') . "</b>" . explode('#', Yii::$app->session->get('UserName'))[1] . "</span>" : ''],
                    ['label' => "<span class='user'><b>" . Yii::t('app', 'Org.Type: ') . "</b>" . Yii::t('app', Yii::$app->session->get('organizations_type')) . "</span>"],
                    ['label' => 'Change password', 'url' => ['/user-management/auth/change-own-password']],
                    ['label' => 'Logout', 'url' => $logout_url],
            ],
        ],
    ],
]);
?>