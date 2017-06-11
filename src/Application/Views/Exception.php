<?php
    namespace Starlight\Application\Views;

    /**
     * Lewis Lancaster 2017
     *
     * Class Exception
     *
     * @package Starlight\Application\Views
     */

    use Flight;
    use Starlight\Framework\Collections\Views\Structures\View;
    use Starlight\Framework\Manager;

    class Exception implements View
    {

        public function routes()
        {

            return [
                '/exception/' => 'index'
            ];
        }

        public function index()
        {

            if( Manager::hasContainerProperty('exceptions') == false )
            {

                Flight::notFound();
            }

            print_r( end( Manager::$container->exceptions->exceptions )['trace'] );
        }
    }