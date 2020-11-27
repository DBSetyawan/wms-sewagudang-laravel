<?php

use App\Http\Controllers\GudangController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::redirect('/', '../public/login');


Route::group(['middleware' => 'checkRole'], function () {
    #region GUDANG
    Route::get('/gudang/daftargudang', 'GudangController@index')->name("gudang.index");
    Route::get('/gudang/tambahgudang', 'GudangController@create')->name("gudang.create");
    Route::get('/gudang/simpan', 'GudangController@store')->name("gudang.store");
    Route::get('/gudang/{gudang}/edit', 'GudangController@edit')->name('gudang.edit');
    Route::get('/gudang/{gudang}/update', 'GudangController@update')->name('gudang.update');
    Route::get('/gudang/generatekodegudang', 'GudangController@generateKodeGudang')->name('gudang.generatekodegudang');
    #endregion

    #region PROJECT
    Route::get('/testing', 'ProjectController@testingRole')->name('project.testing');
    Route::get('/project', 'ProjectController@index')->name('project.index');
    Route::get('/project/tambah', 'ProjectController@create')->name('project.create');
    Route::get('/project/store', 'ProjectController@store')->name('project.store');
    Route::get('/project/{kodeproject}/edit', 'ProjectController@edit')->name('project.edit');
    Route::get('/project/{kodeproject}/update', 'ProjectController@update')->name('project.update');
    Route::get('/project/{kodeproject}/delete', 'ProjectController@destroy')->name('project.delete');
    Route::get('/project/{kodeproject}/check', 'ProjectController@checkTemplateIsExist')->name('project.checktemplateisexist');
    Route::get('/project/{idperusahaan}/generatekodeproject', 'ProjectController@generateKodeProject')->name('project.generatekodeproject');
    #endregion



    #region PERUSAHAAN
    Route::get('/perusahaan_penyewa', 'PerusahaanController@index')->name('perusahaan.index');
    Route::get('/perusahaan_penyewa/all', 'PerusahaanController@getAllPerusahaan')->name('perusahaan.getall');
    Route::get('/perusahan_penyewa/tambah', 'PerusahaanController@create')->name('perusahaan.create');
    Route::get('/perusahaan_penyewa/store', 'PerusahaanController@store')->name('perusahaan.store');
    Route::get('/perusahaan_penyewa/{idperusahaan}/edit', 'PerusahaanController@edit')->name('perusahaan.edit');
    Route::get('/perusahaan_penyewa/{idperusahaan}/update', 'PerusahaanController@update')->name('perusahaan.update');
    #endregion

    #region MODUL
    Route::get('modul/daftarmodul', 'ModulController@index')->name('modul.index');
    Route::get('modul/create', 'ModulController@create')->name('modul.create');
    Route::get('modul/store', 'ModulController@store')->name('modul.store');
    Route::get('modul/{idmodul}/edit', 'ModulController@edit')->name('modul.edit');
    Route::get('modul/{idmodul}/update', 'ModulController@update')->name('modul.update');
    Route::get('modul/{idmodul}/delete', 'ModulController@destroy')->name('modul.delete');
    #endregion

    #region USER
    Route::get('/user/emailchecker', 'UsersController@emailExistChecker')->name('user.emailchecker');
    Route::get('/user/getalluser', 'UsersController@getAllUser')->name('user.getuserlist');
    Route::get('/user', 'UsersController@index')->name('user.index');
    Route::get('/user/logout', 'UsersController@logout')->name('user.logout');
    Route::get('/user/create', 'UsersController@create')->name('user.create');
    Route::post('/user/store', 'UsersController@store')->name('user.store');
    Route::get('/user/{id}/delete', 'UsersController@destroy')->name('user.delete');
    Route::get('/user/{id}/edit', 'UsersController@edit')->name('user.edit');
    Route::post('/user/{id}/update', 'UsersController@update')->name('user.update');
    #endregion

    #region ROLE
    Route::get('/role', 'RolesController@index')->name('role.index');
    Route::get('/role/create', 'RolesController@create')->name('role.create');
    Route::get('/role/store', 'RolesController@store')->name('role.store');
    Route::get('/role/{idrole}/edit', 'RolesController@edit')->name('role.edit');
    Route::get('/role/{idrole}/update', 'RolesController@update')->name('role.update');
    Route::get('/role/{idrole}/delete', 'RolesController@destroy')->name('role.delete');
    Route::get('/role/getallrole', 'RolesController@getAllRole')->name('role.getallrole');
    #endregion

    #region LOCATOR
    Route::get('/gudang/{kodegudang}/locator/getalllocator', 'LocatorController@getAllLocator')->name('locator.getalllocator');
    Route::get('/gudang/{kodegudang}/locator/tambahlocator', 'LocatorController@create')->name('locator.create');
    Route::get('/gudang/{kodegudang}/locator/simpanlocator', 'LocatorController@store')->name('locator.store');
    Route::get('/gudang/{kodegudang}/locator/', 'LocatorController@index')->name('locator.index');
    Route::get('/gudang/{kodegudang}/locator/{idlocator}/edit', 'LocatorController@edit')->name('locator.edit');
    Route::get('/gudang/{kodegudang}/locator/{idlocator}/update', 'LocatorController@update')->name('locator.update');
    #endregion

    #region TYPE LOCATOR
    Route::get('/gudang/{kodegudang}/typelocator/', 'TypeLocatorController@index')->name('typelocator.index');
    Route::get('/listtypelocator', 'TypeLocatorController@getAllTypeLocator')->name('typelocator.gettypelocator');
    #endregion

    #region PROJECT HAS GUDANG 
    Route::get('/project/{kodeproject}/gudang', 'ProjectHasGudangController@index')->name('projecthasgudang.index');
    Route::get('/project/{kodeproject}/gudang/create', 'ProjectHasGudangController@create')->name('projecthasgudang.create');
    Route::get('/project/{kodeproject}/gudang/store', 'ProjectHasGudangController@store')->name('projecthasgudang.store');
    Route::get('/project/{kodeproject}/gudang/{kodegudang}/edit', 'ProjectHasGudangController@edit')->name('projecthasgudang.edit');
    Route::get('/project/{kodeproject}/gudang/{kodegudang}/update', 'ProjectHasGudangController@update')->name('projecthasgudang.update');
    Route::get('/project/{kodeproject}/gudang/{kodegudang}/delete', 'ProjectHasGudangController@destroy')->name('projecthasgudang.delete');
    #endregion
});


Route::group(['middleware' => ['customerRole']], function () {
    #region INBOUND
    Route::get('/project/{kodeproject}/gudang/{kodegudang}/inventory/inbound/{noinbound}/removeitem', 'InboundController@removeItemFromDB')->name('inbound.removeitem');
    Route::post('/project/{kodeproject}/gudang/{kodegudang}/inventory/inbound/{noinbound}/uploadpod', 'InboundController@uploadPOD')->name('inbound.uploadpod');
    Route::get('/project/{kodeproject}/gudang/{kodegudang}/inventory/inbound/{noinbound}/podlist', 'InboundController@getAllPODList')->name('inbound.getallpod');
    Route::get('/project/{kodeproject}/gudang/{kodegudang}/inventory/inbound/{noinbound}/{idpod}/download', 'InboundController@downloadFile')->name('inbound.downloadpod');
    Route::get('/project/{kodeproject}/gudang/{kodegudang}/inventory/inbound/{noinbound}/{idpod}/hapus', 'InboundController@hapusFile')->name('inbound.hapuspod');
    Route::get('/project/{kodeproject}/buattemplateinbound', 'InboundController@buatTemplateInbound')->name('inbound.buattemplateinbound');
    Route::get('/project/{kodeproject}/gudang/{kodegudang}/inventory/inbound', 'InboundController@index')->name('inbound.index');
    Route::get('/project/{kodeproject}/gudang/{kodegudang}/inventory/inbound/report', 'InboundController@displayReport')->name('inbound.displayreport');
    Route::get('/project/{kodeproject}/gudang/{kodegudang}/inventory/inbound/buatinbound', 'InboundController@create')->name('inbound.create');
    Route::post('project/{kodeproject}/simpantemplateinbound', 'InboundController@saveTemplateInbound')->name('inbound.simpantemplateinbound');
    Route::get('/project/{kodeproject}/loadtemplateinbound', 'InboundController@loadTemplateInbound')->name('inbound.loadtemplateinbound');
    Route::get('/project/{kodeproject}/gudang/{kodegudang}/inventory/inbound/generatenoinbound', 'InboundController@generateNomorInbound')->name('inbound.generatenomorinbound');
    Route::post('/project/{kodeproject}/gudang/{kodegudang}/inventory/inbound/store', 'InboundController@store')->name('inbound.store');
    Route::get('/project/{kodeproject}/gudang/{kodegudang}/inventory/inbound/{noinbound}/info/{iditem}', 'InboundController@getItemInfoInInboundDetail')->name('inbound.getiteminfoininbounddetail');
    Route::get('/project/{kodeproject}/gudang/{kodegudang}/inventory/inbound/{noinbound}/info', 'InboundController@getDetailInbound')->name('inbound.getdetailinbound');
    Route::get('/project/{kodeproject}/gudang/{kodegudang}/inventory/inbound/{noinbound}/show', 'InboundController@show')->name('inbound.show');
    Route::get('/project/{kodeproject}/gudang/{kodegudang}/inventory/inbound/{noinbound}/edit', 'InboundController@edit')->name('inbound.edit');
    Route::post('/project/{kodeproject}/gudang/{kodegudang}/inventory/inbound/{noinbound}/update', 'InboundController@update')->name('inbound.update');
    Route::get('/project/{kodeproject}/gudang/{kodegudang}/inventory/inbound/{noinbound}/delete', 'InboundController@destroy')->name('inbound.delete');
    Route::post('/project/{kodeproject}/gudang/{kodegudang}/inventory/inbound/importexcel', 'InboundController@importExcelInbound')->name('inbound.uploadexcelinbound');
    Route::get('/project/{kodeproject}/gudang/{kodegudang}/inventory/inbound/downloadtemplateexcel', 'InboundController@exportExcelInbound')->name('inbound.downloadtemplateexcelinbound');
    #endregion

    #region INCOMING
    Route::get('/project/{kodeproject}/gudang/{kodegudang}/inventory/inbound/{noinbound}/incoming', 'IncomingInboundController@index')->name('incoming.index');
    Route::get('/project/{kodeproject}/gudang/{kodegudang}/inventory/inbound/{noinbound}/incoming/proses', 'IncomingInboundController@prosesIncoming')->name('incoming.proses');
    Route::get('/project/{kodeproject}/gudang/{kodegudang}/inventory/inbound/{noinbound}/incoming/{noincoming}/info', 'IncomingInboundController@getIncomingDetail')->name('incoming.info');
    Route::get('/project/{kodeproject}/gudang/{kodegudang}/inventory/inbound/{noinbound}/incoming/finishdocument', 'IncomingInboundController@finishDocumentIncoming')->name('incoming.finsihdocument');
    Route::get('/project/{kodeproject}/gudang/{kodegudang}/inventory/inbound/{noinbound}/incoming/create', 'IncomingInboundController@create')->name('incoming.create');
    Route::get('/project/{kodeproject}/gudang/{kodegudang}/inventory/inbound/{noinbound}/generatenoincoming', 'IncomingInboundController@generateNoIncoming')->name('incoming.generatenoincoming');
    Route::get('/project/{kodeproject}/gudang/{kodegudang}/inventory/inbound/{noinbound}/store', 'IncomingInboundController@store')->name('incoming.store');
    Route::get('/project/{kodeproject}/gudang/{kodegudang}/inventory/inbound/{noinbound}/incoming/{noincoming}/storetoincomingdetail', 'IncomingInboundController@storeToIncomingDetail')->name('incoming.storetoincomingdetail');
    Route::get('/project/{kodeproject}/gudang/{kodegudang}/inventory/inbound/{noinbound}/incoming/{noincoming}/edit', 'IncomingInboundController@edit')->name('incoming.edit');
    Route::get('/project/{kodeproject}/gudang/{kodegudang}/inventory/inbound/{noinbound}/incoming/{noincoming}/update', 'IncomingInboundController@update')->name('incoming.update');
    Route::get('/project/{kodeproject}/gudang/{kodegudang}/inventory/inbound/{noinbound}/incoming/{noincoming}/delete', 'IncomingInboundController@destroy')->name('incoming.delete');
    #endregion

    #region OUTBOUND
    Route::get('/project/{kodeproject}/gudang/{kodegudang}/inventory/outbound', 'OutboundController@index')->name('outbound.index');
    Route::get('/project/{kodeproject}/gudang/{kodegudang}/inventory/transfer', 'OutboundController@transferItem')->name('outbound.transfer');
    Route::get('/project/{kodeproject}/gudang/{kodegudang}/inventory/outbound/tambah', 'OutboundController@create')->name('outbound.create');
    Route::get('/project/{kodeproject}/gudang/{kodegudang}/inventory/outbound/store', 'OutboundController@store')->name('outbound.store');
    Route::get('/project/{kodeproject}/gudang/{kodegudang}/inventory/outbound/{nooutbound}/show', 'OutboundController@show')->name('outbound.show');
    Route::get('/project/{kodeproject}/gudang/{kodegudang}/inventory/outbound/report', 'OutboundController@displayReport')->name('outbound.displayreport');
    Route::get('/project/{kodeproject}/gudang/{kodegudang}/inventory/outbound/{nooutbound}/edit', 'OutboundController@edit')->name('outbound.edit');
    Route::get('/project/{kodeproject}/gudang/{kodegudang}/inventory/outbound/{nooutbound}/update', 'OutboundController@update')->name('outbound.update');
    Route::get('/project/{kodeproject}/gudang/{kodegudang}/inventory/outbound/{nooutbound}/delete', 'OutboundController@destroy')->name('outbound.delete');
    Route::get('/project/{kodeproject}/gudang/{kodegudang}/inventory/outbound/{nooutbound}/pickingproses', 'OutboundController@pickingProses')->name('outbound.pickingproses');
    Route::get('/project/{kodeproject}/gudang/{kodegudang}/inventory/outbound/canceloutbound', 'OutboundController@cancelOutbound')->name('outbound.canceloutbound');
    Route::get('/project/{kodeproject}/gudang/{kodegudang}/inventory/outbound/{nooutbound}/detail', 'OutboundController@getOutboundDetail')->name('outbound.getoutbounddetail');
    Route::get('/project/{kodeproject}/gudang/{kodegudang}/inventory/outbound/{nooutbound}/removeitemfromoutbound', 'OutboundController@removeItemFromOutbound')->name('outbound.removeitemfromoutbound');
    Route::get('/project/{kodeproject}/buattemplateoutbound', 'OutboundController@buatTemplateOutbound')->name('outbound.buattemplateoutbound');
    Route::post('/project/{kodeproject}/simpantemplateoutbound', 'OutboundController@saveTemplateOutbound')->name('outbound.simpantemplateoutbound');
    Route::get('/project/{kodeproject}/loadtemplateoutbound', 'OutboundController@loadTemplateOutbound')->name('outbound.loadtemplateoutbound');
    Route::get('/project/{kodeproject}/gudang/{kodegudang}/inventory/outbound/updateqtyavailable', 'OutboundController@updateAvailableQuantity')->name('outbound.updateqty');
    Route::get('/project/{kodeproject}/gudang/{kodegudang}/inventory/outbound/generatekodeoutbound', 'OutboundController@generateNomorInbound')->name('outbound.generatekodeoutbound');
    Route::get('/project/{kodeproject}/gudang/{kodegudang}/inventory/outbound/getitemavailable', 'OutboundController@getAllItemStilLAvailable')->name('outbound.getitemavailable');
    Route::get('/project/{kodeproject}/gudang/{kodegudang}/inventory/outbound/{nooutbound}/unsavelistitem', 'OutboundController@getUnsaveListItem')->name('outbound.unsavelistitem');
    Route::post('/project/{kodeproject}/gudang/{kodegudang}/inventory/outbound/{nooutbound}/uploadpod', 'OutboundController@uploadPOD')->name('outbound.uploadpod');
    Route::get('/project/{kodeproject}/gudang/{kodegudang}/inventory/outbound/{nooutbound}/podlist', 'OutboundController@getAllPODList')->name('outbound.getallpod');
    Route::get('/project/{kodeproject}/gudang/{kodegudang}/inventory/outbound/{nooutbound}/{idpod}/download', 'OutboundController@downloadFile')->name('outbound.downloadpod');
    Route::get('/project/{kodeproject}/gudang/{kodegudang}/inventory/outbound/{nooutbound}/{idpod}/hapus', 'OutboundController@hapusFile')->name('outbound.hapuspod');
    Route::post('/project/{kodeproject}/gudang/{kodegudang}/inventory/outbound/importoutbound', 'OutboundController@importExceOutbound')->name('outbound.importoutbound');
    Route::get('/project/{kodeproject}/gudang/{kodegudang}/inventory/outbound/downloadtemplateoutbound', 'OutboundController@exportExcelOutbound')->name('outbound.downloadtemplateoutbound');
    #endregion

    #region OUTGOING OUTBOUND
    Route::get('/project/{kodeproject}/gudang/{kodegudang}/inventory/outbound/{nooutbound}/outgoing', 'OutgoingOutboundController@index')->name('outgoing.index');
    Route::get('/project/{kodeproject}/gudang/{kodegudang}/inventory/outbound/{nooutbound}/outgoing/create', 'OutgoingOutboundController@create')->name('outgoing.create');
    Route::get('/project/{kodeproject}/gudang/{kodegudang}/inventory/outbound/{nooutbound}/outgoing/store', 'OutgoingOutboundController@store')->name('outgoing.store');
    Route::get('/project/{kodeproject}/gudang/{kodegudang}/inventory/outbound/{nooutbound}/outgoing/{nooutgoing}/edit', 'OutgoingOutboundController@edit')->name('outgoing.edit');
    Route::get('/project/{kodeproject}/gudang/{kodegudang}/inventory/outbound/{nooutbound}/outgoing/{nooutgoing}/update', 'OutgoingOutboundController@update')->name('outgoing.update');
    Route::get('/project/{kodeproject}/gudang/{kodegudang}/inventory/outbound/{nooutbound}/outgoing/{nooutgoing}/delete', 'OutgoingOutboundController@destroy')->name('outgoing.delete');
    Route::get('/project/{kodeproject}/gudang/{kodegudang}/inventory/outbound/{nooutbound}/outgoing/{nooutgoing}/prosespicking', 'OutgoingOutboundController@prosesPicking')->name('outgoing.prosespicking');
    Route::get('/project/{kodeproject}/gudang/{kodegudang}/inventory/outbound/{nooutbound}/outgoing/{nooutgoing}/getoutgoingdetail', 'OutgoingOutboundController@getOutgoingDetail')->name('outgoing.getoutgoingdetail');
    Route::get('/project/{kodeproject}/gudang/{kodegudang}/inventory/outbound/{nooutbound}/outgoing/generatenooutgoing', 'OutgoingOutboundController@generateNoOutgoingOutbound')->name('outgoing.generatenooutgoing');
    #endregion

    #region ITEM
    Route::get('/project/{kodeproject}/gudang/{kodegudang}/items/{kodeitem}/printbarcode', 'ItemsController@printBarcode')->name('item.printbarcode');
    Route::get('/project/{kodeproject}/gudang/{kodegudang}/items/getallitem', 'ItemsController@getAllItem')->name('item.getallitem');
    Route::get('/project/{kodeproject}/gudang/{kodegudang}/items/getallitembaselabel', 'ItemsController@getAllItemBaseLabel')->name('item.getallitembaselabel');
    Route::get('/project/{kodeproject}/gudang/{kodegudang}/items/store', 'ItemsController@store')->name('item.store');
    Route::get('/project/{kodeproject}/gudang/{kodegudang}/items/{kodeitem}/info', 'ItemsController@getInfoItem')->name('item.info');
    Route::get('/project/{kodeproject}/gudang/{kodegudang}/items/infofrominbounddetail', 'ItemsController@getAllItemFromInboundDetail')->name('item.infofrominbounddetail');
    Route::get('/project/{kodeproject}/gudang/{kodegudang}/items/generatekodeitem', 'ItemsController@generateKodeItem')->name('item.generatekodeitem');
    Route::get('/project/{kodeproject}/gudang/{kodegudang}/items/daftaritem', 'ItemsController@index')->name('item.index');
    Route::get('/project/{kodeproject}/gudang/{kodegudang}/items/create', 'ItemsController@create')->name('item.create');
    Route::get('/project/{kodeproject}/gudang/{kodegudang}/items/{kodeitem}/edit', 'ItemsController@edit')->name('item.edit');
    Route::get('/project/{kodeproject}/gudang/{kodegudang}/items/{kodeitem}/update', 'ItemsController@update')->name('item.update');
    Route::get('/project/{kodeproject}/gudang/{kodegudang}/items/{kodeitem}/delete', 'ItemsController@destroy')->name('item.delete');
    Route::get('/project/{kodeproject}/gudang/{kodegudang}/items/getitembaselocator', 'ItemsController@getItemBaseLocator')->name('item.getitembaselocator');
    Route::get('/project/{kodeproject}/gudang/{kodegudang}/items/getallitembaselocator', 'ItemsController@getAllItemBaseLocator')->name('item.getallitembaselocator');
    Route::get('/project/{kodeproject}/gudang/{kodegudang}/items/getallitemfrominventory', 'ItemsController@getAllItemFromInventory')->name('item.getallitemfrominventory');
    #endregion

    #region STOCK COUNT
    Route::get('/project/{kodeproject}/gudang/{kodegudang}/stock', 'StockCountController@index')->name('stock.index');
    Route::get('/project/{kodeproject}/gudang/{kodegudang}/stock/create', 'StockCountController@create')->name('stock.create');
    Route::get('/project/{kodeproject}/gudang/{kodegudang}/stock/store', 'StockCountController@store')->name('stock.store');
    Route::get('/project/{kodeproject}/gudang/{kodegudang}/stock/{kodestock}/checkstatusbaseitem', 'StockCountController@viewCheckStatusInventoryBaseItem')->name('stock.viewcheckstatusbaseitem');
    Route::get('/project/{kodeproject}/gudang/{kodegudang}/stock/{kodestock}/checkstatusbaselabel', 'StockCountController@viewCheckStatusInventoryBaseLabel')->name('stock.viewcheckstatusbaselabel');
    Route::get('/project/{kodeproject}/gudang/{kodegudang}/stock/{kodestock}/prosesstatus', 'StockCountController@checkStatusInventory')->name('stock.prosesstatus');
    Route::get('/project/{kodeproject}/gudang/{kodegudang}/stock/generatekodestock', 'StockCountController@generateKodeStockCount')->name('stock.generatekodestock');
    #endregion

    #region PUT AWAY
    Route::get('/project/{kodeproject}/gudang/{kodegudang}/inventory/putaway', 'PutAwayController@index')->name('putaway.index');
    Route::get('/project/{kodeproject}/gudang/{kodegudang}/inventory/putaway/prosesputaway', 'PutAwayController@prosesPutAway')->name('putaway.prosesputaway');
    Route::get('/project/{kodeproject}/gudang/{kodegudang}/inventory/displayreport', 'PutAwayController@displayReport')->name('putaway.displayreport');
    #endregion

    Route::get('/project/{kodeproject}/gudang/{kodegudang}/inventory/report', 'InventoryController@displayReport')->name('inventory.displayreport');
    Route::get('/project/{kodeproject}/gudang/{kodegudang}/inventory/reportbalanceinventory', 'InventoryController@displayReportBalanceInventory')->name('inventory.displayReportBalanceInventory');
});

Route::get('logout', '\App\Http\Controllers\Auth\LoginController@logout');

#region PROJECT HAS GUDANG
Route::get('/project/getgudangfromproject', 'ProjectHasGudangController@getGudangFromProject')->name('projecthasgudang.getgudangfromproject');
#endregion

#region INVENTORY
Route::get('/project/{kodeproject}/gudang/{kodegudang}/inventory', 'InventoryController@index')->name('inventory.index');
Route::get('/project/{kodeproject}/gudang/{kodegudang}/inventory/getaksesprojectgudang', 'InventoryController@getAksesProjectGudang')->name('inventory.getaksesprojectgudang');
#endregion

#region UTILITY
Route::get('/utility/provinsi', 'UtilityController@getProvinsi')->name('utility.getprovinsi');
Route::get('/utility/kabupaten', 'UtilityController@getKabupaten')->name('utility.getkabupaten');
Route::get('/utility/kecamatan', 'UtilityController@getKecamatan')->name('utility.getkecamatan');

#endregion

#region REDICRECTOR PAGE REPORT
Route::get('/project/{kodeproject}/gudang/{kodegudang}/reportinbound', 'ReportController@pageReportInbound')->name('report.pagereportinbound');
Route::get('/project/{kodeproject}/gudang/{kodegudang}/reportoutbound', 'ReportController@pageReportOutbound')->name('report.pagereportoutbound');
Route::get('/project/{kodeproject}/gudang/{kodegudang}/reportinventory', 'ReportController@pageReportInventory')->name('report.pagereportinventory');
Route::get('/project/{kodeproject}/gudang/{kodegudang}/reportbalanceinventory', 'ReportController@pageReportBalanceInventory')->name('report.pagereporbalancetinventory');
Route::get('/project/{kodeproject}/gudang/{kodegudang}/reportputaway', 'ReportController@pageReportPutaway')->name('report.pagereportputaway');
#endregion

Route::get('/user/logout', 'UsersController@logout')->name('user.logout');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/testingelo', 'GudangController@testingModelEloquent');
