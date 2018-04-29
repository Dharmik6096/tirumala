<?php

namespace app\modules\dcsoperation\models;

/**
 * This is the ActiveQuery class for [[TblFormulaHistory]].
 *
 * @see TblFormulaHistory
 */
class TblFormulaHistoryQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblFormulaHistory[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblFormulaHistory|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
