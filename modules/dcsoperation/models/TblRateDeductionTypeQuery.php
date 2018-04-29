<?php

namespace app\modules\dcsoperation\models;

/**
 * This is the ActiveQuery class for [[TblRateDeductionType]].
 *
 * @see TblRateDeductionType
 */
class TblRateDeductionTypeQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblRateDeductionType[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblRateDeductionType|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
