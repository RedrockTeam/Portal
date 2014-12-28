<?php
	namespace Admin\Model;
	use Think\Model;
	class FinderModel extends Model {
	   	protected $_validate = array(
		    array('job_title','require','必填'), // 默认情况下用正则进行验证
		    array('job_address','require','必填'), // 在新增的时候验证name字段是否唯一
		    array('job_tel','require','必填'), // 默认情况下用正则进行验证array('job_title','require','验证码必须'), //默认情况下用正则进行验证
		    array('job_master','require','必填'), // 当值不为空的时候判断是否在一个范围内
	    );
	}


?>