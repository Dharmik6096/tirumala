<?php

namespace app\modules\organisation\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\organisation\models\TblVehicleMaster;

/**
 * TblVehicleMasterSearch represents the model behind the search form about `app\modules\organisation\models\TblVehicleMaster`.
 */
class TblVehicleMasterSearch extends TblVehicleMaster {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['vehicle_code', 'registration_no', 'applicable_rto', 'driver_name', 'driver_contact_no', 'wef_date', 'driving_license_number', 'transporter_code', 'mapped_route', 'rc_book_no', 'expiry_date', 'average', 'union_code', 'created_at', 'created_by', 'updated_at', 'updated_by', 'delete_at', 'delete_by'], 'safe'],
            [['vehicle_type_code', 'capacity_code', 'pollution_certificate', 'insurance', 'rent', 'is_active'], 'integer'],
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
            'tbl_vehicle_master.vehicle_type_code' => $this->vehicle_type_code,
            'tbl_vehicle_master.capacity_code' => $this->capacity_code,
            'tbl_vehicle_master.wef_date' => $this->wef_date,
            'tbl_vehicle_master.pollution_certificate' => $this->pollution_certificate,
            'tbl_vehicle_master.insurance' => $this->insurance,
            'tbl_vehicle_master.expiry_date' => $this->expiry_date,
            'tbl_vehicle_master.rent' => $this->rent,
            'tbl_vehicle_master.is_active' => $this->is_active,
        ]);

        $query->andFilterWhere(['like', 'tbl_vehicle_master.vehicle_code', $this->vehicle_code])
                ->andFilterWhere(['like', 'tbl_vehicle_master.registration_no', $this->registration_no])
                ->andFilterWhere(['like', 'tbl_vehicle_master.applicable_rto', $this->applicable_rto])
                ->andFilterWhere(['like', 'tbl_vehicle_master.driver_name', $this->driver_name])
                ->andFilterWhere(['like', 'tbl_vehicle_master.driver_contact_no', $this->driver_contact_no])
                ->andFilterWhere(['like', 'tbl_vehicle_master.driving_license_number', $this->driving_license_number])
                ->andFilterWhere(['like', 'tbl_vehicle_master.transporter_code', $this->transporter_code])
                ->andFilterWhere(['like', 'tbl_vehicle_master.mapped_route', $this->mapped_route])
                ->andFilterWhere(['like', 'tbl_vehicle_master.rc_book_no', $this->rc_book_no])
                ->andFilterWhere(['like', 'tbl_vehicle_master.average', $this->average])
                ->andFilterWhere(['like', 'tbl_vehicle_master.union_code', $this->union_code]);

        return $dataProvider;
    }

}
