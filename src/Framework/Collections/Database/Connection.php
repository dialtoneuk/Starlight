<?php
    namespace Starlight\Framework\Collections\Database;

    /**
     * Lewis Lancaster 2017
     *
     * Class Connection
     *
     * @package Starlight\Framework\Collections\Database
     */

    use Illuminate\Database\Capsule\Manager as Database;
    use Starlight\Framework\Collections\IO\File;
    use Starlight\Framework\Collections\Settings;
    use Exception;

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