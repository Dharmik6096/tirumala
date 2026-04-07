<?php

namespace app\modules\product\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\product\models\TblDispatchCenter;

/**
 * TblDispatchCenterSearch represents the model behind the search form about `app\modules\product\models\TblDispatchCenter`.
 */
class TblDispatchCenterSearch extends TblDispatchCenter {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['dispatch_center_code', 'dispatch_center_name', 'dispatch_center_type_code', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
                [['originating_type'], 'integer'],
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
        $query = TblDispatchCenter::find();

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
        $query->joinWith(['unionCode','dispatchCenterTypeCode']);
        Yii::$app->general->filterByOrg($query, $this, 'tbl_unions');

        $query->andFilterWhere(['like', 'dispatch_center_code', $this->dispatch_center_code])
                ->andFilterWhere(['like', 'dispatch_center_name', $this->dispatch_center_name])
                ->andFilterWhere(['like', 'tbl_dispatch_center_type.dispatch_center_type', $this->dispatch_center_type_code]);

        return $dataProvider;
    }

}
