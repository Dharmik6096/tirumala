<?php

namespace app\modules\collection\models;

/**
 * This is the ActiveQuery class for [[TblMilkDispatch]].
 *
 * @see TblMilkDispatch
 */
class TblMilkDispatchQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblMilkDispatch[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblMilkDispatch|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
