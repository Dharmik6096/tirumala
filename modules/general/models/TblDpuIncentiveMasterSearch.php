<?php

namespace app\modules\general\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\general\models\TblDpuIncentiveMaster;

/**
 * TblDpuIncentiveMasterSearch represents the model behind the search form about `app\modules\general\models\TblDpuIncentiveMaster`.
 */
class TblDpuIncentiveMasterSearch extends TblDpuIncentiveMaster {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['incentive_master_code'], 'integer'],
            [['dcs_code', 'm_cutoff_time', 'e_cutoff_time', 'm_start_time', 'e_start_time', 'm_lock_time', 'e_lock_time', 'union_code', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'safe'],
            [['inc_rate', 'inc_deduction'], 'number'],
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
        $query = TblDpuIncentiveMaster::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        $query->joinWith(['dcsCode']);
        Yii::$app->general->filterByOrg($query, $this);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'incentive_master_code' => $this->incentive_master_code,
            'inc_rate' => $this->inc_rate,
            'inc_deduction' => $this->inc_deduction,
        ]);

        $query->andFilterWhere(['like', 'dcs_code', $this->dcs_code])
                ->andFilterWhere(['like', 'm_cutoff_time', $this->m_cutoff_time])
                ->andFilterWhere(['like', 'e_cutoff_time', $this->e_cutoff_time])
                ->andFilterWhere(['like', 'm_start_time', $this->m_start_time])
                ->andFilterWhere(['like', 'e_start_time', $this->e_start_time])
                ->andFilterWhere(['like', 'm_lock_time', $this->m_lock_time])
                ->andFilterWhere(['like', 'e_lock_time', $this->e_lock_time])
                ->andFilterWhere(['like', 'union_code', $this->union_code])
        ;

        return $dataProvider;
    }

}
