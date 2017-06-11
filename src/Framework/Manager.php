<?php
    namespace Starlight\Framework;

    /**
     * Lewis Lancaster 2017
     *
     * Class Manager
     *
     * @package Starlight\Framework
     */

    use Exception;
    use Flight;
    use ReflectionClass;
    use ReflectionMethod;
    use Starlight\Framework\Collections\Container;
    use Starlight\Framework\Collections\Exceptions;
    use Starlight\Framework\Collections\Settings;
    use Starlight\Framework\Collections\Views\Controller;
    use stdClass;

    class Manager
    {

        /**
         * @var Container
         */

        public static $container;

        /**
         * @var int
         */

        public static $state = 0;

        /**
         * Starts the framework
         */

        public static function start()
        {

            self::doStartup();

            if( empty( Settings::$settings ) )
            {

                throw new Exception('Failed to load settings');
            }

            if( Settings::getSetting('starlight.container') == true )
            {

                self::$container = new Container();
            }

            if( Settings::getSetting('flight.error.map') == true )
            {

                $exceptionhandler = new Exceptions();

                if( Settings::getSetting('starlight.container') == true )
                {

                    self::$container->exceptions = $exceptionhandler;
                }

                Flight::map('error', function( Exception $exception ) use ( $exceptionhandler )
                {

                    $exceptionhandler->handleException( $exception );

                    if( Settings::getSetting('flight.error.halt') == true )
                    {

                        Flight::halt(503,'Error');
                    }

                    Flight::redirect( Settings::getSetting('flight.error.page') );
                });
            }

            self::$state = 1;

            if( Settings::getSetting('flight.auto') == true )
            {

                Flight::start();
            }
        }

        /**
         * Preforms the frameworks startup
         *
         * @throws Exception
         */

        public static function doStartup()
        {

            $methods = self::getStartupMethods();

            if( empty( $methods ) )
            {

                return;
            }

            foreach( $methods as $key=>$value )
            {

                if( class_exists( $key ) == false )
                {

                    throw new Exception('Class ' . $key . ' does not exist');
                }

                if( self::hasMethod( $key, $value ) == false )
                {

                    throw new Exception('Class ' . $key . ' does not have method ' . $value );
                }

                if( self::isStaticMethod( $key, $value ) )
                {

                    forward_static_call( array( $key, $value ) );
                }
                else
                {

                    call_user_func( array( new $key, $value ) );
                }
            }
        }

        /**
         * Adds a value to the container
         *
         * @param $name
         *
         * @param $class
         */

        public static function addToContainer( $name, $class )
        {

            self::$container->$name = $class;
        }

        /**
         * Returns true if we have this container property
         *
         * @param $name
         *
         * @return bool
         */

        public static function hasContainerProperty( $name )
        {

            return isset( self::$container->$name );
        }

        /**
         * Stops the framework
         *
         * @throws Exception
         */

        public static function stop()
        {

            if( self::$state == 0 )
            {

                throw new Exception('Starlight cannot be stopped if it has not even begun');
            }

            self::$container->clear();

            if( Settings::getSetting('views.clear') == true )
            {

                Controller::clear();
            }

            Settings::$settings = new stdClass();

            self::$state = 0;
        }

        /**
         * Restarts the framework
         */

        public static function restart()
        {

            self::stop();

            self::start();
        }

        /**
         * Returns true if the framework is currently running
         *
         * @return bool
         */

        public static function running()
        {

            if( self::$state == 0 )
            {

                return false;
            }

            return true;
        }

        /**
         * Returns true if a class has this method
         *
         * @param $class
         *
         * @param $method
         *
         * @return bool
         */

        private static function hasMethod( $class, $method )
        {

            $reflection = new ReflectionClass( $class );

            return $reflection->hasMethod( $method );
        }

        /**
         * Returns true if this method is a static method
         *
         * @param $class
         *
         * @param $method
         *
         * @return bool
         *
         * @throws Exception
         */

        private static function isStaticMethod( $class, $method )
        {

            $reflection = new ReflectionClass( $class );

            if( $reflection->hasMethod( $method ) == false )
            {

                throw new Exception('Class ' . $class . ' does not have method ' . $method );
            }

            $reflectionmethod = new ReflectionMethod( $class . '::' . $method );

            return $reflectionmethod->isStatic();
        }

        /**
         * Gets the start up methods to execute
         *
         * @return mixed
         *
         * @throws Exception
         */

        private static function getStartupMethods()
        {

            $file = STARLIGHT_FILE_PATH . '/config/starlight.startup.json';

            if( file_exists( $file ) == false )
            {

                throw new Exception('Starlight startup file not found');
            }

            return json_decode( file_get_contents( $file ) );
        }
    }