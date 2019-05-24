<?php
namespace Common\Model;
use Think\Model;

class MenuModel extends  Model {
    private $_db = '';
    public function __construct() {
        $this->_db = M('menu');
    }
    //执行插入的方法
    public function insert($data = array()) {
        if(!$data || !is_array($data)) {
            return 0;
        }
        return $this->_db->add($data);
    }

    // 获取菜单列表
    public function getMenus($data,$page,$pageSize=10) {
        //过滤状态是删除状态的菜单
        $data['status'] = array('neq',-1);
        //设置起始位置
        $offset = ($page -1) * $pageSize;
        //进行查询
        $list = $this->_db->where($data)->order('menu_id desc')->limit($offset,$pageSize)->select();
        return $list;
    }
    //获取菜单总数
    public function getMenusCount($data= array()) {
        //过滤状态是删除状态的菜单
        $data['status'] = array('neq',-1);
        return $this->_db->where($data)->count();
    }
    //根据id查询单条结果
    public function find($id){
        //如果id不存在 或者不是一个数字
        if(!$id || !is_numeric($id)){
            retu
        }
        $this->_db->where('menu_id'.$id)->find();
    }
    public function updateMenuById($id, $data) {
        if(!$id || !is_numeric($id)) {
            throw_exception('ID不合法');
        }

        if(!$data || !is_array($data)) {
            throw_exception('更新的数据不合法');
        }

        return $this->_db->where('menu_id='.$id)->save($data);

    }

    public function updateStatusById($id, $status) {
        if(!is_numeric($id) || !$id) {
            throw_exception("ID不合法");
        }
        if(!is_numeric($status) || !$status) {
            throw_exception("状态不合法");
        }

        $data['status'] = $status;
        return $this->_db->where('menu_id='.$id)->save($data);
     }
    public function updateMenuListorderById($id, $listorder) {
        if(!$id || !is_numeric($id)) {
            throw_exception('ID不合法');
        }

        $data = array(
            'listorder' => intval($listorder),
        );

        return $this->_db->where('menu_id='.$id)->save($data);
    }

    public function getAdminMenus() {
        $data = array(
            'status' => array('neq',-1),
            'type' => 1,
        );

        return $this->_db->where($data)->order('listorder desc,menu_id desc')->select();
    }

    public function getBarMenus() {
        $data = array(
            'status' => 1,
            'type' => 0,
        );

        $res = $this->_db->where($data)
            ->order('listorder desc,menu_id desc')
            ->select();
        return $res;
    }
}