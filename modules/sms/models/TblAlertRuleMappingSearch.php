<?php

namespace app\modules\sms\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\sms\models\TblAlertRuleMapping;

/**
 * TblAlertRuleMappingSearch represents the model behind the search form about `app\modules\sms\models\TblAlertRuleMapping`.
 */
class TblAlertRuleMappingSearch extends TblAlertRuleMapping {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['union_code', 'mapping_code', 'rule_code', 'level', 'frequency', 'interval', 'is_active', 'originating_type', 'department_id', 'module_name', 'organization_type', 'module_code', 'result_key', 'only_for', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type'], 'safe'],
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
        $query = TblAlertRuleMapping::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $query->joinWith(['unionCode']);
        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'rule_code' => $this->rule_code,
        ]);
        return $dataProvider;
    }

}
