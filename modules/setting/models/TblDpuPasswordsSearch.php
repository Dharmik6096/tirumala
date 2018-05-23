<?php

namespace app\modules\setting\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\setting\models\TblDpuPasswords;

/**
 * TblDpuPasswordsSearch represents the model behind the search form about `app\modules\setting\models\TblDpuPasswords`.
 */
class TblDpuPasswordsSearch extends TblDpuPasswords
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['dcs_code', 'PPCode', 'bmc_code', 'mcc_code', 'AdminPwd', 'SuperPwd', 'UserPwd', 'lastmodified', 'modifiedby'], 'safe'],
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
        $query = TblDpuPasswords::find();

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
            'lastmodified' => $this->lastmodified,
        ]);

        $query->andFilterWhere(['like', 'dcs_code', $this->dcs_code])
            ->andFilterWhere(['like', 'PPCode', $this->PPCode])
            ->andFilterWhere(['like', 'bmc_code', $this->bmc_code])
            ->andFilterWhere(['like', 'mcc_code', $this->mcc_code])
            ->andFilterWhere(['like', 'AdminPwd', $this->AdminPwd])
            ->andFilterWhere(['like', 'SuperPwd', $this->SuperPwd])
            ->andFilterWhere(['like', 'UserPwd', $this->UserPwd])
            ->andFilterWhere(['like', 'modifiedby', $this->modifiedby]);

        return $dataProvider;
    }
}
