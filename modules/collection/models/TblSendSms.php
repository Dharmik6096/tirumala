<?php

namespace app\modules\collection\models;

use Yii;

/**
 * This is the model class for table "tbl_send_sms".
 *
 * @property integer $id
 * @property string $member_code
 * @property string $dcs_code
 * @property string $fat
 * @property string $snf
 * @property string $qty
 * @property string $amount
 * @property string $shift
 * @property string $date_time_of_collection
 * @property string $mobile
 * @property string $status
 */
class TblSendSms extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_send_sms';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['id'], 'integer'],
            [['member_code', 'dcs_code', 'shift', 'mobile', 'status'], 'string'],
            [['fat', 'snf', 'qty', 'amount','rate'], 'number'],
            [['date_time_of_collection'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'member_code' => Yii::t('app', 'Member Code'),
            'dcs_code' => Yii::t('app', 'Dcs Code'),
            'fat' => Yii::t('app', 'Fat'),
            'snf' => Yii::t('app', 'Snf'),
            'qty' => Yii::t('app', 'Qty'),
            'amount' => Yii::t('app', 'Amount'),
            'shift' => Yii::t('app', 'Shift'),
            'date_time_of_collection' => Yii::t('app', 'Date Time Of Collection'),
            'mobile' => Yii::t('app', 'Mobile'),
            'status' => Yii::t('app', 'Status'),
        ];
    }

    /**
     * @inheritdoc
     * @return TblSendSmsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TblSendSmsQuery(get_called_class());
    }
}
