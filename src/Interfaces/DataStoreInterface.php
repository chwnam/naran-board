<?php


namespace Naran\Board\Interfaces;

/**
 * Interface DataStoreInterface
 *
 * @package Naran\Board\Interfaces
 *
 * 옵션, 커스텀 포스트, 택소노미 등의 정보를 가져오고 저장하기 위한 용도.
 * 일반 데이터베이스의 테이블처럼 쿼리문을 통해 정보의 일부를 조회, 조작하기보다는
 * 정보의 덩어리를 모두 가져와 있는 상태를 상정한다.
 */
interface DataStoreInterface
{
    public function delete($id);

    public function get($id);

    public function getValues();

    public function load();

    public function save();

    public function set($id, $value);

    public function import($records);

    public function export();

    public static function getStorageKey();

    public static function getDefaultValues();
}