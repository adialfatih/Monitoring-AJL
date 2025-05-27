<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller'] = 'beranda';
$route['dashboard'] = 'beranda';
$route['pembelian-benang/(:any)'] = 'beranda/belibenang'; 
$route['pembelian-benang'] = 'beranda/belibenang';
$route['data-pembelian-benang'] = 'beranda/belibenangdata';
$route['generate-kode/(:num)'] = 'beranda/generatekode/$1';
$route['stok-benang'] = 'beranda/stokbenang';
$route['stok-onpkg/(:any)'] = 'alldashboard/stokonpkg';
$route['warping'] = 'beranda/warping';
$route['warping-pershift/(:any)'] = 'beranda/warpingpershift/$1';
$route['sizing-pershift/(:any)'] = 'beranda/sizingpershift/$1';
$route['sizing'] = 'beranda/sizing';
$route['data-produksi-warping'] = 'beranda/dtwarping';
$route['data-produksi-sizing'] = 'beranda/dtsizing';
$route['data-beam-sizing'] = 'beranda/stokbeamsizing';
$route['data-beam-warping'] = 'beranda/stokbeamwarping';
$route['produksi-ajl'] = 'beranda/ajl';
$route['data-produksi-ajl'] = 'beranda/ajldata';
$route['lock-ajl/(:any)'] = 'apldtcs/view_lock';
$route['data-produksi-ajl2'] = 'beranda/ajldata2';
$route['data-operator'] = 'beranda/operatorp';
$route['login-operator'] = 'operator/login';
$route['input-loom'] = 'operator/inputloom';
$route['data-loom'] = 'operator/dataloom';
$route['operator-loom'] = 'operator/oploom';
$route['show-mesin'] = 'operator/mesin';
$route['add-operator-loom'] = 'operator/addoploom';
$route['data-bahan-baku'] = 'data/baku';
$route['input-sizing-opt'] = 'operator/sizing';
$route['input-sizing-form'] = 'operator/inputsizing';
$route['pembelian-baku'] = 'data/inputbaku';
$route['input-loom-produksi/(:any)'] = 'operator/inputloom2';
$route['produksi-mesin'] = 'operator/showproduksimesin';
$route['add-produksi-mesin'] = 'operator/produksimesin';
$route['get/oka/(:num)'] = 'apldtcs/getoka';
/*
| -------------------------------------------------------------------------
| Sample REST API Routes
| -------------------------------------------------------------------------
*/

$route['api/example/users/(:num)'] = 'api/example/users/id/$1'; // Example 4
$route['api/example/users/(:num)(\.)([a-zA-Z0-9_-]+)(.*)'] = 'api/example/users/id/$1/format/$3$4'; // Example 8
