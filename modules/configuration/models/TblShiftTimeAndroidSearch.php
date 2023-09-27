<?php

namespace app\modules\configuration\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\configuration\models\TblShiftTimeAndroid;

/**
 * TblAssetGroupSearch represents the model behind the search form about `app\modules\configuration\models\TblShiftTimeAndroid`.
 */
class TblShiftTimeAndroidSearch extends TblShiftTimeAndroid {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['org_code', 'collection_type', 'm_start_time', 'e_start_time', 'm_lock_time', 'e_lock_time', 'originating_org_code', 'originating_org_type'], 'safe'],
            [['created_at', 'created_by', 'updated_at', 'updated_by', 'originating_type', 'date_shift_enable', 'grace_hr'], 'safe'],
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
        $query = TblShiftTimeAndroid::find();

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
            'tbl_shift_time_android.date_shift_enable' => $this->date_shift_enable,
        ]);

        $query->joinWith(['bmcCode', 'mccCode']);

        // grid filtering conditions
        $query->andFilterWhere(['or', ['like', 'tbl_bmc.bmc_name', $this->org_code], ['like', 'tbl_mcc_plant.name', $this->org_code]])
                ->andFilterWhere(['like', 'code', $this->code])
                ->andFilterWhere(['like', 'collection_type', $this->collection_type])
                ->andFilterWhere(['like', 'grace_hr', $this->grace_hr]);
        return $dataProvider;
    }

}
