<?php

namespace app\modules\setting\models;

use Yii;
use app\modules\organisation\models\TblDcs;
use app\modules\organisation\models\TblDcsBmc;

/**
 * This is the model class for table "tblDPUPasswords".
 *
 * @property string $dcs_code
 * @property string $PPCode
 * @property string $bmc_code
 * @property string $mcc_code
 * @property string $AdminPwd
 * @property string $SuperPwd
 * @property string $UserPwd
 * @property string $lastmodified
 * @property string $modifiedby
 */
class TblDpuPasswords extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tblDPUPasswords';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['dcs_code', 'PPCode', 'bmc_code', 'mcc_code', 'AdminPwd', 'SuperPwd', 'UserPwd'], 'safe'],
            [['AdminPwd', 'SuperPwd', 'UserPwd'], 'trim'],
            [['AdminPwd', 'SuperPwd', 'UserPwd'], 'string'],
            [['AdminPwd', 'SuperPwd', 'UserPwd'], 'number'],
            [['dcs_code', 'PPCode', 'bmc_code', 'mcc_code', 'AdminPwd', 'SuperPwd', 'UserPwd', 'modifiedby'], 'string'],
            [['lastmodified'], 'safe'],
            [['dcs_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblDcs::className(), 'targetAttribute' => ['dcs_code' => 'dcs_code'], 'on' => ['importCsv']],
            [['dcs_code', 'AdminPwd', 'SuperPwd', 'UserPwd'], 'required', 'on' => 'importCsv'],
            [['dcs_code'], 'setImportFileds', 'skipOnError' => true, 'on' => 'importCsv'],
            [['dcs_code'], 'validatePassword'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'dcs_code' => Yii::t('app', 'DCS Code'),
            'PPCode' => Yii::t('app', 'Ppcode'),
            'bmc_code' => Yii::t('app', 'Bmc Code'),
            'mcc_code' => Yii::t('app', 'Mcc Code'),
            'AdminPwd' => Yii::t('app', 'Admin'),
            'SuperPwd' => Yii::t('app', 'Super'),
            'UserPwd' => Yii::t('app', 'UserP'),
            'lastmodified' => Yii::t('app', 'Lastmodified'),
            'modifiedby' => Yii::t('app', 'Modifiedby'),
        ];
    }

    public function getDcsCode() {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'dcs_code']);
    }

    public function getDpuDetails() {
        return $this->find()->where(['dcs_code' => $this->dcs_code])->one();
    }

    public function setImportFileds() {
        $this->bmc_code = Yii::$app->general->getforeignkey($this->dcsCode, 'bmc_code');
        $this->PPCode = substr($this->dcs_code, -3);
        $this->mcc_code = Yii::$app->general->getforeignkey($this->dcsCode, 'mcc_plant_code');
        $this->lastmodified = date('Y-m-d H:i:s');
        $this->modifiedby = isset(\Yii::$app->user->identity->user_code) ? \Yii::$app->user->identity->user_code : null;
    }

    public function getBmcCode() {
        return $this->hasOne(TblDcsBmc::className(), ['bmc_code' => 'bmc_code']);
    }

    public function validatePassword($attribute, $params){
        if(count(array_filter(array($this->AdminPwd,$this->SuperPwd,$this->UserPwd))) != 0){
            if(strlen($this->AdminPwd) != 7){
                $this->addError('AdminPwd', Yii::t('app/validation', Yii::$app->general->getforeignkey($this->dcsCode, 'dcs_name').' '.Yii::t('app', 'Admin').' Password must be of 7 characters.'));
            }
            if(strlen($this->SuperPwd) != 7){
                $this->addError('SuperPwd', Yii::t('app/validation', Yii::$app->general->getforeignkey($this->dcsCode, 'dcs_name').' '.Yii::t('app', 'Super').' Password must be of 7 characters.'));
            }
            if(strlen($this->UserPwd) != 7){
                $this->addError('UserPwd', Yii::t('app/validation', Yii::$app->general->getforeignkey($this->dcsCode, 'dcs_name').' '.Yii::t('app', 'UserP').' Password must be of 7 characters.'));
            }
        }
        // var_dump($this);
        // die;
    }
}
