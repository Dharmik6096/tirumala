<?php

namespace app\modules\configuration\models;

use Yii;
use yii\helpers\ArrayHelper;
use app\modules\tankermovement\models\TblConfigTxnResult;

/**
 * This is the model class for table "tbl_config".
 *
 * @property integer $config_code
 * @property string $config_name
 * @property string $config_key
 * @property string $config_for
 */
class TblConfig extends \app\models\ChildModel {

    public $union_code, $plant_code, $mcc_plant_code, $bmc_code;

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
                [['union_code', 'plant_code', 'process_name'], 'required', 'on' => ['PaymentConfig']],
                [['union_code', 'plant_code', 'mcc_plant_code', 'bmc_code'], 'safe'],
                [['bmc_code', 'mcc_plant_code'], 'required', 'when' => function($model) {
                    return $model->config_for == 'BMC';
                }, 'whenClient' => "function (attribute, value) { 
                        return $('#tblconfig-config_for').val() == 'BMC'; 
                    }"],
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
            'plant_code' => Yii::t('app', 'Plant'),
            'mcc_plant_code' => Yii::t('app', 'MCC'),
            'bmc_code' => Yii::t('app', 'BMC'),
        ];
    }

    public function configForList($notin) {
        $data = $this->find()
                ->andWhere(['NOT IN', 'config_for', $notin])
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

    public function getControlConfigList() {
        return $this->find()
                        ->where(['tbl_config.config_for' => $this->config_for, 'tbl_config.process_name' => $this->process_name, 'tbl_config.config_type' => $this->config_type])
                        ->orderby(['tbl_config.seq_no' => SORT_ASC])
                        ->all();
    }

    public function getOrgConfigList($org_type, $org_code) {
        return $this->find()->distinct()
                        ->joinWith(['configResult', 'configMapping'])
                        ->where(['tbl_config.config_for' => $this->config_for, 'tbl_config.process_name' => $this->process_name, 'tbl_config.config_type' => $this->config_type])
                        ->andWhere(['tbl_config_mapping.org_type' => $org_type, 'tbl_config_mapping.org_code' => $org_code, 'tbl_config_result.is_active' => 1])
                        ->orderby(['tbl_config.seq_no' => SORT_ASC])
                        ->all();
    }

    public function prepareControl($form, $config, $index) {
        $config_data = ArrayHelper::map($this->configResult, 'config_result_key', 'config_result');
        $config->config_result = empty($config->config_result) ? '0' : $config->config_result;
        if ($this->control_type == 'RADIO') {
            return $form->field($config, '[' . $index . ']config_result')->inline()->radioList($config_data, ['itemOptions' => ['class' => 'custom-radio-class']])->label(Yii::t('app', $this->config_name));
        } else if ($this->control_type == 'DROPDOWN') {
            return $form->field($config, '[' . $index . ']config_result')->dropDownList($config_data, ['class' => 'form-control config_class', 'prompt' => Yii::t('app', 'Select')])->label(Yii::t('app', $this->config_name));
        } else if ($this->control_type == 'CHECKBOX') {
            return $form->field($config, '[' . $index . ']config_result', ['checkboxTemplate' => '<div class="checkbox mt25 height_65">{input}{beginLabel}{labelTitle}{endLabel}</div>{error}{hint}'])->checkbox()->label(Yii::t('app', $this->config_name));
        } else {
            $options = ($this->control_type == 'NUMERIC') ? ['class' => 'form-control number-validate'] : [];
            return $form->field($config, '[' . $index . ']config_result')->textInput($options)->label(Yii::t('app', $this->config_name));
        }
    }

    public function getProcessList($configFor, $inputAllow = '') {
        $data = $this->find()->select(['process_name', 'config_for'])
                ->distinct()
                ->where(['config_for' => $configFor])
                ->andWhere(['IS NOT', 'process_name', NULL]);
        if ($inputAllow == '0') {
            $data->andWhere(['or', ['!=', 'is_input_config', 1], ['is', 'is_input_config', NULL]]);
        } else if ($inputAllow == '1') {
            $data->andWhere(['is_input_config' => 1]);
        }
        $data = $data->all();
        if ($inputAllow == '1') {
            $array = \yii\helpers\ArrayHelper::map($data, function ($value) {
                        return $value->process_name . '##' . $value->config_for;
                    }, 'process_name');
        } else {
            $array = \yii\helpers\ArrayHelper::map($data, 'process_name', 'process_name');
        }
        return $array;
    }

    public function getConfigDetail() {
        $data = $this->find()
                ->where(['config_for' => $this->config_for, 'config_type' => $this->config_type, 'process_name' => $this->process_name, 'is_input_config' => $this->is_input_config])
                ->all();
        return $data;
    }

    public function getConfigList() {
        return $this->find()->distinct()
                        ->joinWith(['configResult'])
                        ->where(['tbl_config.config_for' => $this->config_for, 'tbl_config.process_name' => $this->process_name, 'tbl_config.config_type' => $this->config_type])
                        ->andWhere(['tbl_config_result.is_active' => 1])
                        ->orderby(['tbl_config.seq_no' => SORT_ASC])
                        ->all();
    }

    public function getconfigResultTxn() {
        return $this->hasOne(TblConfigTxnResult::className(), ['config_code' => 'config_code']);
    }

    public function getConfigResultTxnList($ref_code) {
        return TblConfigTxnResult::find()->select('tbl_config_txn_result.config_result')
                        ->join('inner join', 'tbl_config', 'tbl_config.config_code = tbl_config_txn_result.config_code')
                        ->where(['tbl_config.config_for' => $this->config_for, 'tbl_config.process_name' => $this->process_name, 'tbl_config.config_type' => $this->config_type])
                        ->andWhere(['tbl_config_txn_result.config_code' => $this->config_code, 'tbl_config_txn_result.ref_code' => $ref_code])
                        ->orderby(['tbl_config.seq_no' => SORT_ASC])
                        ->one();
    }

}
