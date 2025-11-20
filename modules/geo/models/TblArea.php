<?php

namespace app\modules\geo\models;

use app\modules\organisation\models\TblUnions;
use app\modules\organisation\models\TblPlant;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "tbl_area".
 *
 * @property string $area_code
 * @property string $plant_code
 * @property string $area_name
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
class TblArea extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_area';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['address', 'area_name', 'state_code'], 'required'],
                [['area_name', 'local_name', 'address', 'local_address', 'description', 'created_by', 'updated_by', 'union_code'], 'string'],
                [['is_active'], 'integer'],
                [['region_code', 'state_code', 'address', 'area_name', 'created_at', 'updated_at'], 'safe'],
                [['union_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblUnions::className(), 'targetAttribute' => ['union_code' => 'union_code']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'region_code' => Yii::t('app', 'Region Name'),
            'area_name' => Yii::t('app', 'Area Name'),
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

    public function getRegionCode() {
        return $this->hasOne(TblRegion::className(), ['region_code' => 'region_code']);
    }

    public function getMCCList($plantCode, $RLS = 'TRUE') {
        $value = $this->getMCC($plantCode, $RLS);
        $value = ArrayHelper::map($value, 'mcc_plant_code', function($value) {
                    return $value->name . ' - ' . $value->ref_code;
                });
        return $value;
    }

    public function getBMCList($plantCode, $RLS = 'TRUE', $hasBMC = false, $invert = false, $channelCode = [], $plant_bmc = []) {
        $value = $this->getBMC($plantCode, $RLS, $hasBMC, $channelCode, $plant_bmc);
        $value = ArrayHelper::map($value, 'bmc_code', function($value) use ($invert) {
                    return $invert ? $value->ref_code . ' - ' . $value->bmc_name : $value->bmc_name . ' - ' . $value->ref_code;
                });
        return $value;
    }

    public function getBMC($plantCode = [], $RLS = 'TRUE', $hasBMC = 1, $channelCode = [], $plant_bmc = []) {
        $query = $this->find()->select(['bmc_code', 'bmc_name', 'ref_code'])->where(['is_active' => 1]);
        if (!empty($plantCode)) {
            $query->andWhere(['mcc_plant_code' => $plantCode]);
        }
        if (!empty($channelCode)) {
            $query->andWhere(['x_col1' => $channelCode]);
        }
        if (Yii::$app->session->get('BMC') !== '' && $RLS == 'TRUE') {
            $query->andWhere(['bmc_code' => explode(',', Yii::$app->session->get('BMC'))]);
        }
        if (Yii::$app->session->get('Unions') !== '') {
            $query->andFilterWhere(['union_code' => explode(',', Yii::$app->session->get('Unions'))]);
        }
        if (Yii::$app->session->get('hasBMC') == 0) {
            $query->andFilterWhere(['is_mcc' => 1]);
        }
        if (!empty($plant_bmc)) {
            $query->andWhere(['plant_code' => $plant_bmc]);
        }
        return $query->orderby('bmc_name asc')->all();
    }

    public function getRegionAreaList($regionCode) {
        $query = $this->find()->select(['tbl_area.area_name', 'tbl_area.area_code'])
                ->leftJoin('tbl_region', 'tbl_area.region_code = tbl_region.region_code')
                ->where(['tbl_region.region_code' => $regionCode]);
        $data = $query->all();
        return ArrayHelper::map($data, 'area_code', 'area_name');
    }

}
