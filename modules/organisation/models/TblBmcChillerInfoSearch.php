<?php

namespace app\modules\organisation\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\organisation\models\TblBmcChillerInfo;

/**
 * TblBmcChillerInfoSearch represents the model behind the search form about `app\modules\organisation\models\TblBmcChillerInfo`.
 */
class TblBmcChillerInfoSearch extends TblBmcChillerInfo {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['chilling_capacity', 'installation_date', 'agreement_from_date', 'agreement_to_date', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'is_default'], 'safe'],
            [['chilling_capacity', 'originating_type', 'is_active'], 'integer'],
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
        $query = TblBmcChillerInfo::find()->andWhere(['bmc_code' => $params['id']]);
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }
        $query->andFilterWhere(['is_active' => $this->is_active]);

        return $dataProvider;
    }

}
