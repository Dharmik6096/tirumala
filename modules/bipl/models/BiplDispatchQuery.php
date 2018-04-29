<?php

namespace app\modules\bipl\models;

/**
 * This is the ActiveQuery class for [[BiplDispatch]].
 *
 * @see BiplDispatch
 */
class BiplDispatchQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return BiplDispatch[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return BiplDispatch|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
