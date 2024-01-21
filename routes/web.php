<?php

use App\Http\Controllers\Admins\AdministratorController;
use App\Http\Controllers\Admins\BankController;
use App\Http\Controllers\Admins\BaseAdminController;
use App\Http\Controllers\Admins\CoController;
use App\Http\Controllers\Admins\CoTmpController;
use App\Http\Controllers\Admins\ConfigController;
use App\Http\Controllers\Admins\DashboardController;
use App\Http\Controllers\Admins\LogAdminController;
use App\Http\Controllers\Admins\LoginController;
use App\Http\Controllers\Admins\PaymentController;
use App\Http\Controllers\Admins\ReceiptController;
use App\Http\Controllers\Admins\RequestController;
use App\Http\Controllers\Admins\RoleController;
use App\Http\Controllers\Admins\WarehousePlateController;
use App\Http\Controllers\Admins\WarehouseRemainController;
use App\Http\Controllers\Admins\WarehouseSpwController;
use App\Http\Controllers\Admins\CustomerController;
use App\Http\Controllers\Admins\PriceSurveyController;
use App\Http\Controllers\Admins\DeliveryController;
use App\Http\Controllers\Admins\WarehouseExportSellController;
use App\Http\Controllers\Admins\WarehouseExportController;
use App\Http\Controllers\Admins\WarehouseReceiptController;
use App\Http\Controllers\Admins\ManufactureController;
use App\Http\Controllers\Admins\DocumentController;
use App\Http\Controllers\Admins\PDFController;
use App\Http\Controllers\Admins\ReportController;
use App\Http\Controllers\Admins\BankLoanController;
use App\Http\Controllers\Admins\WarehouseController;
use App\Http\Controllers\Admins\WarehouseGroupController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admins\WarehouseProductCodeController;

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

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('admin')->group(function () {
	Route::get('/', [LoginController::class, 'getLogin'])->name('admin.login.getLogin');
	Route::post('/checkLogin', [LoginController::class, 'postLogin'])->name('admin.login.postLogin');
	Route::get('/reset', [LoginController::class, 'getReset'])->name('admin.login.getReset');
	Route::post('/reset', [LoginController::class, 'postReset'])->name('admin.login.postReset');
	Route::get('/logout', [LoginController::class, 'getLogout'])->name('admin.login.getLogout');
});
Route::group(['prefix' => 'admin', 'middleware' => ['authAdmin']], function() {
    /* Dashboard */
    Route::get('/dashboard/index/co-tmps', [DashboardController::class, 'coTmp'])->name('admin.dashboard.co-tmp');
	Route::get('/dashboard/index/coes', [DashboardController::class, 'co'])->name('admin.dashboard.co');
	Route::get('/dashboard/index/requests', [DashboardController::class, 'requests'])->name('admin.dashboard.request');
    /* Administrator */
    Route::group(['prefix' => 'administrator'], function() {
		Route::match(['get', 'post'], '/index', [AdministratorController::class, 'index'])->name('admin.administrator.index');
	    Route::get('/status/{id}', [AdministratorController::class, 'status'])->name('admin.administrator.status');
		Route::get('/create', [AdministratorController::class, 'create'])->name('admin.administrator.create');
	    Route::post('/create', [AdministratorController::class, 'store'])->name('admin.administrator.store');
		Route::get('/me', [AdministratorController::class, 'meEdit'])->name('admin.administrator.meEdit');
		Route::patch('/me', [AdministratorController::class, 'meUpdate'])->name('admin.administrator.meUpdate');
		Route::get('/edit/{id}', [AdministratorController::class, 'edit'])->name('admin.administrator.edit');
		Route::patch('/edit/{id}', [AdministratorController::class, 'update'])->name('admin.administrator.update');
		Route::post('/remove-file', [AdministratorController::class, 'removeFile'])->name('admin.administrator.removeFile');
		Route::get('/destroy/{id}', [AdministratorController::class, 'destroy'])->name('admin.administrator.destroy');
		Route::delete('/destroyMul', [AdministratorController::class, 'destroyMul'])->name('admin.administrator.destroyMul');
		Route::get('/reset/{id}', [AdministratorController::class, 'resetPassword'])->name('admin.administrator.resetPassword');
    });
    /* Role */
	Route::group(['prefix' => 'role'], function() {
		Route::get('/index', [RoleController::class, 'index'])->name('admin.role.index');
		Route::get('create', [RoleController::class, 'create'])->name('admin.role.create');
		Route::post('create', [RoleController::class, 'store'])->name('admin.role.store');
		Route::get('/edit/{id}', [RoleController::class, 'edit'])->name('admin.role.edit');
		Route::patch('/edit/{id}', [RoleController::class, 'update'])->name('admin.role.update');
	});
	/* Log admin */
	Route::group(['prefix' => 'logadmin'], function() {
		Route::match(['get', 'post'], '/index', [LogAdminController::class, 'index'])->name('admin.logadmin.index');
		Route::get('/show/{id}', [LogAdminController::class, 'show'])->name('admin.logadmin.show');
	});
	/* Bank */
	Route::group(['prefix' => 'bank'], function() {
		Route::get('/index', [BankController::class, 'index'])->name('admin.bank.index');
		Route::get('create', [BankController::class, 'create'])->name('admin.bank.create');
		Route::post('create', [BankController::class, 'store'])->name('admin.bank.store');
		Route::get('/edit/{id}', [BankController::class, 'edit'])->name('admin.bank.edit');
		Route::patch('/edit/{id}', [BankController::class, 'update'])->name('admin.bank.update');
		Route::get('/destroy/{id}', [BankController::class, 'destroy'])->name('admin.bank.destroy');
		Route::post('/transaction/{id}', [BankController::class, 'transaction'])->name('admin.bank.transaction');
	});
	/* Co Tmp */
	Route::group(['prefix' => 'co-tmp'], function() {
		Route::get('/index', [CoTmpController::class, 'index'])->name('admin.co-tmp.index');
		Route::get('create', [CoTmpController::class, 'create'])->name('admin.co-tmp.create');
		Route::post('create', [CoTmpController::class, 'store'])->name('admin.co-tmp.store');
		Route::get('/edit/{id}', [CoTmpController::class, 'edit'])->name('admin.co-tmp.edit');
		Route::patch('/edit/{id}', [CoTmpController::class, 'update'])->name('admin.co-tmp.update');
		Route::get('/destroy/{id}', [CoTmpController::class, 'destroy'])->name('admin.co-tmp.destroy');
		Route::post('/get-data-warehouse', [CoTmpController::class, 'getDataWarehouse'])->name('admin.co-tmp.get-data-warehouse');
		Route::post('/get-offer-price', [CoTmpController::class, 'getOfferPrice'])->name('admin.co-tmp.get-offer-price');
	});
	/* CO */
	Route::group(['prefix' => 'co'], function() {
		Route::get('/index', [CoController::class, 'index'])->name('admin.co.index');
		Route::get('create/{coTmpId?}', [CoController::class, 'create'])->name('admin.co.create');
		Route::post('create', [CoController::class, 'store'])->name('admin.co.store');
		Route::get('/edit/{id}', [CoController::class, 'edit'])->name('admin.co.edit');
		Route::patch('/edit/{id}', [CoController::class, 'update'])->name('admin.co.update');
		Route::get('/destroy/{id}', [CoController::class, 'destroy'])->name('admin.co.destroy');
		Route::post('/get-data-warehouse', [CoController::class, 'getDataWarehouse'])->name('admin.co.get-data-warehouse');
		Route::post('/get-offer-price', [CoController::class, 'getOfferPrice'])->name('admin.co.get-offer-price');
		Route::post('/get-material', [CoController::class, 'getMaterial'])->name('admin.co.get-material');
		Route::post('/delivery/save/{id}', [CoController::class, 'saveDelivery'])->name('admin.co.delivery.save');
		Route::post('/remove-file', [CoController::class, 'removeFile'])->name('admin.co.remove-file');
	});
	/* Request */
	Route::group(['prefix' => 'request'], function() {
		Route::get('/index', [RequestController::class, 'index'])->name('admin.request.index');
		Route::get('create/{coId?}', [RequestController::class, 'create'])->name('admin.request.create');
		Route::post('create', [RequestController::class, 'store'])->name('admin.request.store');
		Route::get('/edit/{id}', [RequestController::class, 'edit'])->name('admin.request.edit');
		Route::patch('/edit/{id}', [RequestController::class, 'update'])->name('admin.request.update');
		Route::get('/destroy/{id}', [RequestController::class, 'destroy'])->name('admin.request.destroy');
		Route::post('/get-co', [RequestController::class, 'getCo'])->name('admin.request.get-co');
		Route::post('/remove-file', [RequestController::class, 'removeFile'])->name('admin.request.remove-file');
		Route::patch('/update-survey-price/{id}', [RequestController::class, 'updateSurveyPrice'])->name('admin.request.update-survey-price');
		Route::post('/remove-file-survey-price', [RequestController::class, 'removeFileSurveyPrice'])->name('admin.request.remove-file-survey-price');
        Route::post('/remove-file-payment-document', [RequestController::class, 'removeFilePaymentDocument'])->name('admin.request.remove-file-payment-document');
	});
	/* Payment */
	Route::group(['prefix' => 'payment'], function() {
		Route::get('/index', [PaymentController::class, 'index'])->name('admin.payment.index');
		Route::get('create/{requestId?}', [PaymentController::class, 'create'])->name('admin.payment.create');
		Route::post('create', [PaymentController::class, 'store'])->name('admin.payment.store');
		Route::get('/edit/{id}', [PaymentController::class, 'edit'])->name('admin.payment.edit');
		Route::patch('/edit/{id}', [PaymentController::class, 'update'])->name('admin.payment.update');
		Route::get('/destroy/{id}', [PaymentController::class, 'destroy'])->name('admin.payment.destroy');
		Route::post('/get-requests', [PaymentController::class, 'getRequests'])->name('admin.payment.get-requests');
		Route::post('/remove-file', [PaymentController::class, 'removeFile'])->name('admin.payment.remove-file');
	});
	/* Receipt */
	Route::group(['prefix' => 'receipt'], function() {
		Route::get('/index', [ReceiptController::class, 'index'])->name('admin.receipt.index');
		Route::get('create/{type?}/{id?}', [ReceiptController::class, 'create'])->name('admin.receipt.create');
		Route::post('create', [ReceiptController::class, 'store'])->name('admin.receipt.store');
		Route::get('/edit/{id}', [ReceiptController::class, 'edit'])->name('admin.receipt.edit');
		Route::patch('/edit/{id}', [ReceiptController::class, 'update'])->name('admin.receipt.update');
		Route::get('/destroy/{id}', [ReceiptController::class, 'destroy'])->name('admin.receipt.destroy');
		Route::post('/get-co', [ReceiptController::class, 'getCo'])->name('admin.receipt.get-co');
		Route::post('/get-payments', [ReceiptController::class, 'getPayments'])->name('admin.receipt.get-payments');
		Route::post('/remove-file', [ReceiptController::class, 'removeFile'])->name('admin.receipt.remove-file');
	});
	/* WarehousePlate */
	Route::group(['prefix' => 'warehouse-plate'], function() {
		Route::get('/index/{model?}', [WarehousePlateController::class, 'index'])->name('admin.warehouse-plate.index');
		Route::get('create/{model}', [WarehousePlateController::class, 'create'])->name('admin.warehouse-plate.create');
		Route::post('create/{model}', [WarehousePlateController::class, 'store'])->name('admin.warehouse-plate.store');
		Route::get('/edit/{model}/{id}', [WarehousePlateController::class, 'edit'])->name('admin.warehouse-plate.edit');
		Route::patch('/edit/{model}/{id}', [WarehousePlateController::class, 'update'])->name('admin.warehouse-plate.update');
		Route::get('/destroy/{model}/{id}', [WarehousePlateController::class, 'destroy'])->name('admin.warehouse-plate.destroy');
		Route::post('import/{model}', [WarehouseController::class, 'import'])->name('admin.warehouse-plate.import');
		Route::get('history/{model}', [WarehousePlateController::class, 'history'])->name('admin.warehouse-plate.history');
	});
	/* WarehouseSpw */
	Route::group(['prefix' => 'warehouse-spw'], function() {
		Route::get('/index/{model?}', [WarehouseSpwController::class, 'index'])->name('admin.warehouse-spw.index');
		Route::get('create/{model}', [WarehouseSpwController::class, 'create'])->name('admin.warehouse-spw.create');
		Route::post('create/{model}', [WarehouseSpwController::class, 'store'])->name('admin.warehouse-spw.store');
		Route::get('/edit/{model}/{id}', [WarehouseSpwController::class, 'edit'])->name('admin.warehouse-spw.edit');
		Route::patch('/edit/{model}/{id}', [WarehouseSpwController::class, 'update'])->name('admin.warehouse-spw.update');
		Route::get('/destroy/{model}/{id}', [WarehouseSpwController::class, 'destroy'])->name('admin.warehouse-spw.destroy');
		Route::post('import/{model}', [WarehouseSpwController::class, 'import'])->name('admin.warehouse-spw.import');
	});
	/* WarehouseSpw */
	Route::group(['prefix' => 'warehouse-remain'], function() {
		Route::get('/index/{model?}', [WarehouseRemainController::class, 'index'])->name('admin.warehouse-remain.index');
		Route::get('create/{model}', [WarehouseRemainController::class, 'create'])->name('admin.warehouse-remain.create');
		Route::post('create/{model}', [WarehouseRemainController::class, 'store'])->name('admin.warehouse-remain.store');
		Route::get('/edit/{model}/{id}', [WarehouseRemainController::class, 'edit'])->name('admin.warehouse-remain.edit');
		Route::patch('/edit/{model}/{id}', [WarehouseRemainController::class, 'update'])->name('admin.warehouse-remain.update');
		Route::get('/destroy/{model}/{id}', [WarehouseRemainController::class, 'destroy'])->name('admin.warehouse-remain.destroy');
		Route::post('import/{model}', [WarehouseRemainController::class, 'import'])->name('admin.warehouse-remain.import');
	});
	/* Config */
	Route::group(['prefix' => 'config'], function() {
		Route::get('/index', [ConfigController::class, 'index'])->name('admin.config.index');
		Route::post('create', [ConfigController::class, 'store'])->name('admin.config.store');
	});
	/* Base admin */
	Route::group(['prefix' => 'base-admin'], function() {
		Route::get('/approval', [BaseAdminController::class, 'approval'])->name('admin.base-admin.approval');
        Route::get('/check-warehouse', [BaseAdminController::class, 'checkWarehouse'])->name('admin.base-admin.check-warehouse');
	});
    /* Customer */
    Route::group(['prefix' => 'customer'], function() {
        Route::get('/index', [CustomerController::class, 'index'])->name('admin.customer.index');
        Route::get('create', [CustomerController::class, 'create'])->name('admin.customer.create');
        Route::post('create', [CustomerController::class, 'store'])->name('admin.customer.store');
        Route::get('/edit/{id}', [CustomerController::class, 'edit'])->name('admin.customer.edit');
        Route::patch('/edit/{id}', [CustomerController::class, 'update'])->name('admin.customer.update');
        Route::get('/destroy/{id}', [CustomerController::class, 'destroy'])->name('admin.customer.destroy');
    });
    /* Customer */
    Route::group(['prefix' => 'price-survey'], function() {
        Route::get('/index', [PriceSurveyController::class, 'index'])->name('admin.price-survey.index');
        Route::get('create', [PriceSurveyController::class, 'create'])->name('admin.price-survey.create');
        Route::post('create', [PriceSurveyController::class, 'store'])->name('admin.price-survey.store');
        Route::get('/edit/{id}', [PriceSurveyController::class, 'edit'])->name('admin.price-survey.edit');
        Route::patch('/edit/{id}', [PriceSurveyController::class, 'update'])->name('admin.price-survey.update');
        Route::get('/destroy/{id}', [PriceSurveyController::class, 'destroy'])->name('admin.price-survey.destroy');
        Route::post('/insert-multiple', [PriceSurveyController::class, 'insertMultiple'])->name('admin.price-survey.insert-multiple');
    });
    /* Delivery */
    Route::group(['prefix' => 'delivery'], function() {
        Route::get('/index', [DeliveryController::class, 'index'])->name('admin.delivery.index');
        Route::get('create', [DeliveryController::class, 'create'])->name('admin.delivery.create');
        Route::post('create', [DeliveryController::class, 'store'])->name('admin.delivery.store');
        Route::get('/edit/{id}', [DeliveryController::class, 'edit'])->name('admin.delivery.edit');
        Route::patch('/edit/{id}', [DeliveryController::class, 'update'])->name('admin.delivery.update');
        Route::get('/destroy/{id}', [DeliveryController::class, 'destroy'])->name('admin.delivery.destroy');
    });
    /* Warehouse-export-sell */
    Route::group(['prefix' => 'warehouse-export-sell'], function() {
        Route::get('/index', [WarehouseExportSellController::class, 'index'])->name('admin.warehouse-export-sell.index');
        Route::get('create', [WarehouseExportSellController::class, 'create'])->name('admin.warehouse-export-sell.create');
        Route::post('create', [WarehouseExportSellController::class, 'store'])->name('admin.warehouse-export-sell.store');
        Route::get('/edit/{id}', [WarehouseExportSellController::class, 'edit'])->name('admin.warehouse-export-sell.edit');
        Route::patch('/edit/{id}', [WarehouseExportSellController::class, 'update'])->name('admin.warehouse-export-sell.update');
        Route::get('/destroy/{id}', [WarehouseExportSellController::class, 'destroy'])->name('admin.warehouse-export-sell.destroy');
        Route::post('/remove-file', [WarehouseExportSellController::class, 'removeFile'])->name('admin.warehouse-export-sell.remove-file');
    });
    /* Warehouse-export */
    Route::group(['prefix' => 'warehouse-export'], function() {
        Route::get('/index', [WarehouseExportController::class, 'index'])->name('admin.warehouse-export.index');
        Route::get('create', [WarehouseExportController::class, 'create'])->name('admin.warehouse-export.create');
        Route::post('create', [WarehouseExportController::class, 'store'])->name('admin.warehouse-export.store');
        Route::get('/edit/{id}', [WarehouseExportController::class, 'edit'])->name('admin.warehouse-export.edit');
        Route::patch('/edit/{id}', [WarehouseExportController::class, 'update'])->name('admin.warehouse-export.update');
        Route::get('/destroy/{id}', [WarehouseExportController::class, 'destroy'])->name('admin.warehouse-export.destroy');
        Route::post('/remove-file', [WarehouseExportController::class, 'removeFile'])->name('admin.warehouse-export.remove-file');
    });
    /* Warehouse-receipt */
    Route::group(['prefix' => 'warehouse-receipt'], function() {
        Route::get('/index', [WarehouseReceiptController::class, 'index'])->name('admin.warehouse-receipt.index');
        Route::get('create', [WarehouseReceiptController::class, 'create'])->name('admin.warehouse-receipt.create');
        Route::post('create', [WarehouseReceiptController::class, 'store'])->name('admin.warehouse-receipt.store');
        Route::get('/edit/{id}', [WarehouseReceiptController::class, 'edit'])->name('admin.warehouse-receipt.edit');
        Route::patch('/edit/{id}', [WarehouseReceiptController::class, 'update'])->name('admin.warehouse-receipt.update');
        Route::get('/destroy/{id}', [WarehouseReceiptController::class, 'destroy'])->name('admin.warehouse-receipt.destroy');
        Route::post('/remove-file', [WarehouseReceiptController::class, 'removeFile'])->name('admin.warehouse-receipt.remove-file');
    });

    /* Manufacture */
    Route::group(['prefix' => 'manufacture'], function() {
        Route::get('/index', [ManufactureController::class, 'index'])->name('admin.manufacture.index');
        Route::get('create', [ManufactureController::class, 'create'])->name('admin.manufacture.create');
        Route::post('create', [ManufactureController::class, 'store'])->name('admin.manufacture.store');
        Route::get('/edit/{id}', [ManufactureController::class, 'edit'])->name('admin.manufacture.edit');
        Route::patch('/edit/{id}', [ManufactureController::class, 'update'])->name('admin.manufacture.update');
        Route::get('/destroy/{id}', [ManufactureController::class, 'destroy'])->name('admin.manufacture.destroy');
        Route::post('/remove-file', [ManufactureController::class, 'removeFile'])->name('admin.manufacture.remove-file');
    });

    /* Documents */
    Route::group(['prefix' => 'documents'], function() {
        Route::get('/index', [DocumentController::class, 'index'])->name('admin.documents.index');
        Route::get('create', [DocumentController::class, 'create'])->name('admin.documents.create');
        Route::post('create', [DocumentController::class, 'store'])->name('admin.documents.store');
        Route::get('/edit/{id}', [DocumentController::class, 'edit'])->name('admin.documents.edit');
        Route::patch('/edit/{id}', [DocumentController::class, 'update'])->name('admin.documents.update');
        Route::get('/destroy/{id}', [DocumentController::class, 'destroy'])->name('admin.documents.destroy');
        Route::post('/remove-file', [DocumentController::class, 'removeFile'])->name('admin.documents.remove-file');
    });

    Route::group(['prefix' => 'generate-pdf'], function() {
        Route::get('/manufacture/{id}', [PDFController::class, 'manufacture'])->name('admin.pdf.manufacture');
        Route::get('/manufacture-check/{id}', [PDFController::class, 'manufactureCheck'])->name('admin.pdf.manufacture-check');
        Route::get('/warehouse-export-sell/{id}', [PDFController::class, 'warehouseExportSell'])->name('admin.pdf.warehouse-export-sell');
    });

    Route::group(['prefix' => 'report'], function() {
        Route::get('/', [ReportController::class, 'index'])->name('admin.report.index');
        Route::get('/tmp-co', [ReportController::class, 'getTmpCo'])->name('admin.report.tmp-co');
        Route::get('/co', [ReportController::class, 'getCo'])->name('admin.report.co');
        Route::get('/request', [ReportController::class, 'getRequest'])->name('admin.report.request');
        Route::get('/customer-tmp-co', [ReportController::class, 'getCustomerTmpCo'])->name('admin.report.customer-tmp-co');
        Route::get('/customer-co', [ReportController::class, 'getCustomerCo'])->name('admin.report.customer-co');
    });

    /* Bank loans */
    Route::group(['prefix' => 'bank-loans'], function() {
        Route::get('/index', [BankLoanController::class, 'index'])->name('admin.bank-loans.index');
        Route::get('create', [BankLoanController::class, 'create'])->name('admin.bank-loans.create');
        Route::post('create', [BankLoanController::class, 'store'])->name('admin.bank-loans.store');
        Route::get('/edit/{id}', [BankLoanController::class, 'edit'])->name('admin.bank-loans.edit');
        Route::patch('/edit/{id}', [BankLoanController::class, 'update'])->name('admin.bank-loans.update');
        Route::get('/destroy/{id}', [BankLoanController::class, 'destroy'])->name('admin.bank-loans.destroy');
        Route::post('insert-detail/{id}', [BankLoanController::class, 'insertDetail'])->name('admin.bank-loans.insert-detail');
    });

    /* Warehouse Group */
    Route::group(['prefix' => 'warehouse-group'], function() {
        Route::get('/index', [WarehouseGroupController::class, 'index'])->name('admin.warehouse-group.index');
        Route::get('create', [WarehouseGroupController::class, 'create'])->name('admin.warehouse-group.create');
        Route::post('create', [WarehouseGroupController::class, 'store'])->name('admin.warehouse-group.store');
        Route::get('/edit/{id}', [WarehouseGroupController::class, 'edit'])->name('admin.warehouse-group.edit');
        Route::patch('/edit/{id}', [WarehouseGroupController::class, 'update'])->name('admin.warehouse-group.update');
        Route::get('/destroy/{id}', [WarehouseGroupController::class, 'destroy'])->name('admin.warehouse-group.destroy');
    });

    /* Warehouse product code */
    Route::group(['prefix' => 'warehouse-product-code'], function() {
        Route::get('/index', [WarehouseProductCodeController::class, 'index'])->name('admin.warehouse-product-code.index');
        Route::get('create', [WarehouseProductCodeController::class, 'create'])->name('admin.warehouse-product-code.create');
        Route::post('create', [WarehouseProductCodeController::class, 'store'])->name('admin.warehouse-product-code.store');
        Route::get('/edit/{id}', [WarehouseProductCodeController::class, 'edit'])->name('admin.warehouse-product-code.edit');
        Route::patch('/edit/{id}', [WarehouseProductCodeController::class, 'update'])->name('admin.warehouse-product-code.update');
        Route::get('/destroy/{id}', [WarehouseProductCodeController::class, 'destroy'])->name('admin.warehouse-product-code.destroy');
        Route::post('/import', [WarehouseProductCodeController::class, 'importCode'])->name('admin.warehouse-product-code.import');
    });

	/* Get form create material */
	Route::group(['prefix' => 'warehouse'], function() {
		Route::get('show-form-create', [WarehouseController::class, 'showFormCreate'])->name('admin.warehouse.show-form-create');
	});
});