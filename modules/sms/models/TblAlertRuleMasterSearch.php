<?php

namespace app\modules\sms\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\sms\models\TblAlertRuleMaster;

/**
 * TblAlertRuleMasterSearch represents the model behind the search form about `app\modules\sms\models\TblAlertRuleMaster`.
 */
class TblAlertRuleMasterSearch extends TblAlertRuleMaster {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['rule_code', 'is_trigger', 'is_active', 'rule_name', 'union_code', 'sp_check', 'sp_name', 'sp_param', 'next_date', 'add_days', 'template_type', 'result_column', 'only_for', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'safe'],
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
        $query = TblAlertRuleMaster::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $query->joinWith(['unionCode']);
        Yii::$app->general->filterByOrg($query, $this);
        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions

        $query->andFilterWhere(['like', 'rule_name', $this->rule_name]);

        return $dataProvider;
    }

}
