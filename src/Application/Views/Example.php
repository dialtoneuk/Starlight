<?php
    namespace Starlight\Application\Views;

    /**
     * Lewis Lancaster 2017
     *
     * Class Example
     *
     * @package Framework\Application\Views
     */

    use Starlight\Framework\Collections\Views\Structures\View;

    class Example implements View
    {

        /**
         * The routes below translate to what happens when a user visits the given url. The key is relative to the websites
         * current url, so for instance, localhost/example/. The value points to the method of which will be called.
         *
         * @return array
         */

        public function routes()
        {

            return [
              '/example/' => 'test'
            ];
        }

        /**
         * Displays a message
         */

        public function test()
        {

            die('This is a test');
        }
    }