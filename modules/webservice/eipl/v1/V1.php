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
                'param' => 'select_param:[union_code],[union_name],ISNULL([has_bmc],0) as has_bmc#organization_type#organization_code#table:tbl_unions',
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
                'param' => 'select_param:[mcc_plant_code] as mcc_plant_code,[bmc_code],CONCAT([bmc_name], \'(\', [bmc_code_ex], \')\') as bmc_name#organization_type#organization_code#table:tbl_bmc',
                'sp' => 'sp_app_eipl_v1_master_data',
            ],
            'dcs/master' => [
                'param' => 'organization_type#organization_code',
                'sp' => 'sp_app_eipl_v1_dcs_master',
//                'param' => 'select_param:[bmc_code],[dcs_code],[dcs_name],1 as dpu_sync_allowed,1 as dpu_connection_type,\'98216CAA89BB76436941ABCCC8885442\' as dpu_enc_key,\'\' as dpu_salt,\'v1\' as dpu_version,\'AES\' as dpu_enc_type#organization_type#organization_code#table:tbl_dcs', 'sp' => 'sp_app_eipl_v1_master_data',
            ],
            'member/master' => [
                'param' => 'select_param:[dcs_code],[member_code],[member_name],convert(varchar,registration_date,105) as registration_date#organization_type#organization_code#table:tbl_member',
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
                'param' => 'union#plant#mcc#bmc#dcs#from_datetime#to_datetime#member',
                'sp' => 'sp_app_eipl_v1_manual_milk_entry',
            ],
            'report/manual-milk-entry-summary' => [
                'param' => 'union#plant#mcc#bmc#dcs#from_datetime#to_datetime',
                'sp' => 'sp_app_eipl_v1_manual_milk_entry_summary',
            ],
            'report/member-collection' => [
                'param' => 'union#plant#mcc#bmc#dcs#member#from_datetime#to_datetime',
                'sp' => 'sp_app_eipl_v1_member_collection_day_wise_report',
                'call_action' => TRUE,
                'blank_org_to_zero' => true,
                'rls_param_array' => ['union']
            ],
            'report/member-collection-passbook' => [
                'param' => 'union#plant#mcc#bmc#dcs#member#from_datetime#to_datetime',
                'sp' => 'sp_app_eipl_v1_member_collection_passbook',
                'call_action' => TRUE,
                'blank_org_to_zero' => true,
                'rls_param_array' => ['union']
            ],
            'report/member-collection-summary' => [
                'param' => 'union#plant#mcc#bmc#dcs#member#from_datetime#to_datetime',
                'sp' => 'sp_app_eipl_v1_member_collection_summary',
                'call_action' => TRUE,
                'blank_org_to_zero' => true,
                'rls_param_array' => ['union']
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
                'param' => 'union#login_type#department',
                'sp' => 'sp_app_eipl_v1_menu_master'
            ],
            'user-widget/list' => [
                'param' => 'union#login_type#department',
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
                'param' => 'from_datetime#to_datetime#union#plant#mcc#bmc#dcs#member',
                'sp' => 'sp_app_eipl_v1_dashboard_dsk_avg_fat',
            ],
            'dashboard/dsk-avgsnf' => [
                'param' => 'from_datetime#to_datetime#union#plant#mcc#bmc#dcs#member',
                'sp' => 'sp_app_eipl_v1_dashboard_dsk_avg_snf',
            ],
            'dashboard/dsk-totalqty' => [
                'param' => 'from_datetime#to_datetime#union#plant#mcc#bmc#dcs#member',
                'sp' => 'sp_app_eipl_v1_dashboard_dsk_total_quantity',
            ],
            'dashboard/dsk-avgrate' => [
                'param' => 'from_datetime#to_datetime#union#plant#mcc#bmc#dcs#member',
                'sp' => 'sp_app_eipl_v1_dashboard_dsk_avg_rate',
            ],
            'dashboard/dsk-totalamt' => [
                'param' => 'from_datetime#to_datetime#union#plant#mcc#bmc#dcs#member',
                'sp' => 'sp_app_eipl_v1_dashboard_dsk_total_amount',
            ],
            'dashboard/dsk-totalmember' => [
                'param' => 'from_datetime#to_datetime#union#plant#mcc#bmc#dcs',
                'sp' => 'sp_app_eipl_v1_dashboard_dsk_total_member',
            ],
            'dashboard/calendar' => [
                'param' => 'month#member',
                'sp' => 'sp_app_eipl_v1_dashboard_member_calendar',
                'call_action' => TRUE
            ],
            'dashboard/dsk-memberdiff' => [
                'param' => 'from_datetime#to_datetime#union#plant#mcc#bmc#dcs',
                'sp' => 'sp_app_eipl_v1_widget_pie_member_diff',
            ],
            'dashboard/dsk-societycollection' => [
                'param' => 'from_datetime#to_datetime#union#plant#mcc#bmc#dcs',
                'sp' => 'sp_app_eipl_v1_widget_pie_society_collection',
            ],
            'dashboard/bmc-societydiff' => [
                'param' => 'from_datetime#to_datetime#union#plant#mcc#bmc#dcs',
                'sp' => 'sp_app_eipl_v1_widget_pie_society_diff',
            ],
            'dashboard/bmc-bmccollection' => [
                'param' => 'from_datetime#to_datetime#union#plant#mcc#bmc#dcs',
                'sp' => 'sp_app_eipl_v1_widget_pie_bmc_collection',
            ],
            'dashboard/society-comparison' => [
                'param' => 'union#plant#mcc#bmc#dcs',
                'sp' => 'sp_app_eipl_v1_widget_bar_society_comparison',
                'call_action' => TRUE
            ],
            'dashboard/bmc-comparison' => [
                'param' => 'union#plant#mcc#bmc#dcs',
                'sp' => 'sp_app_eipl_v1_widget_bar_bmc_comparison',
                'call_action' => TRUE
            ],
            'dashboard/society-weekly-collection' => [
                'param' => 'union#plant#mcc#bmc#dcs',
                'sp' => 'sp_app_eipl_v1_widget_bar_society_weekly_collection',
                'call_action' => TRUE
            ],
            'dashboard/bmc-weekly-collection' => [
                'param' => 'union#plant#mcc#bmc#dcs',
                'sp' => 'sp_app_eipl_v1_widget_bar_bmc_weekly_collection',
                'call_action' => TRUE
            ],
            'report/bmc-collection-passbook' => [
                'param' => 'union#plant#mcc#bmc#dcs#from_datetime#to_datetime',
                'sp' => 'sp_app_eipl_v1_bmc_collection_passbook',
            ],
            'report/member-collection-paymentcyclewise' => [
                'param' => 'union#plant#mcc#bmc#dcs#member#from_datetime',
                'sp' => 'sp_app_eipl_v1_member_collection_paymentcycle_wise_report',
                'blank_org_to_zero' => true,
                'rls_param_array' => ['union']
            ],
            'report/member-collection-monthwise' => [
                'param' => 'union#plant#mcc#bmc#dcs#member#from_datetime',
                'sp' => 'sp_app_eipl_v1_member_collection_monthwise_report',
                'blank_org_to_zero' => true,
                'rls_param_array' => ['union']
            ],
            'dashboard/shift-collection-summary' => [
                'param' => 'union#plant#mcc#bmc#dcs#member#from_datetime#to_datetime',
                'sp' => 'sp_app_eipl_v1_shift_collection_summary',
            ],
            'dashboard/today-collection-summary' => [
                'param' => 'union#plant#mcc#bmc#dcs#member#from_datetime#to_datetime',
                'sp' => 'sp_app_eipl_v1_today_collection_summary',
            ],
            'dashboard/current-payment-cycle-summary' => [
                'param' => 'union#plant#mcc#bmc#dcs#member#from_datetime#to_datetime',
                'sp' => 'sp_app_eipl_v1_current_payment_cycle_summary',
            ],
            'dashboard/current-month-collection-summary' => [
                'param' => 'union#plant#mcc#bmc#dcs#member#from_datetime#to_datetime',
                'sp' => 'sp_app_eipl_v1_current_month_collection_summary',
            ],
            'dashboard/calendar-summary' => [
                'param' => 'month#member',
                'sp' => 'sp_app_eipl_v1_dashboard_member_calendar_summary',
                'as_object' => TRUE
            ],
            'dashboard/dsk-dsk-online' => [
                'param' => 'from_datetime#to_datetime#union#plant#mcc#bmc#dcs',
                'sp' => 'sp_app_eipl_v1_dashboard_dsk_dsk_online',
            ],
            'dashboard/bmc-dsk-online' => [
                'param' => 'from_datetime#to_datetime#union#plant#mcc#bmc#dcs',
                'sp' => 'sp_app_eipl_v1_dashboard_bmc_dsk_online',
            ],
            'dashboard/bmc-weigth-sample' => [
                'param' => 'from_datetime#to_datetime#union#plant#mcc#bmc',
                'sp' => 'sp_app_eipl_v1_dashboard_bmc_weight_sample',
            ],
            'dashboard/bmc-quality-sample' => [
                'param' => 'from_datetime#to_datetime#union#plant#mcc#bmc',
                'sp' => 'sp_app_eipl_v1_dashboard_bmc_quality_sample',
            ],
            'report/member-collection-shift' => [
                'param' => 'union#plant#mcc#bmc#dcs#collection_date',
                'sp' => 'sp_app_eipl_v1_milk_collection_shift_report',
                'call_action' => TRUE,
                'blank_org_to_zero' => true,
                'rls_param_array' => ['union']
            ],
            'report/member-collection-date-shift-summary' => [
                'param' => 'union#plant#mcc#bmc#dcs#from_datetime#to_datetime#staus_type',
                'sp' => 'sp_app_eipl_v1_member_milk_colleciton_summary_date_shift_wise',
                'call_action' => TRUE
            ],
            'report/member-collection-date-wise-summary' => [
                'param' => 'union#plant#mcc#bmc#dcs#from_datetime#to_datetime#staus_type',
                'sp' => 'sp_app_eipl_v1_member_milk_colleciton_summary_date_wise',
                'call_action' => TRUE
            ],
            'report/member-collection-consolidated' => [
                'param' => 'union#plant#mcc#bmc#dcs#from_datetime#to_datetime#staus_type',
                'sp' => 'sp_app_eipl_v1_member_milk_colleciton_consolidated',
                'call_action' => TRUE
            ],
            'report/society-collection-date-shift-summary' => [
                'param' => 'union#plant#mcc#bmc#dcs#from_datetime#to_datetime#staus_type',
                'sp' => 'sp_app_eipl_v1_society_wise_milk_collection_date_shift_wise',
                'call_action' => TRUE,
                'blank_org_to_zero' => true,
                'rls_param_array' => ['union']
            ],
            'report/society-collection-date-wise-summary' => [
                'param' => 'union#plant#mcc#bmc#dcs#from_datetime#to_datetime#staus_type',
                'sp' => 'sp_app_eipl_v1_society_wise_milk_collection_date_wise',
                'call_action' => TRUE,
                'blank_org_to_zero' => true,
                'rls_param_array' => ['union']
            ],
            'report/society-collection-consolidated' => [
                'param' => 'union#plant#mcc#bmc#dcs#from_datetime#to_datetime#staus_type',
                'sp' => 'sp_app_eipl_v1_society_wise_milk_collection_consolidated',
                'call_action' => TRUE,
                'blank_org_to_zero' => true,
                'rls_param_array' => ['union', 'dcs']
            ],
            'report/manual-milk-entry-society-date-shift-wise' => [
                'param' => 'union#plant#mcc#bmc#from_datetime#to_datetime#staus_type',
                'sp' => 'sp_app_eipl_v1_manual_milk_entry_society_date_shift_wise',
                'call_action' => TRUE,
                'blank_org_to_zero' => true,
                'rls_param_array' => ['union']
            ],
            'report/manual-milk-entry-member-date-shift-wise' => [
                'param' => 'union#plant#mcc#bmc#dcs#from_datetime#to_datetime#staus_type',
                'sp' => 'sp_app_eipl_v1_manual_milk_entry_member_date_shift_wise',
                'call_action' => TRUE,
                'blank_org_to_zero' => true,
                'rls_param_array' => ['union']
            ],
            'report/manual-milk-entry-society-date-wise' => [
                'param' => 'union#plant#mcc#bmc#from_datetime#to_datetime#staus_type',
                'sp' => 'sp_app_eipl_v1_manual_milk_entry_society_date_wise',
                'call_action' => TRUE,
                'blank_org_to_zero' => true,
                'rls_param_array' => ['union']
            ],
            'report/manual-milk-entry-member-date-wise' => [
                'param' => 'union#plant#mcc#bmc#dcs#from_datetime#to_datetime#staus_type',
                'sp' => 'sp_app_eipl_v1_manual_milk_entry_member_date_wise',
                'call_action' => TRUE,
                'blank_org_to_zero' => true,
                'rls_param_array' => ['union']
            ],
            'report/manual-milk-entry-society-consolidated' => [
                'param' => 'union#plant#mcc#bmc#from_datetime#to_datetime#staus_type',
                'sp' => 'sp_app_eipl_v1_manual_milk_entry_society_consolidated',
                'call_action' => TRUE,
                'blank_org_to_zero' => true,
                'rls_param_array' => ['union']
            ],
            'report/manual-milk-entry-member-consolidated' => [
                'param' => 'union#plant#mcc#bmc#dcs#from_datetime#to_datetime#staus_type',
                'sp' => 'sp_app_eipl_v1_manual_milk_entry_member_consolidated',
                'call_action' => TRUE,
                'blank_org_to_zero' => true,
                'rls_param_array' => ['union']
            ],
            'report/milk-collection-vs-receipt' => [
                'param' => 'union#plant#mcc#bmc#dcs#from_datetime#to_datetime#staus_type',
                'sp' => 'sp_app_eipl_v1_milk_collection_vs_milk_receipt',
                'blank_org_to_zero' => true,
                'rls_param_array' => ['union', 'dcs']
            ],
            'report/milk-dispatch-vs-receipt' => [
                'param' => 'union#plant#mcc#bmc#dcs#from_datetime#to_datetime#staus_type',
                'sp' => 'sp_app_eipl_v1_milk_dispatch_vs_receipt',
                'blank_org_to_zero' => true,
                'rls_param_array' => ['union', 'dcs']
            ],
            'report/member-collection-vs-dispatch' => [
                'param' => 'union#plant#mcc#bmc#dcs#from_datetime#to_datetime#staus_type',
                'sp' => 'sp_app_eipl_v1_member_collection_vs_dispatch',
                'blank_org_to_zero' => true,
                'rls_param_array' => ['union', 'dcs']
            ],
            'report/company-wise-collection-vs-dispatch' => [
                'param' => 'union#plant#mcc#bmc#from_datetime#to_datetime#staus_type',
                'sp' => 'sp_app_eipl_v1_company_wise_collection_vs_dispatch',
                'blank_org_to_zero' => true,
                'rls_param_array' => ['union', 'mcc', 'bmc']
            ],
            'report/company-wise-collection-vs-recipt' => [
                'param' => 'union#plant#mcc#bmc#from_datetime#to_datetime#staus_type',
                'sp' => 'sp_app_eipl_v1_company_wise_collection_vs_receipt',
                'blank_org_to_zero' => true,
                'rls_param_array' => ['union', 'mcc', 'bmc']
            ],
            'report/company-wise-dispatch-vs-recipt' => [
                'param' => 'union#plant#mcc#bmc#from_datetime#to_datetime#staus_type',
                'sp' => 'sp_app_eipl_v1_company_wise_dispatch_vs_receipt',
                'blank_org_to_zero' => true,
                'rls_param_array' => ['union', 'mcc', 'bmc']
            ],
            'report/bmc-collection-shift-report' => [
                'param' => 'union#plant#mcc#bmc#from_datetime#to_datetime',
                'sp' => 'sp_app_eipl_v1_bmc_collection_shift_report',
                'call_action' => TRUE,
                'blank_org_to_zero' => true,
                'rls_param_array' => ['union']
            ],
            'report/bmc-collection-date-shift-summary' => [
                'param' => 'union#plant#mcc#bmc#dcs#from_datetime#to_datetime#staus_type',
                'sp' => 'sp_app_eipl_v1_bmc_collection_date_and_shift_wise_summary',
                'call_action' => TRUE,
                'blank_org_to_zero' => true,
                'rls_param_array' => ['union', 'dcs']
            ],
            'report/bmc-collection-date-wise-summary' => [
                'param' => 'union#plant#mcc#bmc#dcs#from_datetime#to_datetime#staus_type',
                'sp' => 'sp_app_eipl_v1_bmc_collection_date_wise_summary',
                'call_action' => TRUE,
                'blank_org_to_zero' => true,
                'rls_param_array' => ['union', 'dcs']
            ],
            'report/bmc-collection-consolidated' => [
                'param' => 'union#plant#mcc#bmc#dcs#from_datetime#to_datetime#staus_type',
                'sp' => 'sp_app_eipl_v1_bmc_collection_consolidated',
                'call_action' => TRUE,
                'blank_org_to_zero' => true,
                'rls_param_array' => ['union', 'dcs']
            ],
            'report/union-collection-date-shift-summary' => [
                'param' => 'union#plant#mcc#bmc#from_datetime#to_datetime#staus_type',
                'sp' => 'sp_app_eipl_v1_company_wise_collection_date_shift_wise',
                'call_action' => TRUE,
                'blank_org_to_zero' => true,
                'rls_param_array' => ['union']
            ],
            'report/union-collection-date-summary' => [
                'param' => 'union#plant#mcc#bmc#from_datetime#to_datetime#staus_type',
                'sp' => 'sp_app_eipl_v1_company_wise_collection_date_wise',
                'call_action' => TRUE,
                'blank_org_to_zero' => true,
                'rls_param_array' => ['union']
            ],
            'report/union-collection-consolidated' => [
                'param' => 'union#plant#mcc#bmc#from_datetime#to_datetime#staus_type',
                'sp' => 'sp_app_eipl_v1_company_wise_collection_consolidated',
                'call_action' => TRUE,
                'blank_org_to_zero' => true,
                'rls_param_array' => ['union']
            ],
            'report/member-wise-summary' => [
                'param' => 'union#plant#mcc#bmc#dcs#from_datetime#to_datetime',
                'sp' => 'sp_app_eipl_v1_member_wise_summary',
                'call_action' => TRUE,
                'blank_org_to_zero' => true
            ],
            'report/member-wise-product-wise-date-wise' => [
                'param' => 'union#plant#mcc#bmc#dcs#from_datetime#to_datetime',
                'sp' => 'sp_app_eipl_v1_member_wise_product_wise_date_wise',
                'call_action' => TRUE,
                'blank_org_to_zero' => true
            ],
            'report/member-wise-payment-cycle-wise-summary' => [
                'param' => 'union#plant#mcc#bmc#dcs#from_datetime#to_datetime',
                'sp' => 'sp_app_eipl_v1_member_wise_payment_cycle_wise_summary',
                'call_action' => TRUE,
                'blank_org_to_zero' => true
            ],
            'report/member-wise-product-wise-payment-cycle-wise-summary' => [
                'param' => 'union#plant#mcc#bmc#dcs#from_datetime#to_datetime',
                'sp' => 'sp_app_eipl_v1_member_wise_product_wise_payment_cycle_wise_summary',
                'call_action' => TRUE,
                'blank_org_to_zero' => true
            ],
            'report/member-wise-no-of-payment-cycle' => [
                'param' => 'union#plant#mcc#bmc#dcs#member#no_of_payment_cycle',
                'sp' => 'sp_app_eipl_v1_member_wise_no_of_payment_cycle',
                'call_action' => TRUE,
                'blank_org_to_zero' => true,
                'rls_param_array' => ['union']
            ],
            'report/member-wise-payment-cycle-wise' => [
                'param' => 'union#plant#mcc#bmc#dcs#from_datetime#to_datetime',
                'sp' => 'sp_app_eipl_v1_member_wise_payment_cycle_wise',
                'call_action' => TRUE,
                'blank_org_to_zero' => true,
                'rls_param_array' => ['union']
            ],
            'report/mpp-wise-payment-cycle-wise' => [
                'param' => 'union#plant#mcc#bmc#dcs#from_datetime#to_datetime',
                'sp' => 'sp_app_eipl_v1_mpp_wise_payemnt_cycle_wise',
                'call_action' => TRUE,
                'blank_org_to_zero' => true,
                'rls_param_array' => ['union', 'dcs']
            ],
            'report/bmc-wise-payment-cycle-wise' => [
                'param' => 'union#plant#mcc#bmc#from_datetime#to_datetime',
                'sp' => 'sp_app_eipl_v1_bmc_wise_payemnt_cycle_wise',
                'call_action' => TRUE,
                'blank_org_to_zero' => true,
                'rls_param_array' => ['union', 'mcc', 'bmc']
            ],
            'report/company-wise-payment-cycle-wise' => [
                'param' => 'union#from_datetime#to_datetime',
                'sp' => 'sp_app_eipl_v1_company_wise_payemnt_cycle_wise',
                'call_action' => TRUE,
                'blank_org_to_zero' => true,
                'rls_param_array' => ['union']
            ],
            'union/collection-dcs' => [
                'param' => 'from_datetime#to_datetime#union#plant#mcc#bmc#dcs',
                'sp' => 'sp_app_eipl_v1_union_data_collection_dcs',
            ],
            'union/collection-member' => [
                'param' => 'from_datetime#to_datetime#union#plant#mcc#bmc#dcs',
                'sp' => 'sp_app_eipl_v1_union_data_collection_member',
            ],
            'dashboard/bmc-union' => [
                'param' => 'from_datetime#to_datetime#union#plant#mcc#bmc#dcs',
                'sp' => 'sp_app_eipl_v1_dashboard_bmc_union',
            ],
            'dashboard/dsk-union' => [
                'param' => 'from_datetime#to_datetime#union#plant#mcc#bmc#dcs',
                'sp' => 'sp_app_eipl_v1_dashboard_dsk_union',
            ],
            'mcc/collection-dcs' => [
                'param' => 'from_datetime#to_datetime#union#plant#mcc#bmc#dcs',
                'sp' => 'sp_app_eipl_v1_mcc_data_collection_dcs',
            ],
            'mcc/collection-member' => [
                'param' => 'from_datetime#to_datetime#union#plant#mcc#bmc#dcs',
                'sp' => 'sp_app_eipl_v1_mcc_data_collection_member',
            ],
            'indent/master' => [
                'param' => 'from_datetime#to_datetime#union#plant#mcc#bmc#dcs#member',
                'sp' => 'sp_app_eipl_v1_indent_master_data',
            ],
            'indentCreate' => [
                'main_table' => 'TblIndentMaster',
                'replace_array_key' => ['date' => 'indent_date'],
                'save_child_other' => true,
            ],
            'indentUpdate' => [
                'main_table' => 'TblIndentMaster',
                'save_child_other' => true,
            ],
            'bulk/notification' => [
                'param' => 'union#plant#mcc#bmc#dcs#notification_type',
                'sp' => 'sp_app_eipl_v1_bulk_notification',
            ],
            'provisional-member/master' => [
                'param' => 'select_param:*#organization_type#organization_code#table:tbl_member_provisional',
                'sp' => 'sp_app_eipl_v1_master_data',
                'to_decrypt' => ['dob', 'adhar_no', 'pan_no'],
            ],
            'bank/master' => [
                'param' => 'select_param:*#organization_type#state_code#table:tbl_banks',
                'sp' => 'sp_app_eipl_v1_master_data',
            ],
            'branch/master' => [
                'param' => 'bank_code',
                'param' => 'select_param:*#organization_type:bank_code#bank_code#table:tbl_branch:condition:ifsc IS NOT NULL',
                'sp' => 'sp_app_eipl_v1_master_data',
            ],
            'gender/master' => [
                'param' => 'select_param:*#organization_type#state_code#table:tbl_gender',
                'sp' => 'sp_app_eipl_v1_master_data',
            ],
            'mapping-document/master' => [
                'param' => 'union#master_type',
                'sp' => 'sp_eipl_app_provisioanl_member_document_master_data',
            ],
            'provisional-member' => [
                'main_table' => 'TblMemberProvisional',
                'save_child' => true
            ],
            'state/master' => [
                'param' => 'select_param:*#organization_type#state_code#table:tbl_states',
                'sp' => 'sp_app_eipl_v1_master_data',
            ],
            'district/master' => [
                'param' => 'select_param:*#organization_type:state_code#state_code#table:tbl_districts',
                'sp' => 'sp_app_eipl_v1_master_data',
            ],
            'sub-district/master' => [
                'param' => 'select_param:*#organization_type:district_code#district_code#table:tbl_sub_districts',
                'sp' => 'sp_app_eipl_v1_master_data',
            ],
            'village/master' => [
                'param' => 'select_param:*#organization_type:sub_district_code#sub_district_code#table:tbl_villages',
                'sp' => 'sp_app_eipl_v1_master_data',
            ],
            'member/edit' => [
                'param' => 'member_code',
                'sp' => 'sp_app_eipl_update_member_data',
                'to_decrypt' => ['dob', 'adhar_no', 'pan_no'],
            ],
            'report/milk-collection-audit' => [
                'param' => 'from_datetime#to_datetime#union#plant#mcc#bmc#dcs#member',
                'sp' => 'sp_app_eipl_v1_milk_collection_history',
            ],
            'manual-collection-request/list' => [
                'param' => 'union#plant#mcc#bmc#dcs#process_name',
                'sp' => 'sp_app_eipl_v1_manual_collection',
            ],
            'manual-collection-request' => [
                'main_table' => 'TblAllowManualCollectionRange',
                'multi_auto_increment_key' => true
            ],
            'milk-collection' => [
                'main_table' => 'TblMilkCollection',
                'multi_auto_inc_key_save_other' => true
            ],
            'milk-collection/list' => [
                'param' => 'collection_date#dcs',
                'sp' => 'sp_app_eipl_v1_milk_collection',
            ],
            'manual-collection-request-approve/list' => [
                'param' => 'union#plant#mcc#bmc#dcs#process_name#login_type#access_token',
                'sp' => 'sp_app_eipl_v1_manual_collection_approve_list',
            ],
            'manual-collection-request-approve/save' => [
                'main_table' => 'TblAllowManualCollectionRange',
                'save_child_other' => true,
            ],
            'bmc-collection/list' => [
                'param' => 'collection_date#bmc',
                'sp' => 'sp_app_eipl_v1_bmc_collection',
            ],
            'bmc-collection/save' => [
                'main_table' => 'TblBmcCollection',
                'multi_auto_increment_key' => true
            ],
            'siloinfo/master' => [
                'param' => 'organization_type#organization_code',
                'sp' => 'sp_app_eipl_v1_siloinfo_master',
            ],
            'trip-master/list' => [
                'param' => 'plant#bmc#login_type#mobile_no',
                'sp' => 'sp_app_eipl_v1_trip_list',
            ],
            'trip-detail/list' => [
                'param' => 'vehicle_trip_code',
                'sp' => 'sp_app_eipl_v1_trip_detail_list',
            ],
            'trip-check-in-out' => [
                'main_table' => 'TblVehicleTrip',
                'save_child' => true
            ],
            'trip-gate-in-out' => [
                'main_table' => 'TblVehicleTripDetail',
                'save_child' => true
            ],
            'composite-dispatch-actual/list' => [
                'param' => 'union#plant#mcc#bmc#dcs#from_datetime#to_datetime#status',
                'sp' => 'sp_app_eipl_v1_CDA',
            ],
        ];
        return $label;
    }

}
