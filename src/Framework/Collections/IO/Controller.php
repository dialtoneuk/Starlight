<?php
    namespace Starlight\Framework\Collections\IO;

    /**
     * Lewis Lancaster 2017
     *
     * Class Controller
     *
     * @package Starlight\Framework\Collections\IO
     */

    use Exception;

    class Controller
    {

        /**
         * Creates a new file
         *
         * @param $path
         *
         * @param null $data
         *
         * @return File
         *
         * @throws Exception
         */

        public static function createFile( $path, $data = null )
        {

            file_put_contents( self::getRealPath( $path ), $data );

            if( self::exists( $path ) == false )
            {

                throw new Exception('File failed to be created possibly due to permission error');
            }

            return new File( $path );
        }

        /**
         * Creates a new directory
         *
         * @param $dir
         *
         * @return Directory
         *
         * @throws Exception
         */

        public static function createDir( $dir )
        {

            mkdir( $dir );

            if( self::exists( $dir ) == false )
            {

                throw new Exception('Dir failed to be created possibly due to permission error');
            }

            return new Directory( $dir );
        }

        /**
         * Deletes a file
         *
         * @param $path
         */

        public static function deleteFile( $path )
        {

            unlink( self::getRealPath( $path ) );
        }

        /**
         * Deletes a directory
         *
         * @param $dir
         */

        public static function deleteDir( $dir )
        {

            rmdir( self::getRealPath( $dir ) );
        }

        /**
         * Checks if a path exists
         *
         * @param $path
         *
         * @return bool
         */

        public static function exists( $path )
        {

            return file_exists( self::getRealPath( $path ) );
        }

        /**
         * Gets the real path
         *
         * @param $path
         *
         * @return string
         */

        private static function getRealPath( $path )
        {

            return STARLIGHT_FILE_PATH . $path;
        }
    }