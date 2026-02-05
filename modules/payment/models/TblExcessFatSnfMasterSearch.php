<?php

namespace app\modules\payment\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\payment\models\TblExcessFatSnfMaster;

/**
 * TblExcessFatSnfMasterSearch represents the model behind the search form about `app\modules\payment\models\TblExcessFatSnfMaster`.
 */
class TblExcessFatSnfMasterSearch extends TblExcessFatSnfMaster {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['excess_fat_snf_id', 'is_active', 'originating_type'], 'integer'],
            [['union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'from_date', 'to_date', 'created_by', 'created_at', 'update_by', 'updated_at', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
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
        $query = TblExcessFatSnfMaster::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        
        Yii::$app->general->filterByOrg($query, $this, 'tbl_excess_fat_snf_master', 'tbl_excess_fat_snf_master', 'tbl_excess_fat_snf_master');

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        
        if (!empty($this->from_date)) {
            $from_date = date('Y-m-d', strtotime($this->from_date));
            $query->andFilterWhere(['>=', 'CAST(tbl_excess_fat_snf_master.from_date as date)', $from_date]);
        }
        if (!empty($this->to_date)) {
            $to_date = date('Y-m-d', strtotime($this->to_date));
            $query->andFilterWhere(['<=', 'CAST(tbl_excess_fat_snf_master.to_date as date)', $to_date]);
        }

        $query->andFilterWhere([
            'is_active' => $this->is_active,
        ]);

        return $dataProvider;
    }

}
