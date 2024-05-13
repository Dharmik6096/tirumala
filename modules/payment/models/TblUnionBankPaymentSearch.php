<?php

namespace app\modules\payment\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\payment\models\TblUnionBankPayment;

/**
 * TblUnionBankPaymentSearch represents the model behind the search form about `app\modules\payment\models\TblUnionBankPayment`.
 */
class TblUnionBankPaymentSearch extends TblUnionBankPayment
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['union_bank_payment_code', 'is_active'], 'integer'],
            [['union_code', 'bank_name', 'bank_code', 'branch_name', 'branch_code', 'ifsc', 'bank_account_no', 'file_path', 'server_type', 'created_at', 'created_by', 'updated_at', 'updated_by', 'mobile_no', 'email', 'ftp_type', 'ftp_server', 'ftp_username', 'ftp_password', 'ftp_port', 'reverse_ftp_path', 'reverse_server_path', 'compare_file_name', 'bank_email', 'bank_mobile', 'corporate_code'], 'safe'],
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
        $query = TblUnionBankPayment::find();

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
            'union_bank_payment_code' => $this->union_bank_payment_code,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'is_active' => $this->is_active,
        ]);

        $query->andFilterWhere(['like', 'union_code', $this->union_code])
            ->andFilterWhere(['like', 'bank_name', $this->bank_name])
            ->andFilterWhere(['like', 'bank_code', $this->bank_code])
            ->andFilterWhere(['like', 'branch_name', $this->branch_name])
            ->andFilterWhere(['like', 'branch_code', $this->branch_code])
            ->andFilterWhere(['like', 'ifsc', $this->ifsc])
            ->andFilterWhere(['like', 'bank_account_no', $this->bank_account_no])
            ->andFilterWhere(['like', 'file_path', $this->file_path])
            ->andFilterWhere(['like', 'server_type', $this->server_type])
            ->andFilterWhere(['like', 'created_by', $this->created_by])
            ->andFilterWhere(['like', 'updated_by', $this->updated_by])
            ->andFilterWhere(['like', 'mobile_no', $this->mobile_no])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'ftp_type', $this->ftp_type])
            ->andFilterWhere(['like', 'ftp_server', $this->ftp_server])
            ->andFilterWhere(['like', 'ftp_username', $this->ftp_username])
            ->andFilterWhere(['like', 'ftp_password', $this->ftp_password])
            ->andFilterWhere(['like', 'ftp_port', $this->ftp_port])
            ->andFilterWhere(['like', 'reverse_ftp_path', $this->reverse_ftp_path])
            ->andFilterWhere(['like', 'reverse_server_path', $this->reverse_server_path])
            ->andFilterWhere(['like', 'compare_file_name', $this->compare_file_name])
            ->andFilterWhere(['like', 'bank_email', $this->bank_email])
            ->andFilterWhere(['like', 'bank_mobile', $this->bank_mobile])
            ->andFilterWhere(['like', 'corporate_code', $this->corporate_code]);

        return $dataProvider;
    }
}
