<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/




//Route::middleware(['token'])->prefix('admin')->group(function () {
Route::prefix('admin')->group(function () {
    //获取当前登陆用户基本信息和修改
    Route::get('/account',[\App\Http\Controllers\Api\Admin\UserData::class,'user_data'])->withoutMiddleware('token');
    Route::put('/account',[\App\Http\Controllers\Api\Admin\UserData::class,'update']);
    Route::put('/account/password',[\App\Http\Controllers\Api\Admin\UserData::class,'update_pwd']);
    //登陆接口校验
    Route::post('/login',[\App\Http\Controllers\Api\Admin\LoginController::class,'login_data_check'])->withoutMiddleware('token');
    //权限列表
    Route::get('/system/access/list',[\App\Http\Controllers\Api\Admin\AccessController::class,'list']);
    Route::post('/system/access',[\App\Http\Controllers\Api\Admin\AccessController::class,'create']);
    Route::put('/system/access',[\App\Http\Controllers\Api\Admin\AccessController::class,'update']);
    Route::delete('/system/access',[\App\Http\Controllers\Api\Admin\AccessController::class,'delete']);
    //角色列表
    Route::get('/system/role/list',[\App\Http\Controllers\Api\Admin\RoleController::class,'list']);
    Route::post('/system/role',[\App\Http\Controllers\Api\Admin\RoleController::class,'create']);
    Route::put('/system/role',[\App\Http\Controllers\Api\Admin\RoleController::class,'update']);
    Route::delete('/system/role',[\App\Http\Controllers\Api\Admin\RoleController::class,'delete']);
    //部门列表
    Route::get('/system/department/list',[\App\Http\Controllers\Api\Admin\DepartmentController::class,'list']);
    Route::post('/system/department',[\App\Http\Controllers\Api\Admin\DepartmentController::class,'create']);
    Route::put('/system/department',[\App\Http\Controllers\Api\Admin\DepartmentController::class,'update']);
    Route::delete('/system/department',[\App\Http\Controllers\Api\Admin\DepartmentController::class,'delete']);
    //upload
    Route::post('/upload/image',[\App\Http\Controllers\Comment\Upload::class,'upload_img'])->withoutMiddleware('token');
    //后台设置配置
    Route::get('/system/config',[\App\Http\Controllers\Api\Admin\ConfigDataController::class,'list'])->withoutMiddleware('token');
    Route::put('/system/config',[\App\Http\Controllers\Api\Admin\ConfigDataController::class,'update']);
    //系统操作日志
    Route::get('/system/logs',[\App\Http\Controllers\api\Admin\OperatorLogController::class,'list']);
    //员工列表
    Route::get('/system/admin/list',[\App\Http\Controllers\Api\Admin\UserController::class,'list']);
    Route::post('/system/admin',[\App\Http\Controllers\Api\Admin\UserController::class,'create']);
    Route::put('/system/admin',[\App\Http\Controllers\Api\Admin\UserController::class,'update']);
    Route::delete('/system/admin',[\App\Http\Controllers\Api\Admin\UserController::class,'delete']);
    //菜单
    Route::get('/menu/list',[\App\Http\Controllers\Api\Admin\MenuController::class,'list']);
    Route::post('/menu',[\App\Http\Controllers\Api\Admin\MenuController::class,'create']);
    Route::post('/menu/sort',[\App\Http\Controllers\Api\Admin\MenuController::class,'sort']);
    Route::put('/menu',[\App\Http\Controllers\Api\Admin\MenuController::class,'update']);
    Route::delete('/menu',[\App\Http\Controllers\Api\Admin\MenuController::class,'delete']);
    //自定义页面
    Route::get('/page/list',[\App\Http\Controllers\Api\Admin\HomePageController::class,'list']);
    Route::get('/page',[\App\Http\Controllers\Api\Admin\HomePageController::class,'details']);
    Route::post('/page',[\App\Http\Controllers\Api\Admin\HomePageController::class,'create']);
    Route::put('/page',[\App\Http\Controllers\Api\Admin\HomePageController::class,'update']);
    Route::delete('/page',[\App\Http\Controllers\Api\Admin\HomePageController::class,'delete']);
    //iterations
    Route::get('/iterations/list',[\App\Http\Controllers\Api\Admin\IterationsController::class,'list']);
    Route::get('/iterations',[\App\Http\Controllers\Api\Admin\IterationsController::class,'details']);
    Route::post('/iterations',[\App\Http\Controllers\Api\Admin\IterationsController::class,'create']);
    Route::put('/iterations',[\App\Http\Controllers\Api\Admin\IterationsController::class,'update']);
    Route::delete('/iterations',[\App\Http\Controllers\Api\Admin\IterationsController::class,'delete']);
    //chat api keys
    Route::get('/chat_api_key/list',[\App\Http\Controllers\Api\Admin\ChatApiKeyController::class,'list']);
    Route::post('/chat_api_key',[\App\Http\Controllers\Api\Admin\ChatApiKeyController::class,'create']);
    Route::put('/chat_api_key',[\App\Http\Controllers\Api\Admin\ChatApiKeyController::class,'update']);
    Route::post('/chat_api_key/sort',[\App\Http\Controllers\Api\Admin\ChatApiKeyController::class,'sort']);

    //chat models
    Route::get('/chat_models/list',[\App\Http\Controllers\Api\Admin\ChatModelsController::class,'list']);
    Route::post('/chat_models',[\App\Http\Controllers\Api\Admin\ChatModelsController::class,'create']);
    Route::put('/chat_models',[\App\Http\Controllers\Api\Admin\ChatModelsController::class,'update']);
    Route::delete('/chat_models',[\App\Http\Controllers\Api\Admin\ChatModelsController::class,'delete']);
    Route::post('/chat_models/sort',[\App\Http\Controllers\Api\Admin\ChatModelsController::class,'sort']);
    //chat Role
    Route::get('/chat_role/list',[\App\Http\Controllers\Api\Admin\ChatRoleController::class,'list']);
    Route::post('/chat_role',[\App\Http\Controllers\Api\Admin\ChatRoleController::class,'create']);
    Route::put('/chat_role',[\App\Http\Controllers\Api\Admin\ChatRoleController::class,'update']);
    Route::delete('/chat_role',[\App\Http\Controllers\Api\Admin\ChatRoleController::class,'delete']);
    //chat RoleTag
    Route::get('/chat_role_tag/list',[\App\Http\Controllers\Api\Admin\ChatRoleTagController::class,'list']);
    Route::post('/chat_role_tag',[\App\Http\Controllers\Api\Admin\ChatRoleTagController::class,'create']);
    Route::put('/chat_role_tag',[\App\Http\Controllers\Api\Admin\ChatRoleTagController::class,'update']);
    Route::delete('/chat_role_tag',[\App\Http\Controllers\Api\Admin\ChatRoleTagController::class,'delete']);
    Route::post('/chat_role_tag/sort',[\App\Http\Controllers\Api\Admin\ChatRoleTagController::class,'sort']);
    //chat Item
    Route::get('/chat_item/list',[\App\Http\Controllers\Api\Admin\ChatItemsController::class,'list']);
    //shop
    Route::get('/shop',[\App\Http\Controllers\Api\Admin\ShopController::class,'shop']);
    Route::get('/shop/list',[\App\Http\Controllers\Api\Admin\ShopController::class,'list']);
    Route::post('/shop',[\App\Http\Controllers\Api\Admin\ShopController::class,'create']);
    Route::put('/shop',[\App\Http\Controllers\Api\Admin\ShopController::class,'update']);
    //user order
    Route::get('/order/list',[\App\Http\Controllers\Api\Admin\UserOrderController::class,'list']);
    Route::put('/order/confirm',[\App\Http\Controllers\Api\Admin\UserOrderController::class,'update'])->withoutMiddleware('token');
    //user token
    Route::get('/user_token/list',[\App\Http\Controllers\Api\Admin\UserTokenController::class,'list']);
    //chat History
    Route::get('/chat_history/list',[\App\Http\Controllers\Api\Admin\ChatHistoryController::class,'list']);
    //user
    Route::get('/user/list',[\App\Http\Controllers\Api\Admin\HomeUserController::class,'list']);
    Route::post('/user/add_token',[\App\Http\Controllers\Api\Admin\HomeUserController::class,'add_token']);
    Route::get('/user/history/sms',[\App\Http\Controllers\Api\Admin\HomeUserController::class,'sms_list']);
    Route::get('/user/history/email',[\App\Http\Controllers\Api\Admin\HomeUserController::class,'email_list']);
    Route::get('/user/history/login',[\App\Http\Controllers\Api\Admin\HomeUserController::class,'token_list']);


});

Route::prefix('home')->group(function () {
    //登陆/注册需要的
    Route::get('/captcha/image',[\App\Http\Controllers\Api\Home\LoginController::class,'captcha']);
    Route::post('/sms',[\App\Http\Controllers\Api\Home\LoginController::class,'send_sms']);
    Route::post('/email',[\App\Http\Controllers\Api\Home\LoginController::class,'send_email']);
    Route::post('/login',[\App\Http\Controllers\Api\Home\LoginController::class,'login']);
    Route::post('/login/sms',[\App\Http\Controllers\Api\Home\LoginController::class,'login_sms']);
    Route::post('/pwd_reset',[\App\Http\Controllers\Api\Home\LoginController::class,'pwd_reset']);
    //用户操作相关的
    Route::get('/user',[\App\Http\Controllers\Api\Home\UserController::class,'user_data'])->middleware('VerifyHomeToken');
    Route::post('/user/register',[\App\Http\Controllers\Api\Home\UserController::class,'register']);
    Route::get('/user/role_like',[\App\Http\Controllers\Api\Home\UserController::class,'role_like'])->middleware('VerifyHomeToken');
    Route::get('/user/my_role',[\App\Http\Controllers\Api\Home\UserController::class,'my_role'])->middleware('VerifyHomeToken');
    Route::post('/user/bind',[\App\Http\Controllers\Api\Home\UserController::class,'bind'])->middleware('VerifyHomeToken');
    Route::post('/user/update_pwd',[\App\Http\Controllers\Api\Home\UserController::class,'update_pwd'])->middleware('VerifyHomeToken');
    Route::post('/user/update_data',[\App\Http\Controllers\Api\Home\UserController::class,'update_data'])->middleware('VerifyHomeToken');
    //获取用户相关
    Route::get('/chat/list',[\App\Http\Controllers\Api\Home\ChatController::class,'list'])->middleware('VerifyHomeToken');
    Route::get('/chat/detail',[\App\Http\Controllers\Api\Home\ChatController::class,'detail'])->middleware('VerifyHomeToken');
    Route::get('/chat/history',[\App\Http\Controllers\Api\Home\ChatController::class,'history'])->middleware('VerifyHomeToken');
    Route::post('/chat/message',[\App\Http\Controllers\Api\Home\ChatController::class,'chat_message'])->middleware('VerifyHomeToken');
    Route::post('/chat_new/message',[\App\Http\Controllers\Api\Home\ChatController::class,'chat_new_message'])->middleware('VerifyHomeToken');
    Route::post('/chat/stop',[\App\Http\Controllers\Api\Home\ChatController::class,'stop'])->middleware('VerifyHomeToken');
    Route::delete('/chat',[\App\Http\Controllers\Api\Home\ChatController::class,'delete'])->middleware('VerifyHomeToken');
    //chat model
    Route::get('/model/list',[\App\Http\Controllers\Api\Home\ChatModelsController::class,'model_list']);
    //chat role
    Route::get('/role/list',[\App\Http\Controllers\Api\Home\ChatRoleController::class,'role_list']);
    Route::get('/role',[\App\Http\Controllers\Api\Home\ChatRoleController::class,'role']);
    Route::Post('/role/like',[\App\Http\Controllers\Api\Home\ChatRoleController::class,'role_like'])->middleware('VerifyHomeToken');
    Route::post('/role',[\App\Http\Controllers\Api\Home\ChatRoleController::class,'create_role'])->middleware('VerifyHomeToken');
    Route::put('/role',[\App\Http\Controllers\Api\Home\ChatRoleController::class,'update_role'])->middleware('VerifyHomeToken');
    Route::delete('/role',[\App\Http\Controllers\Api\Home\ChatRoleController::class,'delete_role'])->middleware('VerifyHomeToken');
    //chat role tag
    Route::get('/role_tag/list',[\App\Http\Controllers\Api\Home\ChatRoleTagController::class,'role_tag_list']);
    //user bill
    Route::get('/bill/token',[\App\Http\Controllers\Api\Home\UserBillController::class,'token'])->middleware('VerifyHomeToken');
    Route::get('/bill/order',[\App\Http\Controllers\Api\Home\UserBillController::class,'order'])->middleware('VerifyHomeToken');
    Route::get('/bill/token_desc',[\App\Http\Controllers\Api\Home\UserBillController::class,'get_token_desc'])->middleware('VerifyHomeToken');
    Route::get('/bill/week_token',[\App\Http\Controllers\Api\Home\UserBillController::class,'get_week_token'])->middleware('VerifyHomeToken');
    //配置信息
    Route::get('/config',[\App\Http\Controllers\Api\Home\ConfigController::class,'all_config']);

    Route::get('/shop',[\App\Http\Controllers\Api\Home\ShopController::class,'shop_list']);
    Route::post('/shop/pay',[\App\Http\Controllers\Api\Home\ShopController::class,'pay'])->middleware('VerifyHomeToken');
    Route::post('/shop/pay_confirm',[\App\Http\Controllers\Api\Home\ShopController::class,'pay_confirm'])->middleware('VerifyHomeToken');
    Route::post('/upload/image',[\App\Http\Controllers\Comment\Upload::class,'upload_img'])->middleware('VerifyHomeToken');
    //商家支付
    Route::get('/shop/alipay',[\App\Http\Controllers\Api\Home\AllPayController::class,'alipay']);
    Route::post('/shop/wxpay',[\App\Http\Controllers\Api\Home\AllPayController::class,'wechatPay']);
    Route::post('/shop/alipay_callback',[\App\Http\Controllers\Api\Home\AllPayController::class,'alipay_callback']);
    Route::post('/shop/wechat_callback',[\App\Http\Controllers\Api\Home\AllPayController::class,'wechat_callback']);

});

Route::prefix('work')->group(function () {
    Route::post('/check',[\App\Http\Controllers\Api\Work\ChatController::class,'check']);
    Route::post('/bind_client_id',[\App\Http\Controllers\Api\Work\ChatController::class,'bind_client_id']);
});



