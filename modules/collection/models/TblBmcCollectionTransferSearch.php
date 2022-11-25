<?php

namespace app\modules\collection\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\collection\models\TblBmcCollectionTransfer;
use yii\data\ArrayDataProvider;

/**
 * TblBmcCollectionTransferSearch represents the model behind the search form about `app\modules\collection\models\TblBmcCollectionTransfer`.
 */
class TblBmcCollectionTransferSearch extends TblBmcCollectionTransfer {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['tranfer_code', 'originating_type'], 'integer'],
            [['union_code', 'from_plant_code', 'from_mcc_plant_code', 'route_code', 'from_date', 'from_shift_code', 'to_date', 'to_shift_code', 'to_plant_code', 'to_mcc_plant_code', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
            [['union_code', 'from_plant_code', 'from_mcc_plant_code', 'from_date', 'from_shift_code', 'to_date', 'to_shift_code'], 'required', 'on' => ['MilkCollectionTranfer']],
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
        $query = TblBmcCollectionTransfer::find();

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
            'tranfer_code' => $this->tranfer_code,
            'from_date' => $this->from_date,
            'from_shift_code' => $this->from_shift_code,
            'to_date' => $this->to_date,
            'to_shift_code' => $this->to_shift_code,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'originating_type' => $this->originating_type,
        ]);

        $query->andFilterWhere(['like', 'union_code', $this->union_code])
                ->andFilterWhere(['like', 'from_plant_code', $this->from_plant_code])
                ->andFilterWhere(['like', 'from_mcc_plant_code', $this->from_mcc_plant_code])
                ->andFilterWhere(['like', 'route_code', $this->route_code])
                ->andFilterWhere(['like', 'to_plant_code', $this->to_plant_code])
                ->andFilterWhere(['like', 'to_mcc_plant_code', $this->to_mcc_plant_code])
                ->andFilterWhere(['like', 'created_by', $this->created_by])
                ->andFilterWhere(['like', 'updated_by', $this->updated_by])
                ->andFilterWhere(['like', 'originating_org_code', $this->originating_org_code])
                ->andFilterWhere(['like', 'originating_org_type', $this->originating_org_type])
                ->andFilterWhere(['like', 'x_col1', $this->x_col1])
                ->andFilterWhere(['like', 'x_col2', $this->x_col2])
                ->andFilterWhere(['like', 'x_col3', $this->x_col3])
                ->andFilterWhere(['like', 'x_col4', $this->x_col4])
                ->andFilterWhere(['like', 'x_col5', $this->x_col5]);

        return $dataProvider;
    }

    public function createsearch($params) {
        $this->load($params);

        $output = [];
        if (!empty($params)) {
            $sp_params = [
                'union_code' => '',
                'from_plant_code' => '',
                'from_mcc_plant_code' => '',
                'route_code' => '',
                'from_date' => '',
                'from_shift_code' => '',
                'to_date' => '',
                'to_shift_code' => ''];

            $sp_params = array_merge($sp_params, $params['TblBmcCollectionTransferSearch']);
            $from_shift = Yii::$app->general->getshift($sp_params['from_shift_code']);
            $to_shift = Yii::$app->general->getshift($sp_params['to_shift_code']);
            $sp_params['from_date'] = date('Y-m-d H:i:s', strtotime($sp_params['from_date'] . ' ' . $from_shift));
            $sp_params['to_date'] = date('Y-m-d H:i:s', strtotime($sp_params['to_date'] . ' ' . $to_shift));
            unset($sp_params['from_shift_code']);
            unset($sp_params['to_shift_code']);
            $sp = 'portal_bmc_collection_data';

            $output = \Yii::$app->general->getSpData($sp, $sp_params);
        }
        $dataProvider = new ArrayDataProvider();
        if (!empty($output)) {
            $attr = '';
            foreach ($output[0] as $att => $value) {
                $attr .= "'" . $att . "',";
            }
            $dataProvider = new ArrayDataProvider([
                'allModels' => $output,
                'pagination' => false,
                'sort' => [
                    'defaultOrder' => [],
                    'attributes' => [
                        $attr
                    ],
                ],
            ]);
        }
        //var_dump($output); exit;
        return $dataProvider;
    }

}
