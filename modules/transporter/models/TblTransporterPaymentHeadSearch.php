<?php

namespace app\modules\transporter\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\transporter\models\TblTransporterPaymentHead;

/**
 * TblTransporterPaymentHeadSearch represents the model behind the search form about `app\modules\transporter\models\TblTransporterPaymentHead`.
 */
class TblTransporterPaymentHeadSearch extends TblTransporterPaymentHead {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['transporter_payment_head_code', 'type', 'is_active', 'union_code'], 'safe'],
            [['transporter_payment_head', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'safe'],
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
        $query = TblTransporterPaymentHead::find();

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
            'transporter_payment_head_code' => $this->transporter_payment_head_code,
            'type' => $this->type,
            'is_active' => $this->is_active,
        ]);

        $query->andFilterWhere(['like', 'transporter_payment_head', $this->transporter_payment_head]);

        return $dataProvider;
    }

}
