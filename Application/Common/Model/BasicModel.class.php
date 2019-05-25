<?php
namespace Common\Model;
use Think\Model;

/**
 * 基本设置
 * @author  singwa
 */
class BasicModel extends Model {

	public function __construct() {

	}
	//保存配置到缓存
	public function save($data = array()) {
		if(!$data) {
			throw_exception('没有提交的数据');
		}
		// 存到缓存中 basic_web_config.php  数组  
		$id = F('basic_web_config', $data);
		return $id;
	}
	//显示缓存数据
	public function select() {
		return F("basic_web_config");
	}




}
