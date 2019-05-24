<?php
/**
 * 后台菜单相关
 */
namespace Admin\Controller;
use Think\Controller;
use Think\Exception;
use Think\Page;

class MenuController extends CommonController {
    // 菜单列表页
    public function index() {
        $data = array();
        //搜索类型的功能
        //如果存在这个变量type 并且值是在 0和这两只之间
        if(isset($_REQUEST['type']) && in_array($_REQUEST['type'],array(0,1))){
            //获取变量的整数值
            $data['type']  = intval($_REQUEST['type']);
            //将上面的type赋值给页面  <if condition="$type eq 1">selected="selected"</if>
            $this->assign('type',$data['type']);
        }else{
            //搜索值为空的时候
            $this->assign('type',-1
        );
        }
        /**
         * 分页操作逻辑
         */
        $page = $_REQUEST['p'] ? $_REQUEST['p'] : 1;//默认第一页
        $pageSize = $_REQUEST['pageSize'] ? $_REQUEST['pageSize'] : 5;//默认显示5
        //动态获取菜单列表数据
        $menus = D("Menu")->getMenus($data,$page,$pageSize);
        $munusCount = D("Menu")->getMenusCount($data);
        $res = new Page($munusCount,$pageSize);
        $pageRes = $res->show();
        $this->assign('pageRes',$pageRes);
        $this->assign('menus',$menus);

        $this->display();
    }
    // 菜单添加页
    public function add(){
        if($_POST) {
            if(!$_POST['name'] || !isset($_POST['name'])){
                return show(0,'菜单名不能为空！');
            }
            if(!$_POST['m'] || !isset($_POST['m'])){
                return show(0,'模块名不能为空！');
            }
            if(!$_POST['c'] || !isset($_POST['c'])){
                return show(0,'控制器不能为空！');
            }
            if(!$_POST['f'] || !isset($_POST['f'])){
                return show(0,'方法不能为空！');
            }
            //执行插入
            $menuId = D("Menu")->insert($_POST);
            
            if($menuId) {
                return show(1,'新增成功',$menuId);
            }
            return show(0,'新增失败',$menuId);
            
        }else {
            $this->display();
        }
        //echo "welcome to singcms";
    }
    //编辑菜单
    public function edit() {
        $this->display
    }
    public function save($data) {
        $menuId = $data['menu_id'];
        unset($data['menu_id']);

        try {
            $id = D("Menu")->updateMenuById($menuId, $data);
            if($id === false) {
                return show(0,'更新失败');
            }
            return show(1,'更新成功');
        }catch(Exception $e) {
            return show(0,$e->getMessage());
        }

    }

    public function setStatus() {
        try {
            if ($_POST) {
                $id = $_POST['id'];
                $status = $_POST['status'];
                // 执行数据更新操作
                $res = D("Menu")->updateStatusById($id, $status);
                if ($res) {
                    return show(1, '操作成功');
                } else {
                    return show(0, '操作失败');
                }

            }
        }catch(Exception $e) {
            return show(0,$e->getMessage());
        }

        return show(0,'没有提交的数据');
    }
    public function listorder() {
        $listorder = $_POST['listorder'];
        $jumpUrl = $_SERVER['HTTP_REFERER'];
        $errors = array();
        if($listorder) {
            try {
                foreach ($listorder as $menuId => $v) {
                    // 执行更新
                    $id = D("Menu")->updateMenuListorderById($menuId, $v);
                    if ($id === false) {
                        $errors[] = $menuId;
                    }

                }
            }catch(Exception $e) {
                return show(0,$e->getMessage(),array('jump_url'=>$jumpUrl));
            }
            if($errors) {
                return show(0,'排序失败-'.implode(',',$errors),array('jump_url'=>$jumpUrl));
            }
            return show(1,'排序成功',array('jump_url'=>$jumpUrl));
        }

        return show(0,'排序数据失败',array('jump_url'=>$jumpUrl));
    }




}