<?php

namespace app\modules\configuration\models;

use Yii;
use yii\helpers\ArrayHelper;
use app\modules\organisation\models\TblDcsBmc;
use app\modules\organisation\models\TblMccPlant;
use app\modules\syncutility\models\TblSentbox;

/**
 * This is the model class for table "tbl_config_mapping".
 *
 * @property integer $config_mapping_code
 * @property integer $config_code
 * @property string $config_result
 * @property string $org_type
 * @property string $org_code
 * @property string $union_code
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property integer $originating_type
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property string $x_col1
 * @property string $x_col2
 * @property string $x_col3
 * @property string $x_col4
 * @property string $x_col5
 */
class TblConfigMapping extends \app\models\ChildModel {

    public $config_for, $process_name;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_config_mapping';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['config_code', 'originating_type'], 'integer'],
            [['config_result', 'org_type', 'org_code', 'union_code', 'created_by', 'updated_by', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'string'],
            [['created_at', 'updated_at', 'plant_code', 'mcc_plant_code', 'bmc_code', 'process_name', 'config_for'], 'safe'],
            [['plant_code', 'mcc_plant_code', 'union_code'], 'required', 'except' => ['savemapping']],
            [['bmc_code'], 'required', 'when' => function ($model) {
                    return $model->config_for == 'BMC';
                }, 'whenClient' => "function (attribute, value) {
              return $('#tblconfigsearch-config_for').val() == 'BMC';
          }"],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'config_mapping_code' => Yii::t('app', 'Config Mapping Code'),
            'config_code' => Yii::t('app', 'Config Code'),
            'config_result' => Yii::t('app', 'Config Result'),
            'org_type' => Yii::t('app', 'Org Type'),
            'org_code' => Yii::t('app', 'Org Code'),
            'union_code' => Yii::t('app', 'Union'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'x_col1' => Yii::t('app', 'X Col1'),
            'x_col2' => Yii::t('app', 'X Col2'),
            'x_col3' => Yii::t('app', 'X Col3'),
            'x_col4' => Yii::t('app', 'X Col4'),
            'x_col5' => Yii::t('app', 'X Col5'),
            'plant_code' => Yii::t('app', 'Plant'),
            'mcc_plant_code' => Yii::t('app', 'MCC'),
            'bmc_code' => Yii::t('app', 'BMC'),
        ];
    }

    public function getExistingMapping() {
        $model = new TblConfig();
        $config = $model->find()
                ->select('config_code')
                ->where(['config_for' => $this->org_type, 'process_name' => $this->process_name])
                ->all();
        if (!empty($config)) {
            $codes = [];
            foreach ($config as $code) {
                $codes[] = $code->config_code;
            }

            $code = $this->org_type == 'BMC' ? $this->bmc_code : $this->mcc_plant_code;
            $query = $this->find()
                    ->where(['IN', 'config_code', $codes])
                    ->andWhere(['org_type' => $this->org_type, 'org_code' => $code])
                    ->all();
            return ArrayHelper::map($query, 'config_code', 'config_code');
        } else {
            return [];
        }
    }

    public function getExistMappedControl() {
        return $this->find()
                        ->where(['org_type' => $this->org_type, 'org_code' => $this->org_code, 'config_code' => $this->config_code])
                        ->one();
    }

    public function afterSave($insert, $changedAttributes) {
        $sentboxArray = [];
        $bmc_code = $mcc_code = $plant_code = '';
        if (strtolower($this->org_type) == 'bmc') {
            $bmc_code = $this->bmc_code;
        }
        $mcc_code = $this->mcc_plant_code;
        $plant_code = $this->plant_code;
        $sentboxArray = Yii::$app->general->getSentBoxCodes($plant_code, $mcc_code, $bmc_code, '', '', false);
        foreach ($sentboxArray as $sent) {
            $flag = (isset($this->operation) && $this->operation == true) ? $this->operation : ($insert) ? 'INSERT' : 'UPDATE';
            $sentbox = $this->sentboxModel($sent['code'], $sent['type']);
            if (!isset($this->is_sentbox) || (isset($this->is_sentbox) && $this->is_sentbox === TRUE)) {
                if (!($sentbox->setSentbox($this, $flag))) {
                    throw new UserException("SentBox Entry is not created so transaction is rollback!");
                }
            }
        }
    }

    public function afterDelete() {
        $sentboxArray = [];
        $bmc_code = $mcc_code = $plant_code = '';
        if (strtoupper($this->org_type) == 'BMC') {
            $bmc_code = $this->org_code;
            $mcc_code = Yii::$app->general->getforeignkey($this->bmcCode, 'mcc_plant_code');
            $plant_code = Yii::$app->general->getforeignkey($this->bmcCode, 'plant_code');
        } else {
            $mcc_code = $this->org_code;
            $plant_code = Yii::$app->general->getforeignkey($this->mccCode, 'plant_code');
        }
        $sentboxArray = Yii::$app->general->getSentBoxCodes($plant_code, $mcc_code, $bmc_code, '', '', false);
        foreach ($sentboxArray as $sent) {
            $sentbox = $this->sentboxModel($sent['code'], $sent['type']);
            if (!isset($this->is_sentbox) || (isset($this->is_sentbox) && $this->is_sentbox === TRUE)) {
                if (!($sentbox->setSentbox($this, 'DELETE'))) {
                    throw new UserException("SentBox Entry is not created so transaction is rollback!");
                }
            }
        }
    }

    private function sentboxModel($code, $type) {
        $sentbox = new TblSentbox();
        $sentbox->dest_org_id = $code;
        $sentbox->source_org_id = $this->union_code;
        $sentbox->dest_org_type = $type;
        return $sentbox;
    }

    public function getBmcCode() {
        return $this->hasOne(TblDcsBmc::className(), ['bmc_code' => 'org_code']);
    }

    public function getMccCode() {
        return $this->hasOne(TblMccPlant::className(), ['mcc_plant_code' => 'org_code']);
    }

}
