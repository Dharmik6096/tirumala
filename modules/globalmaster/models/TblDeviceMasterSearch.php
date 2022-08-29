<?php

namespace app\modules\globalmaster\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\globalmaster\models\TblDeviceMaster;

/**
 * TblDeviceMasterSearch represents the model behind the search form about `app\modules\globalmaster\models\TblDeviceMaster`.
 */
class TblDeviceMasterSearch extends TblDeviceMaster
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['device_master_code', 'tab_type', 'sr_no', 'mac_address', 'remarks', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_type', 'originating_org_code'], 'safe'],
            [['originating_type'], 'integer'],
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
        $query = TblDeviceMaster::find();

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
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'originating_type' => $this->originating_type,
        ]);

        $query->andFilterWhere(['like', 'device_master_code', $this->device_master_code])
            ->andFilterWhere(['like', 'tab_type', $this->tab_type])
            ->andFilterWhere(['like', 'sr_no', $this->sr_no])
            ->andFilterWhere(['like', 'mac_address', $this->mac_address])
            ->andFilterWhere(['like', 'remarks', $this->remarks])
            ->andFilterWhere(['like', 'created_by', $this->created_by])
            ->andFilterWhere(['like', 'updated_by', $this->updated_by])
            ->andFilterWhere(['like', 'originating_org_type', $this->originating_org_type])
            ->andFilterWhere(['like', 'originating_org_code', $this->originating_org_code]);

        return $dataProvider;
    }
}
