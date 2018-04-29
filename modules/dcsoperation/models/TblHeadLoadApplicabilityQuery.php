<?php

namespace app\modules\dcsoperation\models;

/**
 * This is the ActiveQuery class for [[TblHeadLoadApplicability]].
 *
 * @see TblHeadLoadApplicability
 */
class TblHeadLoadApplicabilityQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblHeadLoadApplicability[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblHeadLoadApplicability|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
