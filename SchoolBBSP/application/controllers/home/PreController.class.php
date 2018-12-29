<?php
/**
 * 点赞控制类
 */
class PreController extends BaseController
{
	//默认
	public function indexAction(){
		$this->jump('index.php?c=index','页面访问错误');
	}

	//点赞
	public function addPreAction(){
		//获取数据
		$data['resp_id'] = $_GET['resp_id'] + 0;
		$data['username'] = $_SESSION['user']['username'];
		$msg = "";
		$flag = false;
		//实例化模型
		$preModel = new PreModel('pre_resp');
		$users = $preModel -> getUserByRespId($data['resp_id']);

		foreach ($users as $value) {
			if (implode(",", $value) == $data['username']){
				$flag = true;
				break;
			}
		}
		if ($flag){
			$msg = "你已点赞该评论！";
			$flag = false;
		}else{
			if ($preModel -> insert($data)){
				$msg = "点赞成功！";
				$flag = true;
			}else{
				$msg = "点赞错误！";
				$flag = false;
			}
		}
		echo <<<PRE
		<script type="text/javascript">
				if (($flag + 0) == true){
					var pre_number = window.parent.document.getElementById('pre_number');
					pre_number.innerHTML = parseInt(pre_number.innerHTML) + 1;
				}
				alert("$msg");
		</script>
PRE;
	}
}