<?php

namespace app\modules\details\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\details\models\TblContactDetails;
use app\modules\dcsoperation\models\TblMember;
use app\modules\webservice\eipl\models\TblEiplAppLogin;
use app\modules\usermanagement\models\User;
use yii\db\Expression;
use yii\db\Query;

/**
 * TblContactDetailsSearch represents the model behind the search form about `app\modules\details\models\TblContactDetails`.
 */
class TblContactDetailsSearch extends TblContactDetails {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['detail_code'], 'integer'],
            [['mobile_no'], 'required', 'on' => ['mobile_no']],
            [['module_name', 'module_code', 'contact_person', 'email', 'mobile_no', 'local_contact_person', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'safe'],
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
        $query = TblContactDetails::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['is_active' => SORT_DESC]]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'detail_code' => $this->detail_code,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'module_code' => (string) $this->module_code,
            'module_name' => $this->module_name,
        ]);


//                ->andFilterWhere(['like', 'module_name', $this->module_name])
//            ->andFilterWhere(['like', 'module_code', $this->module_code])
        $query->andFilterWhere(['like', 'contact_person', $this->contact_person])
                ->andFilterWhere(['like', 'email', $this->email])
                ->andFilterWhere(['like', 'mobile_no', $this->mobile_no])
                ->andFilterWhere(['like', 'local_contact_person', $this->local_contact_person])
                ->andFilterWhere(['like', 'created_by', $this->created_by])
                ->andFilterWhere(['like', 'updated_by', $this->updated_by]);

        return $dataProvider;
    }

    public function searchcontact($params) {
        $contactQuery = TblContactDetails::find()
                ->select([
            'mobile_no',
            new Expression("'Contact' as master_name"),
            'is_active',
            'firstname as name',
            new Expression("NULL as login_type"),
            'module_name',
            'module_code',
            new Expression("NULL as app_login_id"),
            new Expression("NULL as module_type"),
            new Expression("NULL as master_type"),
            'is_default',
        ]);

        $memberQuery = TblMember::find()
                ->select([
            'mobile_no',
            new Expression("'Member' as master_name"),
            'is_active',
            'member_name as name',
            new Expression("NULL as login_type"),
            new Expression("NULL as module_name"),
            'member_code as module_code',
            new Expression("NULL as app_login_id"),
            new Expression("NULL as module_type"),
            new Expression("NULL as master_type"),
            new Expression("NULL as is_default"),
        ]);

        $userQuery = User::find()
                ->select([
            'mobile_no',
            new Expression("'User' as master_name"),
            'is_active',
            'name',
            'login_type',
            new Expression("NULL as module_name"),
            new Expression("NULL as module_code"),
            new Expression("NULL as app_login_id"),
            new Expression("NULL as module_type"),
            new Expression("NULL as master_type"),
            new Expression("NULL as is_default"),
        ]);

        $eiplAppLoginQuery = TblEiplAppLogin::find()
                ->select([
            'mobile_no',
            new Expression("'EIPL APP Login' as master_name"),
            'is_active',
            'user_name as name',
            'login_type',
            new Expression("NULL as module_name"),
            'module_code',
            'app_login_id',
            'module_type',
            'master_type',
            new Expression("NULL as is_default"),
        ]);
        $query = (new Query())
                ->select(['mobile_no', 'master_name', 'is_active', 'name', 'login_type', 'module_name', 'module_code', 'app_login_id', 'module_type', 'master_type', 'is_default'])
                ->from(['q' => $contactQuery->union($memberQuery)->union($userQuery)->union($eiplAppLoginQuery)]);

        $this->scenario = 'mobile_no';
        $this->load($params);
        if (!$this->validate()) {
            return new \yii\data\ArrayDataProvider(['allModels' => [], 'sort' => ['attributes' => ['mobile_no', 'master_name']],]);
        }
        if (!empty($this->mobile_no)) {
            $query->where(['or', ['mobile_no' => $this->mobile_no], ['mobile_no' => \Yii::$app->general->encryptData($this->mobile_no)]]);
        }
        $models = $query->all();
        return new \yii\data\ArrayDataProvider([
            'allModels' => $models,
            'sort' => ['attributes' => ['mobile_no', 'master_name'], 'defaultOrder' => ['mobile_no' => SORT_DESC],],
        ]);
    }

}
