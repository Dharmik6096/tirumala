<?php

namespace app\modules\geo\models;

use app\modules\organisation\models\TblUnions;
use app\modules\organisation\models\TblPlant;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "tbl_region".
 *
 * @property string $region_code
 * @property string $plant_code
 * @property string $region_name
 * @property string $local_name
 * @property string $address
 * @property string $local_address
 * @property string $description
 * @property integer $is_active
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $union_code
 *
 * @property TblUnions $unionCode
 * @property TblPlant $plantCode
 */
class TblRegion extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_region';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['address', 'region_name', 'state_code'], 'required'],
                [['region_name', 'local_name', 'address', 'local_address', 'description', 'created_by', 'updated_by', 'union_code'], 'string'],
                [['is_active'], 'integer'],
                [['address', 'region_name', 'state_code', 'created_at', 'updated_at'], 'safe'],
                [['union_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblUnions::className(), 'targetAttribute' => ['union_code' => 'union_code']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'region_name' => Yii::t('app', 'Region Name'),
            'state_code' => Yii::t('app', 'State Code'),
            'local_name' => Yii::t('app', 'Hindi Name'),
            'address' => Yii::t('app', 'Address'),
            'local_address' => Yii::t('app', 'Hindi Address'),
            'description' => Yii::t('app', 'Description'),
            'is_active' => Yii::t('app', 'Is Active'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'union_code' => Yii::t('app', 'Union'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

    public function getStateCode() {
        return $this->hasOne(TblStates::className(), ['state_code' => 'state_code']);
    }

    public function getStateRegionList($stateCode) {
        $query = $this->find()->select(['tbl_region.region_name', 'tbl_region.region_code'])
                ->leftJoin('tbl_states', 'tbl_region.state_code = tbl_states.state_code')
                ->where(['tbl_states.state_code' => $stateCode]);
        $data = $query->all();
        return ArrayHelper::map($data, 'region_code', 'region_name');
    }

}
