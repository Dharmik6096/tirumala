<?php

namespace app\modules\dcsoperation\models;

use Yii;
use webvimark\modules\UserManagement\models\User;
use app\modules\organisation\models\TblDcs;
use app\modules\globalmaster\models\TblMeetingType;
use app\modules\organisation\models\TblUnions;

/**
 * This is the model class for table "tbl_meeting_agenda".
 *
 * @property string $meeting_agenda_code
 * @property string $created_at
 * @property string $date
 * @property string $deleted_at
 * @property string $detailed_agenda
 * @property integer $is_active
 * @property integer $is_delete
 * @property string $meeting_date
 * @property string $subject_line
 * @property string $updated_at
 * @property string $created_by
 * @property string $dcs_code
 * @property string $deleted_by
 * @property integer $meeting_type_code
 * @property string $union_code
 * @property string $updated_by
 *
 * @property User $createdBy
 * @property TblDcs $dcsCode
 * @property User $deletedBy
 * @property TblMeetingType $meetingTypeCode
 * @property TblUnions $unionCode
 * @property User $updatedBy
 * @property TblMeetingAttendance[] $tblMeetingAttendances
 * @property TblMeetingAttendanceHistory[] $tblMeetingAttendanceHistories
 * @property TblMom[] $tblMoms
 * @property TblMomAction[] $tblMomActions
 * @property TblMomActionHistory[] $tblMomActionHistories
 * @property TblMomHistory[] $tblMomHistories
 */
class TblMeetingAgenda extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_meeting_agenda';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['meeting_agenda_code', 'subject_line', 'meeting_type_code'], 'required'],
            [['created_at', 'date', 'deleted_at',  'updated_at'], 'safe'],
//            [['is_active', 'is_delete', 'meeting_type_code'], 'integer'],
            [['meeting_agenda_code', 'detailed_agenda', 'meeting_date'], 'string', 'max' => 255],
            [['subject_line'], 'string', 'max' => 500],
            [['created_by', 'deleted_by', 'updated_by'], 'string', 'max' => 14],
            [['dcs_code'], 'string', 'max' => 9],
            [['union_code'], 'string', 'max' => 3],
            [['dcs_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblDcs::className(), 'targetAttribute' => ['dcs_code' => 'dcs_code']],
//            [['meeting_type_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblMeetingType::className(), 'targetAttribute' => ['meeting_type_code' => 'meeting_type_code']],
            [['union_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblUnions::className(), 'targetAttribute' => ['union_code' => 'union_code']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'meeting_agenda_code' => Yii::t('app', 'Meeting Agenda Code'),
            'created_at' => Yii::t('app', 'Created At'),
            'date' => Yii::t('app', 'Date'),
            'deleted_at' => Yii::t('app', 'Deleted At'),
            'detailed_agenda' => Yii::t('app', 'Detailed Agenda'),
            'is_active' => Yii::t('app', 'Is Active'),
            'is_delete' => Yii::t('app', 'Is Delete'),
            'meeting_date' => Yii::t('app', 'Meeting Date'),
            'subject_line' => Yii::t('app', 'Subject Line'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'dcs_code' => Yii::t('app', 'Dcs Code'),
            'deleted_by' => Yii::t('app', 'Deleted By'),
            'meeting_type_code' => Yii::t('app', 'Meeting Type'),
            'union_code' => Yii::t('app', 'Union'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDcsCode()
    {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'dcs_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDeletedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'deleted_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMeetingTypeCode()
    {
        return $this->hasOne(TblMeetingType::className(), ['meeting_type_code' => 'meeting_type_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUnionCode()
    {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblMeetingAttendances()
    {
        return $this->hasMany(TblMeetingAttendance::className(), ['meeting_agenda_code' => 'meeting_agenda_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblMeetingAttendanceHistories()
    {
        return $this->hasMany(TblMeetingAttendanceHistory::className(), ['meeting_agenda_code' => 'meeting_agenda_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblMoms()
    {
        return $this->hasMany(TblMom::className(), ['meeting_agenda_code' => 'meeting_agenda_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblMomActions()
    {
        return $this->hasMany(TblMomAction::className(), ['meeting_agenda_code' => 'meeting_agenda_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblMomActionHistories()
    {
        return $this->hasMany(TblMomActionHistory::className(), ['meeting_agenda_code' => 'meeting_agenda_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblMomHistories()
    {
        return $this->hasMany(TblMomHistory::className(), ['meeting_agenda_code' => 'meeting_agenda_code']);
    }

    /**
     * @inheritdoc
     * @return TblMeetingAgendaQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TblMeetingAgendaQuery(get_called_class());
    }
}
