<?php

namespace app\modules\feedback\models;

use Yii;
use app\models\ChildModel;

/**
 * This is the model class for table "tbl_MRG_meeting_org_mapping".
 *
 * @property integer $MRG_org_map_id
 * @property integer $MRG_M_Id
 * @property string $mcc_plant_code
 * @property string $bmc_code
 * @property string $route_code
 * @property string $dcs_code
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property integer $originating_type
 * @property string $originating_org_code
 * @property string $originating_org_type
 */
class TblMRGMeetingOrgMapping extends ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_MRG_meeting_org_mapping';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['MRG_M_Id', 'mcc_plant_code', 'bmc_code', 'route_code', 'dcs_code', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_type', 'originating_org_code', 'originating_org_type'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'MRG_org_map_id' => Yii::t('app', 'Mrg Org Map ID'),
            'MRG_M_Id' => Yii::t('app', 'Mrg M ID'),
            'mcc_plant_code' => Yii::t('app', 'Mcc Plant Code'),
            'bmc_code' => Yii::t('app', 'Bmc Code'),
            'route_code' => Yii::t('app', 'Route Code'),
            'dcs_code' => Yii::t('app', 'Dcs Code'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
        ];
    }

}
