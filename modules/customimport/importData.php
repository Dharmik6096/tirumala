<?php

namespace app\modules\customimport;

/**
 * customimport module definition class
 */
class importData extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'app\modules\customimport\controllers';
    public static $label=[            
            'dcs'=>['import_class'=>'dcs_import','import_main_class'=>'DcsImportStrategy','table_name'=>'tbl_dcs','fields'=>'union_code,dcs_name,dcs_short_name,dcs_code_ex,is_bmc,destination_type,destination_code,milk_type_code,address,hamlet_code,route_code,pincode,service_tax,tin_no,effective_date,phone_no,contact_person,mobile_no,email,branch_code,bank_account_no,ifsc,upi_no,dcs_type_code,pan_no,registration_code,registration_date,is_active','scenario'=>'importCsv','mapping'=>1,'mapping_model'=>'TblDcsMilkType','mapping_fields'=>'dcs_code,milk_type_code','operations'=>['create','update']],
            'member'=>['import_class'=>'member_import','history_class'=>'TblMemberHistory','table_name'=>'tbl_member','fields'=>'dcs_code,member_name,local_name,father_name,local_father_name,surname,local_surname,nominee_name,local_nominee_name,nominee_relation,ex_member_code,dob,bloodgroup_code,gender_code,qualification_code,caste_category_code,religion_code,total_land,animal_type_code,no_of_buffalo,no_of_cow_cross,no_of_cow_ind,member_type_code,branch_code,bank_account_no,mobile_no,email,address,local_address,pincode,pan_no,adhar_no,annual_income,hamlet_code,voter_id,member_class,registration_date,reference_code','default_fields'=>'is_active:1,upload:1,is_download:0','scenario'=>'importCsv','update_field'=>'member_code','no_update'=>'dcs_code','operations'=>['create'=>'dcs_code','update'=>'']],
            ];
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }
    
   public static function getLabels($l){
        
        return self::$label[$l];
    }
    
    public static function getData($flag)
    {
        
        $data=  array_filter(self::$label, function($ele) use($flag){
                    return key_exists('operations', $ele) && key_exists($flag,$ele['operations']);
            });
        return $data;
    }
}
