<?php

namespace app\modules\geo\models;


/**
 * This is the ActiveQuery class for [[TblDistricts]].
 *
 * @see TblDistricts
 */
class TblDistrictsQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblDistricts[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblDistricts|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
