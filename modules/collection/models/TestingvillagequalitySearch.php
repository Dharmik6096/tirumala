<?php

namespace app\modules\collection\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\collection\models\Testingvillagequality;

/**
 * TestingvillagequalitySearch represents the model behind the search form about `app\modules\collection\models\Testingvillagequality`.
 */
class TestingvillagequalitySearch extends Testingvillagequality {

    public $operator_fat, $operator_snf;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['shift', 'dtdate', 'mccid', 'qtime', 'modifieddate', 'ModifyBy', 'ModifyDate', 'CreatedBy', 'CreatedDate', 'min_date', 'max_date'], 'safe'],
            [['sampleno', 'retestcount'], 'safe'],
            [['fat', 'snf', 'water', 'operator_fat', 'operator_snf'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios() {
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
    public function search($params) {
        $query = Testingvillagequality::find();
        $request = Yii::$app->request->queryParams;
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        $query->joinWith(['bmcCode.tblMccPlant', 'bmcCode']);
        Yii::$app->general->filterByOrg($query, $this, 'tbl_mcc_plant', 'tbl_mcc_plant');

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        if (!empty($this->fat)) {
            $query->andFilterWhere([$this->operator_fat, 'testingvillagequality.fat', $this->fat]);
        }
        if (!empty($this->snf)) {
            $query->andFilterWhere([$this->operator_snf, 'testingvillagequality.snf', $this->snf]);
        }
        if (!empty($request['min_date']) && !empty($request['max_date'])) {
            $start_date = date('Y-m-d', strtotime($request['min_date']));
            $end_date = date('Y-m-d', strtotime($request['max_date']));
            if ($start_date != $end_date)
                $query->andFilterWhere(['between', 'CAST(dtdate AS DATE)', $start_date, $end_date]);
            else
                $query->andFilterWhere(['like', 'CONVERT(VARCHAR(25), dtdate, 126)', $start_date]);
        }
        if (!empty($this->dtdate))
            $query->andFilterWhere(['like', 'CONVERT(VARCHAR(25), dtdate, 126)', date('Y-m-d', strtotime($this->dtdate))]);

        $query->andFilterWhere([
            'testingvillageweight.shift' => $this->shift,
        ]);
        $query->andFilterWhere(['like', 'tbl_dcs_subcenter_bmc_info.bmc_name', $this->mccid])
                ->andFilterWhere(['like', 'testingvillagequality.sampleno', $this->sampleno]);
        return $dataProvider;
    }

}
