<?php
    namespace Starlight\Framework\Collections\Database;

    /**
     * Lewis Lancaster 2017
     *
     * Class Connection
     *
     * @package Starlight\Framework\Collections\Database
     */

    use Exception;
    use Illuminate\Database\Capsule\Manager as Database;
    use Starlight\Framework\Collections\Encryption;
    use Starlight\Framework\Collections\IO\File;
    use Starlight\Framework\Collections\Settings;

    class Connection
    {

        /**
         * @var Database
         */

        protected static $capsule;

        /**
         * Creates the connection file
         *
         * @throws Exception
         */

        public static function create()
        {

            if( empty( Settings::$settings ) )
            {

                throw new Exception('Settings must be loaded in order to create database connection');
            }

            if( self::hasConnectionFile() == false )
            {

                throw new Exception('Database connection file does not exist');
            }

            self::$capsule = new Database();

            self::$capsule->addConnection( self::read() );

            self::$capsule->setAsGlobal();
        }

        /**
         * Returns true if we have a database connection
         *
         * @return bool
         */

        public static function hasConnection()
        {

            try
            {

                self::$capsule->getConnection();
            }
            catch( Exception $error )
            {

                return false;
            }

            return true;
        }

        /**
         * Gets the capsule
         *
         * @return Database
         */

        public static function getCapsule()
        {

            return self::$capsule;
        }

        /**
         * Reads the connection file
         *
         * @return mixed
         *
         * @throws Exception
         */

        private static function read()
        {

            $file = new File( Settings::getSetting('database.connection.file') );

            if( $file->isJson() == false )
            {

                throw new Exception('Connection file is in incorrect format');
            }

            //If we currently have database encryption set, lets decrypt our connection file

            if( Settings::getSetting('database.encryption') == true )
            {

                $data = $file->toArray();

                if( empty( $data ) )
                {

                    throw new Exception('Cannot decrypt an empty array');
                }

                if( isset( $data['key'] ) == false || isset( $data['iv'] ) == false )
                {

                    throw new Exception('Missing information in array');
                }

                $encryption = new Encryption( $data['key'] );

                return $encryption->decryptArray( $data, $data['iv'] );
            }

            return $file->toArray();
        }

        /**
         * Returns true if we have a connection file
         *
         * @return bool
         */

        private static function hasConnectionFile()
        {

            return file_exists( STARLIGHT_FILE_PATH . Settings::getSetting('database.connection.file') );
        }
    }