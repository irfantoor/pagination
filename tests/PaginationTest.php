<?php
/**
 * PaginationTest
 * php version 7.3
 *
 * @package   IrfanTOOR\Database
 * @author    Irfan TOOR <email@irfantoor.com>
 * @copyright 2020 Irfan TOOR
 */

use IrfanTOOR\Pagination;
use IrfanTOOR\Test;
use Tests\MockPagination;

class PaginationTest extends Test
{
    public function getPagination($para = [])
    {
        return new MockPagination($para);
    }

    public function testInstance()
    {
        $pagination= $this->getPagination();
        $this->assertInstanceOf(Pagination::class, $pagination);
    }

    public function testConstruct()
    {
        $init = [
            'base_url'  => '/my/url/',
            'per_page'  => 1,
            'int_pages' => 8,
        ];

        $pagination= $this->getPagination($init);
        $this->assertEquals($init['base_url'], $pagination->getVar('base_url'));
        $this->assertEquals($init['per_page'], $pagination->getVar('per_page'));
        $this->assertEquals($init['int_pages'] + 1, $pagination->getVar('int_pages'));
    }

    public function testSetBaseUrl()
    {
        $pagination= $this->getPagination();

        $this->assertTrue(method_exists($pagination, 'setBaseUrl'));
        $this->assertEquals('/', $pagination->getVar('base_url'));
        $pagination->setBaseUrl('/its/a/test/');
        $this->assertEquals('/its/a/test/', $pagination->getVar('base_url'));
    }

    public function testSetPerPage()
    {
        $pagination= $this->getPagination();

        $this->assertTrue(method_exists($pagination, 'setPerPage'));
        $this->assertEquals(10, $pagination->getVar('per_page'));
        $pagination->setPerPage(1);
        $this->assertEquals(1, $pagination->getVar('per_page'));
    }

    public function testSetIntermediatePages()
    {
        $pagination= $this->getPagination();

        $this->assertTrue(method_exists($pagination, 'setIntermediatePages'));
        $this->assertEquals(5, $pagination->getVar('int_pages'));
        $pagination->setIntermediatePages(7);
        $this->assertEquals(7, $pagination->getVar('int_pages'));
        $pagination->setIntermediatePages(8);
        $this->assertEquals(9, $pagination->getVar('int_pages'));

    }

    public function testPagination()
    {
        $pagination = $this->getPagination();

        $this->assertTrue(method_exists($pagination, 'html'));
        $pagination->setPerPage(3);
        $pagination->setCurrentPage(1);
        $html = $pagination->html(3);
        $this->assertEquals('', $html);

        $pagination->setPerPage(2);
        $pagination->setCurrentPage(2);
        $html = $pagination->html(3);

        $this->assertString($html);        
        $this->assertNotEquals('', $html);
        $this->assertTrue(strpos($html, 'page=1') !== false);
        $this->assertTrue(strpos($html, 'page=2') === false);

        $pagination->setPerPage(1);
        $pagination->setCurrentPage(3);
        $html = $pagination->html(3);

        $this->assertString($html);
        $this->assertNotEquals('', $html);
        $this->assertTrue(strpos($html, 'page=1') !== false);
        $this->assertTrue(strpos($html, 'page=2') !== false);
        $this->assertTrue(strpos($html, 'page=3') === false);
    }

    public function testReversePagination()
    {
        $pagination= $this->getPagination();

        $pagination->setPerPage(3);
        $pagination->setCurrentPage(1);
        $html = $pagination->html(3, true);
        $this->assertEquals('', $html);

        $pagination->setPerPage(2);
        $pagination->setCurrentPage(2);
        $html = $pagination->html(3, true);

        $this->assertString($html);
        $this->assertNotEquals('', $html);

        $this->assertTrue(strpos($html, 'page=2') === false);
        $this->assertTrue(strpos($html, 'page=1') !== false);

        $pagination->setPerPage(1);
        $pagination->setCurrentPage(3);
        $html = $pagination->html(3, true);

        $this->assertString($html);
        $this->assertNotEquals('', $html);
        $this->assertTrue(strpos($html, 'page=3') === false);
        $this->assertTrue(strpos($html, 'page=2') !== false);
        $this->assertTrue(strpos($html, 'page=1') !== false);
    }
}
