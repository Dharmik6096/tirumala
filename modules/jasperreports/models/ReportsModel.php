<?php

namespace app\modules\jasperreports\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\base\Model;

class ReportsModel extends Model {

    public $federation_code, $union_code, $from_shift, $p_dcs_code, $p_collection_date, $p_language_code;
    public $to_shift, $from_date, $to_date, $dcs_code, $p_animal_type, $shift;
    public $p_member_code, $p_test_status, $p_milk_type, $report_type;
    public $language_code, $milk_class, $milk_type;
    public $p_from_date, $p_to_date, $p_date, $p_milk_class, $p_consumer_type, $consumer_type;
    public $p_payment_type, $p_dcs_payment;
    public $pm_dcs_code, $p_dispatch_type, $dispatch_type, $p_member_type, $date;
    public $p_meeting_type, $p_start_date, $p_end_date, $p_attandance, $p_qty;
    public $financial_year_code, $member_code, $p_status, $with_and_without_milktype, $route_code, $p_route_code, $p_union_code, $p_union_name, $p_dcs_name, $p_route_name, $p_type;
    public $p_is_bank;
    public $state_code, $p_district_code, $p_sub_district_code, $p_block_name;
    public $p_report_name, $p_no_of_pouring_day, $p_pouring_qty, $p_qty_from, $p_qty_to, $p_fat_from, $p_fat_to, $p_snf_from, $p_snf_to;
    public $p_plant_code, $p_mcc_code, $p_bmc_code, $p_ltr_kg, $p_customer_code, $p_customer_type, $p_payment_cycle_code, $p_staff_member_code, $p_month, $p_dcsc_code, $p_billing_for;
    public $region_code, $area_code, $p_transporter_code, $p_party_master_code;
    public $locale, $digit_config, $p_provisional_member_code, $p_lang_code, $p_lr_no, $p_vehicle_no, $p_mpp_survey_id, $p_VCG_M_Id, $p_trip_code, $p_vehicle_code, $trip_code, $p_login_type, $p_bank_type;

    function __construct() {
        if (Yii::$app->session->get('LanguageId') == 0) {
            $this->p_language_code = 0;
            $this->language_code = 0;
        } else {
            $this->p_language_code = Yii::$app->session->get('LanguageId');
            $this->language_code = Yii::$app->session->get('LanguageId');
        }
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['locale', 'digit_config', 'p_provisional_member_code', 'p_language_code', 'p_lang_code', 'p_mpp_survey_id', 'p_VCG_M_Id', 'p_type', 'p_trip_code', 'p_vehicle_code', 'p_login_type', 'p_bank_type'], 'safe'],
                [['p_customer_code', 'p_staff_member_code', 'p_dcs_code', 'p_member_code', 'p_dcsc_code', 'p_route_code', 'p_billing_for', 'route_code'], 'default', 'value' => '0'],
                [['p_plant_code', 'p_mcc_code', 'p_bmc_code', 'union_code', 'p_dcs_code', 'p_collection_date', 'shift'], 'required', 'on' => 'ShiftReportNameWise'],
                [['p_plant_code', 'p_mcc_code', 'p_bmc_code', 'union_code', 'p_from_date', 'p_to_date', 'p_dcs_code', 'from_shift', 'to_shift', 'p_member_type'], 'required', 'on' => 'MemberMilkCollectionRegister'],
                [['p_plant_code', 'p_mcc_code', 'p_bmc_code', 'union_code', 'p_from_date', 'p_to_date', 'p_dcs_code', 'from_shift', 'to_shift', 'p_member_code'], 'required', 'on' => 'MemberMilkCollectionSummary'],
                [['p_plant_code', 'p_mcc_code', 'p_bmc_code', 'union_code', 'p_dcs_code', 'p_from_date', 'p_to_date', 'from_shift', 'to_shift', 'p_milk_type', 'report_type'], 'required', 'on' => 'ConsolidatedMilkCollection'],
                [['union_code', 'p_route_code', 'p_dcs_code', 'p_from_date', 'p_to_date', 'from_shift', 'to_shift', 'with_and_without_milktype', 'p_milk_type', 'report_type'], 'required', 'on' => 'ConsolidatedDcsMilkCollection'],
                [['union_code', 'p_route_code', 'p_dcs_code', 'p_from_date', 'p_to_date', 'from_shift', 'to_shift', 'with_and_without_milktype', 'p_milk_type', 'p_type', 'report_type'], 'required', 'on' => 'ConsolidatedUnionMilkCollection'],
                [['union_code', 'p_route_code', 'p_dcs_code', 'p_from_date', 'p_to_date', 'from_shift', 'to_shift', 'with_and_without_milktype', 'p_milk_type'], 'required', 'on' => 'CollectionDispatchDifferenceReport'],
                [['union_code', 'p_route_code', 'p_from_date', 'p_to_date', 'from_shift', 'to_shift', 'with_and_without_milktype', 'p_milk_type'], 'required', 'on' => 'UnionCollectionDispatchDifferenceReport'],
                [['p_plant_code', 'p_mcc_code', 'p_bmc_code', 'union_code', 'p_dcs_code', 'p_from_date', 'p_to_date', 'from_shift', 'to_shift'], 'required', 'on' => 'CollectionVsDispatchGraph'],
                [['p_plant_code', 'p_mcc_code', 'p_bmc_code', 'union_code', 'p_dcs_code', 'p_dcs_payment'], 'required', 'on' => 'MemberRegister'],
                [['p_plant_code', 'p_mcc_code', 'p_bmc_code', 'union_code', 'p_dcs_code', 'p_member_code', 'p_dcs_payment'], 'required', 'on' => 'MemberWisePaymentRegister'],
                [['p_plant_code', 'p_mcc_code', 'p_bmc_code', 'union_code', 'p_dcs_code', 'p_member_code'], 'required', 'on' => 'MemberClassificationRegister'],
                [['p_union_name', 'p_dcs_name', 'p_route_name', 'p_union_code', 'p_customer_type', 'p_customer_code', 'p_payment_cycle_code', 'p_member_code', 'p_staff_member_code', 'p_month', 'p_mcc_code', 'p_bmc_code', 'p_dcsc_code', 'p_billing_for', 'p_route_code', 'route_code', 'p_from_date', 'p_to_date', 'p_qty_from', 'p_qty_to', 'p_fat_from', 'p_fat_to', 'p_snf_from', 'p_snf_to', 'state_code', 'region_code', 'area_code'], 'safe'],
                [['p_plant_code', 'p_mcc_code', 'p_bmc_code', 'union_code', 'p_dcs_code', 'p_member_code', 'p_dcs_payment', 'p_is_bank'], 'required', 'on' => 'MemberPaymentHeldup'],
                [['union_code', 'p_district_code', 'p_sub_district_code', 'p_block_name', 'p_from_date', 'p_to_date', 'from_shift', 'to_shift'], 'required', 'on' => 'BlockWiseMilkCollection'],
                [['union_code', 'p_dcs_payment'], 'required', 'on' => 'PaymentAuth'],
                [['p_report_name', 'p_lr_no', 'p_vehicle_no'], 'safe'],
                [['p_no_of_pouring_day'], 'integer'],
                [['p_pouring_qty'], 'double'],
                [['p_plant_code', 'p_mcc_code', 'p_bmc_code', 'union_code', 'p_dcs_code', 'p_from_date', 'p_to_date', 'from_shift', 'to_shift', 'p_pouring_qty', 'p_no_of_pouring_day', 'p_member_type'], 'required', 'on' => 'SocietyDetails'],
                [['union_code', 'p_plant_code', 'p_mcc_code', 'p_bmc_code', 'p_route_code', 'p_from_date', 'from_shift', 'p_to_date', 'to_shift', 'p_ltr_kg'], 'required', 'on' => 'ActualBmcCollection'],
                [['union_code', 'p_plant_code', 'p_mcc_code', 'p_bmc_code', 'p_dcs_code', 'p_route_code', 'p_from_date', 'from_shift', 'p_to_date', 'to_shift', 'p_milk_type', 'p_milk_class', 'p_ltr_kg'], 'required', 'on' => 'RmrdMilkCollection'],
                [['union_code', 'p_plant_code', 'p_mcc_code', 'p_bmc_code', 'p_dcs_code', 'p_route_code', 'p_from_date', 'from_shift', 'p_to_date', 'to_shift', 'p_milk_type', 'p_ltr_kg'], 'required', 'on' => 'BmcSummaryReport'],
                [['union_code', 'p_plant_code', 'p_mcc_code', 'p_bmc_code', 'p_dcs_code', 'p_route_code', 'p_from_date', 'from_shift', 'p_to_date', 'to_shift'], 'required', 'on' => 'VariationMilkTypeDateWise'],
                [['union_code', 'p_plant_code', 'p_mcc_code', 'p_bmc_code', 'p_dcs_code', 'p_route_code', 'p_from_date', 'from_shift', 'p_to_date', 'to_shift'], 'required', 'on' => 'VariationMilkTypeVillageWise'],
                [['union_code', 'p_plant_code', 'p_mcc_code', 'p_bmc_code', 'p_dcs_code', 'p_route_code', 'p_from_date', 'from_shift', 'p_to_date', 'to_shift'], 'required', 'on' => 'VariationDateWise'],
                [['union_code', 'p_plant_code', 'p_mcc_code', 'p_bmc_code', 'p_dcs_code', 'p_route_code', 'p_from_date', 'from_shift', 'p_to_date', 'to_shift'], 'required', 'on' => 'VariationVillageWise'],
                [['union_code', 'p_plant_code', 'p_mcc_code', 'p_bmc_code', 'p_dcs_code', 'p_route_code', 'p_from_date', 'from_shift', 'p_to_date', 'to_shift'], 'required', 'on' => 'VariationPercentageWise'],
                [['union_code', 'p_plant_code', 'p_mcc_code', 'p_bmc_code', 'p_dcs_code', 'p_route_code', 'p_from_date', 'from_shift', 'p_to_date', 'to_shift'], 'required', 'on' => 'DifferenceReport'],
                [['union_code', 'p_plant_code', 'p_mcc_code', 'p_bmc_code', 'p_dcs_code', 'p_route_code', 'p_from_date', 'from_shift', 'p_to_date', 'to_shift'], 'required', 'on' => 'DifferenceReportDateWise'],
                [['union_code', 'p_plant_code', 'p_mcc_code', 'p_bmc_code', 'p_dcs_code', 'p_route_code', 'p_from_date', 'from_shift', 'p_to_date', 'to_shift'], 'required', 'on' => 'DifferenceReportVillageWise'],
                [['union_code', 'p_plant_code', 'p_mcc_code', 'p_bmc_code', 'p_dcs_code', 'p_route_code', 'p_from_date', 'from_shift', 'p_to_date', 'to_shift'], 'required', 'on' => 'GprsDataReconciliation'],
                [['union_code', 'p_plant_code', 'p_mcc_code', 'p_bmc_code', 'p_dcs_code', 'p_route_code', 'p_from_date', 'from_shift', 'p_to_date', 'to_shift', 'p_milk_type', 'p_ltr_kg'], 'required', 'on' => 'BmcCollection'],
                [['p_to_date'], function ($attribute, $params) {
                    Yii::$app->general->dateRangeValidate($this, $attribute, $params, 'p_from_date', 'p_to_date');
                }, 'skipOnEmpty' => false, 'except' => ['VlccTransactionDataReportRegionAll']],
                [['union_code', 'p_plant_code', 'p_mcc_code', 'p_bmc_code', 'p_from_date', 'p_to_date'], 'required', 'on' => ['BMCPayment', 'MilkReceiptForMember', 'ProductSaleInvoiceForMember', 'PrimaryTransporterMonthlyBill']],
                [['union_code', 'p_plant_code', 'p_mcc_code', 'p_bmc_code', 'p_customer_type', 'p_payment_cycle_code'], 'required', 'on' => ['VendorMilkPayment', 'VendorMilkBillGLT', 'VendorMilkBillSummaryGLT', 'VspPaymentVrs', 'VspPaymentOnlineVrs', 'VSPPaymentNawasa', 'VSPPaymentOnlineNawasa']],
                [['union_code', 'p_plant_code', 'p_mcc_code', 'p_bmc_code', 'p_payment_cycle_code'], 'required', 'on' => ['VendorMilkBill', 'VendorMilkBillVardaan', 'VendorMilkBillSnmilk', 'VendorMilkBillJgf', 'VendorMilkBillAnig', 'VendorMilkBillShivPrasad', 'PaymentSummary']],
                [['union_code', 'p_plant_code', 'p_mcc_code', 'p_bmc_code', 'p_dcs_code', 'p_payment_cycle_code'], 'required', 'on' => ['MemberMilkPayment']],
                [['union_code', 'p_plant_code', 'p_mcc_code', 'p_bmc_code', 'p_payment_cycle_code'], 'required', 'on' => ['MemberMilkBill', 'MemberMilkBillShivPrasad']],
                [['union_code'], 'required', 'on' => ['StaffSalary']],
                [['union_code', 'p_plant_code', 'p_mcc_code', 'p_bmc_code', 'p_payment_cycle_code'], 'required', 'on' => ['VendorBill', 'BankAdvice', 'MpgBillStatement']],
                [['union_code', 'p_plant_code', 'p_from_date', 'p_to_date'], 'required', 'on' => ['InchargeRemuneration', 'MccChillingBill', 'ProductSaleInvoice', 'DmrWeightedAverage', 'MccBonusReport', 'MccMaintanceReport']],
                [['from_shift', 'to_shift'], 'required', 'on' => ['DmrWeightedAverage', 'MccBonusReport', 'MccMaintanceReport', 'VendorCommissionPayment']],
                [['union_code', 'p_plant_code', 'p_mcc_code', 'p_bmc_code', 'p_payment_cycle_code'], 'required', 'on' => ['MemberBillAbstract', 'BmcMilkPaymentVoucher']],
                [['union_code', 'p_plant_code', 'p_mcc_code', 'p_bmc_code', 'p_payment_cycle_code'], 'required', 'on' => ['VendorBillMmd', 'MemberPaymentVrs', 'VendorBillElanad', 'MemberPaymentNawasa']],
                [['union_code', 'p_plant_code', 'p_mcc_code', 'p_from_date', 'from_shift', 'p_to_date', 'to_shift'], 'required', 'on' => ['FarmerIncentive', 'VlccTransactionDataReport', 'MccVlcRecieptRouteWise', 'DayWiseSummary']],
                [['p_from_date', 'p_to_date', 'p_bmc_code'], 'required', 'on' => ['MccDayBookDispatchHub']],
                [['p_union_code', 'state_code', 'region_code', 'area_code', 'p_bmc_code'], 'required', 'on' => ['VlccTransactionDataReportRegion']],
                [['union_code', 'p_plant_code', 'p_mcc_code', 'p_from_date', 'p_to_date'], 'required', 'on' => ['MilkReceiptForBMC', 'ProductSaleInvoiceForCustomer', 'BmcCollectionSummary', 'MCCChillingBillInvoice']],
                [['p_union_code', 'p_plant_code', 'p_mcc_code', 'p_payment_cycle_code'], 'required', 'on' => ['ProductSaleSummary']],
                [['p_union_code', 'p_plant_code'], 'required', 'on' => ['VendorMilkBillSbd']],
                [['p_provisional_member_code'], 'required', 'on' => ['ProvisionalMemberRegister']],
                [['p_mpp_survey_id'], 'required', 'on' => ['MppSurvey']],
                [['p_VCG_M_Id'], 'required', 'on' => ['VcgMeeting']],
                [['p_lang_code'], 'required', 'on' => ['ProvisionalMemberRegister', 'MppSurvey', 'VcgMeeting']],
                [['p_from_date', 'p_to_date', 'p_lang_code'], 'required', 'on' => ['RptMemberRegisterAll']],
                [['p_date', 'p_lr_no', 'p_vehicle_no'], 'required', 'on' => ['MilkChillBillCenterWise', 'MilkChillingBillLrNoWise']],
                [['union_code', 'p_from_date', 'p_to_date'], 'required', 'on' => ['PartyPaymentBill']],
                [['union_code', 'p_plant_code', 'p_mcc_code', 'p_bmc_code', 'p_payment_cycle_code'], 'required', 'on' => ['ShiftWiseBill']],
                [['p_from_date', 'p_to_date'], 'required', 'on' => ['CompleteTrip', 'VendorCommissionPayment']],
                [['union_code', 'p_plant_code', 'p_mcc_code', 'p_bmc_code', 'p_from_date', 'p_to_date', 'from_shift', 'to_shift'], 'required', 'on' => ['CcTruckSlip', 'DmrReport', 'CcSubStandardMrg', 'DmrCheckList']],
                [['p_qty_from', 'p_qty_to', 'p_fat_from', 'p_fat_to', 'p_snf_from', 'p_snf_to'], 'double'],
                [['p_qty_from', 'p_qty_to', 'p_fat_from', 'p_fat_to', 'p_snf_from', 'p_snf_to'], 'validatePair', 'on' => ['CcSubStandardMrg']],
                [['report_type'], 'required', 'on' => ['DayWiseSummary']],
                [['p_union_code'], 'required', 'on' => ['VlccTransactionDataReportRegionAll']],
                [['p_to_date'], function ($attribute, $params) {
                    Yii::$app->general->dateRangeValidate($this, $attribute, $params, 'p_from_date', 'p_to_date', 15, '>', 'Day Difference can not be greater than 15.');
                }, 'skipOnEmpty' => false, 'on' => ['VlccTransactionDataReportRegionAll']],
                [['p_union_code', 'p_login_type', 'p_from_date', 'p_to_date'], 'required', 'on' => ['UserAttendanceReport']],
                [['p_union_code', 'p_plant_code', 'p_mcc_code', 'p_bmc_code', 'p_payment_cycle_code', 'p_bank_type'], 'required', 'on' => ['MemberBankPaymentReport']],
        ];
    }

    public function attributeLabels() {
        return [
            'p_dcs_code' => \Yii::t('app', 'Society'),
            'p_collection_date' => \Yii::t('app', 'Date'),
            'from_shift' => \Yii::t('app', 'From Shift'),
            'from_date' => \Yii::t('app', 'Date From'),
            'to_date' => \Yii::t('app', 'Date To'),
            'to_shift' => \Yii::t('app', 'To Shift'),
            'p_animal_type' => \Yii::t('app', 'Milk Type'),
            'dcs_code' => \Yii::t('app', 'DCS'),
            'p_member_code' => \Yii::t('app', 'Member'),
            'p_test_status' => \Yii::t('app', 'Result'),
            'p_milk_type' => \Yii::t('app', 'Milk Type'),
            'shift' => \Yii::t('app', 'Shift'),
            'report_type' => \Yii::t('app', 'Report Type'),
            'milk_type' => \Yii::t('app', 'Milk Type'),
            'milk_class' => \Yii::t('app', 'Milk Class'),
            'p_from_date' => \Yii::t('app', 'From Date'),
            'p_to_date' => \Yii::t('app', 'To Date'),
            'p_date' => \Yii::t('app', 'As On Date'),
            'p_milk_class' => \Yii::t('app', 'Milk Class'),
            'p_consumer_type' => \Yii::t('app', 'Consumer Type'),
            'consumer_type' => \Yii::t('app', 'Consumer Type'),
            'p_payment_type' => \Yii::t('app', 'Payment Type'),
            'p_dcs_payment' => \Yii::t('app', 'Payment Cycle'),
            'p_dispatch_type' => \Yii::t('app', 'Dispatch Type'),
            'dispatch_type' => \Yii::t('app', 'Dispatch Type'),
            'pm_dcs_code' => \Yii::t('app', 'DCS'),
            'p_member_type' => \Yii::t('app', 'Member Type'),
            'date' => \Yii::t('app', 'As On Date'),
            'p_meeting_type' => \Yii::t('app', 'Meeting Type'),
            'p_start_date' => \Yii::t('app', 'Start Date'),
            'p_end_date' => \Yii::t('app', 'End Date'),
            'p_attandance' => \Yii::t('app', 'No. of Poured Days (Minimum)>='),
            'p_qty' => \Yii::t('app', 'Poured Quantity (Minimum)>='),
            'financial_year_code' => \Yii::t('app', 'Financial Year'),
            'member_code' => \Yii::t('app', 'Member'),
            'p_status' => \Yii::t('app', 'Report Type'),
            'with_and_without_milktype' => \Yii::t('app', 'Type'),
            'p_type' => \Yii::t('app', 'Parameters'),
            'p_is_bank' => \Yii::t('app', 'Bank Type'),
            'p_district_code' => \Yii::t('app', 'District'),
            'p_sub_district_code' => \Yii::t('app', 'Sub District'),
            'p_block_name' => \Yii::t('app', 'Block Name'),
            'state_code' => \Yii::t('app', 'State'),
            'p_pouring_qty' => \Yii::t('app', 'Pouring Qty >='),
            'p_no_of_pouring_day' => \Yii::t('app', 'No of Pouring Day >='),
            'p_plant_code' => \Yii::t('app', 'Plant'),
            'p_mcc_code' => \Yii::t('app', 'MCC'),
            'p_bmc_code' => \Yii::t('app', 'BMC'),
            'p_ltr_kg' => \Yii::t('app', 'Quantity Mode'),
            'p_customer_code' => \Yii::t('app', 'Name'),
            'p_customer_type' => \Yii::t('app', 'Type'),
            'p_payment_cycle_code' => \Yii::t('app', 'Payment Cycle'),
            'p_staff_member_code' => \Yii::t('app', 'Staff Member'),
            'p_month' => \Yii::t('app', 'Month'),
            'p_billing_for' => \Yii::t('app', 'Billing For'),
            'p_provisional_member_code' => \Yii::t('app', 'Provisional Member'),
            'p_mpp_survey_id' => \Yii::t('app', 'Mpp Survey'),
            'p_VCG_M_Id' => \Yii::t('app', 'Vcg Meeting'),
            'p_lang_code' => \Yii::t('app', 'Language'),
            'p_lr_no' => \Yii::t('app', 'LR No'),
            'p_vehicle_no' => \Yii::t('app', 'Vehicle No'),
            'p_trip_code' => \Yii::t('app', 'Trip Code'),
            'p_qty_from' => \Yii::t('app', 'Qty From'),
            'p_qty_to' => \Yii::t('app', 'Qty To'),
            'p_fat_from' => \Yii::t('app', 'Fat From'),
            'p_fat_to' => \Yii::t('app', 'Fat To'),
            'p_snf_from' => \Yii::t('app', 'Snf From'),
            'p_snf_to' => \Yii::t('app', 'Snf To'),
            'p_bank_type' => \Yii::t('app', 'Bank Type'),
        ];
    }

    public function validatePair($attribute, $params) {
        if (!empty($this->p_qty_from) && empty($this->p_qty_to)) {
            $this->addError('p_qty_to', 'To Qty cannot be blank.');
        }
        if (!empty($this->p_qty_to) && empty($this->p_qty_from)) {
            $this->addError('p_qty_from', 'From Qty cannot be blank.');
        }
        if (!empty($this->p_qty_from) && !empty($this->p_qty_to) && $this->p_qty_from >= $this->p_qty_to) {
            $this->addError('p_qty_to', 'To Qty must be greater than From Qty.');
        }
        if (!empty($this->p_fat_from) && empty($this->p_fat_to)) {
            $this->addError('p_fat_to', 'To Fat cannot be blank.');
        }
        if (!empty($this->p_fat_to) && empty($this->p_fat_from)) {
            $this->addError('p_fat_from', 'From Fat cannot be blank.');
        }
        if (!empty($this->p_fat_from) && !empty($this->p_fat_to) && $this->p_fat_from >= $this->p_fat_to) {
            $this->addError('p_fat_to', 'To Fat must be greater than From Fat.');
        }
        if (!empty($this->p_snf_from) && empty($this->p_snf_to)) {
            $this->addError('p_snf_to', 'To SNF cannot be blank.');
        }
        if (!empty($this->p_snf_to) && empty($this->p_snf_from)) {
            $this->addError('p_snf_from', 'From SNF cannot be blank.');
        }
        if (!empty($this->p_snf_from) && !empty($this->p_snf_to) && $this->p_snf_from >= $this->p_snf_to) {
            $this->addError('p_snf_to', 'To SNF must be greater than From SNF.');
        }
    }

}
