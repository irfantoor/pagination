# Irfan's Pagination

A simple pagination library, which can be used to create the pagination links at the
bottom of a page.

## Quick Start

__example__:
```php
<html>
    <head>
        <style>
            .pagination a {text-decoration: none}

            /* from bootstrap.css */
            .pagination {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            padding-left: 0;
            list-style: none;
            border-radius: 0.25rem;
            }

            .page-link {
            position: relative;
            display: block;
            padding: 0.5rem 0.75rem;
            margin-left: -1px;
            line-height: 1.25;
            color: #007bff;
            background-color: #fff;
            border: 1px solid #dee2e6;
            }

            .page-link:hover {
            color: #0056b3;
            text-decoration: none;
            background-color: #e9ecef;
            border-color: #dee2e6;
            }

            .page-link:focus {
            z-index: 2;
            outline: 0;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
            }

            .page-link:not(:disabled):not(.disabled) {
            cursor: pointer;
            }

            .page-item:first-child .page-link {
            margin-left: 0;
            border-top-left-radius: 0.25rem;
            border-bottom-left-radius: 0.25rem;
            }

            .page-item:last-child .page-link {
            border-top-right-radius: 0.25rem;
            border-bottom-right-radius: 0.25rem;
            }

            .page-item.active .page-link {
            z-index: 1;
            color: #fff;
            background-color: #007bff;
            border-color: #007bff;
            }

            .page-item.disabled .page-link {
            color: #6c757d;
            pointer-events: none;
            cursor: auto;
            background-color: #fff;
            border-color: #dee2e6;
            }
        </style>
    </head>
<body>

<?php
    require dirname(__DIR__) . "/vendor/autoload.php";

    use IrfanTOOR\Pagination;

    $p = new Pagination();
    echo $p->html(100);         # normal pagination
    echo $p->html(100, true);   # reverse pagination
?>

</body>
</html>
```

## Construct Pagination

__method__: new Pagination($options = [])

__parameters__:
 - array $options Array of pagination options

__example__:
```php
<?php

use IrfanTOOR\Pagiation;

$pagination = new Pagination([
    'base_url' => '/blog//',
    'per_page' => 20,
    'int_pages' => 9,
]);
```

### Set the base url

__method__: setBaseUrl(string $url)

__parameteres__:
 - string url - base url to be used while doing the pagination default is '/'

__returns__: nothing

__example__:
```php
<?php
$pagination->setBaseUrl('/users//');
```

### Set the page component name

__method__: setPageComponent(string $page_component)

__parameteres__:
 - string $page_component Name of the page component in URL, used to extract 
 the page number from $_GET

__returns__: nothing

__example__:
```php
<?php
$pagination->setPageComponent('page_no');
# now the url will be like : .../?page_no=3
```

### Set the number of entries per page

__method__: setPerPage(int $per_page)

__parameteres__:
 - int $per_page - number of entries to be displayed on a page default is 10

__returns__: nothing

__example__:
```php
<?php
$per_page = 100;
$pagination->setPerPage($per_page);
```

### Number of intermediate pages

__method__: setIntermediatePages(int $int_pages)

__parameteres__:
 - int $int_page - number of intermediate pages to be displayed in the pagination bar, default is 5 (should always be odd)

__returns__: nothing

__example__:
```php
<?php
$pagination->setIntermediatePages(7);
```

### Current page

Current page number as passed through $_GET global variable or 1
Note: This can be overridden, to apply any kind of normalizations etc.

__method__: currentPage()

__parameteres__: none

__returns__:

int Current page number

__example__:
```php
<?php
$page = $pagination->currentPage();
```

### Retrieve the pagination html

NOTE: it retrieves the current page from ```$_GET['page']

__method__: setIntermediatePages(int $int_pages)

__parameteres__:
 - int  $total   Total number of records
 - bool $reverse Normal (false), or Reverse pagination (true)

__returns__: 

Html block which can be displayed directly in an html page

__example__:
```php
<?php
# ...
$total = 10000; # from a database query for example
echo $pagination->html($total);       # normal pagination
echo $pagination->html($total, true); # reverse pagination
```
