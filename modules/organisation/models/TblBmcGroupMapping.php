<?php

namespace app\modules\organisation\models;

use Yii;

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

}
