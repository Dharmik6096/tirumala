<?php

namespace app\modules\dcsoperation\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\dcsoperation\models\TblMeetingAgenda;

/**
 * TblMeetingAgendaSearch represents the model behind the search form about `app\modules\dcsoperation\models\TblMeetingAgenda`.
 */
class TblMeetingAgendaSearch extends TblMeetingAgenda
{
    public $federation_code;
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['meeting_agenda_code', 'created_at','meeting_time', 'date','meeting_type_code', 'federation_code', 'deleted_at', 'detailed_agenda', 'meeting_date', 'subject_line', 'updated_at', 'created_by', 'dcs_code', 'deleted_by', 'union_code', 'updated_by'], 'safe'],
            [['is_active', 'is_delete',], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
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
        $query = TblMeetingAgenda::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        $query->joinWith(['unionCode','unionCode.federationCode','meetingTypeCode']);
        
        $query->andwhere(['tbl_federations.federation_code' => $this->federation_code]);
        
        if(Yii::$app->session->get('Unions')!==''){
            $query->andFilterWhere([ 'tbl_meeting_agenda.union_code'=>explode(',',Yii::$app->session->get('Unions'))]);
        }else
            $query->andFilterWhere([ 'tbl_meeting_agenda.union_code'=>$this->union_code]);
        
        if(Yii::$app->session->get('Dcs')!==''){
            $query->andFilterWhere([ 'tbl_meeting_agenda.dcs_code'=>explode(',',Yii::$app->session->get('Dcs'))]);
        }else
            $query->andFilterWhere([ 'tbl_meeting_agenda.dcs_code'=>$this->dcs_code]);
        
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'date' => $this->date,
            'tbl_meeting_agenda.is_active' => $this->is_active,
            'tbl_meeting_agenda.is_delete' => 0,
        ]);

        if(!empty($this->meeting_date))
            $query->andFilterWhere(['like', 'meeting_date', date('Y-m-d', strtotime ($this->meeting_date))]);
        
        $query->andFilterWhere(['like', 'tbl_meeting_agenda.meeting_agenda_code', $this->meeting_agenda_code])
            ->andFilterWhere(['like', 'detailed_agenda', $this->detailed_agenda])
            ->andFilterWhere(['like', 'tbl_meeting_type.meeting_type_name', $this->meeting_type_code])
            ->andFilterWhere(['like', 'subject_line', $this->subject_line])
            ->andFilterWhere(['like', 'meeting_time', $this->meeting_time]);

        return $dataProvider;
    }
}
