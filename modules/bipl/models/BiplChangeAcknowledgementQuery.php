<?php

namespace app\modules\bipl\models;

/**
 * This is the ActiveQuery class for [[BiplChangeAcknowledgement]].
 *
 * @see BiplChangeAcknowledgement
 */
class BiplChangeAcknowledgementQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return BiplChangeAcknowledgement[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return BiplChangeAcknowledgement|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
