<?php
namespace Rasty\cache;
/**
 * Interface para Cache
 * 
 * @author bernardo
 * @since 27-11-2013
 */
interface IRastyCache{
	
    public function fetch($id);

    public function contains($id);

    public function save($id, $data, $lifeTime = 0);

    public function delete($id);

    public function getStats();

    public function flushAll();

    public function deleteAll();

}