<?php

namespace app\modules\configuration\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "tbl_config".
 *
 * @property integer $config_code
 * @property string $config_name
 * @property string $config_key
 * @property string $config_for
 */
class TblConfig extends \app\models\ChildModel {

    public $union_code;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_config';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['config_name', 'config_key', 'config_for'], 'string'],
            [['union_code'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'config_code' => Yii::t('app', 'Config Code'),
            'config_name' => Yii::t('app', 'Config Name'),
            'config_key' => Yii::t('app', 'Config Key'),
            'config_for' => Yii::t('app', 'Application'),
            'union_code' => Yii::t('app', 'Union'),
        ];
    }

    public function configForList() {
        $data = $this->find()
                ->distinct()
                ->all();
        $array = \yii\helpers\ArrayHelper::map($data, 'config_for', 'config_for');
        return $array;
    }

    public function getConfigData($code) {
        $data = $this->find()
                ->where(['config_for' => $code])
                ->andWhere(['IS', 'config_type', NULL])
                ->all();
        return $data;
    }

    public function getConfigResult() {
        return $this->hasMany(TblConfigResult::className(), ['config_code' => 'config_code']);
    }

    public function getConfigMapping() {
        return $this->hasMany(TblConfigMapping::className(), ['config_code' => 'config_code']);
    }

    public function getOrgConfigList($org_type, $org_code) {
        return $this->find()->distinct()
                        ->joinWith(['configResult', 'configMapping'])
                        ->where(['tbl_config.config_for' => $this->config_for, 'tbl_config.process_name' => $this->process_name, 'tbl_config.config_type' => $this->config_type])
                        ->andWhere(['tbl_config_mapping.org_type' => $org_type, 'tbl_config_mapping.org_code' => $org_code, 'tbl_config_result.is_active' => 1])
                        ->orderby(['tbl_config.config_code' => SORT_ASC])
                        ->all();
    }

    public function prepareControl($form, $config, $index) {
        $config_data = ArrayHelper::map($this->configResult, 'config_result_key', 'config_result');
        $config->config_result = '0';
        if ($this->control_type == 'RADIO') {
            return $form->field($config, '[' . $index . ']config_result')->inline()->radioList($config_data)->label(Yii::t('app', $this->config_name));
        } else if ($this->control_type == 'DROPDOWN') {
            return $form->field($config, '[' . $index . ']config_result')->dropDownList($config_data)->label(Yii::t('app', $this->config_name));
        } else if ($this->control_type == 'CHECKBOX') {
            return $form->field($config, '[' . $index . ']config_result', ['checkboxTemplate' => '<div class="checkbox mt25 height_65">{input}{beginLabel}{labelTitle}{endLabel}</div>{error}{hint}'])->checkbox()->label(Yii::t('app', $this->config_name));
        } else {
            return $form->field($config, '[' . $index . ']config_result')->textInput()->label(Yii::t('app', $this->config_name));
        }
    }

}
