<?php

namespace app\modules\dcsoperation\models;

use Yii;
use yii\data\ActiveDataProvider;
use webvimark\modules\UserManagement\models\User;

/**
 * This is the model class for table "tbl_meeting_attendance".
 *
 * @property integer $meeting_attendance_id
 * @property string $absent_reason
 * @property string $created_at
 * @property string $deleted_at
 * @property integer $is_active
 * @property integer $is_delete
 * @property integer $is_present
 * @property string $updated_at
 * @property string $created_by
 * @property string $deleted_by
 * @property string $meeting_agenda_code
 * @property string $member_code
 * @property string $updated_by
 *
 * @property User $createdBy
 * @property User $deletedBy
 * @property TblMeetingAgenda $meetingAgendaCode
 * @property TblMember $memberCode
 * @property User $updatedBy
 */
class TblMeetingAttendance extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_meeting_attendance';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at', 'deleted_at','updated_at','member_code'], 'safe'],
            [['is_active', 'is_delete', 'is_present'], 'integer'],
            [['absent_reason', 'meeting_agenda_code'], 'string', 'max' => 255],
            [['created_by', 'deleted_by', 'updated_by'], 'string', 'max' => 14],
//            /[['member_code'], 'string', 'max' => 11],
            [['meeting_agenda_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblMeetingAgenda::className(), 'targetAttribute' => ['meeting_agenda_code' => 'meeting_agenda_code']],
//            [['member_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblMember::className(), 'targetAttribute' => ['member_code' => 'member_code']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'meeting_attendance_id' => Yii::t('app', 'Meeting Attendance ID'),
            'absent_reason' => Yii::t('app', 'Absent Reason'),
            'created_at' => Yii::t('app', 'Created At'),
            'deleted_at' => Yii::t('app', 'Deleted At'),
            'is_active' => Yii::t('app', 'Is Active'),
            'is_delete' => Yii::t('app', 'Is Delete'),
            'is_present' => Yii::t('app', 'Present'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'deleted_by' => Yii::t('app', 'Deleted By'),
            'meeting_agenda_code' => Yii::t('app', 'Meeting Agenda'),
            'member_code' => Yii::t('app', 'Member'),
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
    public function getDeletedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'deleted_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMeetingAgendaCode()
    {
        return $this->hasOne(TblMeetingAgenda::className(), ['meeting_agenda_code' => 'meeting_agenda_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMemberCode()
    {
        return $this->hasOne(TblMember::className(), ['member_code' => 'member_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }

    /**
     * @inheritdoc
     * @return TblMeetingAttendanceQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TblMeetingAttendanceQuery(get_called_class());
    }
    
    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = TblMeetingAttendance::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        $query->joinWith(['meetingAgendaCode','memberCode']);
        
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'tbl_meeting_attendance.is_active' => $this->is_active,
            'is_delete' => $this->is_delete,
            'is_present' => $this->is_present,
        ]);

        $query->andFilterWhere(['like', 'absent_reason', $this->absent_reason])
            ->andFilterWhere(['like', 'tbl_meeting_attendance.meeting_agenda_code', $this->meeting_agenda_code])
            ->andFilterWhere(['like', 'tbl_member.member_name', $this->member_code]);

        return $dataProvider;
    }
}
