<?php

namespace app\modules\installation\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\installation\models\TblAndroidInstallationDetails;

/**
 * TblAndroidInstallationDetailsSearch represents the model behind the search form about `app\modules\installation\models\TblAndroidInstallationDetails`.
 */
class TblAndroidInstallationDetailsSearch extends TblAndroidInstallationDetails {

    public $organization_code, $from_date, $to_date, $organization_type, $code, $refCode;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['android_installation_details_id', 'otp_code', 'is_active', 'is_expired', 'sync_active'], 'integer'],
            [['android_installation_id', 'mobile_no', 'hash_key', 'device_id', 'device_type', 'db_path', 'use_for', 'lat', 'long', 'created_at', 'created_by', 'updated_at', 'updated_by', 'imei_no', 'sync_key', 'db_version', 'installation_type', 'organization_code', 'from_date', 'to_date', 'organization_type', 'password_date', 'password', 'sync_active', 'code', 'refCode'], 'safe'],
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
        $query = TblAndroidInstallationDetails::find();
        $query->where(['tbl_android_installation_details.is_active' => 1]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $query->joinWith(['androidInstallationCode.dcsCode', 'androidInstallationCode.mccCode', 'androidInstallationCode.bmcCode']);
//        $query->andWhere(['tbl_android_installation.organization_type' => 'VLC']);
        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0 = 1');
            return $dataProvider;
        }

        if (!empty($this->from_date)) {
            $from_date = !empty($this->from_date) ? date('Y-m-d', strtotime($this->from_date)) : date('Y-m-d');
            $query->andFilterWhere(['>=', 'cast(tbl_android_installation_details.created_at as date)', $from_date]);
        }

        if (!empty($this->to_date)) {
            $to_date = !empty($this->to_date) ? date('Y-m-d', strtotime($this->to_date)) : date('Y-m-d');
            $query->andFilterWhere(['<=', 'cast(tbl_android_installation_details.created_at as date)', $to_date]);
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'android_installation_details_id' => $this->android_installation_details_id,
            'otp_code' => $this->otp_code,
            'tbl_android_installation_details.installation_type' => $this->installation_type,
            'is_expired' => $this->is_expired,
//            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'sync_active' => $this->sync_active,
        ]);
        $query->andFilterWhere(['or', ['like', 'tbl_dcs.dcs_name', $this->organization_code], ['like', 'tbl_bmc.bmc_name', $this->organization_code], ['like', 'tbl_mcc_plant.name', $this->organization_code]])
               ->andFilterWhere(['or', ['like', 'tbl_dcs.ref_code', $this->refCode], ['like', 'tbl_bmc.ref_code', $this->refCode], ['like', 'tbl_mcc_plant.ref_code', $this->refCode]]);


        $query->andFilterWhere(['like', 'android_installation_id', $this->android_installation_id])
                ->andFilterWhere(['like', 'tbl_android_installation_details.mobile_no', $this->mobile_no])
                ->andFilterWhere(['like', 'hash_key', $this->hash_key])
                ->andFilterWhere(['like', 'tbl_android_installation_details.device_id', $this->device_id])
                ->andFilterWhere(['like', 'device_type', $this->device_type])
                ->andFilterWhere(['like', 'db_path', $this->db_path])
                ->andFilterWhere(['like', 'use_for', $this->use_for])
                ->andFilterWhere(['like', 'lat', $this->lat])
                ->andFilterWhere(['like', 'long', $this->long])
                ->andFilterWhere(['like', 'created_by', $this->created_by])
                ->andFilterWhere(['like', 'updated_by', $this->updated_by])
                ->andFilterWhere(['like', 'imei_no', $this->imei_no])
                ->andFilterWhere(['like', 'sync_key', $this->sync_key])
//                ->andFilterWhere(['like', 'tbl_dcs.dcs_name', $this->organization_code])
                ->andFilterWhere(['like', 'tbl_android_installation_details.db_version', $this->db_version])
                ->andFilterWhere(['like', 'tbl_android_installation.organization_type', $this->organization_type])
                ->andFilterWhere(['like', 'tbl_android_installation.organization_code', $this->code])
                ->andFilterWhere(['like', 'password', $this->password])
                ->andFilterWhere(['like', 'tbl_android_installation_details.created_at', ($this->created_at == '') ? null : Yii::$app->formatter->asDate($this->created_at, DATE_FORMAT)])
                ->andFilterWhere(['like', 'tbl_android_installation_details.password_date', ($this->password_date == '') ? null : Yii::$app->formatter->asDate($this->password_date, DATE_FORMAT)]);
        $query->orderBy('created_at desc');
        return $dataProvider;
    }

}
