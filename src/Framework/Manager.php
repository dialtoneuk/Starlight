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
    use Starlight\Framework\Collections\Settings;

    class Manager
    {

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

        private static function doStartup()
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