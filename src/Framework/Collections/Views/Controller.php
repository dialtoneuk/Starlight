<?php
    namespace Starlight\Framework\Collections\Views;

    /**
     * Lewis Lancaster 2017
     *
     * Class Controller
     *
     * @package Starlight\Framework\Collections\Views
     */

    use Exception;
    use Flight;
    use Starlight\Framework\Collections\IO\Directory;
    use Starlight\Framework\Collections\IO\File;
    use Starlight\Framework\Collections\Settings;
    use Starlight\Framework\Collections\Views\Structures\View;

    class Controller
    {

        /**
         * Holds the routes for each page
         *
         * @var array
         */

        protected static $routes = [];

        /**
         * Holds the route classes
         *
         * @var array
         */

        protected static $classes = [];

        /**
         * Loads the routes into the flight engine
         */

        public static function load()
        {

            self::setRoutes();

            foreach( self::$routes as $name=>$routes )
            {

                foreach( $routes as $key=>$value )
                {

                    if( self::hasClass( $name ) )
                    {

                        Flight::route( $key, array( self::getClass( $name ), $value ) );
                    }
                    else
                    {

                        Flight::route( $key, array( self::createClass( Settings::getSetting('views.namespace') . $name ), $value ) );
                    }
                }
            }
        }

        /**
         * Fills our routes array
         *
         * @throws Exception
         */

        private static function setRoutes()
        {

            $views = self::getViews();

            /** @var File $view */

            foreach($views as $view )
            {

                $name = $view->getFileName();

                if( class_exists( Settings::getSetting('views.namespace') . $name ) == false )
                {

                    throw new Exception('Class ' . Settings::getSetting('views.namespace') . $name . ' does not exist');
                }

                $class = self::createClass( Settings::getSetting('views.namespace') . $name );

                if( $class instanceof View == false )
                {

                    throw new Exception('Class ' . $name . ' must implement Page interface');
                }

                self::$routes[ $name ] = $class->routes();

                self::$classes[ $name ] = $class;
            }
        }

        /**
         * Returns true if we have this class
         *
         * @param $name
         *
         * @return bool
         */

        public static function hasClass( $name )
        {

            if( isset( self::$classes[ $name ] ) == false )
            {

                return false;
            }

            return true;
        }

        /**
         * Gets the routes
         *
         * @return array
         */

        public static function getRoutes()
        {

            return self::$routes;
        }

        /**
         * Gets the classes
         *
         * @return array
         */

        public static function getClasses()
        {

            return self::$classes;
        }

        /**
         * Clears the controller
         */

        public static function clear()
        {

            self::$routes = [];

            self::$classes = [];
        }

        /**
         * Returns a class depending on its name
         *
         * @param $name
         *
         * @return mixed
         */

        public static function getClass( $name )
        {

            return self::$classes[ $name ];
        }

        /**
         * Creates a class
         *
         * @param $class
         *
         * @return mixed
         */

        private static function createClass( $class )
        {

            return new $class;
        }

        /**
         * Gets the view files of the application
         *
         * @return File|\stdClass
         *
         * @throws Exception
         */

        private static function getViews()
        {

            $dir = new Directory( Settings::getSetting('views.location') );

            if( $dir->hasFiles() == false )
            {

                throw new Exception('Location of views is empty');
            }

            return $dir->getFiles();
        }
    }