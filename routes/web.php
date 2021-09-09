<?php

use Illuminate\Support\Facades\Auth;
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

Auth::routes();

Route::get('/', [App\Http\Controllers\HomeController::class, 'root'])->name('root');
Route::get('/icon', [App\Http\Controllers\Controller::class, 'icon'])->name('icon');

Route::middleware(['auth'])->group(function () {
//Update User Details
    Route::post('/update-profile/{id}', [App\Http\Controllers\HomeController::class, 'updateProfile'])->name('updateProfile');
    Route::post('/update-password/{id}', [App\Http\Controllers\HomeController::class, 'updatePassword'])->name('updatePassword');


    // Route::get('{any}', [App\Http\Controllers\HomeController::class, 'index'])->name('index');

    //Language Translation
    // Route::get('index/{locale}', [App\Http\Controllers\HomeController::class, 'lang']);

    // Route::get('/icon', [App\Http\Controllers\HomeController::class, 'icon']);

    //Admin Manage Package
    Route::get('/package', [App\Http\Controllers\PackageController::class, 'index'])->name('package.index');
    Route::post('/package/show', [App\Http\Controllers\PackageController::class, 'show'])->name('package.show');
    Route::post('/package/store', [App\Http\Controllers\PackageController::class, 'store'])->name('package.store');
    Route::post('/package/change-status', [App\Http\Controllers\PackageController::class, 'changeStatus'])->name('package.change-status');

    //Admin Deposit
    Route::get('/admin-deposit', [App\Http\Controllers\AdminDepositController::class, 'index'])->name('admin.deposit.index');
    Route::post('/admin-deposit/store', [App\Http\Controllers\AdminDepositController::class, 'store'])->name('admin.deposit.store');
    Route::post('/admin-deposit/show', [App\Http\Controllers\AdminDepositController::class, 'show'])->name('admin.deposit.show');
    Route::post('/admin-deposit/count-wait', [App\Http\Controllers\AdminDepositController::class, 'countWaitDeposit'])->name('admin.deposit.count-wait');

    //Admin Withdraw
    Route::get('/admin-withdraw', [App\Http\Controllers\AdminWithdrawController::class, 'index'])->name('admin.withdraw.index');
    Route::post('/admin-withdraw/store', [App\Http\Controllers\AdminWithdrawController::class, 'store'])->name('admin.withdraw.store');
    Route::post('/admin-withdraw/show', [App\Http\Controllers\AdminWithdrawController::class, 'show'])->name('admin.withdraw.show');
    Route::post('/admin-withdraw/count-wait', [App\Http\Controllers\AdminWithdrawController::class, 'countWaitWithdraw'])->name('admin.withdraw.count-wait');

    //Admin Report
    Route::get('/summary-in-out-report', [App\Http\Controllers\ReportController::class, 'summaryInOut'])->name('admin.report.summary-in-out.index');
    Route::post('/summary-in-out-report/show', [App\Http\Controllers\ReportController::class, 'showSummaryInOut'])->name('admin.report.summary-in-out.show');

    Route::get('/summary-transaction-report', [App\Http\Controllers\ReportController::class, 'summaryTransaction'])->name('admin.report.summary-transaction.index');
    Route::post('/summary-transaction-report/show', [App\Http\Controllers\ReportController::class, 'showSummaryTransaction'])->name('admin.report.summary-transaction.show');

    //Manage Admin
    Route::get('/manage/admin/list', [App\Http\Controllers\ManageAdminController::class, 'index'])->name('adminList');
    Route::post('/manage/admin/get-admin-list', [App\Http\Controllers\ManageAdminController::class, 'getAdminList'])->name('getAdminList');
    Route::get('/manage/admin/add', [App\Http\Controllers\ManageAdminController::class, 'addAdminIndex'])->name('addAdminIndex');
    Route::post('/manage/admin/add-admin', [App\Http\Controllers\ManageAdminController::class, 'addAdmin'])->name('addAdmin');
    Route::get('/manage/admin/{id}', [App\Http\Controllers\ManageAdminController::class, 'viewAdmin'])->name('viewAdmin');
    Route::post('/manage/admin/update', [App\Http\Controllers\ManageAdminController::class, 'adminUpdate'])->name('adminUpdate');
    Route::delete('/manage/admin/delete', [App\Http\Controllers\ManageAdminController::class, 'deleteAdmin'])->name('deleteAdmin');

    //Manage User
    Route::get('/manage/user/list', [App\Http\Controllers\ManageUserController::class, 'userList'])->name('userList');
    Route::post('/manage/user/getlist', [App\Http\Controllers\ManageUserController::class, 'getUserList'])->name('getUserList');
    Route::get('/manage/user/{id}', [App\Http\Controllers\ManageUserController::class, 'viewUser'])->name('viewUser');
    Route::post('/manage/user/update', [App\Http\Controllers\ManageUserController::class, 'userUpdate'])->name('userUpdate');
    Route::get('/adduser', [App\Http\Controllers\ManageUserController::class, 'addUserIndex'])->name('addUserIndex');
    Route::get('/adduser/param/{upline_id}/{position}', [App\Http\Controllers\ManageUserController::class, 'addUserWithParam'])->name('addUserWithParam');
    Route::post('/member/add', [App\Http\Controllers\ManageUserController::class, 'createUser'])->name('createUser');
    Route::post('/member/list', [App\Http\Controllers\ManageUserController::class, 'checkUserFindInvite'])->name('checkUserInvite');
    Route::post('/member/upline', [App\Http\Controllers\ManageUserController::class, 'checkUserFindUpline'])->name('checkUserUpline');

    //cash and coin history
    Route::get('/member/cash-wallet/{id}', [App\Http\Controllers\ManageUserController::class, 'cashHistory'])->name('cashHistory');
    Route::get('/member/cash-wallet', [App\Http\Controllers\ManageUserController::class, 'cashHistoryIndex'])->name('cashHistoryIndex');
    Route::post('/member/cash-wallet/search', [App\Http\Controllers\ManageUserController::class, 'cashHistorySearch'])->name('cashHistorySearch');

    Route::get('/member/coin-wallet/{id}', [App\Http\Controllers\ManageUserController::class, 'coinHistory'])->name('coinHistory');
    Route::get('/member/coin-wallet', [App\Http\Controllers\ManageUserController::class, 'coinHistoryIndex'])->name('coinHistoryIndex');
    Route::post('/member/coin-wallet/search', [App\Http\Controllers\ManageUserController::class, 'coinHistorySearch'])->name('coinHistorySearch');

    //Manage news
    Route::get('/manage/news/', [App\Http\Controllers\NewsController::class, 'index'])->name('manageNews');
    Route::post('/manage/news/update', [App\Http\Controllers\NewsController::class, 'index'])->name('updateNews');
    Route::post('/ckeditor/upload', [App\Http\Controllers\NewsController::class, 'imageUpload'])->name('uploadImageNews');

    // user
    /*
    Route::get('/member', [App\Http\Controllers\User\UserController::class, 'index'])->name('memberView');
    // Route::get('/member/items', [App\Http\Controllers\User\UserController::class, 'listItem'])->name('itemView');
    Route::get('/member/items/{upline_id}/{position}', [App\Http\Controllers\User\UserController::class, 'listItem'])->name('itemView');
    Route::post('/member/list', [App\Http\Controllers\User\UserController::class, 'indexUserList'])->name('memberUserList');
    Route::get('/member/{product_id}/{upline_id}/{position}/create', [App\Http\Controllers\User\UserController::class, 'create'])->name('createView');
    Route::post('/member/create', [App\Http\Controllers\User\UserController::class, 'createUser'])->name('createUser');
    Route::post('/member/create/search', [App\Http\Controllers\User\UserController::class, 'createUserFindInvite'])->name('create.user.find.invite');
*/
    //Org
    Route::get('/upline', [App\Http\Controllers\User\OrgController::class, 'index'])->name('orgView');
    Route::post('/upline', [App\Http\Controllers\User\OrgController::class, 'uplineList'])->name('orgUplineList');
    Route::post('/upline/info', [App\Http\Controllers\User\OrgController::class, 'uplineListInfo'])->name('orgUplineList.info');
    Route::post('/upline/info-array', [App\Http\Controllers\User\OrgController::class, 'uplineListInfoarray'])->name('orgUplineList.info.array');

    //Upgrade
    Route::get('/member-upgrade', [App\Http\Controllers\Upgrade\UpgradeController::class, 'memberUpgrade'])->name('member.upgrade');
    Route::post('/member-upgrade/product-list', [App\Http\Controllers\Upgrade\UpgradeController::class, 'productList'])->name('member.product-list');
    Route::post('/upgrade/check', [App\Http\Controllers\Upgrade\UpgradeController::class, 'checkUser'])->name('api.check_user');
    Route::post('/upgrade/save', [App\Http\Controllers\Upgrade\UpgradeController::class, 'upgradeSave'])->name('api.update.save');
    
    //Company wallet
    Route::get('/company-wallet', [App\Http\Controllers\CompanyWalletController::class, 'index'])->name('company-wallet.index');
    Route::post('/company-wallet/search', [App\Http\Controllers\CompanyWalletController::class, 'search'])->name('company-wallet.search');

    //Admin Manage Companybank
    Route::get('/company-bank', [App\Http\Controllers\CompanyBankController::class, 'index'])->name('company-bank.index');
    Route::post('/company-bank/show', [App\Http\Controllers\CompanyBankController::class, 'show'])->name('company-bank.show');
    Route::post('/company-bank/store', [App\Http\Controllers\CompanyBankController::class, 'store'])->name('company-bank.store');
    Route::post('/company-bank/change-status', [App\Http\Controllers\CompanyBankController::class, 'changeStatus'])->name('company-bank.change-status');

    //Admin Manage Addtional Function
    Route::get('/additional-function', [App\Http\Controllers\AdditionalFunctionController::class, 'index'])->name('additional-function.index');
    Route::post('/additional-function/show', [App\Http\Controllers\AdditionalFunctionController::class, 'show'])->name('additional-function.show');
    Route::post('/additional-function/store', [App\Http\Controllers\AdditionalFunctionController::class, 'store'])->name('additional-function.store');
    Route::post('/additional-function/delete', [App\Http\Controllers\AdditionalFunctionController::class, 'delete'])->name('additional-function.delete');
    Route::post('/additional-function/change-status', [App\Http\Controllers\AdditionalFunctionController::class, 'changeStatus'])->name('additional-function.change-status');

});
