<?php

namespace app\modules\organisation\models;

/**
 * This is the ActiveQuery class for [[TblBanksDistrictsMapping]].
 *
 * @see TblBanksDistrictsMapping
 */
class TblBanksDistrictsMappingQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblBanksDistrictsMapping[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblBanksDistrictsMapping|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
