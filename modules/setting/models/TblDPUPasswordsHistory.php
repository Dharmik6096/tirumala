<?php

namespace app\modules\setting\models;

use Yii;

/**
 * This is the model class for table "tblDPUPasswordsHistory".
 *
 * @property integer $id
 * @property string $dcs_code
 * @property string $PPCode
 * @property string $bmc_code
 * @property string $mcc_code
 * @property string $AdminPwd
 * @property string $SuperPwd
 * @property string $UserPwd
 * @property string $lastmodified
 * @property string $modifiedby
 * @property string $operation_type
 * @property string $history_created_at
 */
class TblDPUPasswordsHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tblDPUPasswordsHistory';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['dcs_code', 'PPCode', 'bmc_code', 'mcc_code', 'AdminPwd', 'SuperPwd', 'UserPwd', 'modifiedby', 'operation_type'], 'safe'],
            [['lastmodified', 'history_created_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'dcs_code' => Yii::t('app', 'Dcs Code'),
            'PPCode' => Yii::t('app', 'Ppcode'),
            'bmc_code' => Yii::t('app', 'Bmc Code'),
            'mcc_code' => Yii::t('app', 'Mcc Code'),
            'AdminPwd' => Yii::t('app', 'Admin Pwd'),
            'SuperPwd' => Yii::t('app', 'Super Pwd'),
            'UserPwd' => Yii::t('app', 'User Pwd'),
            'lastmodified' => Yii::t('app', 'Lastmodified'),
            'modifiedby' => Yii::t('app', 'Modifiedby'),
            'operation_type' => Yii::t('app', 'Operation Type'),
            'history_created_at' => Yii::t('app', 'History Created At'),
        ];
    }

}
