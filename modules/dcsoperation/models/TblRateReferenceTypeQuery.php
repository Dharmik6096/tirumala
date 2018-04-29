<?php

namespace app\modules\dcsoperation\models;

/**
 * This is the ActiveQuery class for [[TblRateReferenceType]].
 *
 * @see TblRateReferenceType
 */
class TblRateReferenceTypeQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblRateReferenceType[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblRateReferenceType|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
