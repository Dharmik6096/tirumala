<?php

namespace app\modules\configuration\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\configuration\models\TblDcsGeneralConfig;

/**
 * TblDcsGeneralConfigSearch represents the model behind the search form about `app\modules\configuration\models\TblDcsGeneralConfig`.
 */
class TblDcsGeneralConfigSearch extends TblDcsGeneralConfig
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['code', 'allow_multiple_voters', 'backup_per_shift', 'election_alert_day', 'election_term', 'is_backup_user_choice', 'is_backup_on_closing', 'is_backup_disbursement', 'max_share_buy', 'min_share_req', 'nos_of_reminders', 'purchase_rate_with_tax', 'sale_rate_with_tax', 'share_issued', 'milk_dispatch_in', 'headload_km', 'milk_dispatch_quantity_mode', 'milk_receipt_quantity_mode'], 'integer'],
            [['backup_path', 'created_at', 'created_by', 'updated_at', 'updated_by', 'union_code', 'product_sale_in_cash', 'share_amount_editable', 'product_billing', 'billing_zero_amount_auto'], 'safe'],
            [['share_unit_cost'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
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
    public function search($params)
    {
        $query = TblDcsGeneralConfig::find();

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
            'allow_multiple_voters' => $this->allow_multiple_voters,
            'backup_per_shift' => $this->backup_per_shift,
            'created_at' => $this->created_at,
            'election_alert_day' => $this->election_alert_day,
            'election_term' => $this->election_term,
            'is_backup_user_choice' => $this->is_backup_user_choice,
            'is_backup_on_closing' => $this->is_backup_on_closing,
            'is_backup_disbursement' => $this->is_backup_disbursement,
            'max_share_buy' => $this->max_share_buy,
            'min_share_req' => $this->min_share_req,
            'nos_of_reminders' => $this->nos_of_reminders,
            'purchase_rate_with_tax' => $this->purchase_rate_with_tax,
            'sale_rate_with_tax' => $this->sale_rate_with_tax,
            'share_issued' => $this->share_issued,
            'share_unit_cost' => $this->share_unit_cost,
            'updated_at' => $this->updated_at,
            'milk_dispatch_in' => $this->milk_dispatch_in,
            'headload_km' => $this->headload_km,
            'milk_dispatch_quantity_mode' => $this->milk_dispatch_quantity_mode,
            'milk_receipt_quantity_mode' => $this->milk_receipt_quantity_mode,
        ]);

        $query->andFilterWhere(['like', 'backup_path', $this->backup_path])
            ->andFilterWhere(['like', 'created_by', $this->created_by])
            ->andFilterWhere(['like', 'updated_by', $this->updated_by])
            ->andFilterWhere(['like', 'union_code', $this->union_code])
            ->andFilterWhere(['like', 'product_sale_in_cash', $this->product_sale_in_cash])
            ->andFilterWhere(['like', 'share_amount_editable', $this->share_amount_editable])
            ->andFilterWhere(['like', 'product_billing', $this->product_billing])
            ->andFilterWhere(['like', 'billing_zero_amount_auto', $this->billing_zero_amount_auto]);

        return $dataProvider;
    }
}
