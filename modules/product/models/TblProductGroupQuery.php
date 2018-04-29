<?php

namespace app\modules\product\models;

/**
 * This is the ActiveQuery class for [[TblProductGroup]].
 *
 * @see TblProductGroup
 */
class TblProductGroupQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return TblProductGroup[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return TblProductGroup|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
