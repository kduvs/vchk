<?php

namespace app\rbac;

use yii\rbac\Rule;
use app\models\User;

/**
 * Проверяем authorID на соответствие с пользователем, переданным через параметры
 */
class OwnerRule extends Rule
{
    public $name = 'isOwner';

    /**
     * @param string|int $user the user ID.
     * @param Item $item the role or permission that this rule is associated width.
     * @param array $params parameters passed to ManagerInterface::checkAccess().
     * @return bool a value indicating whether the rule permits the role or permission it is associated with.
     */
    public function execute($user, $item, $params)
    {
        return isset($params['book']) ? $params['book']->owner_id == User::findOne($user)->owner_id : false;
    }
}