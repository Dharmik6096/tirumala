<?php

namespace app\modules\transporter\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\transporter\models\TblMobileOilRateMaster;

/**
 * TblMobileOilRateMasterSearch represents the model behind the search form about `app\modules\transporter\models\TblMobileOilRateMaster`.
 */
class TblMobileOilRateMasterSearch extends TblMobileOilRateMaster
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['mobile_oil_rate_master_code', 'vehicle_code'], 'integer'],
            [['rate'], 'number'],
            [['wef_date', 'km_info', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'safe'],
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
        $query = TblMobileOilRateMaster::find();

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
            'tbl_mobile_oil_rate_master.mobile_oil_rate_master_code' => $this->mobile_oil_rate_master_code,
            'tbl_mobile_oil_rate_master.rate' => $this->rate,
            'tbl_mobile_oil_rate_master.wef_date' => $this->wef_date,
            'tbl_mobile_oil_rate_master.vehicle_code' => $this->vehicle_code,
        ]);

        $query->andFilterWhere(['like', 'tbl_mobile_oil_rate_master.km_info', $this->km_info]);

        return $dataProvider;
    }
}
