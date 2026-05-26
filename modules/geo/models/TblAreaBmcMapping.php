<?php

namespace app\modules\geo\models;

use Yii;
use yii\helpers\ArrayHelper;
use app\modules\organisation\models\TblDcsBmc;
use app\modules\organisation\models\TblDcs;

/**
 * This is the model class for table "tbl_bmc_group_mapping".
 *
 * @property integer $bmc_mapping_code
 * @property string $bmc_code
 * @property string $p_bmc_code
 * @property integer $is_active
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 */
class TblAreaBmcMapping extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_area_bmc_mapping';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['bmc_code', 'created_by', 'updated_by'], 'string'],
            [['is_active'], 'integer'],
            [['area_code', 'created_at', 'updated_at', 'applicable_code', 'applicable_type'], 'safe'],
            [['is_active'], 'default', 'value' => '1'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'area_bmc_mapping_code' => Yii::t('app', 'Area Bmc Mapping Code'),
            'area_code' => Yii::t('app', 'Area Code'),
            'area_name' => Yii::t('app', 'Area Name'),
            'bmc_code' => Yii::t('app', 'BMC Code'),
            'is_active' => Yii::t('app', 'Is Active'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

    public function getBmcCode() {
        return $this->hasOne(TblDcsBmc::className(), ['bmc_code' => 'bmc_code']);
    }

    public function getMainBmcCode() {
        return $this->hasOne(TblDcsBmc::className(), ['bmc_code' => 'bmc_code']);
    }

    public function getMainAreaCode() {
        return $this->hasOne(TblArea::className(), ['area_code' => 'area_code']);
    }

    public function getTblDcsCode() {
        return $this->hasMany(TblDcs::className(), ['bmc_code' => 'bmc_code']);
    }

    public function getTblDcs() {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'applicable_code']);
    }

    public function getBMCList($bmcCode, $RLS = 'TRUE', $hasBMC = false, $concateSelf = true) {
        $value = $this->getBMC($bmcCode, $RLS, $hasBMC);

        $value = ArrayHelper::map($value, 'p_bmc_code', function($value) {
                    return Yii::$app->general->getforeignkey($value->bmcCode, 'bmc_name') . ' - ' . Yii::$app->general->getforeignkey($value->bmcCode, 'ref_code');
                });
        if ($concateSelf) {
            $this->bmc_code = $bmcCode;
            $value[$bmcCode] = Yii::$app->general->getforeignkey($this->mainBmcCode, 'bmc_name') . ' - ' . Yii::$app->general->getforeignkey($this->mainBmcCode, 'ref_code');
        }
        return $value;
    }

    public function getAreaBmcList($areaCode) {
        $value = $this->getAreaBmc($areaCode);
        $value = ArrayHelper::map($value, 'bmc_code', 'bmc_name');
        return $value;
    }

    public function getAreaBmc($areaCode = []) {
        $query = TblDcsBmc::find()
                ->select(['tbl_bmc.bmc_name', 'tbl_bmc.bmc_code'])
                ->leftJoin('tbl_area_bmc_mapping', 'tbl_bmc.bmc_code = tbl_area_bmc_mapping.bmc_code')
                ->where(['tbl_area_bmc_mapping.area_code' => $areaCode]);
        return $query->all();
    }

}
