<?php

namespace app\modules\rmrd\models;

use Yii;
use app\modules\organisation\models\TblSocietyCodes;

/**
 * This is the model class for table "DPU_SERVER_LOG".
 *
 * @property integer $TransID
 * @property string $plantcode
 * @property string $bmccode
 * @property string $ppcode
 * @property string $imeino
 * @property string $TransType
 * @property string $date
 * @property string $time
 * @property string $IP
 * @property string $dputype
 * @property string $dpuversion
 * @property string $svrresponse
 * @property string $createddt
 * @property string $status
 */
class DPUSERVERLOG extends \app\models\ChildModel {

    public static function getDb() {
        return Yii::$app->get('db_reil'); // rmrd database
    }

    public $Date;
    public $dcs_code;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'DPU_SERVER_LOG';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['plantcode', 'bmccode', 'ppcode', 'imeino', 'TransType', 'time', 'IP', 'dputype', 'dpuversion', 'svrresponse', 'status'], 'string'],
            [['date', 'createddt'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'TransID' => Yii::t('app', 'Trans ID'),
            'plantcode' => Yii::t('app', 'Plantcode'),
            'bmccode' => Yii::t('app', 'Bmccode'),
            'ppcode' => Yii::t('app', 'Ppcode'),
            'imeino' => Yii::t('app', 'Imeino'),
            'TransType' => Yii::t('app', 'Trans Type'),
            'date' => Yii::t('app', 'Date'),
            'time' => Yii::t('app', 'Time'),
            'IP' => Yii::t('app', 'Ip'),
            'dputype' => Yii::t('app', 'Dputype'),
            'dpuversion' => Yii::t('app', 'Dpuversion'),
            'svrresponse' => Yii::t('app', 'Svrresponse'),
            'createddt' => Yii::t('app', 'Createddt'),
            'status' => Yii::t('app', 'Status'),
        ];
    }

    /**
     * @inheritdoc
     * @return DPUSERVERLOGQuery the active query used by this AR class.
     */
    public static function find() {
        return new DPUSERVERLOGQuery(get_called_class());
    }

    public function getDcsCode() {
        return $this->hasOne(TblSocietyCodes::className(), ['pooling_point_code' => 'ppcode', 'bmc_code' => 'bmccode']);
    }

    public function getRecord() {
        return $this->find()->select(['ppcode', 'bmccode', "Convert(nvarchar(50),date)+' '+Convert(nvarchar(50),time) As Date"])
                        ->where(['TransType' => '3', 'date' => date('Y-m-d')])->all();
    }

}
