<?php

/**
 * IrfanTOOR\Pagination
 * php version 7.3
 *
 * @author    Irfan TOOR <email@irfantoor.com>
 * @copyright 2021 Irfan TOOR
 */

namespace IrfanTOOR;

use Exception;
use Throwable;

/**
 * Pagination -- Creates normal or reversed pagination
 */
class Pagination
{
    const NAME        = "Irfan's Pagination";
    const DESCRIPTION = "Creates normal or reversed pagination";
    const VERSION     = "0.2";

    /** @var string Base url to be used during pagination */
    protected $base_url = "/";

    /** @var int Number of results present per page */
    protected $per_page = 10;

    /** @var int Interval of pages to be printed while preparing pagination */
    protected $int_pages = 5;

    /** @var const Name of the page component in URL, extracts from $_GET */
    protected $page_component = 'page';

    /**
     * Construct Pagination
     *
     * @param array $options Array of pagination options
     */
    public function __construct($options = [])
    {
        if (isset($options['base_url']))
            $this->setBaseUrl($options['base_url']);

        if (isset($options['per_page']))
            $this->setPerPage($options['per_page']);

        if (isset($options['int_pages']))
            $this->setIntermediatePages($options['int_pages']);
    }

    /**
     * Set the base url
     *
     * @param string $url Base url to be used while doing the pagination
     *
     * @return void
     */
    public function setBaseUrl(string $url)
    {
        $this->base_url = $url;
    }

    /**
     * Set the page component name
     *
     * @param string $page_component Name of the page component in URL, used to
     *                               extract the page number from $_GET
     *
     * @return void
     */
    public function setPageComponent(string $page_component)
    {
        $this->page_component = $page_component;
    }

    /**
     * Set the number of entries per page
     *
     * @param int $per_page Number of entries to be displayed on a page
     *
     * @return void
     */
    public function setPerPage(int $per_page)
    {
        $this->per_page = $per_page;
    }

    /**
     * Number of intermediate pages
     *
     * @param int $int_pages Number of intermediate pages to be displayed in the
     *                       pagination bar
     *
     * @return void
     */
    public function setIntermediatePages(int $int_pages)
    {
        // intermediate pages must always be an odd number
        $this->int_pages = $int_pages - $int_pages % 2 + 1;
    }

    /**
     * Current page
     *
     * Current page number as passed through $_GET global variable or 1
     * Note: This can be overridden, to apply any kind of normalizations etc.
     *
     * @return int Current page number
     */
    public function currentPage(): int
    {
        return (int) ($_GET[$this->page_component] ?? 1);
    }

    /**
     * Process the pages according to the total records
     *
     * Note: this data can be used to populate personalaized templates of pagination
     *
     * @param int $total Total number of records
     *
     * @return mixed Associative array containing the pagination information or null
     */
    protected function process(int $total)
    {
        $per_page  = $this->per_page;
        $last      = ceil($total / $per_page);

        if ($last < 2) {
            return null;
        }

        $base_url = $this->base_url;
        $sep = strpos($base_url, '?') === false ? '?' : '&';
        $base_url .= $sep . $this->page_component . '=';
        $int_pages = $this->int_pages;

        $first = 1;

        $current = $this->currentPage();
        $current = $current < $first ? $first : $current;
        $current = $current > $last ? $last : $current;

        $prev = $current - 1;
        $prev = $prev ?: 0;

        $next = $current + 1;
        $next = $next > $last ? 0 : $next;

        $from = $current - ($int_pages - 1)/2;
        $from = $from > $first ? $from : $first;

        $to   = $from + $int_pages - 1;
        $to   = $to < $last ? $to : $last;

        $from = $to - $int_pages + 1;
        $from = $from > $first ? $from : $first;

        $first = $from == $first ? 0 : $first;
        $last = $to == $last ? 0 : $last;
        
        return compact(
            [
                'base_url', 
                'prev', 'first' , 'from', 
                'current', 
                'to', 'last', 'next',
            ]
        );
    }    

    /**
     * Prepares the pagination html
     * 
     * NOTE: it retrieves the current page from $_GET['page']
     *
     * @param int  $total   Total number of records
     * @param bool $reverse Normal (false), or Reverse pagination (true)
     *
     * @return string Html block which can be displayed directly in an html page
     */
    public function html(int $total, bool $reverse = false) :string
    {
        $data = $this->process($total);

        if (!$data) {
            return '';
        }

        return $reverse
            ? $this->reversePagination($data)
            : $this->normalPagination($data);
    }

    /**
     * Normal pagination
     *
     * @param array $data Associative array containing the pagination information
     *
     * @return string Html block which can be displayed directly in an html page
     */
    protected function normalPagination($data): string
    {
        extract($data);

        ob_start();
        echo PHP_EOL . '<ul class="pagination justify-content-center">' . PHP_EOL;

        if ($prev) {
            echo '<li class="page-item"><a class="page-link" href="' . $base_url . $prev . '" rel="prev">&laquo</a></li>' . PHP_EOL;
        } else {
            echo '<li class="page-item disabled"><a class="page-link" href="#">&laquo;</a></li>' . PHP_EOL;
        }

        if ($first) {
            echo '<li class="page-item"><a class="page-link" href="' . $base_url . $first . '">' . $first . '</a></li>' . PHP_EOL;
            if (($from - $first) > 1) {
                echo '<li class="page-item disabled"><a class="page-link" href="#">...</a></li>' . PHP_EOL;
            }
        }

        for ($i = $from; $i <= $to; $i++) {
            if ($i == $current) {
                echo '<li class="page-item active"><a class="page-link" href="#">' . $current . '</a></li>'. PHP_EOL;
            } else {
                echo '<li class="page-item"><a class="page-link" href="' . $base_url . $i . '">' . $i . '</a></li>' . PHP_EOL;
            }
        }

        if ($last) {
            if (($last - $to) > 1) {
                echo '<li class="page-item disabled"><a class="page-link" href="#">...</a></li>' . PHP_EOL;
            }

            echo '<li class="page-item"><a class="page-link" href="' . $base_url . $last . '">' . $last . '</a></li>' . PHP_EOL;
        }

        if ($next) {
            echo '<li class="page-item"><a class="page-link" href="' . $base_url . $next . '" rel="next">&raquo</a></li>' . PHP_EOL;
        } else {
            echo '<li class="page-item disabled"><a class="page-link" href="#">&raquo;</a></li>' . PHP_EOL;
        }

        echo '</ul>' . PHP_EOL;

        return ob_get_clean();
    }

    /**
     * Reverse pagination
     *
     * @param array $data Associative array containing the pagination information 
     *
     * @return string Html block which can be displayed directly in an html page
     */
    protected function reversePagination(array $data): string
    {
        extract($data);

        ob_start();
        echo PHP_EOL . '<ul class="pagination justify-content-center">' . PHP_EOL;

        if ($next) {
            echo '<li class="page-item"><a class="page-link" href="' . $base_url . $next . '" rel="next">&laquo</a></li>' . PHP_EOL;
        } else {
            echo '<li class="page-item disabled"><a class="page-link" href="#">&laquo;</a></li>' . PHP_EOL;
        }

        if ($last) {
            echo '<li class="page-item"><a class="page-link" href="' . $base_url . $last . '">' . $last . '</a></li>' . PHP_EOL;
            if (($last - $to) > 1) {
                echo '<li class="page-item disabled"><a class="page-link" href="#">...</a></li>' . PHP_EOL;
            }
        }

        for ($i = $to; $i >= $from; $i--) {
            if ($i == $current) {
                echo '<li class="page-item active"><a class="page-link" href="#">' . $current . '</a></li>'. PHP_EOL;
            } else {
                echo '<li class="page-item"><a class="page-link" href="' . $base_url . $i . '">' . $i . '</a></li>' . PHP_EOL;
            }
        }

        if ($first) {
            if (($from - $first) > 1) {
                echo '<li class="page-item disabled"><a class="page-link" href="#">...</a></li>' . PHP_EOL;
            }
            echo '<li class="page-item"><a class="page-link" href="' . $base_url . $first . '">' . $first . '</a></li>' . PHP_EOL;
        }

        if ($prev) {
            echo '<li class="page-item"><a class="page-link" href="' . $base_url . $prev . '" rel="prev">&raquo</a></li>' . PHP_EOL;
        } else {
            echo '<li class="page-item disabled"><a class="page-link" href="#">&raquo;</a></li>' . PHP_EOL;
        }


        echo '</ul>' . PHP_EOL;

        return ob_get_clean();
    }
}