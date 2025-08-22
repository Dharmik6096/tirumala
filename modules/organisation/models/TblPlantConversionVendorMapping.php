<?php

namespace app\modules\organisation\models;

use Yii;
use app\modules\tankermovement\models\TblPartyMaster;

/**
 * This is the model class for table "tbl_plant_conversion_vendor_mapping".
 *
 * @property integer $conversion_vendor_mapping_id
 * @property integer $party_master_code
 * @property string $union_code
 * @property string $plant_code
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 */
class TblPlantConversionVendorMapping extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_plant_conversion_vendor_mapping';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['party_master_code', 'union_code', 'plant_code', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'originating_type'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'conversion_vendor_mapping_id' => Yii::t('app', 'Conversion Vendor Mapping ID'),
            'party_master_code' => Yii::t('app', 'Party Master Code'),
            'union_code' => Yii::t('app', 'Union Code'),
            'plant_code' => Yii::t('app', 'Plant Code'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
        ];
    }

    public function getParty() {
        return $this->hasOne(TblPartyMaster::className(), ['party_master_code' => 'party_master_code']);
    }

    public function getMappedPlant($partyMasterCode) {
        return $this->find()
                    ->alias('m')
                    ->innerJoin('tbl_plant p', 'p.plant_code = m.plant_code')
                    ->where(['party_master_code'=>$partyMasterCode])->one();
    }

}
