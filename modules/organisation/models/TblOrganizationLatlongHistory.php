<?php

namespace app\modules\organisation\models;

use Yii;

/**
 * This is the model class for table "tbl_organization_latlong".
 *
 * @property integer $organization_latlong_code
 * @property string $customer_type
 * @property string $customer_code
 * @property string $lat_long
 * @property integer $is_active
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $originating_org_type
 * @property string $originating_org_code
 * @property integer $originating_type
 */
class TblOrganizationLatlongHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_organization_latlong_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['organization_latlong_code','customer_type','customer_code','lat_long','address','is_active','created_at','created_by','updated_at','updated_by','originating_org_type','originating_org_code','originating_type','history_created_at','history_created_by','operation_type'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'organization_latlong_code' => Yii::t('app', 'Organization Latlong Code'),
            'customer_type' => Yii::t('app', 'customer Name'),
            'customer_code' => Yii::t('app', 'customer Code'),
            'lat_long' => Yii::t('app', 'Lat Long'),
            'is_active' => Yii::t('app', 'Is Active'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'history_created_by' => Yii::t('app', 'History Created By'),
            'operation_type' => Yii::t('app', 'Operation Type'),
        ];
    }
}
