<?php
    namespace Starlight\Framework\Collections;

    /**
     * Lewis Lancaster 2017
     *
     * Class Settings
     *
     * @package Starlight\Framework\Collections;
     */

    use Exception;

    class Settings
    {

        /**
         * Holds the settings
         *
         * @var \stdClass
         */

        public static $settings;

        /**
         * Loads the settings
         */

        public static function load()
        {

            self::$settings = self::readSettings();
        }

        /**
         * Gets a setting
         *
         * @param $name
         *
         * @return mixed
         */

        public static function getSetting( $name )
        {

            return self::$settings->$name;
        }

        /**
         * Returns true if we have that setting
         *
         * @param $name
         *
         * @return bool
         */

        public static function hasSetting( $name )
        {

            if( isset( self::$settings->$name ) == false )
            {

                return false;
            }

            return true;
        }

        /**
         * Deletes a setting
         *
         * @param $name
         *
         * @throws Exceptions
         */

        public static function deleteSetting( $name )
        {

            if( isset( self::$settings->$name ) == false )
            {

                throw new Exception('Setting does not exists');
            }

            unset( self::$settings->$name );

            self::saveSettings();
        }

        /**
         * Adds a setting
         *
         * @param $name
         *
         * @param $value
         *
         * @throws Exceptions
         */

        public static function addSetting( $name, $value )
        {

            if( isset( self::$settings->$name ) )
            {

                throw new Exception('Setting already exists');
            }

            self::$settings->$name = $value;

            self::saveSettings();
        }

        /**
         * Saves our settings
         *
         * @throws Exceptions
         */

        private static function saveSettings()
        {

            if( empty( self::$settings ) )
            {

                throw new Exception('Settings are empty and thus cannot be saved');
            }

            $file = STARLIGHT_FILE_PATH . '/config/starlight.settings.json';

            file_put_contents( $file, json_encode( self::$settings ) );
        }

        /**
         * Reads the settings json file
         *
         * @return mixed
         *
         * @throws Exceptions
         */

        private static function readSettings()
        {

            $file = STARLIGHT_FILE_PATH . '/config/starlight.settings.json';

            if( file_exists( $file ) == false )
            {

                throw new Exception('Settings file does not exist');
            }

            return json_decode( file_get_contents( $file ) );
        }
    }