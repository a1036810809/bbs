<?php
/*
 *动态
 */

class DynamicController extends BaseController{
	public function addDynamicAction(){
		//获取数据
		$data['username'] = $_SESSION['user']['username'];
		$data['content'] = trim($_POST['dynamic']);
		$data['starttime'] = date('Y-m-d H:i:s');

		//批量转移
		$this->helper("input");
		$data = deepSpecialChars($data);

		//实例化模型
		$dynamicModel = new DynamicModel('dynamic');
		if ($dynamicId = $dynamicModel -> insert($data)){
			if ($_FILES['file']['error'] !== 4){
				//载入上传类
				$this->library('Upload');
				$upload = new Upload();
				if ($filename = $upload->up($_FILES['file'])){
					$imagesModel = new ImagesModel('images');
					$Idata['dynamic_id'] = $dynamicId - 1;
					$Idata['image'] = $filename;
					if ($imagesModel->insert($Idata)){
						$this -> jump('index.php?c=user&act=showUserInfo&id='.$data['username'],"发表成功");
					}else{
						unlink(UPLOAD_PATH.$filename);
						$this -> jump('index.php?c=user&act=showUserInfo&id='.$data['username'],"发表失败");
					}
				} else {
					//失败
					$dynamicModel->delete($dynamicId-1);
					$this -> jump('index.php?c=user&act=showUserInfo&id='.$data['username'],$upload -> error());
				}
			}else{
				$this -> jump('index.php?c=user&act=showUserInfo&id='.$data['username'],"发表成功");
			}
		}else{
			$this -> jump('index.php?c=user&act=showUserInfo&id='.$data['username'],"发表失败");
		}
	}

	public function respondAction(){
		//收集数据
		$data['username'] = $_SESSION['user']['username'];
		$data['content'] = $_POST['resp'];
		$data['dynamic_id'] = $_POST['dynamic_id'];
		$data['starttime'] = date('Y-m-d H:i:s');

		//调用模型
		$respondModel = new respondModel('respond_dyna');

		if ($id = $respondModel->insert($data)){
			echo json_encode(array('msg' => '评论成功！',
				'rowone' => "<td rowspan='2' width='30px'><div style='width: 30px;height: 30px;border-radius: 50%;overflow: hidden;'><img src='./public/uploads/".$_SESSION['user']['poto']."' height='100%'></div></td><td><span>&nbsp;&nbsp;<a href=''>".$_SESSION['user']['nickname']."</a> : ".$data['content']."</span></td><td rowspan='2' align='right'><a href='index.php?c=respond&act=delFDyn&id=".($id-1)."'>删除</a></td>",
				'rowtwo' => "<td><span style='font-size: .8em'>&nbsp;&nbsp;回复于 ".$data['starttime']."</span></td>",
				'id' => $data['dynamic_id']
			));	
		}else{
			echo json_encode(array('msg' => '评论失败！'));
		}
	}

	public function dianzhanAction(){
		//收集数据
		$data['username'] = $_SESSION['user']['username'];
		$data['dynamic_id'] = $_POST['dynamic_id'];

		//实例化模型
		$preModel = new PreModel('pre_dynam');
		$pre = $preModel->getColsByWhere('*',$where = "dynamic_id = ".$data['dynamic_id']." and username = '".$data['username']."'");
		if (!$pre){
			if ($preModel->insert($data)){
				$total = $preModel->total("dynamic_id = ".$data['dynamic_id']);
				echo json_encode(array('msg' => '点赞成功!','total' => $total));
			}else{
				$total = $preModel->total("dynamic_id = ".$data['dynamic_id']);
				echo json_encode(array('msg' => '未知错误!','total' => $total));
			}
		}else{
			$total = $preModel->total("dynamic_id = ".$data['dynamic_id']);
				echo json_encode(array('msg' => '你已点赞!','total' => $total));
		}
	}

	public function deleteAction(){
		//收集数据
		$username = $_SESSION['user']['username'];
		$id = $_GET['id']+0;

		$dynamicModel = new DynamicModel('dynamic');
		$imagesModel = new ImagesModel('images');
		$preModel = new PreModel('pre_dynam');
		$respondModel = new respondModel('respond_dyna');

		if ($dynamic = $dynamicModel->selectByPk($id)){
			if ($dynamic['username'] != $username){
				$this->jump("index.php?c=user&act=showUserInfo&id=$username",'错误访问！');
			}else{
				$where = "dynamic_id = $id";
				$imagesModel->deleteByWhere($where);
				$preModel->deleteByWhere($where);
				$respondModel->deleteByWhere($where);
				$dynamicModel->delete($id);
				$this->jump("index.php?c=user&act=showUserInfo&id=$username",'删除成功！');
			}
		}else{
			$this->jump("index.php?c=user&act=showUserInfo&id=$username",'错误访问！');
		}
	}
}