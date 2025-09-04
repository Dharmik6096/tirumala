<?php

namespace app\modules\webservice\eipl\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\webservice\eipl\models\TblEiplAppLogin;

/**
 * TblEiplAppLoginSearch represents the model behind the search form about `app\modules\webservice\eipl\models\TblEiplAppLogin`.
 */
class TblEiplAppLoginSearch extends TblEiplAppLogin {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['app_login_id', 'app_type', 'otp_code', 'sms_sent', 'is_active', 'is_expired', 'is_block'], 'integer'],
                [['eipl_code', 'mobile_no', 'master_type', 'master_code', 'login_type', 'module_type', 'module_code', 'imei_no', 'device_id', 'lat_long', 'access_token', 'auth_key', 'version_no', 'orignating_timestamp', 'posting_timestamp', 'sms_log', 'expired_datetime', 'updated_at', 'department', 'device_detail'], 'safe'],
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
        $query = TblEiplAppLogin::find();

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
        if (Yii::$app->session->get('Unions') !== '') {
            $query->andFilterWhere(['union_code' => explode(',', Yii::$app->session->get('Unions'))]);
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'app_login_id' => $this->app_login_id,
            'app_type' => $this->app_type,
            'orignating_timestamp' => $this->orignating_timestamp,
            'posting_timestamp' => $this->posting_timestamp,
            'sms_sent' => $this->sms_sent,
            'is_active' => $this->is_active,
            'is_expired' => $this->is_expired,
            'expired_datetime' => $this->expired_datetime,
            'updated_at' => $this->updated_at,
            'is_block' => $this->is_block,
        ]);

        $query->andFilterWhere(['like', 'eipl_code', $this->eipl_code])
                ->andFilterWhere(['like', 'mobile_no', $this->mobile_no])
                ->andFilterWhere(['like', 'master_type', $this->master_type])
                ->andFilterWhere(['like', 'master_code', $this->master_code])
                ->andFilterWhere(['like', 'login_type', $this->login_type])
                ->andFilterWhere(['like', 'otp_code', $this->otp_code])
                ->andFilterWhere(['like', 'module_type', $this->module_type])
                ->andFilterWhere(['like', 'module_code', $this->module_code])
                ->andFilterWhere(['like', 'imei_no', $this->imei_no])
                ->andFilterWhere(['like', 'device_id', $this->device_id])
                ->andFilterWhere(['like', 'lat_long', $this->lat_long])
                ->andFilterWhere(['like', 'access_token', $this->access_token])
                ->andFilterWhere(['like', 'auth_key', $this->auth_key])
                ->andFilterWhere(['like', 'version_no', $this->version_no])
                ->andFilterWhere(['like', 'sms_log', $this->sms_log])
                ->andFilterWhere(['like', 'department', $this->department])
                ->andFilterWhere(['like', 'device_detail', $this->device_detail]);

        return $dataProvider;
    }

}
