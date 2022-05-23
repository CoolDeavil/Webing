<?php
/**
 * Created by PhpStorm.
 * User: Mike
 * Date: 17/02/2019
 * Time: 12:13
 */

namespace API\Interfaces;

interface RepositoryInterface
{
    public function getAll();
    public function getByID($id);
    public function deleteByID($id);
    public function registerNew($data);
    public function update($data);

}
