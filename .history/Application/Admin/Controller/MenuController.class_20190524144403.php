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
            //如果post过来的值的id是存在 那就是更新数据
            if($_POST['menu_id']){
                //调用save方法
                return $this->save($_POST);
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
        //获取页面传过来的id
        $menuId = $_GET['id'];
        //调用模型层方法 接收id 查到id对应的数据
        $menu = D("Menu")->find($menuId);
        var_dump($menu);
        //传递到页面上 value去接收值
        $this->assign('menu',$menu);
        $this->display();
    }
    //保存更新提交的数据的方法
    public function save($data){
        $menuId = $data['menu_id'];
        unset($data['menu_id']);
        try{
            $id = D("Menu")->updateMenuByid($menuId,$data);
            if($id === false){
                return show(0,'更新失败!');
            }
            return show(1,'编辑成功');
        }catch(Exception $e){
            return show(0,$e->getMessage());
        }
    }
    //删除菜单 改变status
    public function setStatus() {
        try{
            if($_POST){//如果是post提交
                $id = $_POST['menu_id'];
                $status = $_POST['status'];
                $res  = D("Menu")->updateStatusById($id,$status);
                if($res){
                    return show(1,'删除成功！');
                }else{
                    return show(0,'删除失败！');
                }
            }
        }catch(Exception $e){
            return show()
        }
        
            
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