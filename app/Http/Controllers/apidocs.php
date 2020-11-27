<?php

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="WMS Sewagudang API",
 *      description="",
 *      @OA\Contact(
 *          email="thierry.horax@3permata.co.id"
 *      ),
 *     @OA\License(
 *         name="Apache 2.0",
 *         url="http://www.apache.org/licenses/LICENSE-2.0.html"
 *     )
 * )
 */

/**
 * @OA\get(
 *     path="/api/v1/project/{kodeproject}/gudang/{kodegudang}/inventory/inbound/{noinbound}/detail",
 *     tags={"Inbound"},
 *     summary="Return inbound detail",
 *     description="Untuk mengambil inbound detail",
 *     operationId="getInboundDetail",
 *     @OA\Parameter(
 *          name="kodeproject",
 *          description="Kode Project",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="string"
 *          )
 *     ),
 *     @OA\Parameter(
 *          name="kodegudang",
 *          description="Kode Gudang",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="string"
 *          )
 *     ),
 *     @OA\Parameter(
 *          name="noinbound",
 *          description="No Inbound",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="string"
 *          )
 *     ),
 *     @OA\Response(
 *         response="200",
 *         description="successful operation",
 *     ),
 * )
 */

/**
 * @OA\post(
 *     path="/api/v1/project/{kodeproject}/gudang/{kodegudang}/inventory/inbound/daftarinbound",
 *     tags={"Inbound"},
 *     summary="Return list inbound",
 *     description="Untuk mengambil list inbound",
 *     operationId="getListInbound",
 *     @OA\Parameter(
 *          name="kodeproject",
 *          description="Kode Project",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="string"
 *          )
 *     ),
 *     @OA\Parameter(
 *          name="kodegudang",
 *          description="Kode Gudang",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="string"
 *          )
 *     ),
 *     @OA\Response(
 *         response="200",
 *         description="successful operation",
 *     ),
 * )
 */

/**
 * @OA\get(
 *     path="/api/v1/getallproject",
 *     tags={"Project"},
 *     summary="Return list project",
 *     description="Untuk mengambil list project",
 *     operationId="getAllProject",
 *     
 *     @OA\Response(
 *         response="200",
 *         description="successful operation",
 *     ),
 * )
 */

/** 
 * @OA\get(
 *     path="/api/v1/getallgudang",
 *     tags={"Gudang"},
 *     summary="Return list gudang",
 *     description="Untuk mengambil list gudang",
 *     operationId="getAllGudang",
 *     @OA\Response(
 *         response="200",
 *         description="successful operation",
 *     ),
 * )
 */

/** 
 * @OA\get(
 *     path="/api/v1/project/{kodeproject}/templateinbound",
 *     tags={"Inbound"},
 *     summary="Return template inbound",
 *     description="Untuk mengambil format template inbound untuk project tertentu",
 *     operationId="getTemplateInbound",
 *     @OA\Parameter(
 *          name="kodeproject",
 *          description="Kode Project",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="string"
 *          )
 *     ),
 *     @OA\Response(
 *         response="200",
 *         description="successful operation",
 *     ),
 * )
 */

/** 
 * @OA\get(
 *     path="/api/v1/project/{kodeproject}/gudang/{kodegudang}/inventory/getaksesprojectgudang",
 *     tags={"Inventory"},
 *     summary="Return hak akses project gudang",
 *     description="Untuk mengambil daftar hak akses user pada project dan gudang tertentu",
 *     operationId="getAksesProjectGudang",
 *     @OA\Parameter(
 *          name="kodeproject",
 *          description="Kode Project",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="string"
 *          )
 *     ),
 *     @OA\Parameter(
 *          name="kodegudang",
 *          description="Kode Gudang",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="string"
 *          )
 *     ),
 *     @OA\Response(
 *         response="200",
 *         description="successful operation",
 *     ),
 * )
 */

/** 
 * @OA\post(
 *     path="/api/v1/project/getgudangfromproject",
 *     tags={"Project"},
 *     summary="Return list gudang di dalam suatu project.",
 *     description="Untuk mengambil daftar gudang dalam suatu project",
 *     operationId="getGudangFromProject",
 *     @OA\RequestBody(
 *         description="list_project berupa array id project",
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(
 *                 @OA\Property(
 *                     property="list_project",
 *                     type="array",
 *                     @OA\Items()
 *                 ),
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response="200",
 *         description="successful operation",
 *     ),
 * )
 */

/** 
 * @OA\get(
 *     path="/api/v1/gudang/{kodegudang}/locator/getalllocator",
 *     tags={"Locator"},
 *     summary="Return list locator gudang tertentu",
 *     description="Untuk mengambil daftar locator gudang tertentu",
 *     operationId="getAllLocator",
 *     @OA\Parameter(
 *          name="kodegudang",
 *          description="Kode Gudang",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="string"
 *          )
 *     ),
 *     @OA\Response(
 *         response="200",
 *         description="successful operation",
 *     ),
 * )
 */

/** 
 * @OA\get(
 *     path="/api/v1/project/{kodeproject}/gudang/{kodegudang}/items/getallitem",
 *     tags={"Item"},
 *     summary="Return list item pada project gudang tertentu",
 *     description="Untuk mengambil list item pada project gudang tertentu",
 *     operationId="getAllItem",
 *     @OA\Parameter(
 *          name="kodeproject",
 *          description="Kode Project",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="string"
 *          )
 *     ),
 *     @OA\Parameter(
 *          name="kodegudang",
 *          description="Kode Gudang",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="string"
 *          )
 *     ),
 *     @OA\Response(
 *         response="200",
 *         description="successful operation",
 *     ),
 * )
 */

/** 
 * @OA\post(
 *     path="/api/v1/project/{kodeproject}/gudang/{kodegudang}/items/getallitemfrominbound",
 *     tags={"Item"},
 *     summary="Return list item dari inbound.",
 *     description="Untuk mengambil list item dari inbound berdasarkan label / kode item",
 *     operationId="getAllItemFromInboundDetail",
 *     @OA\Parameter(
 *          name="kodeproject",
 *          description="Kode Project",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="string"
 *          )
 *     ),
 *     @OA\Parameter(
 *          name="kodegudang",
 *          description="Kode Gudang",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="string"
 *          )
 *     ),
 *     @OA\RequestBody(
 *       required=false,
 *       @OA\MediaType(
 *           mediaType="application/x-www-form-urlencoded",
 *           @OA\Schema(
 *               type="object",
 *               @OA\Property(
 *                   property="filter",
 *                   description="jenis filter, item / label",
 *                   type="string"
 *               ),
 *               @OA\Property(
 *                   property="filter_value",
 *                   description="Nilai filter",
 *                   type="string"
 *               ),
 *           )
 *       )
 *     ),
 *     @OA\Response(
 *         response="200",
 *         description="successful operation",
 *     ),
 * )
 */

/** 
 * @OA\post(
 *     path="/api/v1/project/{kodeproject}/gudang/{kodegudang}/items/store",
 *     tags={"Item"},
 *     summary="Store item ke dalam sistem",
 *     description="Untuk menyimpan item ke dalam sistem",
 *     operationId="storeItem",
 *     @OA\Parameter(
 *          name="kodeproject",
 *          description="Kode Project",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="string"
 *          )
 *     ),
 *     @OA\Parameter(
 *          name="kodegudang",
 *          description="Kode Gudang",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="string"
 *          )
 *     ),
 *     @OA\RequestBody(
 *       required=false,
 *       @OA\MediaType(
 *           mediaType="application/x-www-form-urlencoded",
 *           @OA\Schema(
 *               type="object",
 *               @OA\Property(
 *                   property="kode_item",
 *                   description="Kode Item",
 *                   type="string"
 *               ),
 *               @OA\Property(
 *                   property="label_barcode",
 *                   description="Label Barcode",
 *                   type="string"
 *               ),
 *               @OA\Property(
 *                   property="nama_item",
 *                   description="Nama Item",
 *                   type="string"
 *               ),
 *               @OA\Property(
 *                   property="hitung_cbm",
 *                   description="Hitung CBM, Langsung/Manual (P, L, T)",
 *                   type="string"
 *               ),
 *               @OA\Property(
 *                   property="panjang",
 *                   description="Panjang item, jika memilih hitung CBM manual",
 *                   type="double"
 *               ),
 *               @OA\Property(
 *                   property="lebar",
 *                   description="Lebar item, jika memilih hitung CBM manual",
 *                   type="double"
 *               ),
 *               @OA\Property(
 *                   property="tinggi",
 *                   description="Tinggi item, jika memilih hitung CBM manual",
 *                   type="double"
 *               ),
 *               @OA\Property(
 *                   property="cbm",
 *                   description="Nilai CBM",
 *                   type="double"
 *               ),
 *               @OA\Property(
 *                   property="berat_bersih",
 *                   description="Berat bersih",
 *                   type="double"
 *               ),
 *               @OA\Property(
 *                   property="berat_kotor",
 *                   description="Berat Kotor",
 *                   type="double"
 *               ),
 *               @OA\Property(
 *                   property="nama_uom",
 *                   description="Nama UOM",
 *                   type="string"
 *               ),
 *           )
 *       )
 *     ),
 *     @OA\Response(
 *         response="200",
 *         description="successful operation",
 *     ),
 * )
 */

/** 
 * @OA\get(
 *     path="/api/v1/project/{kodeproject}/gudang/{kodegudang}/items/generatekodeitem",
 *     tags={"Item"},
 *     summary="Generate kode item",
 *     description="Untuk generate kode item",
 *     operationId="generateKodeItem",
 *     @OA\Parameter(
 *          name="kodeproject",
 *          description="Kode Project",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="string"
 *          )
 *     ),
 *     @OA\Parameter(
 *          name="kodegudang",
 *          description="Kode Gudang",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="string"
 *          )
 *     ),
 *     @OA\Response(
 *         response="200",
 *         description="successful operation",
 *     ),
 * )
 */

/** 
 * @OA\post(
 *     path="/api/v1/project/{kodeproject}/gudang/{kodegudang}/inventory/putaway/prosesputaway",
 *     tags={"Putaway"},
 *     summary="Untuk melakukan proses putaway",
 *     description="Untuk Untuk melakukan proses putaway",
 *     operationId="prosesPutAway",
 *     @OA\Parameter(
 *          name="kodeproject",
 *          description="Kode Project",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="string"
 *          )
 *     ),
 *     @OA\Parameter(
 *          name="kodegudang",
 *          description="Kode Gudang",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="string"
 *          )
 *     ),
 *     @OA\RequestBody(
 *       required=false,
 *       @OA\MediaType(
 *           mediaType="application/x-www-form-urlencoded",
 *           @OA\Schema(
 *               type="object",
 *               @OA\Property(
 *                   property="idinventorydetail",
 *                   description="id inventory detail",
 *                   type="string"
 *               ),
 *               @OA\Property(
 *                   property="locator_lama",
 *                   description="id locator awal",
 *                   type="string"
 *               ),
 *               @OA\Property(
 *                   property="locator_baru",
 *                   description="id locator baru",
 *                   type="string"
 *               ),
 *               @OA\Property(
 *                   property="quantity_move",
 *                   description="Quantity",
 *                   type="integer"
 *               ),

 *               @OA\Property(
 *                   property="idinventory",
 *                   description="Id Inventory",
 *                   type="integer"
 *               ),
 *           )
 *       )
 *     ),
 *     @OA\Response(
 *         response="200",
 *         description="successful operation",
 *     ),
 * )
 */

/** 
 * @OA\get(
 *     path="/api/v1/project/{kodeproject}/gudang/{kodegudang}/inventory/putaway/displayreport",
 *     tags={"Putaway"},
 *     summary="Untuk menampilkan report putaway",
 *     description="Untuk menampilkan report putaway",
 *     operationId="displayReport",
 *     @OA\Parameter(
 *          name="kodeproject",
 *          description="Kode Project",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="string"
 *          )
 *     ),
 *     @OA\Parameter(
 *          name="kodegudang",
 *          description="Kode Gudang",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="string"
 *          )
 *     ),
 *     @OA\Response(
 *         response="200",
 *         description="successful operation",
 *     ),
 * )
 */

/** 
 * @OA\post(
 *     path="/api/v1/project/{kodeproject}/gudang/{kodegudang}/stock/{kodestock}/prosesstatus",
 *     tags={"Stock Count"},
 *     summary="Untuk melakukan proses check stock count",
 *     description="Untuk melakukan proses check stock count",
 *     operationId="checkStatusInventory",
 *     @OA\Parameter(
 *          name="kodeproject",
 *          description="Kode Project",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="string"
 *          )
 *     ),
 *     @OA\Parameter(
 *          name="kodegudang",
 *          description="Kode Gudang",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="string"
 *          )
 *     ),
 *     @OA\Parameter(
 *          name="kodestock",
 *          description="Kode Stock Count",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="string"
 *          )
 *     ),
 *     @OA\RequestBody(
 *       required=false,
 *       @OA\MediaType(
 *           mediaType="application/x-www-form-urlencoded",
 *           @OA\Schema(
 *               type="object",
 *               @OA\Property(
 *                   property="id_locator",
 *                   description="id locator",
 *                   type="string"
 *               ),
 *               @OA\Property(
 *                   property="stock_count_by",
 *                   description="Jenis filter stock count, item/label",
 *                   type="string"
 *               ),
 *               @OA\Property(
 *                   property="id_item",
 *                   description="id item",
 *                   type="string"
 *               ),
 *               @OA\Property(
 *                   property="idinventorydetail",
 *                   description="id inventory detail",
 *                   type="string"
 *               ),
 *               @OA\Property(
 *                   property="idinbounddetail",
 *                   description="id inbound detail",
 *                   type="string"
 *               ),

 *               @OA\Property(
 *                   property="qty",
 *                   description="Quantity",
 *                   type="integer"
 *               ),
 *           )
 *       )
 *     ),
 *     @OA\Response(
 *         response="200",
 *         description="successful operation",
 *     ),
 * )
 */

/** 
 * @OA\post(
 *     path="/api/v1/project/{kodeproject}/gudang/{kodegudang}/stock/{kodestock}/detail",
 *     tags={"Stock Count"},
 *     summary="Untuk mengambil stock count detail",
 *     description="Untuk mengambil stock count detail",
 *     operationId="getStockCountDetail",
 *     @OA\Parameter(
 *          name="kodeproject",
 *          description="Kode Project",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="string"
 *          )
 *     ),
 *     @OA\Parameter(
 *          name="kodegudang",
 *          description="Kode Gudang",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="string"
 *          )
 *     ),
 *     @OA\Parameter(
 *          name="kodestock",
 *          description="Kode Stock Count",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="string"
 *          )
 *     ),
 *     @OA\RequestBody(
 *       required=false,
 *       @OA\MediaType(
 *           mediaType="application/x-www-form-urlencoded",
 *           @OA\Schema(
 *               type="object",
 *               @OA\Property(
 *                   property="stock_count_by",
 *                   description="Jenis filter stock count, item/label",
 *                   type="string"
 *               ),
 *           )
 *       )
 *     ),
 *     @OA\Response(
 *         response="200",
 *         description="successful operation",
 *     ),
 * )
 */

/** 
 * @OA\post(
 *     path="/api/v1/project/{kodeproject}/gudang/{kodegudang}/stock/store",
 *     tags={"Stock Count"},
 *     summary="Untuk membuat stock count",
 *     description="Untuk membuat stock count",
 *     operationId="storeStockCount",
 *     @OA\Parameter(
 *          name="kodeproject",
 *          description="Kode Project",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="string"
 *          )
 *     ),
 *     @OA\Parameter(
 *          name="kodegudang",
 *          description="Kode Gudang",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="string"
 *          )
 *     ),
 *     @OA\RequestBody(
 *       required=false,
 *       @OA\MediaType(
 *           mediaType="application/x-www-form-urlencoded",
 *           @OA\Schema(
 *               type="object",
 *               @OA\Property(
 *                   property="kode_stock",
 *                   description="Kode Stock Count",
 *                   type="string"
 *               ),
 *               @OA\Property(
 *                   property="tanggal",
 *                   description="Tanggal pembuatan stock count",
 *                   type="string"
 *               ),
 *           )
 *       )
 *     ),
 *     @OA\Response(
 *         response="200",
 *         description="successful operation",
 *     ),
 * )
 */

/** 
 * @OA\get(
 *     path="/api/v1/project/{kodeproject}/gudang/{kodegudang}/stock/daftar_stock_count",
 *     tags={"Stock Count"},
 *     summary="Untuk mengambil list stock count",
 *     description="Untuk mengambil list stock count",
 *     operationId="getListStockCount",
 *     @OA\Parameter(
 *          name="kodeproject",
 *          description="Kode Project",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="string"
 *          )
 *     ),
 *     @OA\Parameter(
 *          name="kodegudang",
 *          description="Kode Gudang",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="string"
 *          )
 *     ),
 *     @OA\Response(
 *         response="200",
 *         description="successful operation",
 *     ),
 * )
 */

/** 
 * @OA\post(
 *     path="/api/v1/project/{kodeproject}/gudang/{kodegudang}/inventory/inbound/{noinbound}/incoming/store",
 *     tags={"Incoming Inbound"},
 *     summary="Untuk menambah incomingi inbound",
 *     description="Untuk menambah incomingi inbound",
 *     operationId="getListStockCount",
 *     @OA\Parameter(
 *          name="kodeproject",
 *          description="Kode Project",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="string"
 *          )
 *     ),
 *     @OA\Parameter(
 *          name="kodegudang",
 *          description="Kode Gudang",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="string"
 *          )
 *     ),
 *     @OA\Parameter(
 *          name="noinbound",
 *          description="No Inbound",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="string"
 *          )
 *     ),
 *     @OA\RequestBody(
 *       required=false,
 *       @OA\MediaType(
 *           mediaType="multipart/form-data",
 *           @OA\Schema(
 *               type="object",
 *               @OA\Property(
 *                   property="no_incoming_inbound",
 *                   description="No. Incoming Inbound",
 *                   type="string"
 *               ),
 *               @OA\Property(
 *                   property="tanggal_incoming_inbound",
 *                   description="Tanggal pembuatan nota incoming inbound",
 *                   type="string"
 *               ),
 *               @OA\Property(
 *                     property="incoming_inbound_value",
 *                     description="Value dari custom field pada incomingi nbound berupa array object dengan format <b>[{custom_field : <i>namaField</i>, value : <i>valueField</i>}]</b>",
 *                     type="array",
 *                     @OA\Items(type="object")
 *                 ),
 *           )
 *       ),
 *     ),
 *     @OA\Response(
 *         response="200",
 *         description="successful operation",
 *     ),
 * )
 */

/** 
 * @OA\get(
 *     path="/api/v1/project/{kodeproject}/gudang/{kodegudang}/inventory/inbound/{noinbound}/daftarincoming",
 *     tags={"Incoming Inbound"},
 *     summary="Untuk mengambil list incoming inbound",
 *     description="Untuk mengambil list incoming inbound",
 *     operationId="getListIncoming",
 *     @OA\Parameter(
 *          name="kodeproject",
 *          description="Kode Project",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="string"
 *          )
 *     ),
 *     @OA\Parameter(
 *          name="kodegudang",
 *          description="Kode Gudang",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="string"
 *          )
 *     ),
 *     @OA\Parameter(
 *          name="noinbound",
 *          description="No Inbound",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="string"
 *          )
 *     ),
 *     @OA\Response(
 *         response="200",
 *         description="successful operation",
 *     ),
 * )
 */

/** 
 * @OA\get(
 *     path="/api/v1/project/{kodeproject}/gudang/{kodegudang}/inventory/inbound/{noinbound}/incoming/generatenoincoming",
 *     tags={"Incoming Inbound"},
 *     summary="Untuk mengenerate nomor incoming inbound",
 *     description="Untuk mengenerate nomor incoming inbound",
 *     operationId="generateNoIncoming",
 *     @OA\Parameter(
 *          name="kodeproject",
 *          description="Kode Project",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="string"
 *          )
 *     ),
 *     @OA\Parameter(
 *          name="kodegudang",
 *          description="Kode Gudang",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="string"
 *          )
 *     ),
 *     @OA\Parameter(
 *          name="noinbound",
 *          description="No Inbound",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="string"
 *          )
 *     ),
 *     @OA\Response(
 *         response="200",
 *         description="successful operation",
 *     ),
 * )
 */

/** 
 * @OA\get(
 *     path="/api/v1/project/{kodeproject}/gudang/{kodegudang}/inventory/inbound/{noinbound}/incoming/{noincoming}/incomingdetail",
 *     tags={"Incoming Inbound"},
 *     summary="Untuk mengambil incoming inbound detail",
 *     description="Untuk mengambil incoming inbound detail",
 *     operationId="getIncomingDetail",
 *     @OA\Parameter(
 *          name="kodeproject",
 *          description="Kode Project",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="string"
 *          )
 *     ),
 *     @OA\Parameter(
 *          name="kodegudang",
 *          description="Kode Gudang",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="string"
 *          )
 *     ),
 *     @OA\Parameter(
 *          name="noinbound",
 *          description="No Inbound",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="string"
 *          )
 *     ),
 *     @OA\Parameter(
 *          name="noincoming",
 *          description="No. Incoming",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="string"
 *          )
 *     ),
 *     @OA\Response(
 *         response="200",
 *         description="successful operation",
 *     ),
 * )
 */

/** 
 * @OA\post(
 *     path="/api/v1/project/{kodeproject}/gudang/{kodegudang}/inventory/inbound/{noinbound}/incoming/{noincoming}/checklistitem",
 *     tags={"Incoming Inbound"},
 *     summary="Untuk memproses item pada incoming inbound",
 *     description="Untuk memproses item pada incoming inbound",
 *     operationId="storeToIncomingDetail",
 *     @OA\Parameter(
 *          name="kodeproject",
 *          description="Kode Project",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="string"
 *          )
 *     ),
 *     @OA\Parameter(
 *          name="kodegudang",
 *          description="Kode Gudang",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="string"
 *          )
 *     ),
 *     @OA\Parameter(
 *          name="noinbound",
 *          description="No Inbound",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="string"
 *          )
 *     ),
 *     @OA\Parameter(
 *          name="noincoming",
 *          description="No. Incoming",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="string"
 *          )
 *     ),
 *     @OA\RequestBody(
 *       required=false,
 *       @OA\MediaType(
 *           mediaType="multipart/form-data",
 *           @OA\Schema(
 *               type="object",
 *               @OA\Property(
 *                   property="quantity",
 *                   description="Quantity",
 *                   type="integer"
 *               ),
 *               @OA\Property(
 *                   property="locator",
 *                   description="id locator",
 *                   type="integer"
 *               ),
 *               @OA\Property(
 *                   property="id_item",
 *                   description="Id Item",
 *                   type="integer"
 *               ),
 *               @OA\Property(
 *                   property="id_inbound_detail",
 *                   description="Id Inbound Detail",
 *                   type="integer"
 *               ),
 *               
 *           )
 *       ),
 *     ),
 *     @OA\Response(
 *         response="200",
 *         description="successful operation",
 *     ),
 * )
 */

/** 
 * @OA\get(
 *     path="/api/v1/project/{kodeproject}/gudang/{kodegudang}/inventory/inbound/{noinbound}/incoming/{noincoming}/getlistchecklistitem",
 *     tags={"Incoming Inbound"},
 *     summary="Untuk mengambil list item yang sudah diproses pada incoming inbound",
 *     description="Untuk mengambil list item yang sudah diproses pada incoming inbound",
 *     operationId="getlistchecklistitem",
 *     @OA\Parameter(
 *          name="kodeproject",
 *          description="Kode Project",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="string"
 *          )
 *     ),
 *     @OA\Parameter(
 *          name="kodegudang",
 *          description="Kode Gudang",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="string"
 *          )
 *     ),
 *     @OA\Parameter(
 *          name="noinbound",
 *          description="No Inbound",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="string"
 *          )
 *     ),
 *     @OA\Parameter(
 *          name="noincoming",
 *          description="No. Incoming",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="string"
 *          )
 *     ),
 *     @OA\Response(
 *         response="200",
 *         description="successful operation",
 *     ),
 * )
 */

/** 
 * @OA\get(
 *     path="/api/v1/project/{kodeproject}/gudang/{kodegudang}/items/getallitemfrominventory",
 *     tags={"Item"},
 *     summary="Untuk mengambil list item dari inventory dan digunakan pada stock count",
 *     description="Untuk mengambil list item dari inventory dan digunakan pada stock count",
 *     operationId="getAllItemFromInventory",
 *     @OA\Parameter(
 *          name="kodeproject",
 *          description="Kode Project",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="string"
 *          )
 *     ),
 *     @OA\Parameter(
 *          name="kodegudang",
 *          description="Kode Gudang",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="string"
 *          )
 *     ),
 *     @OA\Response(
 *         response="200",
 *         description="successful operation",
 *     ),
 * )
 */

/** 
 * @OA\get(
 *     path="/api/v1/project/{kodeproject}/gudang/{kodegudang}/inventory/outbound/{nooutbound}/detail",
 *     tags={"Outbound"},
 *     summary="get outbound detail",
 *     description="Untuk mengambil outbound detail",
 *     operationId="getOutboundDetail",
 *     @OA\Parameter(
 *          name="kodeproject",
 *          description="Kode Project",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="string"
 *          )
 *     ),
 *     @OA\Parameter(
 *          name="kodegudang",
 *          description="Kode Gudang",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="string"
 *          )
 *     ),
 *     @OA\Parameter(
 *          name="nooutbound",
 *          description="No. Outbound",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="string"
 *          )
 *     ),
 *     @OA\Response(
 *         response="200",
 *         description="successful operation",
 *     ),
 * )
 */

/** 
 * @OA\post(
 *     path="/api/v1/project/{kodeproject}/gudang/{kodegudang}/inventory/outbound/{nooutbound}/picking",
 *     tags={"Outbound"},
 *     summary="post proses picking",
 *     description="Untuk melakukan proses picking item",
 *     operationId="prosesPicking",
 *     @OA\Parameter(
 *          name="kodeproject",
 *          description="Kode Project",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="string"
 *          )
 *     ),
 *     @OA\Parameter(
 *          name="kodegudang",
 *          description="Kode Gudang",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="string"
 *          )
 *     ),
 *     @OA\Parameter(
 *          name="nooutbound",
 *          description="No. Outbound",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="string"
 *          )
 *     ),
 *     @OA\RequestBody(
 *       required=false,
 *       @OA\MediaType(
 *           mediaType="multipart/form-data",
 *           @OA\Schema(
 *               type="object",
 *               @OA\Property(
 *                   property="idinventorydetail",
 *                   description="Id Inventory Detail",
 *                   type="integer"
 *               ),
 *               @OA\Property(
 *                   property="idoutbounddetail",
 *                   description="Id Outbound Detail",
 *                   type=" integer"
 *               ),
 *               @OA\Property(
 *                   property="qty",
 *                   description="Quantity",
 *                   type="integer"
 *               ),
 *               @OA\Property(
 *                   property="nooutbound",
 *                   description="No. Outbound",
 *                   type="string"
 *               ),
 *               @OA\Property(
 *                   property="action",
 *                   description="Action. Undo/Picked (perhatikan uppercase)",
 *                   type="string"
 *               ),
 *           )
 *       ),
 *     ),
 *     @OA\Response(
 *         response="200",
 *         description="successful operation",
 *     ),
 * )
 */

/** 
 * @OA\post(
 *     path="/api/v1/project/{kodeproject}/gudang/{kodegudang}/inventory/outbound/daftaroutbound",
 *     tags={"Outbound"},
 *     summary="Get list outbound",
 *     description="Untuk mengambil list outbound",
 *     operationId="getListOutbound",
 *     @OA\Parameter(
 *          name="kodeproject",
 *          description="Kode Project",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="string"
 *          )
 *     ),
 *     @OA\Parameter(
 *          name="kodegudang",
 *          description="Kode Gudang",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="string"
 *          )
 *     ),
 *     @OA\Response(
 *         response="200",
 *         description="successful operation",
 *     ),
 * )
 */

/** 
 * @OA\get(
 *     path="/api/v1/project/{kodeproject}/templateoutbound",
 *     tags={"Outbound"},
 *     summary="Get template custom outbound",
 *     description="Untuk mengambil template custom outbound",
 *     operationId="getTemplateOutbound",
 *     @OA\Parameter(
 *          name="kodeproject",
 *          description="Kode Project",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="string"
 *          )
 *     ),
 *     @OA\Response(
 *         response="200",
 *         description="successful operation",
 *     ),
 * )
 */

/** 
 * @OA\post(
 *     path="/api/v1/project/{kodeproject}/gudang/{kodegudang}/inventory/outbound/{nooutbound}/outgoing/store",
 *     tags={"Outgoing Outbound"},
 *     summary="post store document outgoing outbound",
 *     description="Untuk menyimpan dokumen outgoing outbound",
 *     operationId="storeOutgoingOutbound",
 *     @OA\Parameter(
 *          name="kodeproject",
 *          description="Kode Project",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="string"
 *          )
 *     ),
 *     @OA\Parameter(
 *          name="kodegudang",
 *          description="Kode Gudang",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="string"
 *          )
 *     ),
 *     @OA\Parameter(
 *          name="nooutbound",
 *          description="No. Outbound",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="string"
 *          )
 *     ),
 *     @OA\RequestBody(
 *       required=false,
 *       @OA\MediaType(
 *           mediaType="multipart/form-data",
 *           @OA\Schema(
 *               type="object",
 *               @OA\Property(
 *                   property="nooutgoingoutbound",
 *                   description="No. Outgoing Outbound",
 *                   type="string"
 *               ),
 *               @OA\Property(
 *                   property="tanggal_outgoing",
 *                   description="Tanggal Outbound",
 *                   type=" integer"
 *               ),
 *               @OA\Property(
 *                     property="outgoing_outbound_value",
 *                     description="Value dari custom field pada outgoing outbound berupa array object dengan format <b>[{custom_field : <i>namaField</i>, value : <i>valueField</i>}]</b>",
 *                     type="array",
 *                     @OA\Items(type="object")
 *                 ),
 *           )
 *       ),
 *     ),
 *     @OA\Response(
 *         response="200",
 *         description="successful operation",
 *     ),
 * )
 */

/** 
 * @OA\post(
 *     path="/api/v1/project/{kodeproject}/gudang/{kodegudang}/inventory/outbound/{nooutbound}/outgoing/{nooutgoing}/prosesloading",
 *     tags={"Outgoing Outbound"},
 *     summary="post proses loading",
 *     description="Untuk melaukan proses loading",
 *     operationId="prosesLoading",
 *     @OA\Parameter(
 *          name="kodeproject",
 *          description="Kode Project",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="string"
 *          )
 *     ),
 *     @OA\Parameter(
 *          name="kodegudang",
 *          description="Kode Gudang",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="string"
 *          )
 *     ),
 *     @OA\Parameter(
 *          name="nooutbound",
 *          description="No. Outbound",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="string"
 *          )
 *     ),
 *     @OA\Parameter(
 *          name="nooutgoing",
 *          description="No. Outgoing Outbound",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="string"
 *          )
 *     ),
 *     @OA\RequestBody(
 *       required=false,
 *       @OA\MediaType(
 *           mediaType="multipart/form-data",
 *           @OA\Schema(
 *               type="object",
 *               @OA\Property(
 *                   property="idinventorydetail",
 *                   description="Id Inventory Detail",
 *                   type="integer"
 *               ),
 *               @OA\Property(
 *                   property="idoutbounddetail",
 *                   description="Id Outbound Detail",
 *                   type=" integer"
 *               ),
 *               @OA\Property(
 *                   property="nooutgoingoutbound",
 *                   description="No. Outgoing Outbound",
 *                   type="string"
 *               ),
 *               @OA\Property(
 *                   property="iditem",
 *                   description="Id Item",
 *                   type=" integer"
 *               ),
 *               @OA\Property(
 *                   property="qty",
 *                   description="Quantity",
 *                   type=" integer"
 *               ),
 *               
 *           )
 *       ),
 *     ),
 *     @OA\Response(
 *         response="200",
 *         description="successful operation",
 *     ),
 * )
 */

/** 
 * @OA\get(
 *     path="/api/v1/project/{kodeproject}/gudang/{kodegudang}/inventory/outbound/{nooutbound}/outgoing/daftar_outgoing",
 *     tags={"Outgoing Outbound"},
 *     summary="get list dokumen outgoing outbound",
 *     description="Untuk mengambil list outgoing outbound",
 *     operationId="getListOutgoingOutbound",
 *     @OA\Parameter(
 *          name="kodeproject",
 *          description="Kode Project",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="string"
 *          )
 *     ),
 *     @OA\Parameter(
 *          name="kodegudang",
 *          description="Kode Gudang",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="string"
 *          )
 *     ),
 *     @OA\Parameter(
 *          name="nooutbound",
 *          description="No. Outbound",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="string"
 *          )
 *     ),
 *     @OA\Response(
 *         response="200",
 *         description="successful operation",
 *     ),
 * )
 */

/** 
 * @OA\get(
 *     path="/api/v1/project/{kodeproject}/gudang/{kodegudang}/inventory/outbound/{nooutbound}/outgoing/generatenooutgoing",
 *     tags={"Outgoing Outbound"},
 *     summary="get generate nomor dokumen outgoing outbound",
 *     description="Untuk mengambil list outgoing outbound",
 *     operationId="getGenerateNoOutgoing",
 *     @OA\Parameter(
 *          name="kodeproject",
 *          description="Kode Project",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="string"
 *          )
 *     ),
 *     @OA\Parameter(
 *          name="kodegudang",
 *          description="Kode Gudang",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="string"
 *          )
 *     ),
 *     @OA\Parameter(
 *          name="nooutbound",
 *          description="No. Outbound",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="string"
 *          )
 *     ),
 *     @OA\Response(
 *         response="200",
 *         description="successful operation",
 *     ),
 * )
 */

/** 
 * @OA\get(
 *     path="/api/v1/project/{kodeproject}/gudang/{kodegudang}/inventory/outbound/{nooutbound}/outgoing/{nooutgoing}/detail",
 *     tags={"Outgoing Outbound"},
 *     summary="get detail dokumen outgoing outbound",
 *     description="Untuk mengambil detail dokumen outgoing outbound",
 *     operationId="getOutgoingOutboundDetail",
 *     @OA\Parameter(
 *          name="kodeproject",
 *          description="Kode Project",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="string"
 *          )
 *     ),
 *     @OA\Parameter(
 *          name="kodegudang",
 *          description="Kode Gudang",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="string"
 *          )
 *     ),
 *     @OA\Parameter(
 *          name="nooutbound",
 *          description="No. Outbound",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="string"
 *          )
 *     ),
 *     @OA\Parameter(
 *          name="nooutgoing",
 *          description="No. Outgoing Outbound",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="string"
 *          )
 *     ),
 *     @OA\Response(
 *         response="200",
 *         description="successful operation",
 *     ),
 * )
 */
