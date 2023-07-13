<?php

namespace app\modules\dcsoperation\models;

use Yii;
use yii\data\ActiveDataProvider;
use app\modules\usermanagement\models\User;
use app\modules\globalmaster\models\TblMeetingType;

use app\modules\organisation\models\TblDcs;
/**
 * This is the model class for table "tbl_mom".
 *
 * @property integer $mom_code
 * @property string $created_at
 * @property string $deleted_at
 * @property integer $is_active
 * @property integer $is_delete
 * @property string $mom
 * @property integer $status
 * @property string $updated_at
 * @property string $created_by
 * @property string $deleted_by
 * @property string $meeting_agenda_code
 * @property integer $meeting_type_code
 * @property integer $dcs_code
 * @property string $updated_by
 *
 * @property User $createdBy
 * @property User $deletedBy
 * @property TblMeetingAgenda $meetingAgendaCode
 * @property TblMeetingType $meetingTypeCode
 * @property User $updatedBy
 * @property TblMomAction[] $tblMomActions
 * @property TblMomActionHistory[] $tblMomActionHistories
 */
class TblMom extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_mom';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at', 'deleted_at', 'dcs_code', 'meeting_type_code', 'updated_at','meeting_agenda_code','mom_code'], 'safe'],
            [['is_active', 'is_delete', 'status', ], 'integer'],
//            [['meeting_agenda_code'], 'required'],
            [['mom'], 'string', 'max' => 500],
            [['created_by', 'deleted_by', 'updated_by'], 'string', 'max' => 14],
            [['meeting_agenda_code'], 'string', 'max' => 255],
//            [['meeting_agenda_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblMeetingAgenda::className(), 'targetAttribute' => ['meeting_agenda_code' => 'meeting_agenda_code']],
//            [['meeting_type_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblMeetingType::className(), 'targetAttribute' => ['meeting_type_code' => 'meeting_type_code']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'mom_code' => Yii::t('app', 'Mom Code'),
            'created_at' => Yii::t('app', 'Created At'),
            'deleted_at' => Yii::t('app', 'Deleted At'),
            'is_active' => Yii::t('app', 'Is Active'),
            'is_delete' => Yii::t('app', 'Is Delete'),
            'mom' => Yii::t('app', 'Minutes of Meeting'),
            'status' => Yii::t('app', 'Status'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'deleted_by' => Yii::t('app', 'Deleted By'),
            'meeting_agenda_code' => Yii::t('app', 'Meeting Agenda'),
            'meeting_type_code' => Yii::t('app', 'Meeting Type'),
            'dcs_code' => Yii::t('app', 'DCS'),
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
    public function getMeetingAgendaCode()
    {
        return $this->hasOne(TblMeetingAgenda::className(), ['meeting_agenda_code' => 'meeting_agenda_code']);
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
    public function getUpdatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblMomActions()
    {
        return $this->hasMany(TblMomAction::className(), ['mom_code' => 'mom_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblMomActionHistories()
    {
        return $this->hasMany(TblMomActionHistory::className(), ['mom_code' => 'mom_code']);
    }

    /**
     * @inheritdoc
     * @return TblMomQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TblMomQuery(get_called_class());
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
        $query = TblMom::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        $query->joinWith(['meetingTypeCode','meetingAgendaCode','dcsCode','meetingTypeCode']);
        
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'tbl_mom.is_active' => $this->is_active,
            'tbl_mom.is_delete' =>0,
            'mom_code' => $this->mom_code,
            'tbl_mom.meeting_agenda_code' => $this->meeting_agenda_code,
        ]);

        $query->andFilterWhere(['like', 'mom', $this->mom])
            ->andFilterWhere(['like','status', $this->status])
                ->andFilterWhere(['like','tbl_dcs.dcs_name', $this->dcs_code])
            ->orFilterWhere(['like', 'tbl_meeting_agenda.subject_line', $this->meeting_agenda_code])
            ->andFilterWhere(['like', 'tbl_meeting_type.meeting_type_name', $this->meeting_type_code]);

        return $dataProvider;
    }
    
    public function getRecord($id){
        return $this->findOne($id);
    }
}
