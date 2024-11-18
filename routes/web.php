<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\BankController;
use App\Http\Controllers\BankTrasactionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExpenseCategoryController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ExpenseMemberController;
use App\Http\Controllers\HouseController;
use App\Http\Controllers\IncomeCategoryController;
use App\Http\Controllers\IncomeController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\PettycashController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

/* Route::get('/', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard'); */

Route::middleware('auth')->group(function () {
    Route::resource('profile', ProfileController::class);
    Route::get('/', [DashboardController::class, 'index']);
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/get-dashboard-graph-data', [DashboardController::class, 'getAllGraphData']);

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/updatePassword', [ProfileController::class, 'updatePassword'])->name('profile.updatePassword');

    Route::prefix('user')->group(function () {
        Route::get('', [UserController::class, 'index'])->name('user.index');
        Route:: group(['middleware' => 'permission:User,create'], function () {
            Route::get('/create', [UserController::class, 'create'])->name('user.create');
            Route::post('/store', [UserController::class, 'store'])->name('user.store');
        });
        Route:: group(['middleware' => 'permission:User,read'], function () {
            Route::get('/{id}', [UserController::class, 'show'])->name('user.show');
        });
        Route:: group(['middleware' => 'permission:User,update'], function () {
            Route::get('/{user}/edit', [UserController::class, 'edit'])->name('user.edit');
            Route::put('/{id}', [UserController::class, 'update'])->name('user.update');
        });
        Route:: group(['middleware' => 'permission:User,delete'], function () {
            Route::get('delete/{id}', [UserController::class, 'destroy'])->name('user.destroy');
        });
    });

    Route::prefix('roles')->group(function () {
        Route::get('', [RoleController::class, 'index'])->name('roles.index');
        Route::get('/create', [RoleController::class, 'create'])->name('roles.create');
        Route::post('/store', [RoleController::class, 'store'])->name('roles.store');
        Route::get('/{id}', [RoleController::class, 'show'])->name('roles.show');
        Route::get('/{role}/edit', [RoleController::class, 'edit'])->name('roles.edit');
        Route::put('/{id}', [RoleController::class, 'update'])->name('roles.update');
        Route::get('delete/{id}', [RoleController::class, 'destroy'])->name('roles.destroy');
    });

    Route::prefix('accounts')->group(function () {
        Route::get('', [AccountController::class, 'index'])->name('accounts.index');
        Route:: group(['middleware' => 'permission:Account,create'], function () {
            Route::get('/create', [AccountController::class, 'create'])->name('accounts.create');
            Route::post('/store', [AccountController::class, 'store'])->name('accounts.store');
        });
        Route:: group(['middleware' => 'permission:Account,read'], function () {
            Route::get('/{id}', [AccountController::class, 'show'])->name('accounts.show');
        });
        Route:: group(['middleware' => 'permission:Account,update'], function () {
            Route::get('/{account}/edit', [AccountController::class, 'edit'])->name('accounts.edit');
            Route::put('/{id}', [AccountController::class, 'update'])->name('accounts.update');
        });
        Route:: group(['middleware' => 'permission:Account,delete'], function () {
            Route::get('delete/{id}', [AccountController::class, 'destroy'])->name('accounts.destroy');
        });
    });

    Route::prefix('income-category')->group(function () {
        Route::get('', [IncomeCategoryController::class, 'index'])->name('income-category.index');
        Route:: group(['middleware' => 'permission:Income Category,create'], function () {
            Route::get('/create', [IncomeCategoryController::class, 'create'])->name('income-category.create');
            Route::post('/store', [IncomeCategoryController::class, 'store'])->name('income-category.store');
        });
        Route:: group(['middleware' => 'permission:Income Category,read'], function () {
            Route::get('/{id}', [IncomeCategoryController::class, 'show'])->name('income-category.show');
        });
        Route:: group(['middleware' => 'permission:Income Category,update'], function () {
            Route::get('/{income_category}/edit', [IncomeCategoryController::class, 'edit'])->name('income-category.edit');
            Route::put('/{id}', [IncomeCategoryController::class, 'update'])->name('income-category.update');
        });
        Route:: group(['middleware' => 'permission:Income Category,delete'], function () {
            Route::get('delete/{id}', [IncomeCategoryController::class, 'destroy'])->name('income-category.destroy');
        });
    });

    Route::prefix('expense-category')->group(function () {
        Route::get('', [ExpenseCategoryController::class, 'index'])->name('expense-category.index');
        Route:: group(['middleware' => 'permission:Expense Category,create'], function () {
            Route::get('/create', [ExpenseCategoryController::class, 'create'])->name('expense-category.create');
            Route::post('/store', [ExpenseCategoryController::class, 'store'])->name('expense-category.store');
        });
        Route:: group(['middleware' => 'permission:Expense Category,read'], function () {
            Route::get('/{id}', [ExpenseCategoryController::class, 'show'])->name('expense-category.show');
        });
        Route:: group(['middleware' => 'permission:Expense Category,update'], function () {
            Route::get('/{expense_category}/edit', [ExpenseCategoryController::class, 'edit'])->name('expense-category.edit');
            Route::put('/{id}', [ExpenseCategoryController::class, 'update'])->name('expense-category.update');
        });
        Route:: group(['middleware' => 'permission:Expense Category,delete'], function () {
            Route::get('delete/{id}', [ExpenseCategoryController::class, 'destroy'])->name('expense-category.destroy');
        });
    });

    Route::prefix('bank')->group(function () {
        Route::get('', [BankController::class, 'index'])->name('bank.index');
        Route::get('balance-sheet', [BankController::class, 'balanceSheet'])->name('bank.balance-sheet');
        Route::group(['middleware' => 'permission:Bank,create'], function () {
            Route::get('/create', [BankController::class, 'create'])->name('bank.create');
            Route::post('/store', [BankController::class, 'store'])->name('bank.store');
        });
        Route::group(['middleware' => 'permission:Bank,read'], function () {
            Route::get('/{id}', [BankController::class, 'show'])->name('bank.show');
        });
        Route::group(['middleware' => 'permission:Bank,update'], function () {
            Route::get('/{account}/edit', [BankController::class, 'edit'])->name('bank.edit');
            Route::put('/{id}', [BankController::class, 'update'])->name('bank.update');
        });
        Route::group(['middleware' => 'permission:Bank,delete'], function () {
            Route::get('delete/{id}', [BankController::class, 'destroy'])->name('bank.destroy');
        });
    });

    Route::prefix('bank-transaction')->group(function () {
        Route::get('', [BankTrasactionController::class, 'index'])->name('bank-transaction.index');
        Route::get('balance-sheet', [BankTrasactionController::class, 'balanceSheet'])->name('bank-transaction.balance-sheet');
        Route::group(['middleware' => 'permission:Bank,create'], function () {
            Route::get('/create', [BankTrasactionController::class, 'create'])->name('bank-transaction.create');
            Route::post('/store', [BankTrasactionController::class, 'store'])->name('bank-transaction.store');
        });
        Route::group(['middleware' => 'permission:Bank,read'], function () {
            Route::get('/{id}', [BankTrasactionController::class, 'show'])->name('bank-transaction.show');
        });
        Route::group(['middleware' => 'permission:Bank,update'], function () {
            Route::get('/{account}/edit', [BankTrasactionController::class, 'edit'])->name('bank-transaction.edit');
            Route::put('/{id}', [BankTrasactionController::class, 'update'])->name('bank-transaction.update');
        });
        Route::group(['middleware' => 'permission:Bank,delete'], function () {
            Route::get('delete/{id}', [BankTrasactionController::class, 'destroy'])->name('bank-transaction.destroy');
        });
    });

    Route::prefix('member')->group(function () {
        Route::get('', [MemberController::class, 'index'])->name('member.index');
        Route:: group(['middleware' => 'permission:Member,create'], function () {
            Route::get('/create', [MemberController::class, 'create'])->name('member.create');
            Route::post('/store', [MemberController::class, 'store'])->name('member.store');
        });
        Route:: group(['middleware' => 'permission:Member,read'], function () {
            Route::get('/{id}', [MemberController::class, 'show'])->name('member.show');
        });
        Route:: group(['middleware' => 'permission:Member,update'], function () {
            Route::get('/{member}/edit', [MemberController::class, 'edit'])->name('member.edit');
            Route::put('/{id}', [MemberController::class, 'update'])->name('member.update');
        });
        Route:: group(['middleware' => 'permission:Member,delete'], function () {
            Route::get('delete/{id}', [MemberController::class, 'destroy'])->name('member.destroy');
        });
        Route::get('fetch-house-address/{id}', [MemberController::class, 'fetchHouseAddress'])->name('member.fetch-house-address');
    });

    Route::prefix('house')->group(function () {
        Route::get('', [HouseController::class, 'index'])->name('house.index');
        Route:: group(['middleware' => 'permission:House,create'], function () {
            Route::get('/create', [HouseController::class, 'create'])->name('house.create');
            Route::post('/store', [HouseController::class, 'store'])->name('house.store');
        });
        Route:: group(['middleware' => 'permission:House,read'], function () {
            Route::get('/{id}', [HouseController::class, 'show'])->name('house.show');
        });
        Route:: group(['middleware' => 'permission:House,update'], function () {
            Route::get('/{house}/edit', [HouseController::class, 'edit'])->name('house.edit');
            Route::put('/{id}', [HouseController::class, 'update'])->name('house.update');
        });
        Route:: group(['middleware' => 'permission:House,delete'], function () {
            Route::get('delete/{id}', [HouseController::class, 'destroy'])->name('house.destroy');
        });
        Route::get('fetch-owner/{id}', [HouseController::class, 'fetchOwner'])->name('house.fetch-owner');
        Route::post('change-owner/{id}', [HouseController::class, 'changeOwner'])->name('house.change-owner');
        Route::get('remove-house-member/{id}', [HouseController::class, 'removeHouseMember'])->name('house.remove-house-member');

    });

    Route::get('/fetch-all-houses', [HouseController::class, 'fetchAllHouses'])->name('house.fetch-all');

    Route::prefix('income')->group(function () {
        Route::get('', [IncomeController::class, 'index'])->name('income.index');
        Route:: group(['middleware' => 'permission:Income,create'], function () {
            Route::get('/create', [IncomeController::class, 'create'])->name('income.create');
            Route::post('/store', [IncomeController::class, 'store'])->name('income.store');
        });
        Route:: group(['middleware' => 'permission:Income,read'], function () {
            Route::get('/{id}', [IncomeController::class, 'show'])->name('income.show');
            Route::get('receipt/{id}', [IncomeController::class, 'receipt'])->name('income.receipt');
            Route::get('donation/{id}', [IncomeController::class, 'donation'])->name('income.donation');
        });
        Route:: group(['middleware' => 'permission:Income,update'], function () {
            Route::get('/{income}/edit', [IncomeController::class, 'edit'])->name('income.edit');
            Route::put('/{id}', [IncomeController::class, 'update'])->name('income.update');
        });
        Route:: group(['middleware' => 'permission:Income,delete'], function () {
            Route::get('delete/{id}', [IncomeController::class, 'destroy'])->name('income.destroy');
        });
        Route::post('house-member', [IncomeController::class, 'fetchHouseMember'])->name('income.house-member');
    });

    Route::prefix('expense')->group(function () {
        Route::get('', [ExpenseController::class, 'index'])->name('expense.index');
        Route:: group(['middleware' => 'permission:Expense,create'], function () {
            Route::get('/create', [ExpenseController::class, 'create'])->name('expense.create');
            Route::post('/store', [ExpenseController::class, 'store'])->name('expense.store');
        });
        Route:: group(['middleware' => 'permission:Expense,read'], function () {
            Route::get('/{id}', [ExpenseController::class, 'show'])->name('expense.show');
        });
        Route:: group(['middleware' => 'permission:Expense,update'], function () {
            Route::get('/{expense}/edit', [ExpenseController::class, 'edit'])->name('expense.edit');
            Route::put('/{id}', [ExpenseController::class, 'update'])->name('expense.update');
        });
        Route:: group(['middleware' => 'permission:Expense,delete'], function () {
            Route::get('delete/{id}', [ExpenseController::class, 'destroy'])->name('expense.destroy');
        });
    });

    Route::prefix('expense-member')->group(function () {
        Route::get('', [ExpenseMemberController::class, 'index'])->name('expense-member.index');
        Route:: group(['middleware' => 'permission:Expense Member,create'], function () {
            Route::get('/create', [ExpenseMemberController::class, 'create'])->name('expense-member.create');
            Route::post('/store', [ExpenseMemberController::class, 'store'])->name('expense-member.store');
        });
        Route:: group(['middleware' => 'permission:Expense Member,read'], function () {
            Route::get('/{id}', [ExpenseMemberController::class, 'show'])->name('expense-member.show');
        });
        Route:: group(['middleware' => 'permission:Expense Member,update'], function () {
            Route::get('/{expense_member}/edit', [ExpenseMemberController::class, 'edit'])->name('expense-member.edit');
            Route::put('/{id}', [ExpenseMemberController::class, 'update'])->name('expense-member.update');
        });
        Route:: group(['middleware' => 'permission:Expense Member,delete'], function () {
            Route::get('delete/{id}', [ExpenseMemberController::class, 'destroy'])->name('expense-member.destroy');
        });
    });

    Route::get('activity-log', [ActivityLogController::class, 'index'])->name('activity-log.index');

    Route::prefix('report')->group(function () {
        Route::get('change-house-owner', [ReportController::class, 'changeHouseOwner'])->name('report.change-house-owner');
        Route::get('donation-report', [ReportController::class, 'donationReport'])->name('report.donation-report');
        Route::get('expense-report', [ReportController::class, 'expenseReport'])->name('report.expense-report');
        Route::get('income-report', [ReportController::class, 'incomeReport'])->name('report.income-report');
        Route::get('balance-sheet-report', [ReportController::class, 'balanceSheetReport'])->name('report.balance-sheet-report');
        Route::get('petty-cash-report', [ReportController::class, 'pettyCashReport'])->name('report.petty-cash-report');
    });

    Route::prefix('petty-cash')->group(function () {
        Route::get('', [PettycashController::class, 'index'])->name('petty-cash.index');
        Route:: group(['middleware' => 'permission:Petty Cash,create'], function () {
            Route::get('/create', [PettycashController::class, 'create'])->name('petty-cash.create');
            Route::post('/store', [PettycashController::class, 'store'])->name('petty-cash.store');
            Route::get('/log-store/{id}', [PettycashController::class, 'pettyCashLogCreate'])->name('petty-cash-log.create');
            Route::post('/log-store/{id}', [PettycashController::class, 'pettyCashLogStore'])->name('petty-cash-log.store');
        });
        Route::get('/{id}', [PettycashController::class, 'show'])->name('petty-cash.show');
        Route:: group(['middleware' => 'permission:Petty Cash,update'], function () {
            Route::get('/{id}/edit', [PettycashController::class, 'edit'])->name('petty-cash.edit');
            Route::put('/{id}', [PettycashController::class, 'update'])->name('petty-cash.update');
        });
        Route:: group(['middleware' => 'permission:Petty Cash,delete'], function () {
            Route::get('delete/{id}', [PettycashController::class, 'destroy'])->name('petty-cash.destroy');
        });
        Route::get('/log-store/delete-extras/{id}', [PettycashController::class, 'deleteExtraFields'])->name('petty-cash.delete');
    });
});


require __DIR__ . '/auth.php';
