<?php
    namespace Starlight\Framework\Collections\Database;

    /**
     * Lewis Lancaster 2017
     *
     * Class Table
     *
     * @package Starlight\Framework\Collections\Database
     */

    use Exception;
    use ReflectionClass;

    class Table
    {

        protected $database;

        /**
         * Table constructor.
         *
         * @throws Exception
         */

        public function __construct()
        {

            if( (bool)class_parents( $this ) == false )
            {

                throw new Exception('Table class must have a parent');
            }

            if( Connection::hasConnection() == false )
            {

                throw new Exception('Database currently does not have a connection');
            }

            $this->database = Connection::getCapsule();
        }

        /**
         * Preforms a simple where query.
         *
         * @param $array
         *
         * @return \Illuminate\Database\Query\Builder
         */

        public function where( $array )
        {

            return $this->table()->where( $array );
        }

        /**
         * Returns the current table
         *
         * @return \Illuminate\Database\Query\Builder
         *
         * @throws Exception
         */

        public function table()
        {

            $table = strtolower( $this->getShortName() );

            if( $this->database->getConnection()->table( $table )->exists() == false )
            {

                try
                {

                    $this->database->getConnection()->table( $table )->get();
                }
                catch( Exception $error )
                {

                    throw new Exception('Table does not exist');
                }
            }

            return $this->database->getConnection()->table( $table );
        }

        /**
         * Gets the database connection
         *
         * @return \Illuminate\Database\Connection
         */

        public function getConnection()
        {

            return $this->database->getConnection();
        }

        /**
         * Gets the shortname of this class ( or the parent class if extended )
         *
         * @return string
         */

        private function getShortName()
        {

            $reflection = new ReflectionClass( $this );

            return $reflection->getShortName();
        }
    }