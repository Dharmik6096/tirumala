<?php

namespace app\modules\product\models;

use Yii;
use app\modules\product\models\TblDispatchCenterType;
use app\modules\globalmaster\models\TblCustomerType;
use app\modules\organisation\models\TblDcs;
use app\models\TblUserOrganizationMapping;
use webvimark\modules\UserManagement\models\User;

/**
 * This is the model class for table "tbl_dispatch_center_applicability".
 *
 * @property string $dispatch_center_applicability_code
 * @property string $dispatch_center_code
 * @property string $dispatch_center_name
 * @property string $applicable_code
 * @property string $applicable_for
 * @property string $applicable_type
 * @property string $union_code
 * @property string $mcc_plant_code
 * @property string $dcs_code
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 * @property string $x_col1
 * @property string $x_col2
 * @property string $x_col3
 * @property string $x_col4
 * @property string $x_col5
 */
class TblDispatchCenterApplicability extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_dispatch_center_applicability';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['applicable_code'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['originating_type'], 'integer'],
            [['dispatch_center_code'], 'safe'],
            [['dispatch_center_name'], 'safe'],
            [['applicable_for', 'applicable_type'], 'safe'],
            [['union_code'], 'safe'],
            [['mcc_plant_code'], 'safe'],
            [['dcs_code'], 'safe'],
            [['created_by', 'updated_by'], 'safe'],
            [['originating_org_code', 'originating_org_type'], 'safe'],
            [['x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
//                [['applicable_code'], 'unique', 'targetAttribute' => ['dcs_code', 'dispatch_center_code'], 'skipOnEmpty' => true, 'message' => Yii::t('app/validation', '{attribute} has already been taken.')],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'dispatch_center_applicability_code' => Yii::t('app', 'Dispatch Center Applicability Code'),
            'dispatch_center_code' => Yii::t('app', 'Dispatch Center Code'),
            'dispatch_center_name' => Yii::t('app', 'Dispatch Center Name'),
            'applicable_code' => Yii::t('app', 'Applicable Code'),
            'applicable_for' => Yii::t('app', 'Applicable For'),
            'applicable_type' => Yii::t('app', 'Applicable Type'),
            'union_code' => Yii::t('app', 'Union Code'),
            'mcc_plant_code' => Yii::t('app', 'Mcc Plant Code'),
            'dcs_code' => Yii::t('app', 'Dcs Code'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'x_col1' => Yii::t('app', 'X Col1'),
            'x_col2' => Yii::t('app', 'X Col2'),
            'x_col3' => Yii::t('app', 'X Col3'),
            'x_col4' => Yii::t('app', 'X Col4'),
            'x_col5' => Yii::t('app', 'X Col5'),
        ];
    }

    public function getDispatchCenterTypeCode() {
        return $this->hasOne(TblDispatchCenterType::className(), ['dispatch_center_type_code' => 'dispatch_center_type_code']);
    }

    public function getCustomerType() {
        return $this->hasOne(TblCustomerType::className(), ['customer_type' => 'applicable_for', 'union_code' => 'union_code']);
    }

    public function getDcsCode() {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'applicable_code']);
    }

    public function allowDelete() {
        return true;
    }

    public function saveApplicabilityChild($model, &$modelSave, $appCode) {
        $users = User::find()->where(['dispatch_center_code' => $model->dispatch_center_code])->all();
        if (!empty($users)) {
            foreach ($users as $user) {
                $userOrgMapping = new TblUserOrganizationMapping();
                $userOrgMapping->organization_code = $appCode;
                $userOrgMapping->user_id = $user->id;
                $userOrgMapping->organization_type = 'DCS';
                $userOrgMapping->is_active = $user->is_active;
                $modelSave[] = $userOrgMapping;
            }
        }
    }

}
