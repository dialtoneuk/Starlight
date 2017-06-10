<?php
    namespace Starlight\Framework\Collections;

    use Exception;

    /**
     * Lewis Lancaster 2017
     *
     * Class Encryption
     *
     * @package Starlight\Framework\Collections
     */
    class Encryption
    {

        /**
         * Holds our key
         *
         * @var string
         */

        public $key;

        /**
         * Encryption constructor.
         *
         * @param null $key
         *
         * @throws Exceptions
         */

        public function __construct( $key = null )
        {

            if( extension_loaded('opensll') == false )
            {

                throw new Exception('Extension opensll is required to encrypt data');
            }

            if( $key !== null )
            {

                $this->key = $key;
            }
            else
            {

                $this->key = base64_encode( $this->randomBytes() );
            }
        }

        /**
         * Encrypts an array
         *
         * @param array $data
         *
         * @param $iv
         *
         * @return array
         */

        public function encryptArray( array $data, $iv )
        {

            $result = [];

            foreach( $data as $key=>$value )
            {

                $result[ $this->encrypt( $key, $iv ) ] = $this->encrypt( $value, $iv );
            }

            return $result;
        }

        /**
         * Encrypts an encoded string
         *
         * @param string $data
         *
         * @param $iv
         *
         * @return string
         */

        public function encrypt( string $data, $iv )
        {

            return base64_encode( openssl_encrypt( $data, Settings::getSetting('encryption.method'), $this->key, 0, $iv ) );
        }

        /**
         * Decrypts an array
         *
         * @param array $data
         *
         * @param $iv
         *
         * @return array
         */

        public function decryptArray( array $data, $iv )
        {

            $result = [];

            foreach( $data as $key=>$value )
            {

                $result[ $this->decrypt( $key, $iv ) ] = $this->decrypt( $value, $iv );
            }

            return $result;
        }

        /**
         * Decrypts an encoded string
         *
         * @param string $data
         *
         * @param $iv
         *
         * @return string
         */

        public function decrypt( string $data, $iv )
        {

            return base64_decode( openssl_decrypt( $data, Settings::getSetting('encryption.method'), $this->key, 0, $iv ) );
        }

        /**
         * Generetes an IV
         *
         * @return string
         */

        public function generateIV()
        {

            return substr( $this->randomBytes(), 0, 16 );
        }

        /**
         * Generates random bytes
         *
         * @return string
         */

        private function randomBytes()
        {

            return openssl_random_pseudo_bytes(32);
        }
    }