<?php

namespace app\modules\organisation\models;

/**
 * This is the ActiveQuery class for [[TblDcs]].
 *
 * @see TblDcs
 */
class TblDcsQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblDcs[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblDcs|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
