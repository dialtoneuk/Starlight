<?php
    namespace Starlight\Framework\Collections\Views;

    /**
     * Lewis Lancaster 2017
     *
     * Class Flight
     *
     * @package Starlight\Framework\Collections\Views
     */

    use Flight as Renderer;
    use Starlight\Framework\Collections\Settings;

    class Flight
    {

        /**
         * Holds a list of all the pages we have rendered for debugging
         *
         * @var array
         */

        protected static $payload = [];

        /**
         * Sets the view location
         *
         * @param $location
         */

        public static function setViewLocation( $location )
        {

            Renderer::set('flight.views.path', $location );
        }

        /**
         * Renders a page
         *
         * @param $file
         *
         * @param array $variables
         */

        public static function render( $file, array $variables )
        {

            if( Settings::getSetting('flight.payload') == true )
            {

                if( isset( self::$payload[ $file ] ) )
                {

                    if( self::$payload[ $file ] !== $variables )
                    {

                        self::$payload[ $file ] = $variables;
                    }
                }
                else
                {

                    self::$payload[ $file ] = $variables;
                }
            }

            Renderer::render( $file, $variables );
        }

        /**
         * Renders file to a layout
         *
         * @param $file
         *
         * @param array $variables
         *
         * @param $template
         */

        public static function layout( $file, array $variables, $template )
        {

            Renderer::render( $file, $variables, $template );
        }

        /**
         * Redirects the user to another page and holds the given variables in the session IF the session
         * is currently active.
         *
         * @param $page
         *
         * @param array $variables
         */

        public static function redirect( $page, array $variables )
        {

            if( session_status() == PHP_SESSION_ACTIVE )
            {

                if( empty( $variables ) == false )
                {

                    $_SESSION = array_merge( $_SESSION, $variables );
                }
            }

            Renderer::redirect( $page );
        }

        /**
         * Halts the renderer
         *
         * @param int $code
         *
         * @param string $message
         */

        public static function halt( int $code = 200, string $message = 'Halted' )
        {

            Renderer::halt( $code, $message );
        }

        /**
         * Gets the request
         *
         * @return \flight\net\Request
         */

        public static function request()
        {

            return Renderer::request();
        }

        /**
         * Gets the type
         *
         * @return string
         */

        public static function type()
        {

            return self::request()->type;
        }

        /**
         * Returns true if this request is a json type request
         *
         * @return bool
         */

        public static function isJsonRequest()
        {

            if( self::type() == 'application/json' )
            {

                return true;
            }

            return false;
        }

        /**
         * Gets the data from the request
         *
         * @return \flight\util\Collection
         */

        public static function data()
        {

            return self::request()->data;
        }

        /**
         * Returns true if this datafield is set
         *
         * @param $data
         *
         * @return bool
         */

        public static function hasDataField( $data )
        {

            if( isset( self::data()->$data ) == false )
            {

                return false;
            }

            return true;
        }

        /**
         * Gets the uploaded files
         *
         * @return \flight\util\Collection
         */

        public static function files()
        {

            return self::request()->files;
        }

        /**
         * Gets the ip address of the user
         *
         * @return string
         */

        public static function ip()
        {

            return self::request()->ip;
        }

        /**
         * Gets the method
         *
         * @return string
         */

        public static function method()
        {

            return self::request()->method;
        }

        /**
         * Returns true if the method is post
         *
         * @return bool
         */

        public static function isPost()
        {

            if( self::method() == 'POST' )
            {

                return true;
            }

            return false;
        }

        /**
         * Renders json
         *
         * @param array $data
         */

        public static function json( array $data )
        {

            Renderer::json( $data );
        }

        /**
         * Gets the payload of al the rendered pages
         *
         * @return array
         */

        public static function getPayload()
        {

            return self::$payload;
        }

        /**
         * Returns true if this render file exists
         *
         * @param $file
         *
         * @return bool
         */

        public static function fileExists( $file )
        {

            if( file_exists( Settings::getSetting('flight.views') . $file ) == false )
            {

                return false;
            }

            return true;
        }
    }