<?php
return [

    'main/index' => ['controller' => 'Main', 'action' => 'index'],
    'main' => ['controller' => 'Main', 'action' => 'index'],
    '' => ['controller' => 'Main', 'action' => 'index'],

    'main/test' => ['controller' => 'Main', 'action' => 'test'],

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
];