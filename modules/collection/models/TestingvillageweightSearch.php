<?php

namespace app\modules\collection\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\collection\models\Testingvillageweight;

/**
 * TestingvillageweightSearch represents the model behind the search form about `app\modules\collection\models\Testingvillageweight`.
 */
class TestingvillageweightSearch extends Testingvillageweight {
    
    public $operator_qty;
    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['mccid', 'routeid', 'vlccid', 'producerflag', 'dtdate', 'shift', 'milktype', 'milkqtype', 'qtymode', 'FaultFlag', 'createddate', 'createdby', 'modifiedby', 'modifieddate', 'min_date', 'max_date'], 'safe'],
            [['sampleno', 'rejected_can'], 'safe'],
            [['qty', 'cans', 'quantity_reject_total', 'operator_qty'], 'safe'],
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
        $query = Testingvillageweight::find();
        $request = Yii::$app->request->queryParams;
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        $query->joinWith(['dcsCode']);


        Yii::$app->general->filterByOrg($query, $this, 'tbl_dcs');

        
        if (!empty($this->qty)) {
            $query->andFilterWhere([$this->operator_qty, 'testingvillageweight.qty', $this->qty]);
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

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'testingvillageweight.shift' => $this->shift,
            'testingvillageweight.milktype' => $this->milktype,
        ]);
        $query->andFilterWhere(['like', 'tbl_dcs.dcs_name', $this->vlccid])
                ->andFilterWhere(['like', 'testingvillageweight.sampleno', $this->sampleno]);

        return $dataProvider;
    }

}
