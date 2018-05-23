<?php

namespace app\modules\setting\models;

use Yii;
use app\modules\organisation\models\TblDcs;
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
class TblDpuPasswords extends \app\models\ChildModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tblDPUPasswords';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['dcs_code', 'PPCode', 'bmc_code', 'mcc_code', 'AdminPwd', 'SuperPwd', 'UserPwd'], 'safe'],
            [['AdminPwd', 'SuperPwd', 'UserPwd'], 'string', 'min' => 7],
            [['AdminPwd', 'SuperPwd', 'UserPwd'], 'string', 'max' => 7],
            [['AdminPwd', 'SuperPwd', 'UserPwd'], 'number'],
            [['dcs_code', 'PPCode', 'bmc_code', 'mcc_code', 'AdminPwd', 'SuperPwd', 'UserPwd', 'modifiedby'], 'string'],
            [['lastmodified'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
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
    
    public function getDpuDetails(){
        return $this->find()->where(['dcs_code' => $this->dcs_code])->one();
    }
}
