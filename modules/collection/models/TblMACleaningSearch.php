<?php

namespace app\modules\collection\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\collection\models\TblMACleaning;

/**
 * TblMACleaningSearch represents the model behind the search form about `app\modules\collection\models\TblMACleaning`.
 */
class TblMACleaningSearch extends TblMACleaning {

    /**
     * @inheritdoc
     */
    public $from_date, $to_date;

    public function rules() {
        return [
            [['id', 'CleaningCycles', 'Measuring', 'counter'], 'integer'],
            [['BMCCode', 'PPCode', 'dtdate', 'shift', 'cleaningdatetime', 'updatedby', 'updateddate', 'from_date', 'to_date'], 'safe'],
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
        $query = TblMACleaning::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        $query->joinWith(['dcsCode', 'bmcCode']);
        
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        if (!empty($this->from_date)) {
            $from_date = !empty($this->from_date) ? date('Y-m-d', strtotime($this->from_date)) : date('Y-m-d');
//            $from_date .=' ';
            $query->andFilterWhere(['>=', 'cast(dtdate as date)', $from_date]);
        }

        if (!empty($this->to_date)) {
            $to_date = !empty($this->to_date) ? date('Y-m-d', strtotime($this->to_date)) : date('Y-m-d');
//            $to_date .=' ';
            $query->andFilterWhere(['<=', 'cast(dtdate as date)', $to_date]);
        }
          if (!empty($this->dtdate))
            $query->andFilterWhere(['like', 'CONVERT(VARCHAR(25), dtdate, 126)', date('Y-m-d', strtotime($this->dtdate))]);

        // grid filtering conditions
        $query->andFilterWhere([
            'tbl_MA_Cleaning.id' => $this->id,
//            'dtdate' => $this->dtdate,
            'tbl_MA_Cleaning.shift' => $this->shift,
        ]);

        $query->andFilterWhere(['like', 'tbl_dcs_subcenter_bmc_info.bmc_name', $this->BMCCode])
                ->andFilterWhere(['like', 'tbl_dcs.dcs_name', $this->PPCode])
                ->andFilterWhere(['like', 'tbl_MA_Cleaning.updatedby', $this->updatedby]);

        return $dataProvider;
    }

}
