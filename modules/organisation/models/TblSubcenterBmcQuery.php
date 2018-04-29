<?php

namespace app\modules\organisation\models;

/**
 * This is the ActiveQuery class for [[TblSubcenterBmc]].
 *
 * @see TblSubcenterBmc
 */
class TblSubcenterBmcQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblSubcenterBmc[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblSubcenterBmc|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
