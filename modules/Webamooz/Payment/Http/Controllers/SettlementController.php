<?php

namespace Webamooz\Payment\Http\Controllers;

use App\Helper\Generate;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Webamooz\Payment\Http\Requests\SettlementRequest;
use Webamooz\Payment\Models\Settlement;
use Webamooz\Payment\Repositories\SettlementRepositories;
use Webamooz\Payment\Service\SettlementService;
use Webamooz\RolePermissions\Model\Permission;

class SettlementController extends Controller
{

    private $settlementRepo;

    public function __construct(SettlementRepositories $settlementRepo)
    {
        $this->settlementRepo = $settlementRepo;
    }

    public function index()
    {
        $this->authorize('index', Settlement::class);  //permission TEACHER
//      if (auth()->user()->hasAnyPermission([Permission::PERMISSION_MANAGE_SETTLEMENTS ,Permission::PERMISSION_SUPER_ADMIN])) {  //hasPermissionTo -> Gate::before(نمیاد چک کنه که سوپر ادمین)
        if (auth()->user()->can(Permission::PERMISSION_MANAGE_SETTLEMENTS)) {  //can -> Gate::before(میاد چک میکنه که سوپر ادمین)
            $settlements = $this->settlementRepo->paginate(12);
        } else {
            $settlements = $this->settlementRepo->paginateUserSettlements(12, auth()->user()->id);
        }
        return view('Payment::settlements.index', compact('settlements'));
    }

    public function create()
    {
        if (auth()->user()->hasPermissionTo(Permission::PERMISSION_TEACH)) {
//          $this->authorize('store', Settlement::class);  //permission TEACHER

            if ($this->settlementRepo->getLastPendingSettlement(auth()->id())) {  //آخرین مقدار وضعیت در حال انتظار کاربر پیدا کن اگر وجود داشت نتونه درخواست جدیدی ارسال کنه
                Generate::newFeedback('ناموفق', 'شما یک در خواست تسویه در حال انتظار دارد، و نمیتوانید در خواست جدیدی فعلا ثبت کنید', 'error');
                return to_route('settlements.index');
            }
            return view("Payment::settlements.create");
        } else {
            Generate::newFeedback("اطلاعات", "شما مدیر سایت هستید، و لازم به درخواست تسویه حساب ندارید :)", "info");
            return to_route('settlements.index');
        }
    }

    public function store(SettlementRequest $request)
    {
        if (auth()->user()->hasPermissionTo(Permission::PERMISSION_TEACH)) {
//          $this->authorize('store', Settlement::class);  //permission TEACHER

            if ($this->settlementRepo->getLastPendingSettlement(auth()->id())) {  //آخرین مقدار وضعیت در حال انتظار کاربر پیدا کن اگر وجود داشت نتونه درخواست جدیدی ارسال کنه
                Generate::newFeedback('ناموفق', 'شما یک در خواست تسویه در حال انتظار دارد، و نمیتوانید در خواست جدیدی فعلا ثبت کنید', 'error');
                return to_route('settlements.index');
            }
            SettlementService::store($request);
            return to_route('settlements.index');
        } else {
            Generate::newFeedback("اطلاعات", "شما مدیر سایت هستید، و لازم به درخواست تسویه حساب ندارید :)", "info");
            return to_route('settlements.index');
        }
    }

    public function edit($settlement_id)
    {
        $this->authorize('manage', Settlement::class);  //permission SETTLEMENT
        $requestSettlement = $this->settlementRepo->findById($settlement_id);
        $settlement = $this->settlementRepo->getLastSettlement($requestSettlement->user_id);  //بتونه آخرین درخواست خودش ویرایش کنه ما بقی نتونه
        if ($settlement->id != $settlement_id) {  //آیدی آخرین درخواست کاربر مساوی نبود با همون درخواست و روی درخواست دیگری دکمه ویرایش کلیک کرده بود
            Generate::newFeedback('ناموفق', 'این درخواست تسویه قابل ویرایش نیست و بایگانی شده است. فقط آخرین درخواست تسویه هر کاربر قابل ویرایش است.', 'error');
            return to_route('settlements.index');
        }
        return view("Payment::settlements.edit", compact('settlement'));
//
    }

    public function update(SettlementRequest $request, $settlement_id)
    {
        $this->authorize('manage', Settlement::class);  //permission SETTLEMENT
        $requestSettlement = $this->settlementRepo->findById($settlement_id);
        $settlement = $this->settlementRepo->getLastSettlement($requestSettlement->user_id);  //بتونه آخرین درخواست خودش ویرایش کنه ما بقی نتونه
        if ($settlement->id != $settlement_id) {  //آیدی آخرین درخواست کاربر مساوی نبود با همون درخواست و روی درخواست دیگری دکمه ویرایش کلیک کرده بود
            Generate::newFeedback('ناموفق', 'این درخواست تسویه قابل ویرایش نیست و بایگانی شده است. فقط آخرین درخواست تسویه هر کاربر قابل ویرایش است.', 'error');
            return to_route('settlements.index');
        }
        SettlementService::update($settlement_id, $request);  //update Settlement + update status
        return to_route('settlements.index');
    }

}
