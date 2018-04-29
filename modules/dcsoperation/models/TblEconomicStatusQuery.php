<?php

namespace app\modules\dcsoperation\models;

/**
 * This is the ActiveQuery class for [[TblEconomicStatus]].
 *
 * @see TblEconomicStatus
 */
class TblEconomicStatusQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblEconomicStatus[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblEconomicStatus|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
