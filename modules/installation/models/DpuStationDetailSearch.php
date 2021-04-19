<?php

namespace app\modules\installation\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\installation\models\DpuStationDetail;

/**
 * DpuStationDetailSearch represents the model behind the search form about `app\modules\installation\models\DpuStationDetail`.
 */
class DpuStationDetailSearch extends DpuStationDetail {
    
    public $f_union_code;
    public function rules() {
        return [
            [['id'], 'integer'],
            [['station_code', 'flag_key', 'other_value', 'created_by', 'transferred_by', 'download_datetime', 'download_by', 'ref_code', 'dpu_header', 'vendor_code'], 'safe'],
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
        $query = DpuStationDetail::find();

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
        $query->joinWith(['dcsCode','unionCode']);

        Yii::$app->general->filterByOrg($query,$this,'tbl_dcs');

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'download_datetime' => $this->download_datetime,
        ]);

        $query->andFilterWhere(['like', 'station_code', $this->station_code])
                ->andFilterWhere(['like', 'flag_key', $this->flag_key])
                ->andFilterWhere(['like', 'other_value', $this->other_value])
                ->andFilterWhere(['like', 'dpu_station_detail.vendor_code', $this->vendor_code])
                ->andFilterWhere(['like', 'transferred_by', $this->transferred_by])
                ->andFilterWhere(['like', 'download_by', $this->download_by])
                ->andFilterWhere(['like', 'tbl_dcs.dcs_name', $this->ref_code])
                ->andFilterWhere(['like', 'dpu_header', $this->dpu_header]);
        ;

        return $dataProvider;
    }

}
