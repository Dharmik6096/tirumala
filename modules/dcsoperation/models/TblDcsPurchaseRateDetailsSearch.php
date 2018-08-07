<?php

namespace app\modules\dcsoperation\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\dcsoperation\models\TblDcsPurchaseRateAuto;

/**
 * TblDcsPurchaseRateAutoSearch represents the model behind the search form about `app\modules\dcsoperation\models\TblDcsPurchaseRateAuto`.
 */
class TblDcsPurchaseRateDetailsSearch extends TblDcsPurchaseRateAuto {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
//            [['code', 'is_delete', 'milk_quality_type_code'], 'integer'],
            [['wef_date', 'animal_type', 'created_at', 'fat', 'code', 'is_delete', 'milk_quality_type_code', 'rtpl', 'snf', 'deleted_at', 'flg_sentbox_entry', 'ratetype', 'sync_status', 'sync_timestamp', 'updated_at', 'created_by', 'deleted_by', 'purchase_rate_id', 'updated_by'], 'safe'],
//            [['fat', 'rtpl', 'snf'], 'number'],
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
        $query = TblDcsPurchaseRateAuto::find();

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
            'code' => $this->code,
            'wef_date' => $this->wef_date,
            'created_at' => $this->created_at,
            'fat' => $this->fat,
            'is_delete' => $this->is_delete,
            'rtpl' => $this->rtpl,
            'snf' => $this->snf,
            'sync_timestamp' => $this->sync_timestamp,
            'updated_at' => $this->updated_at,
            'milk_quality_type_code' => $this->milk_quality_type_code,
        ]);

        $query->andFilterWhere(['like', 'animal_type', $this->animal_type])
                ->andFilterWhere(['like', 'flg_sentbox_entry', $this->flg_sentbox_entry])
                ->andFilterWhere(['like', 'ratetype', $this->ratetype])
                ->andFilterWhere(['like', 'sync_status', $this->sync_status])
                ->andFilterWhere(['like', 'created_by', $this->created_by])
                ->andFilterWhere(['like', 'purchase_rate_id', $this->purchase_rate_id])
                ->andFilterWhere(['like', 'updated_by', $this->updated_by]);

        return $dataProvider;
    }

}
