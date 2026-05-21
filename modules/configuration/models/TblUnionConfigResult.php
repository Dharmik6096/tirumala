<?php

namespace app\modules\configuration\models;

use Yii;
use yii\caching\TagDependency;

/**
 * This is the model class for table "tbl_union_config_result".
 *
 * @property integer $config_txn_code
 * @property integer $config_code
 * @property string $config_name
 * @property string $config_key
 * @property integer $config_result_code
 * @property string $config_result_key
 * @property string $config_result
 * @property string $union_code
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
class TblUnionConfigResult extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_union_config_result';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['config_code', 'config_result_code', 'originating_type'], 'safe'],
            [['config_name', 'config_key', 'config_result_key', 'config_result', 'union_code', 'created_by', 'updated_by', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'string'],
            [['created_at', 'updated_at', 'config_for'], 'safe'],
            [['config_result_key'], 'required'],
            [['config_result_key'], 'validateMinMaxValue']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'config_txn_code' => Yii::t('app', 'Config Txn Code'),
            'config_code' => Yii::t('app', 'Config Code'),
            'config_name' => Yii::t('app', 'Config Name'),
            'config_key' => Yii::t('app', 'Config Key'),
            'config_result_code' => Yii::t('app', 'Config Result Code'),
            'config_result_key' => Yii::t('app', 'Config Value'),
            'config_result' => Yii::t('app', 'Config Result'),
            'union_code' => Yii::t('app', 'Union Code'),
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

    public function getConfigCode() {
        return $this->hasOne(TblConfig::className(), ['config_code' => 'config_code']);
    }

    public function getConfigList() {
        return $this->find()->select(['tbl_union_config_result.config_key', 'tbl_union_config_result.config_result_key', 'tbl_union_config_result.config_for'])
            ->join('INNER JOIN', 'tbl_config', 'tbl_config.config_code=tbl_union_config_result.config_code')
            ->where(['tbl_union_config_result.union_code' => $this->union_code, 'tbl_config.config_for' => $this->config_for])
            ->asArray()
            ->all();
    }

    public function getConfigResultCode() {
        return $this->hasOne(TblConfigResult::className(), ['config_code' => 'config_code', 'config_result_key' => 'config_result_key']);
    }

    public function getConfigResult() {
        return $this->hasOne(TblConfigResult::className(), ['config_code' => 'config_code']);
    }

    public function getExistConfig() {
        return $this->find()->where(['config_code' => $this->config_code, 'config_for' => $this->config_for, 'union_code' => $this->union_code])->one();
    }

    public function getType($code) {
        $model = new TblConfigResult();
        $data = $model->find()->where(['config_code' => $code, 'is_active' => 1])->all();
        return $data;
    }

    protected $configRanges = [
        'portal_max_login_attempts' => ['min' => 3, 'max' => 5],
        'portal_login_suspension_time' => ['min' => 5, 'max' => 30],
        'portal_otp_suspension_time' => ['min' => 5, 'max' => 30],
    ];

    public function validateMinMaxValue($attribute, $params) {
        if (isset($this->configRanges[$this->config_key])) {
            $min = $this->configRanges[$this->config_key]['min'];
            $max = $this->configRanges[$this->config_key]['max'];
            $value = $this->$attribute;

            if (floor($value) != $value) {
                $this->addError($attribute, Yii::t('app/validation', $this->getAttributeLabel($attribute) . ' (' . $this->config_name . ') must be an integer.'));
                return;
            }
            if ($value < $min || $value > $max) {
                $this->addError($attribute, Yii::t('app/validation', $this->getAttributeLabel($attribute) . ' (' . $this->config_name . ') must be between ' . $min . ' and ' . $max . '.'));
                return;
            }
        }
    }

    public function afterSave($insert, $changedAttributes) {
        parent::afterSave($insert, $changedAttributes);
        if ($this->union_code) {
            $configCacheKey = 'bmc_dispatch_config_union_' . $this->union_code;
            Yii::$app->general->removeRedisCache($configCacheKey);
        }
    }
}
