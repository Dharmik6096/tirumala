<?php

namespace app\modules\welfarescheme\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\welfarescheme\models\TblSchemeApplicationApproval;

/**
 * TblSchemeApplicationApprovalSearch represents the model behind the search form about `app\modules\welfarescheme\models\TblSchemeApplicationApproval`.
 */
class TblSchemeApplicationApprovalSearch extends TblSchemeApplicationApproval
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['app_approval_id', 'application_id', 'level', 'originating_type'], 'integer'],
            [['user_code', 'approval_mode', 'application_status', 'status_date', 'status_by', 'status_remarks', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type'], 'safe'],
            [['approved_value'], 'number'],
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
        $query = TblSchemeApplicationApproval::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'app_approval_id' => $this->app_approval_id,
            'application_id' => $this->application_id,
            'level' => $this->level,
            'approved_value' => $this->approved_value,
            'status_date' => $this->status_date,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'originating_type' => $this->originating_type,
        ]);

        $query->andFilterWhere(['like', 'user_code', $this->user_code])
            ->andFilterWhere(['like', 'approval_mode', $this->approval_mode])
            ->andFilterWhere(['like', 'application_status', $this->application_status])
            ->andFilterWhere(['like', 'status_by', $this->status_by])
            ->andFilterWhere(['like', 'status_remarks', $this->status_remarks])
            ->andFilterWhere(['like', 'created_by', $this->created_by])
            ->andFilterWhere(['like', 'updated_by', $this->updated_by])
            ->andFilterWhere(['like', 'originating_org_code', $this->originating_org_code])
            ->andFilterWhere(['like', 'originating_org_type', $this->originating_org_type]);

        return $dataProvider;
    }
}
