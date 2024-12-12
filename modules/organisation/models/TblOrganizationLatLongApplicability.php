<?php

namespace app\modules\organisation\models;

use Yii;
use yii\db\Query;
use yii\db\Expression;
use app\modules\organisation\models\TblSocietyCodes;
use app\modules\syncutility\models\TblSentbox;
use app\modules\globalmaster\models\TblCustomerType;
use app\modules\organisation\models\TblCustomerMaster;
use app\modules\organisation\models\TblDcs;
use app\modules\organisation\models\TblSocietyCodesHistory;
use app\modules\organisation\models\TblDcsHistory;
use app\modules\organisation\models\TblCustomerMasterHistory;
use app\modules\organisation\models\TblOrganizationLatlong;
use app\modules\organisation\models\TblUserOrganizationMapping;



/**
 * This is the model class for table "tbl_route_mapping_sources".
 *
 * @property integer $route_mapping_source_code
 * @property string $route_code
 * @property string $from_type
 * @property string $from_dest
 * @property string $to_type
 * @property string $to_dest
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property integer $is_active
 *
 * @property TblOrganizationLatLongApplicability $routeCode
 */
class TblOrganizationLatLongApplicability extends \app\models\ChildModel {

    public $customer_code;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_organization_latlong_applicability';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['organization_latlong_code','user_code','applicable_for','applicable_code','union_code','originating_org_code','originating_org_type','originating_type','updated_at','updated_by','created_at','created_by'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'organization_latlong_code' => Yii::t('app', 'Organization Latlong Code'),
            'user_code' => Yii::t('app', 'User Code'),
            'applicable_for' => Yii::t('app', 'Applicable For'),
            'applicable_code' => Yii::t('app', 'Applicable Code'),
            'union_code' => Yii::t('app', 'Union Code'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating org'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
          
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrgLatCode() {
        return $this->hasOne(TblOrganizationLatLongApplicability::className(), ['organization_latlong_code' => 'organization_latlong_code']);
    }

    /**
     * @inheritdoc
     * @return TblOrganizationLatLongApplicabilityQuery the active query used by this AR class.
     */


    public function getDcsCode() {
        return $this->hasMany(TblDcs::className(), ['dcs_code' => 'dcs_code']);
    }

    

    public function getCustomerType() {
        return $this->hasOne(TblCustomerType::className(), ['customer_type' => 'customer_type', 'union_code' => 'union_code'])->andOnCondition(['is_active' => 1, 'is_routemapping' => 1]);
    }

    public function getCustomerCode() {
        return $this->hasOne(TblCustomerMaster::className(), ['customer_type' => 'customer_type'])->andwhere(['union_code' => $this->union_code, 'customer_code_ex' => $this->dcs_code_ex]);
    }


  

}
