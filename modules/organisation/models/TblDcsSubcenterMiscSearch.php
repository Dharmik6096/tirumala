<?php

namespace app\modules\organisation\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\organisation\models\TblDcsSubcenterMisc;

/**
 * TblDcsSubcenterMiscSearch represents the model behind the search form about `app\modules\organisation\models\TblDcsSubcenterMisc`.
 */
class TblDcsSubcenterMiscSearch extends TblDcsSubcenterMisc
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['dcs_miscellaneous_code', 'created_at', 'miscellaneous_code', 'description', 'updated_at', 'created_by', 'dcs_code', 'subcenter_code', 'updated_by'], 'safe'],
            [['is_active'], 'integer'],
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
        $query = TblDcsSubcenterMisc::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        $query->joinWith(['miscellaneousCode']);


        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'tbl_dcs_subcenter_misc.is_active' => $this->is_active,
            'dcs_code' => $this->dcs_code,
            'tbl_dcs_subcenter_misc.subcenter_code' => $this->subcenter_code,
        ]);
        $query->andFilterWhere(['like', 'tbl_dcs_subcenter_misc.dcs_miscellaneous_code', $this->dcs_miscellaneous_code])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'tbl_miscellaneous.miscellaneous_name', $this->miscellaneous_code]);

        return $dataProvider;
    }
}
