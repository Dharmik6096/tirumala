<?php
namespace app\modules\sms\models;

use app\models\ChildModel;
use app\modules\organisation\models\TblDcs;
use app\modules\organisation\models\TblUnions;
use webvimark\modules\UserManagement\models\User;
use Yii;

/**
 * This is the model class for table "tbl_message".
 *
 * @property string|null $union_code
 * @property string|null $plant_code
 * @property string|null $mcc_plant_code
 * @property string|null $bmc_code
 * @property string|null $dcs_code
 * @property string $message_code
 * @property string|null $created_at
 * @property string|null $created_by
 * @property int|null $flg_sentbox_entry
 * @property string|null $from_date
 * @property int|null $is_active
 * @property int|null $is_delete
 * @property string $message
 * @property string|null $message_local
 * @property string|null $sync_status
 * @property string|null $sync_timestamp
 * @property string|null $to_date
 * @property string|null $updated_at
 * @property string|null $updated_by
 * @property int|null $for_shift_code
 * @property int|null $from_shift
 * @property int|null $to_shift
 * @property string|null $originating_org_code
 * @property string|null $originating_org_type
 * @property int|null $originating_type
 * @property string|null $x_col1
 * @property string|null $x_col2
 * @property string|null $x_col3
 * @property string|null $x_col4
 * @property string|null $x_col5
 */
class TblMessage extends ChildModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tbl_message';
    }
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['message_code'], 'required', 'on' => ['androidsync']],
            [['union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'message_code', 'created_at', 'created_by', 'from_date', 'is_active', 'is_delete', 'message', 'message_local', 'sync_status', 'sync_timestamp', 'to_date', 'updated_at', 'updated_by', 'for_shift_code', 'from_shift', 'to_shift', 'originating_org_code', 'originating_org_type', 'originating_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'union_code' => Yii::t('app', 'Union'),
            'plant_code' => Yii::t('app', 'Plant'),
            'mcc_plant_code' => Yii::t('app', 'MCC'),
            'bmc_code' => Yii::t('app', 'BMC'),
            'dcs_code' => Yii::t('app', 'DCS'),
            'message_code' => Yii::t('app', 'Message Code'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'from_date' => Yii::t('app', 'From Date'),
            'is_active' => Yii::t('app', 'Is Active'),
            'is_delete' => Yii::t('app', 'Is Delete'),
            'message' => Yii::t('app', 'Message'),
            'message_local' => Yii::t('app', 'Message Local'),
            'sync_status' => Yii::t('app', 'Sync Status'),
            'sync_timestamp' => Yii::t('app', 'Sync Timestamp'),
            'to_date' => Yii::t('app', 'To Date'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'for_shift_code' => Yii::t('app', 'For Shift Code'),
            'from_shift' => Yii::t('app', 'From Shift'),
            'to_shift' => Yii::t('app', 'To Shift'),
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

    public function getUnionCode()
    {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

    public function getDcsCode()
    {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'dcs_code']);
    }

    public function getUserCode()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

}
