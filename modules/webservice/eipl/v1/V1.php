<?php

namespace app\modules\webservice\eipl\v1;

/**
 * v1 module definition class
 */
class V1 extends \yii\base\Module {

    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'app\modules\webservice\eipl\v1\controllers';

    /**
     * @inheritdoc
     */
    public function init() {
        parent::init();
    }

    public static function getLabels($l) {
        $label = V1::ServiceArray();
        return isset($label[$l]) ? $label[$l] : NULL;
    }

    public static function ServiceArray() {
        $label = [
            'union/master' => [
                'param' => 'select_param:[union_code],[union_name],[has_bmc]#organization_type#organization_code#table:tbl_unions',
                'sp' => 'sp_app_eipl_v1_master_data',
            ],
            'plant/master' => [
//                'param' => 'select_param:[union_code],[plant_code],[name] as plant_name#organization_type#organization_code#table:tbl_plant#condition:parent_id is null',
                'param' => 'select_param:[union_code],[plant_code],[name] as plant_name#organization_type#organization_code#table:tbl_plant',
                'sp' => 'sp_app_eipl_v1_master_data',
            ],
            'mcc/master' => [
                'param' => 'select_param:[plant_code],[mcc_plant_code],[name] as mcc_plant_name#organization_type#organization_code#table:tbl_mcc_plant',
                'sp' => 'sp_app_eipl_v1_master_data',
            ],
            'bmc/master' => [
                'param' => 'select_param:[mcc_code] as mcc_plant_code,[bmc_code],[bmc_name]#organization_type#organization_code#table:tbl_dcs_subcenter_bmc_info',
                'sp' => 'sp_app_eipl_v1_master_data',
            ],
            'dcs/master' => [
                'param' => 'select_param:[bmc_code],[dcs_code],[dcs_name]#organization_type#organization_code#table:tbl_dcs',
                'sp' => 'sp_app_eipl_v1_master_data',
            ],
            'member/master' => [
                'param' => 'select_param:[dcs_code],[member_code],[member_name]#organization_type#organization_code#table:tbl_member',
                'sp' => 'sp_app_eipl_v1_master_data',
            ],
            'route/master' => [
                'param' => 'select_param:[route_code],[route_name]#organization_type#organization_code#table:tbl_route_mapping',
                'sp' => 'sp_app_eipl_v1_master_data',
            ],
            'asset/master' => [
                'param' => 'select_param:[asset_code],[asset_name]#organization_type#organization_code#table:tbl_asset_master',
                'sp' => 'sp_app_eipl_v1_master_data',
            ],
            'product/master' => [
                'param' => 'select_param:[product_code],[product_name]#organization_type#organization_code#table:tbl_product#condition:is_amount_only=0',
                'sp' => 'sp_app_eipl_v1_master_data',
            ],
            'productcomp/master' => [
                'param' => 'select_param:[cmpl_product_code],[cmpl_product_name]#organization_type#organization_code#table:tbl_complain_product',
                'sp' => 'sp_app_eipl_v1_master_data',
            ],
            'problemcomp/master' => [
                'param' => 'select_param:[Id],[cmpl_problem_name]#organization_type#organization_code#table:tbl_complain_problem#condition:#depend_key:cmpl_product_code#cmpl_product_code',
                'sp' => 'sp_app_eipl_v1_master_data',
            ],
            'userorg/master' => [
                'param' => 'organization_type#organization_code',
                'sp' => 'sp_app_eipl_v1_user_org_data',
            ],
            'productsale/creditlimit' => [
                'param' => 'member_code#sale_date',
                'sp' => 'sp_app_eipl_v1_check_credit_limit',
            ],
            'product/ratelist' => [
                'param' => 'mcc_plant_code#product_code#sale_date',
                'sp' => 'sp_app_eipl_v1_product_rate_list',
            ],
            'complaint' => [
                'main_table' => 'TblComplaint',
                'save_child' => true,
            ],
            'product-sale' => [
                'main_table' => 'TblProductSale',
                'save_child' => true,
            ],
            'dashboard/societycollection' => [
                'param' => 'from_datetime#to_datetime#union#plant#mcc#bmc#dcs',
                'sp' => 'sp_app_eipl_v1_widget_society_collection',
            ],
            'dashboard/memberdiff' => [
                'param' => 'from_datetime#to_datetime#union#plant#mcc#bmc#dcs',
                'sp' => 'sp_app_eipl_v1_widget_member_diff',
            ],
            'dashboard/bmccollection' => [
                'param' => 'from_datetime#to_datetime#union#plant#mcc#bmc#dcs',
                'sp' => 'sp_app_eipl_v1_widget_bmc_collection',
            ],
            'dashboard/societydiff' => [
                'param' => 'from_datetime#to_datetime#union#plant#mcc#bmc#dcs',
                'sp' => 'sp_app_eipl_v1_widget_society_diff',
            ],
            'dashboard/societycomparison' => [
                'param' => 'union#plant#mcc#bmc#dcs',
                'sp' => 'sp_app_eipl_v1_widget_society_comparison',
            ],
            'dashboard/bmccomparison' => [
                'param' => 'union#plant#mcc#bmc#dcs',
                'sp' => 'sp_app_eipl_v1_widget_bmc_comparison',
            ],
            'dashboard/societyweeklycollection' => [
                'param' => 'union#plant#mcc#bmc#dcs',
                'sp' => 'sp_app_eipl_v1_widget_society_weekly_collection',
            ],
            'dashboard/bmcweeklycollection' => [
                'param' => 'union#plant#mcc#bmc#dcs',
                'sp' => 'sp_app_eipl_v1_widget_bmc_weekly_collection',
            ],
            'dashboard/homedata' => [
                'param' => 'from_datetime#to_datetime#union#plant#mcc#bmc#dcs',
                'sp' => 'sp_app_eipl_v1_homedata',
            ],
            'dashboard/bmchomedata' => [
                'param' => 'from_datetime#to_datetime#union#plant#mcc#bmc#dcs',
                'sp' => 'sp_app_eipl_v1_bmchomedata',
            ],
            'complaint/list' => [
                'param' => 'from_datetime#to_datetime#union#plant#mcc#bmc#dcs',
                'sp' => 'sp_app_eipl_v1_complaint_list',
            ],
            'product-sale/list' => [
                'param' => 'from_datetime#to_datetime#union#plant#mcc#bmc#dcs',
                'sp' => 'sp_app_eipl_v1_product_sale_list',
            ],
            'product-sale/detail' => [
                'param' => 'product_sale_code',
                'sp' => 'sp_app_eipl_v1_product_sale_detail',
            ],
            'complaint/activity' => [
                'param' => 'complaint_code',
                'sp' => 'sp_app_eipl_v1_complaint_activity',
            ],
            'complaint/asset' => [
                'param' => 'dcs',
                'sp' => 'sp_app_eipl_v1_complaint_asset',
            ],
            'delivery-challan/list' => [
                'param' => 'from_datetime#to_datetime#union#plant#mcc#bmc',
                'sp' => 'sp_app_eipl_v1_delivery_challan_list',
            ],
            'delivery-challan/view' => [
                'param' => 'challanno',
                'sp' => 'sp_app_eipl_v1_delivery_challan_view',
            ],
            'delivery-challan' => [
                'main_table' => 'DeliveryChallan',
                'save_child' => false,
                'replace_array_key' => ['challanno' => 'Challanno', 'qty' => 'Qty', 'to_type' => 'Type', 'to_place' => 'ToPlace', 'gross_weight' => 'GrossWight', 'tare_weight' => 'TareWight']
            ],
            'plant/all-plant-list' => [
                'param' => 'select_param:[union_code],[plant_code],[name] as plant_name#organization_type#organization_code#table:tbl_plant',
                'sp' => 'sp_app_eipl_v1_master_data',
            ],
            'bmc/collection-dcs' => [
                'param' => 'from_datetime#to_datetime#union#plant#mcc#bmc#dcs',
                'sp' => 'sp_app_eipl_v1_bmc_data_collection_dcs',
            ],
            'bmc/collection-member' => [
                'param' => 'from_datetime#to_datetime#union#plant#mcc#bmc#dcs',
                'sp' => 'sp_app_eipl_v1_bmc_data_collection_member',
            ],
            'bmc/detail-info' => [
                'param' => 'union#plant#mcc#bmc#dcs',
                'sp' => 'sp_app_eipl_v1_bmc_data_master',
            ],
            'dcs/collection-dcs' => [
                'param' => 'from_datetime#to_datetime#union#plant#mcc#bmc#dcs',
                'sp' => 'sp_app_eipl_v1_dcs_data_collection_dcs',
            ],
            'dcs/collection-member' => [
                'param' => 'from_datetime#to_datetime#union#plant#mcc#bmc#dcs',
                'sp' => 'sp_app_eipl_v1_dcs_data_collection_member',
            ],
            'dcs/detail-info' => [
                'param' => 'union#plant#mcc#bmc#dcs',
                'sp' => 'sp_app_eipl_v1_dcs_data_master',
            ],
            'member/collection' => [
                'param' => 'from_datetime#to_datetime#union#plant#mcc#bmc#dcs',
                'sp' => 'sp_app_eipl_v1_member_data_collection',
            ],
            'member/detail-info' => [
                'param' => 'union#plant#mcc#bmc#dcs',
                'sp' => 'sp_app_eipl_v1_member_data_master',
            ],
            'member/collection-detail' => [
                'param' => 'from_datetime#to_datetime#union#plant#mcc#bmc#dcs#member',
                'sp' => 'sp_app_eipl_v1_member_data_collection_details',
            ],
            'report/collection-detail' => [
                'param' => 'union#plant#mcc#bmc#dcs#from_datetime',
                'sp' => 'sp_app_eipl_v1_dcs_wise_collection_report',
            ],
            'report/collection-passbook' => [
                'param' => 'union#plant#mcc#bmc#dcs#from_datetime#to_datetime',
                'sp' => 'sp_app_eipl_v1_collection_passbook',
            ],
            'report/dcs-register' => [
                'param' => 'union#plant#mcc#bmc#dcs',
                'sp' => 'sp_app_eipl_v1_dcs_register',
            ],
            'report/collection-summary' => [
                'param' => 'union#plant#mcc#bmc#dcs#from_datetime#to_datetime',
                'sp' => 'sp_app_eipl_v1_collection_summary',
            ],
            'report/dispatch-receipt' => [
                'param' => 'union#plant#mcc#bmc#dcs#from_datetime#to_datetime',
                'sp' => 'sp_app_eipl_v1_dispatch_vs_receipt',
            ],
            'report/manual-milk-entry' => [
                'param' => 'union#plant#mcc#bmc#dcs#from_datetime#to_datetime',
                'sp' => 'sp_app_eipl_v1_manual_milk_entry',
            ],
            'report/manual-milk-entry-summary' => [
                'param' => 'union#plant#mcc#bmc#dcs#from_datetime#to_datetime',
                'sp' => 'sp_app_eipl_v1_manual_milk_entry_summary',
            ],
            'report/member-collection' => [
                'param' => 'union#plant#mcc#bmc#dcs#from_datetime#member',
                'sp' => 'sp_app_eipl_v1_member_collection_day_wise_report',
            ],
            'report/member-collection-passbook' => [
                'param' => 'union#plant#mcc#bmc#dcs#member#from_datetime#to_datetime',
                'sp' => 'sp_app_eipl_v1_member_collection_passbook',
            ],
            'report/member-collection-summary' => [
                'param' => 'union#plant#mcc#bmc#dcs#member#from_datetime#to_datetime',
                'sp' => 'sp_app_eipl_v1_member_collection_summary',
            ],
            'report/collection-dispatch' => [
                'param' => 'union#plant#mcc#bmc#dcs#from_datetime#to_datetime',
                'sp' => 'sp_app_eipl_v1_milk_collection_vs_dispatch',
            ],
            'report/collection-receipt' => [
                'param' => 'union#plant#mcc#bmc#dcs#from_datetime#to_datetime',
                'sp' => 'sp_app_eipl_v1_milk_collection_vs_receipt',
            ],
            'report/purchase-rate' => [
                'param' => 'rate_code#fat#snf#milk_type#milk_qlty_type#rate_type',
                'sp' => 'sp_app_eipl_v1_purchase_rate',
            ],
            'report/purchase-rate-detail' => [
                'param' => 'dcs#from_datetime#rate_type',
                'sp' => 'sp_app_eipl_v1_purchase_rate_detail',
            ],
            'report/local-milk-sale-data' => [
                'param' => 'union#plant#mcc#bmc#dcs#member#from_datetime#to_datetime',
                'sp' => 'sp_app_eipl_v1_local_milk_sale_data',
            ],
            'general/alert-notification' => [
                'param' => 'device_id',
                'sp' => 'sp_app_eipl_v1_alert_notification',
            ],
            'menu/master' => [
                'param' => 'login_type#department',
                'sp' => 'sp_app_eipl_v1_menu_master'
            ],
            'user-widget/list' => [
                'param' => 'login_type#department',
                'sp' => 'sp_app_eipl_v1_user_widget',
            ],
            'dashboard/bmc-mcc' => [
                'param' => 'from_datetime#to_datetime#union#plant#mcc#bmc#dcs',
                'sp' => 'sp_app_eipl_v1_dashboard_bmc_mcc',
            ],
            'dashboard/bmc-dsk' => [
                'param' => 'from_datetime#to_datetime#union#plant#mcc#bmc#dcs',
                'sp' => 'sp_app_eipl_v1_dashboard_bmc_dsk',
            ],
            'dashboard/bmc-avgfat' => [
                'param' => 'from_datetime#to_datetime#union#plant#mcc#bmc#dcs',
                'sp' => 'sp_app_eipl_v1_dashboard_bmc_avg_fat',
            ],
            'dashboard/bmc-avgsnf' => [
                'param' => 'from_datetime#to_datetime#union#plant#mcc#bmc#dcs',
                'sp' => 'sp_app_eipl_v1_dashboard_bmc_avg_snf',
            ],
            'dashboard/bmc-totalqty' => [
                'param' => 'from_datetime#to_datetime#union#plant#mcc#bmc#dcs',
                'sp' => 'sp_app_eipl_v1_dashboard_bmc_total_quantity',
            ],
            'dashboard/bmc-avgrate' => [
                'param' => 'from_datetime#to_datetime#union#plant#mcc#bmc#dcs',
                'sp' => 'sp_app_eipl_v1_dashboard_bmc_avg_rate',
            ],
            'dashboard/bmc-totalamt' => [
                'param' => 'from_datetime#to_datetime#union#plant#mcc#bmc#dcs',
                'sp' => 'sp_app_eipl_v1_dashboard_bmc_total_amount',
            ],
            'dashboard/dsk-mcc' => [
                'param' => 'from_datetime#to_datetime#union#plant#mcc#bmc#dcs',
                'sp' => 'sp_app_eipl_v1_dashboard_dsk_mcc',
            ],
            'dashboard/dsk-dsk' => [
                'param' => 'from_datetime#to_datetime#union#plant#mcc#bmc#dcs',
                'sp' => 'sp_app_eipl_v1_dashboard_dsk_dsk',
            ],
            'dashboard/dsk-avgfat' => [
                'param' => 'from_datetime#to_datetime#union#plant#mcc#bmc#dcs',
                'sp' => 'sp_app_eipl_v1_dashboard_dsk_avg_fat',
            ],
            'dashboard/dsk-avgsnf' => [
                'param' => 'from_datetime#to_datetime#union#plant#mcc#bmc#dcs',
                'sp' => 'sp_app_eipl_v1_dashboard_dsk_avg_snf',
            ],
            'dashboard/dsk-totalqty' => [
                'param' => 'from_datetime#to_datetime#union#plant#mcc#bmc#dcs',
                'sp' => 'sp_app_eipl_v1_dashboard_dsk_total_quantity',
            ],
            'dashboard/dsk-avgrate' => [
                'param' => 'from_datetime#to_datetime#union#plant#mcc#bmc#dcs',
                'sp' => 'sp_app_eipl_v1_dashboard_dsk_avg_rate',
            ],
            'dashboard/dsk-totalamt' => [
                'param' => 'from_datetime#to_datetime#union#plant#mcc#bmc#dcs',
                'sp' => 'sp_app_eipl_v1_dashboard_dsk_total_amount',
            ],
            'dashboard/dsk-totalmember' => [
                'param' => 'from_datetime#to_datetime#union#plant#mcc#bmc#dcs',
                'sp' => 'sp_app_eipl_v1_dashboard_dsk_total_member',
            ],
        ];
        return $label;
    }

}
