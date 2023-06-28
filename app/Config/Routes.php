<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
// The Auto Routing (Legacy) is very dangerous. It is easy to create vulnerable apps
// where controller filters or CSRF protection are bypassed.
// If you don't want to define all routes, please use the Auto Routing (Improved).
// Set `$autoRoutesImproved` to true in `app/Config/Feature.php` and set the following to true.
// $routes->setAutoRoute(false);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/', 'Home::index');

$routes->group('v1', function($routes){
    // Login
    $routes->post('login', 'Auth::signIn');
    $routes->post('logout', 'Auth::logoutUser');

    // User
    $routes->post('user', 'Users::createUser');
    $routes->get('user', 'Users::getUser');
    $routes->get('user/(:num)', 'Users::getUserId/$1');
    $routes->put('user/(:num)', 'Users::updateUsers/$1');

    // Banner
    $routes->post('banner', 'Banner::createBanner');
    $routes->get('banner', 'Banner::getBanner');
    $routes->get('banner/(:num)', 'Banner::getBannerId/$1');
    $routes->delete('banner/(:num)', 'Banner::deleteBanner/$1');

    // Kategori
    $routes->post('kategori', 'Kategori::createKategori');
    $routes->get('kategori', 'Kategori::allKategori');
    $routes->get('kategori/(:num)', 'Kategori::idKategori/$1');
    $routes->put('kategori/(:num)', 'Kategori::updateKategori/$1');
    $routes->delete('kategori/(:num)', 'Kategori::deleteKategori/$1');

});

/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
