<?php
    namespace Starlight\Framework\Collections\IO;

    /**
     * Lewis Lancaster 2017
     *
     * Class File
     *
     * @package Starlight\Framework\Collections\IO
     */

    use Exception;

    class File
    {

        /**
         * Holds the filepath
         *
         * @var string
         */

        protected $file;

        /**
         * File constructor.
         *
         * @param $file
         *
         * @throws Exception
         */

        public function __construct( $file )
        {

            if( file_exists( STARLIGHT_FILE_PATH . $file ) == false )
            {

                throw new Exception('File ' . $file . ' does not exist');
            }

            $this->file = $file;
        }

        /**
         * Returns true if this file is a valid json file
         *
         * @return bool
         */

        public function isJson()
        {

            json_decode( $this->read() );

            if( json_last_error() !== JSON_ERROR_NONE )
            {

                return false;
            }

            return true;
        }

        /**
         * Converts the contents of this file to an object
         *
         * @return mixed
         *
         * @throws Exception
         */

        public function toObject()
        {

            if( $this->isJson() == false )
            {

                throw new Exception('File needs to be a valid json type in order to convert to object');
            }

            return json_decode( $this->read() );
        }

        /**
         * Converts the contents of a file to an array
         *
         * @return mixed
         *
         * @throws Exception
         */

        public function toArray()
        {

            if( $this->isJson() == false )
            {

                throw new Exception('File needs to be a valid json type in order to convert to array');
            }

            return json_decode( $this->read(), true );
        }

        /**
         * Serializes data to a file
         *
         * @param $data
         */

        public function serialize( $data )
        {

            $this->write( serialize( $data ) );
        }

        /**
         * Unserializes data
         *
         * @return mixed
         */

        public function unserialize()
        {

            return unserialize( $this->read() );
        }

        /**
         * Reads the file
         *
         * @return string
         */

        public function read()
        {

            return file_get_contents( $this->getRealPath() );
        }

        /**
         * Writes to the file
         *
         * @param $data
         */

        public function write( $data )
        {

            file_put_contents( $this->getRealPath(), $data );
        }

        /**
         * Gets the size of a file
         *
         * @return int
         */

        public function size()
        {

            return filesize( $this->getRealPath() );
        }

        /**
         * Gets the files name
         *
         * @return mixed
         */

        public function getFileName()
        {

            return explode('.', basename( $this->getRealPath() ) )[0];
        }

        /**
         * Gets the path info
         *
         * @return mixed
         */

        public function getPathInfo()
        {

            return pathinfo( $this->getRealPath() );
        }

        /**
         * Gets the real path of the file
         *
         * @return string
         */

        private function getRealPath()
        {

            return STARLIGHT_FILE_PATH . $this->file;
        }
    }