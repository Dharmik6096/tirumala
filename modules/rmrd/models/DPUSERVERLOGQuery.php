<?php

namespace app\modules\rmrd\models;

/**
 * This is the ActiveQuery class for [[DPUSERVERLOG]].
 *
 * @see DPUSERVERLOG
 */
class DPUSERVERLOGQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return DPUSERVERLOG[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return DPUSERVERLOG|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
