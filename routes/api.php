<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/api/auth/user', function (Request $request) {
    return $request->user();
});

Route::post('v1/loginmobile', 'UsersController@loginMobile');
Route::group(['middleware' => ['auth:api']], function () {
    Route::get('v1/getallproject', 'ProjectController@getAllProject');
    Route::get('v1/getallgudang', 'GudangController@getAllGudang');
    Route::get('v1/project/{kodeproject}/gudang/{kodegudang}/inventory/inbound/{noinbound}/detail', 'InboundController@getDetailInbound');
    Route::post('v1/project/{kodeproject}/gudang/{kodegudang}/inventory/inbound/{noinbound}/additem', 'InboundController@AddItemToExistingInbound');

    Route::get('v1/project/{kodeproject}/gudang/{kodegudang}/inventory/inbound/daftarinbound', 'InboundController@getListInbound');
    Route::get('v1/project/{kodeproject}/templateinbound', 'InboundController@loadTemplateInbound');
    Route::get('v1/project/{kodeproject}/gudang/{kodegudang}/inventory/getaksesprojectgudang', 'InventoryController@getAksesProjectGudang');
    Route::post('v1/project/getgudangfromproject', 'ProjectHasGudangController@getGudangFromProject');
    Route::get('v1/gudang/{kodegudang}/locator/getalllocator', 'LocatorController@getAllLocator');

    #region ITEM
    Route::get('v1/project/{kodeproject}/gudang/{kodegudang}/items/getallitem', 'ItemsController@getAllItem');
    Route::get('v1/project/{kodeproject}/gudang/{kodegudang}/items/getallitembaselabel', 'ItemsController@getAllItemBaseLabel');
    Route::post('v1/project/{kodeproject}/gudang/{kodegudang}/items/getallitemfrominbound', 'ItemsController@getAllItemFromInboundDetail');
    #endregion

    #region PUTAWAY
    Route::post('v1/project/{kodeproject}/gudang/{kodegudang}/inventory/putaway/prosesputaway', 'PutAwayController@prosesPutAway');
    Route::get('v1/project/{kodeproject}/gudang/{kodegudang}/inventory/putaway/displayreport', 'PutAwayController@displayReport');
    #endregion

    #region STOCK COUNT
    Route::post('v1/project/{kodeproject}/gudang/{kodegudang}/stock/{kodestock}/prosesstatus', 'StockCountController@checkStatusInventory');
    Route::get('v1/project/{kodeproject}/gudang/{kodegudang}/stock/generatekodestock', 'StockCountController@generateKodeStockCount');
    Route::post('v1/project/{kodeproject}/gudang/{kodegudang}/stock/{kodestock}/detail', 'StockCountController@getStockCountDetail');
    Route::post('v1/project/{kodeproject}/gudang/{kodegudang}/stock/store', 'StockCountController@store');
    #endregion

    #region INCOMING
    Route::post('v1/project/{kodeproject}/gudang/{kodegudang}/inventory/inbound/{noinbound}/incoming/store', 'IncomingInboundController@store');
    Route::get('v1/project/{kodeproject}/gudang/{kodegudang}/inventory/inbound/{noinbound}/daftarincoming', 'IncomingInboundController@getListIncoming');
    Route::get('v1/project/{kodeproject}/gudang/{kodegudang}/inventory/inbound/{noinbound}/incoming/generatenoincoming', 'IncomingInboundController@generateNoIncoming');
    Route::get('v1/project/{kodeproject}/gudang/{kodegudang}/inventory/inbound/{noinbound}/incoming/{noincoming}/incomingdetail', 'IncomingInboundController@getIncomingDetail');
    Route::post('v1/project/{kodeproject}/gudang/{kodegudang}/inventory/inbound/{noinbound}/incoming/{noincoming}/checklistitem', 'IncomingInboundController@storeToIncomingDetail');
    Route::get('v1/project/{kodeproject}/gudang/{kodegudang}/inventory/inbound/{noinbound}/incoming/{noincoming}/getlistchecklistitem', 'IncomingInboundController@prosesIncoming');
    Route::post('v1/project/{kodeproject}/gudang/{kodegudang}/inventory/inbound/{noinbound}/incoming/{noincoming}/finishdocument', 'IncomingInboundController@finishDocumentIncoming');
    Route::get('v1/project/{kodeproject}/gudang/{kodegudang}/items/getallitemfrominventory', 'ItemsController@getAllItemFromInventory');
    #endregion

    #region OUTBOUND
    Route::get('v1/project/{kodeproject}/gudang/{kodegudang}/inventory/outbound/{nooutbound}/detail', 'OutboundController@getOutboundDetail');
    Route::post('v1/project/{kodeproject}/gudang/{kodegudang}/inventory/outbound/{nooutbound}/picking', 'OutboundController@pickingProses');
    Route::get('v1/project/{kodeproject}/templateoutbound', 'OutboundController@loadTemplateOutbound');
    #endregion

    #region OUTGOING OUTBOUND
    Route::post('v1/project/{kodeproject}/gudang/{kodegudang}/inventory/outbound/{nooutbound}/outgoing/store', 'OutgoingOutboundController@store');
    Route::post('v1/project/{kodeproject}/gudang/{kodegudang}/inventory/outbound/{nooutbound}/outgoing/{nooutgoing}/prosesloading', 'OutgoingOutboundController@prosesPicking');
    Route::get('v1/project/{kodeproject}/gudang/{kodegudang}/inventory/outbound/{nooutbound}/outgoing/generatenooutgoing', 'OutgoingOutboundController@generateNoOutgoingOutbound');
    #endregion
});
