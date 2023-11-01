<?php

namespace app\modules\organisation\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\organisation\models\TblBmcChillerInfo;

/**
 * TblBmcChillerInfoSearch represents the model behind the search form about `app\modules\organisation\models\TblBmcChillerInfo`.
 */
class TblBmcChillerInfoSearch extends TblBmcChillerInfo
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['chilling_capacity', 'installation_date', 'agreement_from_date', 'agreement_to_date', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type'], 'safe'],
            [['chilling_capacity', 'originating_type', 'is_active'], 'integer'],
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
        $query = TblBmcChillerInfo::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'chiller_info_code' => $this->chiller_info_code,
            'chilling_capacity' => $this->chilling_capacity,
            'installation_date' => $this->installation_date,
            'agreement_from_date' => $this->agreement_from_date,
            'agreement_to_date' => $this->agreement_to_date,
            'owner_name' => $this->owner_name,
            'pan_no' => $this->pan_no,
            'rate_type' => $this->rate_type,
            'agreement_no' => $this->agreement_no,
            'min_qty' => $this->min_qty,
            'tds_percentage' => $this->tds_percentage,
            'is_active' => $this->is_active,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'originating_type' => $this->originating_type,
        ]);

        $query->andFilterWhere(['like', 'created_by', $this->created_by])
            ->andFilterWhere(['like', 'updated_by', $this->updated_by])
            ->andFilterWhere(['like', 'originating_org_code', $this->originating_org_code])
            ->andFilterWhere(['like', 'originating_org_type', $this->originating_org_type]);

        return $dataProvider;
    }
}
