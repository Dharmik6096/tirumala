<?php

namespace app\modules\general\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\general\models\TblSocietyVendor;

/**
 * TblSocietyVendorSearch represents the model behind the search form about `app\modules\general\models\TblSocietyVendor`.
 */
class TblSocietyVendorSearch extends TblSocietyVendor
{
    
    public $union_code;
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['society_vendor_code', 'is_active'], 'integer'],
            [['dcs_code', 'vendor_code','union_code'], 'safe'],
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
        $query = TblSocietyVendor::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

//        if(Yii::$app->session->get('Unions')!==''){
                $query->joinWith(['dcsCode']);
//                $query->andFilterWhere([ 'tbl_dcs.union_code'=>explode(',',Yii::$app->session->get('Unions'))]);
//            }
        $this->load($params);
        
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'society_vendor_code' => $this->society_vendor_code,
            'is_active' => $this->is_active,
        ]);

        $query->andFilterWhere(['like', 'tbl_dcs.dcs_name', $this->dcs_code])
            ->andFilterWhere(['like', 'vendor_code', $this->vendor_code]);

        return $dataProvider;
    }
}
