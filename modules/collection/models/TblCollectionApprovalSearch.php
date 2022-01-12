<?php

namespace app\modules\collection\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\collection\models\TblCollectionApproval;

/**
 * TblCollectionApprovalSearch represents the model behind the search form about `app\modules\collection\models\TblCollectionApproval`.
 */
class TblCollectionApprovalSearch extends TblCollectionApproval {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['uuid', 'date', 'code', 'requested_by', 'approved_by', 'approve_date', 'allow_till_date', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_type', 'originating_org_code', 'collection_type'], 'safe'],
                [['shift_code', 'collection_type', 'is_approve', 'valid_hours', 'originating_type'], 'integer'],
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
        $query = TblCollectionApproval::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $query->joinWith(['userAndroidCode']);


        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        if (!empty($this->collection_type)) {
            $query->andFilterWhere(['collection_type' => (int) $this->collection_type]);
        }
        if (!empty($this->date)) {
            $query->andFilterWhere(['like', 'CONVERT(VARCHAR(25), tbl_collection_approval.date, 126)', date('Y-m-d', strtotime($this->date))]);
        }
        // grid filtering conditions

        $query->andFilterWhere(['like', 'tbl_user_android.name', $this->requested_by]);


        return $dataProvider;
    }

}
