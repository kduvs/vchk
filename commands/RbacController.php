<?php
namespace app\commands;

use Yii;
use yii\console\Controller;

class RbacController extends Controller
{
    public function actionInit()
    {
        $auth = Yii::$app->authManager;

        $manage = $auth->createPermission('manage'); //для обычных админов
        $manage->description = 'Админка';
        $auth->add($manage);

        $checkOwnList = $auth->createPermission('checkOwnList'); //для обычных админов
        $checkOwnList->description = 'Просмотр главных страниц своих крудов';
        $auth->add($checkOwnList);

        $manageBook = $auth->createPermission('manageBook'); //для обычных админов
        $manageBook->description = 'Управление коллекциями';
        $auth->add($manageBook);
        // кароче вот тут новый крудбук сделал. удалишь упдатеовнбук и упдатекриейтбук сверху и старый крудбук снизу
        // потом удалишь из бд те таблицы рбака и по новой вызовешь миграцию
        // а потом в лайбрериКонтроллере, буксКонтроллере и во вьюхе книги в папке библиотеки подредачишь код

        $ownerRule = new \app\rbac\OwnerRule;
        $auth->add($ownerRule);

        $manageOwnBook = $auth->createPermission('manageOwnBook');
        $manageOwnBook->description = 'Управление частной коллекцией';
        $manageOwnBook->ruleName = $ownerRule->name;
        $auth->add($manageOwnBook);

        $auth->addChild($manageOwnBook, $manageBook);

        $bookBooking = $auth->createPermission('bookBooking');
        $bookBooking->description = 'бронирование книги'; //например можно будет снимать по желанию данное разрешение со спамеров
        $auth->add($bookBooking);

        $chat = $auth->createPermission('chat');
        $chat->description = 'чат'; //например можно будет снимать по желанию данное разрешение со спамеров
        $auth->add($chat);

        $user = $auth->createRole('user'); //может юзать поиск, подавать заявки на книги, брать и возвращать их
        $auth->add($user);
        $auth->addChild($user, $bookBooking);
        $auth->addChild($user, $chat);

        $employee = $auth->createRole('employee'); 
        $auth->add($employee);
        $auth->addChild($employee, $user);
        $auth->addChild($employee, $checkOwnList);
        $auth->addChild($employee, $manageOwnBook);

        $admin = $auth->createRole('admin');
        $auth->add($admin);
        $auth->addChild($admin, $manage);
        $auth->addChild($admin, $manageBook);
        $auth->addChild($admin, $employee);

        
        
        $auth->assign($employee, 5);
        $auth->assign($admin, 6);
    }
}