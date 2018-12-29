<?php
class MessageController extends Controller{

	public function addMessageAction(){
		//收集数据
		$data['username_mes'] = $_SESSION['user']['username'];
		$data['content'] = trim($_POST['message']);
		$data['starttime'] = date('Y-m-d H:i:s');
		$data['username_ed'] = $_POST['id'];

		//实例化模型
		$messageModel = new MessageModel('message');

		if ($messageModel->insert($data)){
			$this -> jump('index.php?c=user&act=showUserInfo&id='.$data['username_ed']."&i=3","发表成功",1);
		}else{
			$this -> jump('index.php?c=user&act=showUserInfo&id='.$data['username_ed']."&i=3","发表失败");
		}
	}

	public function deleteAction(){
		//获取数据
		$username = $_SESSION['user']['username'];
		$id = $_GET['id'] + 0;

		$messageModel = new MessageModel('message');

		if ($mes = $messageModel->selectByPk($id)){
			if ($username == $mes['username_ed'] || $username == $mes['username_mes']){
				if ($messageModel->delete($id)){
					$this -> jump('index.php?c=user&act=showUserInfo&id='.$mes['username_ed']."&i=3","删除成功",1);
				}else{
					$this -> jump('index.php?c=user&act=showUserInfo&id='.$mes['username_ed']."&i=3","删除失败");
				}
			}else{
				$this -> jump('index.php?c=user&act=showUserInfo&id='.$mes['username_ed']."&i=3","非法访问！");
			}
		}else{
			$this -> jump('index.php?c=user&act=showUserInfo&id='.$mes['username_ed']."&i=3","非法访问！");
		}
	}
}