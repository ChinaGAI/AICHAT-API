<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminUser extends Model
{
    use HasFactory;
    protected $table = 'admin_user';
    public $timestamps = false;
    protected $primaryKey = 'id';

    protected $fillable=['username','pwd','created_at','updated_at','state','department_id','role_id','nickname','avatar','phone_number'];

    /**显示部分用户接口
     * @return void
     * @author:阿文
     * @date:2024/1/8 17:22
     */
    public function user_data_show($user_id)
    {
        return $this->from('admin_user as user')
            ->select('user.nickname','user.avatar','role.role_name','user.created_at','user.phone_number','user.role_id','user.username')
            ->leftjoin('admin_user_role as role','user.role_id','=','role.id')
            ->where('user.id',$user_id)
            ->firstOrFail();

    }

    /**查询用户角色有的权限所有id
     * @param $user_id
     * @return mixed
     * @author:阿文
     * @date:2024/1/9 20:54
     */
    public function getRolodata($user_id)
    {
        return $this->from('admin_user as user')
            ->select('role.access_ids','role.id','user.id','user.role_id')
            ->leftjoin('admin_user_role as role','user.role_id','=','role.id')
            ->where('user.id',$user_id)
            ->firstOrFail();
    }

    /**获取用户列表关联查询
     * @return void
     * @author:阿文
     * @date:2024/1/11 16:40
     */
    public function getUserlst($where,$page_size,$page)
    {
        return $this->from('admin_user as user')
            ->select('role.role_name','user.*','department.name as department')
            ->leftjoin('admin_user_role as role','user.role_id','=','role.id')
            ->leftjoin('admin_department as department','user.department_id','=','department.id')
            ->where($where)
            ->paginate($page_size,['*'],'page',$page)
            ->toArray();
    }
}
