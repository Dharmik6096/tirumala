<?php

namespace app\modules\tankermovement\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\tankermovement\models\TblPaymentHead;

/**
 * TblTransporterPaymentHeadSearch represents the model behind the search form about `app\modules\transporter\models\TblTransporterPaymentHead`.
 */
class TblPaymentHeadSearch extends TblPaymentHead {

//    public $type;
    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['payment_head_code', 'payment_head_type', 'is_active', 'union_code','sequence_no'], 'safe'],
            [['payment_head_name', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'safe'],
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
        $query = TblPaymentHead::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        Yii::$app->general->filterByOrg($query, $this);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'payment_head_code' => $this->payment_head_code,
            'payment_head_type' => $this->payment_head_type,
            'is_active' => $this->is_active,
        ]);

        $query->andFilterWhere(['like', 'payment_head_name', $this->payment_head_name])
              ->andFilterWhere(['like', 'sequence_no', $this->sequence_no]);

        return $dataProvider;
    }

}
