<?php

namespace app\modules\transporter\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\transporter\models\TblVehicleMaster;
use app\modules\payment\models\TblVehiclePayment;

/**
 * TblVehicleMasterSearch represents the model behind the search form about `app\modules\organisation\models\TblVehicleMaster`.
 */
class TblVehicleMasterSearch extends TblVehicleMaster {

    /**
     * @inheritdoc
     */
    public $shift_code;
    public $from_date, $to_date;

    public function rules() {
        return [
            [['vehicle_code', 'registration_no', 'applicable_rto', 'driver_name', 'driver_contact_no', 'wef_date', 'driving_license_number', 'transporter_code', 'mapped_route', 'rc_book_no', 'expiry_date', 'average', 'union_code', 'created_at', 'created_by', 'updated_at', 'updated_by', 'fuel_type_code', 'parsing_no', 'billing_method', 'from_date', 'to_date'], 'safe'],
            [['vehicle_type_code', 'capacity_code', 'pollution_certificate', 'insurance', 'rent', 'is_active'], 'integer'],
            [['bmc_code', 'transporter_code', 'wef_date', 'shift_code'], 'required', 'on' => 'km_info_create']
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
        $query = TblVehicleMaster::find();

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
            'vehicle_type_code' => $this->vehicle_type_code,
            'capacity_code' => $this->capacity_code,
            'pollution_certificate' => $this->pollution_certificate,
            'insurance' => $this->insurance,
            'expiry_date' => $this->expiry_date,
            'rent' => $this->rent,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'billing_method' => $this->billing_method,
        ]);
        if (!empty($this->wef_date))
            $query->andFilterWhere(['and', ['>=', 'wef_date', date('Y-m-d', strtotime($this->wef_date))], ['<=', 'wef_date', date('Y-m-d', strtotime($this->wef_date))]]);

        if (!empty($this->from_date)) {
            $from_date = !empty($this->from_date) ? date('Y-m-d', strtotime($this->from_date)) : date('Y-m-d');
            $query->andFilterWhere(['>=', 'cast(wef_date as date)', $from_date]);
        }

        if (!empty($this->to_date)) {
            $to_date = !empty($this->to_date) ? date('Y-m-d', strtotime($this->to_date)) : date('Y-m-d');
            $query->andFilterWhere(['<=', 'cast(wef_date as date)', $to_date]);
        }
        $query->andFilterWhere(['like', 'vehicle_code', $this->vehicle_code])
                ->andFilterWhere(['like', 'registration_no', $this->registration_no])
                ->andFilterWhere(['like', 'applicable_rto', $this->applicable_rto])
                ->andFilterWhere(['like', 'driver_name', $this->driver_name])
                ->andFilterWhere(['like', 'driver_contact_no', $this->driver_contact_no])
                ->andFilterWhere(['like', 'driving_license_number', $this->driving_license_number])
                ->andFilterWhere(['like', 'transporter_code', $this->transporter_code])
                ->andFilterWhere(['like', 'mapped_route', $this->mapped_route])
                ->andFilterWhere(['like', 'rc_book_no', $this->rc_book_no])
                ->andFilterWhere(['like', 'average', $this->average])
                ->andFilterWhere(['like', 'created_by', $this->created_by])
                ->andFilterWhere(['like', 'updated_by', $this->updated_by])
                ->andFilterWhere(['like', 'parsing_no', $this->parsing_no])
                ->andFilterWhere(['like', 'fuel_type_code', $this->fuel_type_code]);

        return $dataProvider;
    }

    public function searchVehicle($params) {
        $query = TblVehicleMaster::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $this->load($params);
        $kminfo_model = new TblVehicleKmInfo();
        $data = $kminfo_model->find()->select(['vehicle_code'])
                ->andFilterWhere(['>=', 'wef_date', date('Y-m-d', strtotime($this->wef_date))])
                ->andFilterWhere(['transporter_code' => $this->transporter_code])
                ->andFilterWhere(['shift_code' => $this->shift_code])
                ->all();
        $payment_model = new TblVehiclePayment();
        $payment_data = $payment_model->find()->select(['vehicle_code'])
                ->where(['!=', 'status', 'processed'])
                ->andFilterWhere(['transporter_code' => $this->transporter_code])
                ->andFilterWhere(['>=', 'to_date', date('Y-m-d', strtotime($this->wef_date))])
                ->orderBy('to_date desc')
                ->all();
        $query->andFilterWhere([
            'transporter_code' => $this->transporter_code,
            'is_active' => 1,
        ]);
        $query->andFilterWhere(['NOT IN', 'vehicle_code', $data])
                ->andFilterWhere(['NOT IN', 'vehicle_code', $payment_data]);
        return $dataProvider;
    }

}
