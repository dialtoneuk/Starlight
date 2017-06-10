<?php
    namespace Starlight\Application\Views;

    /**
     * Lewis Lancaster 2017
     *
     * Class Developer
     *
     * @package Starlight\Application\Views
     */

    use Starlight\Framework\Collections\Settings;
    use Starlight\Framework\Collections\Views\Structures\View;

    class Developer implements View
    {

        public function routes()
        {

            //If the developer area is disabled, lets return no routes.

            if( Settings::getSetting('developer.disabled') == true )
            {

                return [];
            }

            return [
                '/developer/' => 'index'
            ];
        }

        public function index()
        {

            die('developer page');
        }
    }