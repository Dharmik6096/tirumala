<?php

namespace app\models;

use Yii;
use yii\base\UserException;

class TblMasterHierarchy extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_master_hierarchy';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['master_hierarchy_code','master_key','master_type','union_code','plant_code','mcc_plant_code','bmc_code','dcs_code','route_code','member_code','customer_code','ref_code1','ref_code2','ref_code3','ref_code4','ref_code5','is_active','wef_date','created_at','created_by','updated_at','updated_by','originating_org_code','originating_org_type','originating_type','x_col1','x_col2','x_col3','x_col4','x_col5'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'master_hierarchy_code' => Yii::t('app', 'Master Hierarchy Code'),
            'master_key' => Yii::t('app', 'Master Key'),
            'master_type' => Yii::t('app', 'Master Type'),
            'union_code' => Yii::t('app', 'Union Code'),
            'plant_code' => Yii::t('app', 'Plant Code'),
            'mcc_plant_code' => Yii::t('app', 'MCC Plant Code'),
            'bmc_code' => Yii::t('app', 'BMC Code'),
            'dcs_code' => Yii::t('app', 'DCS Code'),
            'route_code' => Yii::t('app', 'Route Code'),
            'member_code' => Yii::t('app', 'Member Code'),
            'customer_code' => Yii::t('app', 'Customer Code'),
            'ref_code1' => Yii::t('app', 'Ref Code 1'),
            'ref_code2' => Yii::t('app', 'Ref Code 2'),
            'ref_code3' => Yii::t('app', 'Ref Code 3'),
            'ref_code4' => Yii::t('app', 'Ref Code 4'),
            'ref_code5' => Yii::t('app', 'Ref Code 5'),
            'is_active' => Yii::t('app', 'Is Active'),
            'wef_date' => Yii::t('app', 'WEF Date'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'x_col1' => Yii::t('app', 'X Col 1'),
            'x_col2' => Yii::t('app', 'X Col 2'),
            'x_col3' => Yii::t('app', 'X Col 3'),
            'x_col4' => Yii::t('app', 'X Col 4'),
            'x_col5' => Yii::t('app', 'X Col 5'),
        ];
    }
    
    public function getActiveCount($key_name, $key_reset_on){
        return $this->find()->where(['union_code' => $this->union_code, $key_reset_on => $this->{$key_reset_on}, 'convert(bigint,'.$key_name.')' => (int) $this->{$key_name}])
        ->count();
    }
}
