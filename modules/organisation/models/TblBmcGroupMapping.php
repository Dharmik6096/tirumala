<?php

namespace app\modules\organisation\models;

use Yii;
use yii\helpers\ArrayHelper;

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
class TblBmcGroupMapping extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_bmc_group_mapping';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['bmc_code', 'p_bmc_code', 'created_by', 'updated_by'], 'string'],
            [['is_active'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['is_active'], 'default', 'value' => '1'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'bmc_mapping_code' => Yii::t('app', 'Bmc Mapping Code'),
            'bmc_code' => Yii::t('app', 'BMC Name'),
            'p_bmc_code' => Yii::t('app', 'BMC Code'),
            'is_active' => Yii::t('app', 'Is Active'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

    public function getBmcCode() {
        return $this->hasOne(TblDcsBmc::className(), ['bmc_code' => 'p_bmc_code']);
    }

    public function getMainBmcCode() {
        return $this->hasOne(TblDcsBmc::className(), ['bmc_code' => 'bmc_code']);
    }

    public function getTblDcsCode() {
        return $this->hasMany(TblDcs::className(), ['bmc_code' => 'p_bmc_code']);
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

    public function getBMC($bmcCode = [], $RLS = 'TRUE') {
        $query = $this->find()->select(['p_bmc_code'])->where(['bmc_code' => $bmcCode, 'is_active' => 1]);
        if (Yii::$app->session->get('BMC') !== '' && $RLS == 'TRUE') {
            $query->andWhere(['bmc_code' => explode(',', Yii::$app->session->get('BMC'))]);
        }
        if (Yii::$app->session->get('BMC') !== '' && $RLS == 'TRUE') {
            $query->andWhere(['p_bmc_code' => explode(',', Yii::$app->session->get('BMC'))]);
        }
        return $query->all();
    }

}
