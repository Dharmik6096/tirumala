<?php

namespace app\modules\organisation\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\organisation\models\TblDcsDeactive;

/**
 * TblDcsDeactiveSearch represents the model behind the search form about `app\modules\organisation\models\TblDcsDeactive`.
 */
class TblDcsDeactiveSearch extends TblDcsDeactive {

    public $from_date, $to_date;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['dcs_deactive_code', 'originating_type'], 'integer'],
            [['union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'from_date', 'to_date', 'remarks', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type'], 'safe'],
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
        $query = TblDcsDeactive::find()->where(['IS', 'to_date', NULL]);

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
        Yii::$app->general->filterByOrg($query, $this, 'tbl_dcs_deactive', 'tbl_dcs_deactive', 'tbl_dcs_deactive');
        if (!empty($this->from_date))
            $query->andFilterWhere(['and', ['>=', 'tbl_dcs_deactive.from_date', date('Y-m-d', strtotime($this->from_date))], ['<=', 'tbl_dcs_deactive.from_date', date('Y-m-d', strtotime($this->from_date))]]);

        // grid filtering conditions
        $query->andFilterWhere([
            'dcs_deactive_code' => $this->dcs_deactive_code,
        ]);

        $query->andFilterWhere(['like', 'remarks', $this->remarks]);

        return $dataProvider;
    }

    public function viewsearch($params) {
        $query = TblDcsDeactive::find();

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
            'dcs_code' => $this->dcs_code,
        ]);

        return $dataProvider;
    }

}
