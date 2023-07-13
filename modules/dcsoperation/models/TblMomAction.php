<?php

namespace app\modules\dcsoperation\models;

use Yii;
use yii\data\ActiveDataProvider;
use app\modules\usermanagement\models\User;
use app\modules\organisation\models\TblDcs;
/**
 * This is the model class for table "tbl_mom_action".
 *
 * @property integer $mom_action_code
 * @property string $action_taken
 * @property string $created_at
 * @property string $date
 * @property string $deleted_at
 * @property integer $is_active
 * @property integer $is_delete
 * @property string $updated_at
 * @property string $created_by
 * @property string $deleted_by
 * @property string $meeting_agenda_code
 * @property integer $mom_code
 * @property integer $dcs_code
 * @property string $updated_by
 *
 * @property User $createdBy
 * @property User $deletedBy
 * @property TblMeetingAgenda $meetingAgendaCode
 * @property TblMom $momCode
 * @property User $updatedBy
 */
class TblMomAction extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_mom_action';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at', 'date', 'deleted_at', 'mom_code',  'dcs_code','updated_at'], 'safe'],
            [['is_active', 'is_delete'], 'integer'],
//            [['meeting_agenda_code', 'mom_code'], 'required'],
            [['action_taken', 'meeting_agenda_code'], 'string', 'max' => 255],
            [['created_by', 'deleted_by', 'updated_by'], 'string', 'max' => 14],
//            [['meeting_agenda_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblMeetingAgenda::className(), 'targetAttribute' => ['meeting_agenda_code' => 'meeting_agenda_code']],
//            [['mom_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblMom::className(), 'targetAttribute' => ['mom_code' => 'mom_code']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'mom_action_code' => Yii::t('app', 'Mom Action Code'),
            'action_taken' => Yii::t('app', 'Action Taken'),
            'created_at' => Yii::t('app', 'Created At'),
            'date' => Yii::t('app', 'Date'),
            'deleted_at' => Yii::t('app', 'Deleted At'),
            'is_active' => Yii::t('app', 'Is Active'),
            'is_delete' => Yii::t('app', 'Is Delete'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'deleted_by' => Yii::t('app', 'Deleted By'),
            'meeting_agenda_code' => Yii::t('app', 'Meeting Agenda'),
            'mom_code' => Yii::t('app', 'Minutes of Meeting'),
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
    public function getMomCode()
    {
        return $this->hasOne(TblMom::className(), ['mom_code' => 'mom_code']);
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
     * @return TblMomActionQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TblMomActionQuery(get_called_class());
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
        $query = TblMomAction::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        $query->joinWith(['momCode','dcsCode','meetingAgendaCode']);
        
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'tbl_mom_action.is_active' => $this->is_active,
            'tbl_mom_action.is_delete' => 0,
        ]);
        
        if(!empty($this->date))
            $query->andwhere(['tbl_mom_action.date' => date('Y-m-d', strtotime($this->date))]);

        $query->andFilterWhere(['like', 'action_taken', $this->action_taken])
            ->andFilterWhere(['like','tbl_dcs.dcs_name', $this->dcs_code])
            ->andFilterWhere(['like', 'tbl_meeting_agenda.subject_line', $this->meeting_agenda_code]);

        return $dataProvider;
    }
    
    public function getRecord($id){
        return $this->find()->where(['mom_code'=>$id])->one();
    }
}
