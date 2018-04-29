<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[TblUserTypes]].
 *
 * @see TblUserTypes
 */
class TblUserTypesQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblUserTypes[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblUserTypes|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
