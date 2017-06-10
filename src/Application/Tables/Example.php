<?php
    namespace Framework\Application\Tables;

    /**
     * Lewis Lancaster 2017
     *
     * Class Example
     *
     * @package Framework\Application\Tables
     */

    use Starlight\Framework\Collections\Database\Table;

    /**
     * The class name is relative to the table name inside of your database. In this instance, all of our queries will be preformed
     * in the table 'Example'
     *
     * Class Example
     *
     * @package Framework\Application\Tables
     */

    class Example extends Table
    {

        /**
         * Looks up for a table called 'Example' which is our class name and then does a simple where query to look for
         * the example id given.
         *
         * @param $exampleid
         *
         * @return \Illuminate\Support\Collection
         */

        public function getExample( $exampleid )
        {

            return $this->where( array(
                'exampleid' => $exampleid
            ))->get();
        }
    }