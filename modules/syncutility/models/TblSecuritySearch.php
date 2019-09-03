<?php

namespace app\modules\syncutility\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\syncutility\models\TblSecurity;

/**
 * TblSecuritySearch represents the model behind the search form about `app\modules\hardwareconfiguration\models\TblSecurity`.
 */
class TblSecuritySearch extends TblSecurity {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['col_a'], 'safe'],
            [['col_b', 'col_c', 'col_d', 'col_e', 'col_f', 'col_g', 'created_at', 'updated_at', 'created_by', 'updated_by', 'flg_sentbox_entry', 'sync_status', 'sync_timestamp'], 'safe'],
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
        $query = TblSecurity::find();

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
            'col_a' => $this->col_a,
           // 'created_at' => $this->created_at,
          //  'updated_at' => $this->updated_at,
          //  'sync_timestamp' => $this->sync_timestamp,
        ]);

        $query->andFilterWhere(['like', 'col_b', $this->col_b])
                ->andFilterWhere(['like', 'col_c', $this->col_c])
                ->andFilterWhere(['like', 'col_d', $this->col_d])
                ->andFilterWhere(['like', 'col_e', $this->col_e]);
        //   ->andFilterWhere(['like', 'col_f', $this->col_f])
        //   ->andFilterWhere(['like', 'col_g', $this->col_g])
        // ->andFilterWhere(['like', 'created_by', $this->created_by])
        // ->andFilterWhere(['like', 'updated_by', $this->updated_by])
        // ->andFilterWhere(['like', 'flg_sentbox_entry', $this->flg_sentbox_entry])
        // ->andFilterWhere(['like', 'sync_status', $this->sync_status]);

        return $dataProvider;
    }

}
