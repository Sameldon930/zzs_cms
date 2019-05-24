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
            return array();
        }
        return $this->_db->where('menu_id='.$id)->find();
    }
    //更新单条数据的方法
    public function updateMenuByid($id,$data){
        //如果id不存在 或者  id的值不是一个数字
        if(!$id || !is_numeric($id)){
            //抛出异常
            throw_exception('ID不合法');
        }
        //如果data不存在 或者 不是一个数组
        if(!$data || !is_array($data)){
            throw_exception('更新的数据不合法');
        }
        return $this->_db->where('menu_id='.$id)->save($data);
    }
    //删除菜单  也就是改变状态值
    public function updateStatusById($id, $status) {
        
        //如果这个id不是数字 或者id不存在
        if(!$id || !is_numeric($id)){
            throw_exception('id不合法');
        }
        //如果状态值不存在 或者不是数字
        if(!$status || !is_numeric($status)){
            throw_exception('状态值不合法');
        }
        $data['status'] = $status;
        return $this->_db->where('menu_id='.$id)->save($data);
     }
     //更新列表排序的方法
    public function updateMenuListorderById($id, $listorder) {
        //如果id不存在或者不是数字
        if(!$id || !is_numeric($id)){
            throw_exception('id不合法');
        }
        $data = array(
            'listorder'=>intval($listorder),
        );
        return $this->_db->where('menu_id='.$id)->save($data);
    }
    //获取zuo'ce
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