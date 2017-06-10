<?php
    namespace Starlight\Application\Views;

    /**
     * Lewis Lancaster 2017
     *
     * Class Exception
     *
     * @package Starlight\Application\Views
     */

    use Flight;
    use Starlight\Framework\Collections\Exceptions;
    use Starlight\Framework\Collections\Views\Structures\View;

    class Exception implements View
    {

        protected $exception;

        public function __construct()
        {

            $this->exception = new Exceptions();
        }

        public function routes()
        {

            return [
                '/exception/' => 'index'
            ];
        }

        public function index()
        {

            if( empty( $this->exception->exceptions ) )
            {

                Flight::notFound();
            }

            print_r( end( $this->exception->exceptions )['trace'] );
        }
    }