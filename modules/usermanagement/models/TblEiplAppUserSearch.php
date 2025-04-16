<?php

namespace app\modules\usermanagement\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\usermanagement\models\TblEiplAppUser;

/**
 * TblEiplAppUserSearch represents the model behind the search form about `app\modules\usermanagement\models\TblEiplAppUser`.
 */
class TblEiplAppUserSearch extends TblEiplAppUser {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['eipl_app_user_code', 'auth_key', 'bind_to_ip', 'confirmation_token', 'created_at', 'email', 'mobile_no', 'name', 'password_hash', 'portal_type', 'registration_ip', 'updated_at', 'user_code', 'user_identity', 'username', 'created_by', 'updated_by', 'device_id', 'department'], 'safe'],
                [['email_confirmed', 'is_active', 'status', 'superadmin', 'alert_recipient_group_id', 'user_type_id', 'allow_app_login'], 'integer'],
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
        $query = TblEiplAppUser::find();

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
            'created_at' => $this->created_at,
            'email_confirmed' => $this->email_confirmed,
            'is_active' => $this->is_active,
            'status' => $this->status,
            'superadmin' => $this->superadmin,
            'updated_at' => $this->updated_at,
            'alert_recipient_group_id' => $this->alert_recipient_group_id,
            'user_type_id' => $this->user_type_id,
            'allow_app_login' => $this->allow_app_login,
        ]);

        $query->andFilterWhere(['like', 'eipl_app_user_code', $this->eipl_app_user_code])
                ->andFilterWhere(['like', 'auth_key', $this->auth_key])
                ->andFilterWhere(['like', 'bind_to_ip', $this->bind_to_ip])
                ->andFilterWhere(['like', 'confirmation_token', $this->confirmation_token])
                ->andFilterWhere(['like', 'email', $this->email])
                ->andFilterWhere(['like', 'mobile_no', $this->mobile_no])
                ->andFilterWhere(['like', 'name', $this->name])
                ->andFilterWhere(['like', 'password_hash', $this->password_hash])
                ->andFilterWhere(['like', 'portal_type', $this->portal_type])
                ->andFilterWhere(['like', 'registration_ip', $this->registration_ip])
                ->andFilterWhere(['like', 'user_code', $this->user_code])
                ->andFilterWhere(['like', 'user_identity', $this->user_identity])
                ->andFilterWhere(['like', 'username', $this->username])
                ->andFilterWhere(['like', 'created_by', $this->created_by])
                ->andFilterWhere(['like', 'updated_by', $this->updated_by])
                ->andFilterWhere(['like', 'device_id', $this->device_id])
                ->andFilterWhere(['like', 'department', $this->department]);

        return $dataProvider;
    }

}
