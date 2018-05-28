<?php

namespace app\modules\collection\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\collection\models\TblDpuShiftEndSummary;

/**
 * TblDpuShiftEndSummarySearch represents the model behind the search form about `app\modules\collection\models\TblDpuShiftEndSummary`.
 */
class TblDpuShiftEndSummarySearch extends TblDpuShiftEndSummary
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Id', 'samplecount'], 'integer'],
            [['BMCCode', 'VillageCode', 'dtdate', 'shift', 'updatedby', 'updateddate'], 'safe'],
            [['Qty', 'fat', 'snf', 'amount'], 'number'],
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
        $query = TblDpuShiftEndSummary::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        $query->joinWith(['dcsCode']);
        Yii::$app->general->filterByOrg($query, $this, 'tbl_dcs');

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        if (!empty($this->dtdate)) {
            $query->andFilterWhere(['like', 'CONVERT(VARCHAR(25), dtdate, 126)', date('Y-m-d', strtotime($this->dtdate))]);
        }
        $query->andFilterWhere(['like', 'tbl_dcs.dcs_name', $this->VillageCode]);

        return $dataProvider;
    }
}
