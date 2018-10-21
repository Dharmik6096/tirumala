<?php

namespace app\modules\creamy\models;

use Yii;

/**
 * This is the model class for table "tbl_MA_SerialNo".
 *
 * @property integer $id
 * @property string $BMCCode
 * @property string $PPCode
 * @property string $DtDate
 * @property string $shift
 * @property string $serialno
 * @property string $updatedby
 * @property string $updateddate
 * @property integer $data_post_status
 */
class TblMASerialNoCreamy extends \yii\db\ActiveRecord {

    public static function getDb() {
        return Yii::$app->get('db_creamy'); // second database
    }

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_MA_SerialNo';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['BMCCode', 'PPCode', 'DtDate', 'serialno', 'updatedby'], 'required'],
            [['BMCCode', 'PPCode', 'shift', 'serialno', 'updatedby'], 'string'],
            [['DtDate', 'updateddate'], 'safe'],
            [['data_post_status'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'BMCCode' => Yii::t('app', 'Bmccode'),
            'PPCode' => Yii::t('app', 'Ppcode'),
            'DtDate' => Yii::t('app', 'Dt Date'),
            'shift' => Yii::t('app', 'Shift'),
            'serialno' => Yii::t('app', 'Serialno'),
            'updatedby' => Yii::t('app', 'Updatedby'),
            'updateddate' => Yii::t('app', 'Updateddate'),
            'data_post_status' => Yii::t('app', 'Data Post Status'),
        ];
    }

}
