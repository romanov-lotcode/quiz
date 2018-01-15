<?php
return [

    'main/index' => ['controller' => 'Main', 'action' => 'index'],
    'main' => ['controller' => 'Main', 'action' => 'index'],
    '' => ['controller' => 'Main', 'action' => 'index'],

    'main/test' => ['controller' => 'Main', 'action' => 'test'],
    'main/quiz' => ['controller' => 'Main', 'action' => 'quiz'],

    'main/login' => ['controller' => 'Main', 'action' => 'login'],
    'main/logout' => ['controller' => 'Main', 'action' => 'logout'],

    'direction/index' => ['controller' => 'Direction', 'action' => 'index'],
    'direction/add' => ['controller' => 'Direction', 'action' => 'add'],
    'direction/edit' => ['controller' => 'Direction', 'action' => 'edit'],
    'direction/delete' => ['controller' => 'Direction', 'action' => 'delete'],

    'test/index' => ['controller' => 'Test', 'action' => 'index'],
    'test/add' => ['controller' => 'Test', 'action' => 'add'],
    'test/edit' => ['controller' => 'Test', 'action' => 'edit'],
    'test/delete' => ['controller' => 'Test', 'action' => 'delete'],

    'testing/index' => ['controller' => 'Testing', 'action' => 'index'],
    'testing/add' => ['controller' => 'Testing', 'action' => 'add'],
    'testing/edit' => ['controller' => 'Testing', 'action' => 'edit'],
    'testing/delete' => ['controller' => 'Testing', 'action' => 'delete'],

    'question/index' => ['controller' => 'Question', 'action' => 'index'],
    'question/add' => ['controller' => 'Question', 'action' => 'add'],
    'question/edit' => ['controller' => 'Question', 'action' => 'edit'],
    'question/delete' => ['controller' => 'Question', 'action' => 'delete'],

    'answer/index' => ['controller' => 'Answer', 'action' => 'index'],
    'answer/add' => ['controller' => 'Answer', 'action' => 'add'],
    'answer/edit' => ['controller' => 'Answer', 'action' => 'edit'],
    'answer/delete' => ['controller' => 'Answer', 'action' => 'delete'],

    'user_group/index' => ['controller' => 'UserGroup', 'action' => 'index'],
    'user_group/add' => ['controller' => 'UserGroup', 'action' => 'add'],
    'user_group/edit' => ['controller' => 'UserGroup', 'action' => 'edit'],
    'user_group/delete' => ['controller' => 'UserGroup', 'action' => 'delete'],

    'user/index' => ['controller' => 'User', 'action' => 'index'],
    'user/add' => ['controller' => 'User', 'action' => 'add'],
    'user/edit' => ['controller' => 'User', 'action' => 'edit'],
    'user/delete' => ['controller' => 'User', 'action' => 'delete'],
    'user/password' => ['controller' => 'User', 'action' => 'password'],

    'user_user_group/index' => ['controller' => 'UserUserGroup', 'action' => 'index'],
    'user_user_group/add' => ['controller' => 'UserUserGroup', 'action' => 'add'],
    'user_user_group/delete' => ['controller' => 'UserUserGroup', 'action' => 'delete'],

    'user_testing/index' => ['controller' => 'UserTesting', 'action' => 'index'],

    'result/index' => ['controller' => 'Result', 'action' => 'index'],
    'result/view' => ['controller' => 'Result', 'action' => 'view'],
    'result/print' => ['controller' => 'Result', 'action' => 'print'],
];