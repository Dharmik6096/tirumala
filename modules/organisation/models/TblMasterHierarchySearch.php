<?php

namespace app\modules\organisation\models;

use Yii;
use app\modules\organisation\models\TblMasterHierarchy;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * TblMasterHierarchySearch represents the model behind the search form about `app\modules\organisation\models\TblMasterHierarchy`.
 */
class TblMasterHierarchySearch extends TblMasterHierarchy {
    public $from_date, $to_date;
    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['master_hierarchy_code','master_key','master_type','union_code','plant_code','mcc_plant_code','bmc_code','dcs_code','route_code','member_code','customer_code','ref_code1','ref_code2','ref_code3','ref_code4','ref_code5','is_active','wef_date','created_at','created_by','updated_at','updated_by','originating_org_code','originating_org_type','originating_type','x_col1','x_col2','x_col3','x_col4','x_col5'], 'safe'],
            [['f_union_code', 'from_date', 'to_date'],'required']
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
        $query = TblMasterHierarchy::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $query->joinWith(['unionCode']);

        $this->load($params);
        Yii::$app->general->filterByOrg($query, $this, 'tbl_master_hierarchy', 'tbl_master_hierarchy', 'tbl_master_hierarchy', 'tbl_master_hierarchy');
        if (!$this->validate()) {
            $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'tbl_master_hierarchy.is_active' => $this->is_active,
        ]);

        $query->andFilterWhere([
            'tbl_master_hierarchy.master_type' => $this->master_type,
        ]);

        if (!empty($this->from_date))
            $query->andFilterWhere(['>=', 'cast(tbl_master_hierarchy.created_at as date)', date('Y-m-d', strtotime($this->from_date))]);

        if (!empty($this->to_date))
            $query->andFilterWhere(['<=', 'cast(tbl_master_hierarchy.created_at as date)', date('Y-m-d', strtotime($this->to_date))]);

        if (!empty($this->wef_date))
            $query->andFilterWhere(['cast(tbl_master_hierarchy.wef_date as date)' => date('Y-m-d', strtotime($this->wef_date))]);

        $query->andFilterWhere(['like', 'tbl_master_hierarchy.ref_code1', $this->ref_code1])
                ->andFilterWhere(['like', 'tbl_master_hierarchy.ref_code2', $this->ref_code2])
                ->andFilterWhere(['like', 'tbl_master_hierarchy.ref_code3', $this->ref_code3])
                ->andFilterWhere(['like', 'tbl_master_hierarchy.ref_code4', $this->ref_code4])
                ->andFilterWhere(['like', 'tbl_master_hierarchy.ref_code5', $this->ref_code5]);

        return $dataProvider;
    }
}
?>