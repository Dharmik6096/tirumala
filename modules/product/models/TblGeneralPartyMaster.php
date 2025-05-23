<?php

namespace app\modules\product\models;

use Yii;

/**
 * This is the model class for table "tbl_general_party_master".
 *
 * @property integer $general_party_master_code
 * @property string $party_name
 * @property string $union_code
 * @property string $plant_code
 * @property string $mcc_plant_code
 * @property string $bmc_code
 * @property string $dcs_code
 * @property string $ref_code
 * @property string $party_type
 * @property string $party_code
 * @property integer $is_product_sale
 * @property integer $is_active
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 */
class TblGeneralPartyMaster extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_general_party_master';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['party_name','union_code','plant_code','mcc_plant_code','bmc_code','dcs_code','ref_code','party_type','party_code','is_product_sale','is_active','created_at','created_by','updated_at','updated_by','originating_org_code','originating_org_type','originating_type'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'general_party_master_code' => Yii::t('app', 'General Party Master Code'),
            'party_name' => Yii::t('app', 'Party Name'),
            'union_code' => Yii::t('app', 'Union Code'),
            'plant_code' => Yii::t('app', 'Plant Code'),
            'mcc_plant_code' => Yii::t('app', 'Mcc Plant Code'),
            'bmc_code' => Yii::t('app', 'Bmc Code'),
            'dcs_code' => Yii::t('app', 'Dcs Code'),
            'ref_code' => Yii::t('app', 'Ref Code'),
            'party_type' => Yii::t('app', 'Party Type'),
            'party_code' => Yii::t('app', 'Party Code'),
            'is_product_sale' => Yii::t('app', 'Is Product Sale'),
            'is_active' => Yii::t('app', 'Is Active'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
        ];
    }
}
