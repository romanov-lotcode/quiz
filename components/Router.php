<?php


/**
 * Компонент для работы с маршрутами
 */
class Router
{

    /**
     * Свойство для хранения массива роутов
     * @var array
     */
    private $routes;

    private $controller = false;

    private $action = false;

    /**
     * Конструктор
     */
    public function __construct()
    {
        // Путь к файлу с роутами
        $routesPath = (!User::checkLogged())? ROOT . '/config/not_logged_routes.php' : ROOT . '/config/routes.php';

        // Получаем роуты из файла
        $this->routes = include($routesPath);
    }

    /**
     * Возвращает строку запроса
     */
    private function getURI()
    {
        if (!empty($_SERVER['REQUEST_URI'])) {
            return trim($_SERVER['REQUEST_URI'], '/');
        }
    }

    /**
     * Возвращает строку запроса
     * @return string
     */
    private function getURIDec()
    {
        return trim(urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)), '/');
    }

    /**
     * Метод для обработки запроса
     */
    public function run()
    {
        $uri_dec = $this->getURIDec();
        $validate = new App_Validate();

        $segments = explode('/', $uri_dec);

        if (count($segments) < 2)
        {
            $uri = $uri_dec;
        }
        else
        {
            $contr = array_shift($segments);
            $act = array_shift($segments);
            $uri = $contr.'/'.$act;
        }
        
        if (isset($this->routes[$uri]))
        {
            $this->controller = $this->routes[$uri]['controller'].'Controller';
            $this->action = 'action'.trim($validate->my_ucwords($this->routes[$uri]['action']));
        }

        if (!$this->controller || !$this->action)
        {
            $errors['no_controller_or_action'] = 'Указанный вами путь не найден.';
            $error_file = ROOT . '/app/views/error/error.php';
            if (file_exists($error_file))
            {
                include_once $error_file;
            }
            else
            {
                echo $errors['no_controller_or_action'];
            }
            return false;
        }

        $controller = $this->controller;
        $action = $this->action;

        if (!is_callable([$controller, $action]))
        {
            $errors['no_callable'] = 'Функция не может быть вызвана.';
            $error_file = ROOT . '/app/views/error/error.php';
            if (file_exists($error_file))
            {
                include_once $error_file;
            }
            else
            {
                echo $errors['no_callable'];
            }
            return false;
        }
        // Создаем новый объект
        $controller_obj = new $controller;
        // Вызываем функцию
        $controller_obj->$action();
    }

}
