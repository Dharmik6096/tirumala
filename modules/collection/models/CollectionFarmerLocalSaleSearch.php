<?php

namespace app\modules\collection\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\collection\models\CollectionFarmerLocalSale;

/**
 * CollectionFarmerLocalSaleSearch represents the model behind the search form about `app\modules\collection\models\CollectionFarmerLocalSale`.
 */
class CollectionFarmerLocalSaleSearch extends CollectionFarmerLocalSale {

    /**
     * @inheritdoc
     */
    public $from_date, $to_date;

    public function rules() {
        return [
            [['farmerid', 'vlccid', 'routeid', 'bmcid', 'dtdate', 'shift', 'qtytime', 'qltytime', 'qtymode', 'qtydecimals', 'qltydecimals', 'milktype', 'milkqtype', 'createddate', 'createdby', 'modifieddate', 'modifiedby', 'lastsynchronized', 'syncdirection', 'usbflag', 'RateType', 'RateRecalType', 'from_date', 'to_date'], 'safe'],
            [['sampleno', 'rateid', 'qtyauto', 'qltyauto', 'paymentid', 'farmerstatus'], 'integer'],
            [['qty', 'fat', 'snf', 'water', 'clr', 'rtpl', 'amount', 'kgltrconst', 'ltrkgconst', 'StdRate', 'KgFatRate', 'KgSnfRate'], 'number'],
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
        $query = CollectionFarmerLocalSale::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        $query->joinWith(['bmcCode', 'dcsCode', 'memberCode']);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        if (!empty($this->from_date)) {
            $from_date = !empty($this->from_date) ? date('Y-m-d', strtotime($this->from_date)) : date('Y-m-d');
//            $from_date .=' ';
            $query->andFilterWhere(['>=', 'cast(dtdate as date)', $from_date]);
        }

        if (!empty($this->to_date)) {
            $to_date = !empty($this->to_date) ? date('Y-m-d', strtotime($this->to_date)) : date('Y-m-d');
//            $to_date .=' ';
            $query->andFilterWhere(['<=', 'cast(dtdate as date)', $to_date]);
        }
        if (!empty($this->dtdate))
            $query->andFilterWhere(['like', 'CONVERT(VARCHAR(25), dtdate, 126)', date('Y-m-d', strtotime($this->dtdate))]);


        // grid filtering conditions
        
        $query->andFilterWhere(['like', 'tbl_bmc.bmc_name', $this->bmcid])
                ->andFilterWhere(['like', 'CollectionFarmerLocalSale.shift', $this->shift])
                ->andFilterWhere(['like', 'tbl_member.member_name', $this->farmerid])
                ->andFilterWhere(['like', 'tbl_dcs.dcs_name', $this->vlccid]);

        return $dataProvider;
    }

}
