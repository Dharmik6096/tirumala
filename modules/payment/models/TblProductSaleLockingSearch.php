<?php

namespace app\modules\payment\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\payment\models\TblProductSaleLocking;
use yii\data\ArrayDataProvider;

/**
 * TblProductSaleLockingSearch represents the model behind the search form about `app\modules\payment\models\TblProductSaleLocking`.
 */
class TblProductSaleLockingSearch extends TblProductSaleLocking {

    public $type, $plant_code, $mcc_plant_code, $bmc_code;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['locking_code', 'from_date', 'to_date', 'locking_date', 'union_code', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'type'], 'safe'],
            [['total_count', 'originating_type'], 'integer'],
            [['plant_code', 'mcc_plant_code', 'bmc_code', 'type'], 'safe'],
            [['from_date', 'to_date', 'locking_date', 'union_code', 'plant_code', 'mcc_plant_code'], 'required', 'on' => ['saleLockData']],
        ];
    }

    public function attributeLabels() {
        return [
            'plant_code' => Yii::t('app', 'Plant'),
            'mcc_plant_code' => Yii::t('app', 'MCC'),
            'bmc_code' => Yii::t('app', 'BMC'),
            'locking_date' => Yii::t('app', 'Booking Date'),
            'union_code' => Yii::t('app', 'Union'),
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
        $query = TblProductSaleLocking::find();

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


        $query->andFilterWhere(['like', 'locking_code', $this->locking_code])
                ->andFilterWhere(['like', 'total_count', $this->total_count]);

        return $dataProvider;
    }

    public function locksearch($params) {
        //var_dump($params); exit;
        $this->load($params);

        $output = [];
        if (!empty($params)) {
            $sp_params = [
                'union_code' => '',
                'plant_code' => '',
                'union_code' => '',
                'mcc_plant_code' => '',
                'bmc_code' => '',
                'from_date' => '',
                'to_date' => '',
                'type' => '',
            ];
            if (empty($this->bmc_code)) {
                $this->bmc_code = !empty(Yii::$app->session->get('BMC')) ? ',' . Yii::$app->session->get('BMC') . ',' : 0;
            }
            $sp_params = array_merge($sp_params, $params['TblProductSaleLockingSearch']);
            $sp_params['bmc_code'] = is_array($params['TblProductSaleLockingSearch']['bmc_code']) ? ',' . implode(',', $params['TblProductSaleLockingSearch']['bmc_code']) . ',' : $params['TblProductSaleLockingSearch']['bmc_code'];

            $sp_params['from_date'] = date('Y-m-d', strtotime($sp_params['from_date']));
            $sp_params['to_date'] = date('Y-m-d', strtotime($sp_params['to_date']));

            unset($sp_params['locking_date']);
            $output = \Yii::$app->general->getSpData('Portal_product_sale_data_lock', $sp_params);
        }
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails

            $output = [];
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
