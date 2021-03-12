<?php
namespace app\commands;

use Yii;
use yii\console\Controller;

class RbacController extends Controller
{
    public function actionInit()
    {
        $auth = Yii::$app->authManager;

        $rule = new \app\rbac\OwnerRule;
        $auth->add($rule);

        $updateOwnBook = $auth->createPermission('updateOwnBook'); //для обычных админов
        $updateOwnBook->description = 'Редактирование своих книг';
        $updateOwnBook->ruleName = $rule->name;
        $auth->add($updateOwnBook);

        $createOwnBook = $auth->createPermission('createOwnBook'); //для обычных админов
        $createOwnBook->description = 'Добавление своих книг';
        $auth->add($createOwnBook);

        $bookBooking = $auth->createPermission('bookBooking');
        $bookBooking->description = 'бронирование книги'; //например можно будет снимать по желанию данное разрешение со спамеров
        $auth->add($bookBooking);

        $chat = $auth->createPermission('chat');
        $chat->description = 'чат'; //например можно будет снимать по желанию данное разрешение со спамеров
        $auth->add($chat);/* */
        $crudBook = $auth->createPermission('crudBook');
        $crudBook->description = 'Круд для книг';
        $auth->add($crudBook);

        $user = $auth->createRole('user'); //может юзать поиск, подавать заявки на книги, брать и возвращать их
        $auth->add($user);
        $auth->addChild($user, $bookBooking);
        $auth->addChild($user, $chat);

        $employee = $auth->createRole('employee'); 
        $auth->add($employee);
        $auth->addChild($employee, $updateOwnBook);
        $auth->addChild($employee, $createOwnBook);
        $auth->addChild($employee, $user);

        $admin = $auth->createRole('admin'); //не может иметь книги, но имеет власть над книгами, юзерами, логами и тегами
        $auth->add($admin);
        $auth->addChild($admin, $crudBook);
        $auth->addChild($admin, $user); //не от сотрудника, потому что админу не особо и нужно иметь свои книги, если он и так владеет всеми книгами ИС
        //при регистрации роль сотрудника задается путем проверки личного кабинета, а под роль админа изначально существует отдельный аккаунт

        $auth->assign($employee, 5);
        $auth->assign($admin, 6);
    }
}