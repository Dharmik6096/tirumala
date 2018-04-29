<?php

namespace app\modules\dcsoperation\models;

/**
 * This is the ActiveQuery class for [[TblRateType]].
 *
 * @see TblRateType
 */
class TblRateTypeQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblRateType[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblRateType|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
