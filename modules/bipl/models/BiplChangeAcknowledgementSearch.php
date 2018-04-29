<?php

namespace app\modules\bipl\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\bipl\models\BiplChangeAcknowledgement;

/**
 * BiplChangeAcknowledgementSearch represents the model behind the search form about `app\modules\bipl\models\BiplChangeAcknowledgement`.
 */
class BiplChangeAcknowledgementSearch extends BiplChangeAcknowledgement
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['svc', 'usr', 'pswd', 'cp', 'imei', 'mcc', 'cp_code', 'census_code', 'vendor_id', 'date', 'time', 'file_name'], 'safe'],
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
        $query = BiplChangeAcknowledgement::find();

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
        
        if(!empty($this->date))
            $query->andwhere(['date' => date('Y-m-d', strtotime($this->date))]);

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            //'date' => $this->date,
            'time' => $this->time,
            'svc'=>$this->svc
        ]);

        $query->andFilterWhere(['like', 'usr', $this->usr])
            ->andFilterWhere(['like', 'pswd', $this->pswd])
            ->andFilterWhere(['like', 'cp', $this->cp])
            ->andFilterWhere(['like', 'imei', $this->imei])
            ->andFilterWhere(['like', 'mcc', $this->mcc])
            ->andFilterWhere(['like', 'cp_code', $this->cp_code])
            ->andFilterWhere(['like', 'census_code', $this->census_code])
            ->andFilterWhere(['like', 'vendor_id', $this->vendor_id])
            ->andFilterWhere(['like', 'file_name', $this->file_name]);

        return $dataProvider;
    }
}
