<?php

namespace app\modules\webservice\eipl\models;

use Yii;
use yii\helpers\ArrayHelper;
use app\modules\details\models\TblContactDetails;

/**
 * This is the model class for table "tbl_app_organization_mapping".
 *
 * @property integer $mapping_id
 * @property integer $detail_code
 * @property string $mobile_no
 * @property string $organization_code
 * @property string $organization_type
 * @property integer $is_active
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 */
class TblAppOrganizationMapping extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_app_organization_mapping';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['detail_code'], 'required'],
                [['detail_code', 'is_active'], 'integer'],
                [['mobile_no', 'organization_code', 'organization_type', 'created_by', 'updated_by'], 'string'],
                [['created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'mapping_id' => Yii::t('app', 'Mapping ID'),
            'detail_code' => Yii::t('app', 'Detail Code'),
            'mobile_no' => Yii::t('app', 'Mobile No'),
            'organization_code' => Yii::t('app', 'Organization Code'),
            'organization_type' => Yii::t('app', 'Organization Type'),
            'is_active' => Yii::t('app', 'Is Active'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

    /**
     * @inheritdoc
     * @return TblAppOrganizationMappingQuery the active query used by this AR class.
     */
    public static function find() {
        return new TblAppOrganizationMappingQuery(get_called_class());
    }

    public function getData() {
        $list = $this->find()->select(['organization_code', 'organization_type'])->where(['mobile_no' => $this->mobile_no])->all();
        $data = ArrayHelper::map($list, 'organization_code', 'organization_code');
        return $data;
    }

    public function getActiveData() {
        return $this->find()
                        ->where(['mobile_no' => $this->mobile_no, 'organization_type' => $this->organization_type])
                        ->all();
    }

    public function getTblContactDetails() {
        return $this->hasOne(TblContactDetails::className(), ['detail_code' => 'detail_code']);
    }

}
