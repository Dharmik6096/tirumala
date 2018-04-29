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
            'mobile_oil_rate_master_code' => $this->mobile_oil_rate_master_code,
            'rate' => $this->rate,
            'wef_date' => $this->wef_date,
            'vehicle_code' => $this->vehicle_code,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'km_info', $this->km_info])
            ->andFilterWhere(['like', 'created_by', $this->created_by])
            ->andFilterWhere(['like', 'updated_by', $this->updated_by]);

        return $dataProvider;
    }
}
