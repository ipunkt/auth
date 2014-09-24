<?php
/**
 * Created by PhpStorm.
 * User: sven
 * Date: 23.05.14
 * Time: 15:03
 */

namespace Ipunkt\Auth\Fascades;


use Illuminate\Support\Facades\Facade;

class Repository extends Facade {
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() { return 'Ipunkt\Auth\Repositories\RepositoryInterface'; }
}