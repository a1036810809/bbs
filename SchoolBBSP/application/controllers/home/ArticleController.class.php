<?php
//帖子控制类
class ArticleController extends BaseController {
	//默认主页
	public function indexAction(){
		$user = $_SESSION['user'];
		//模型
		$classname = isset($_GET['classname']) ? "classname = '".trim($_GET['classname'])."'" : '';
		$classname = isset($_POST['word']) ? "title like '%".$_POST['word']."%'" : $classname;
		$respondModel = new RespondModel('respond_arti');
		$articleModel = new ArticleModel('article');
		$classfityModel = new ClassfityModel('classfity');
		$attentionModel = new AttentionModel('attention');
		$userInfoModel = new UserInfoModel('userinfo');
		//分页获取数据
		$pagesize = 5; //页面大小
		$current = isset($_GET['page']) ? $_GET['page'] : 1; //当前页
		$offset = ($current - 1) * $pagesize;
		$articles = $articleModel -> getAllArticleByPage($offset,$pagesize,$classname);
		$total = $articleModel -> total($classname); //总共的数据数

		$this->library('Page');
		$page = new Page($total,$pagesize,$current,"index.php",array('p'=>'home','c'=>'article','act'=>'index'));
		$pageinfo = $page -> showPage();
		
		//读取数据
		$classs = $classfityModel->getAll();
		$att_num = $attentionModel->total("username_att = '".$user['username']."'");
		$fens_num = $attentionModel->total("username_ed = '".$user['username']."'");
		$guanf = $articleModel -> getAll("classname = '官方公告'");
		$ans = array();

		foreach ($articles as $key => $value) {
			$ans[$key]['article'] = $value;
			$ans[$key]['userinfo'] = $userInfoModel -> selectByPk($value['username']);
			$whereone = "arti_id = {$value['arti_id']}";
			$whereTow = "arti_id = {$value['arti_id']} and starttime = '{$value['endTime']}'";
			$ans[$key]['respondnum'] = $respondModel -> total($whereone);
			$username = ($respondModel -> getUsernameByWhere($whereTow));
			$ans[$key]['user_respond'] = $ans[$key]['respondnum'] == 0 ? $ans[$key]['userinfo'] : $userInfoModel -> selectByPk($username[0]['username']);
		}
		include CUR_VIEW_PATH . 'articlelist.html';
	}

	public function addArtiAction(){
		$data['title'] = $_POST['title'];
		$data['article'] = $_POST['article'];
		$data['username'] = $_SESSION['user']['username'];
		$data['starttime'] = date("Y-m-d H:i:s");
		$data['endTime'] = date("Y-m-d H:i:s");
		$data['classname'] = $_POST['classname'];
		$data['power'] = $_POST['power'];
		$json = $_POST['data'];
		$images = json_decode($json);
		
		if ($data['power'] == 2 && $_SESSION['user']['identity'] == 1){
			echo json_encode(array('url' =>'index.php' , 'msg'=>'非法上传'));
			exit();
		}

		//批量转移
		$this->helper("input");
		$this->library('Upload');
		$data = deepSpecialChars($data);
		$upload = new Upload();
		//插入
		$articleModel = new ArticleModel('article');
		if ($articleNo = $articleModel -> insert($data)){
			$imagesModel = new ImagesModel('images');
			foreach ($images as $image) {
				# code...
				$filename = $upload->baseToImage($image);
				$idata['arti_id'] = $articleNo-1;
				$idata['image'] = $filename;
				$imagesModel->insert($idata);
			}
			echo json_encode(array('url' =>'index.php' , 'msg'=>'发布成功'));
		}else{
			echo json_encode(array('url' =>'index.php' , 'msg'=>'发布失败'));
		}
	}

	public function showPageAction(){
		$user = $_SESSION['user'];
		//获取id
		$id = $_GET['id'] + 0;
		//模型
		$respondModel = new RespondModel('respond_arti');
		$articleModel = new ArticleModel('article');
		$userInfoModel = new UserInfoModel('userinfo');
		$preModel = new PreModel('pre_resp');
		$imagesModel = new ImagesModel('images');
		//where条件
		$where = "arti_id = {$id}";
		//分页获取数据
		$pagesize = 10; //页面大小
		$current = isset($_GET['page']) ? $_GET['page'] : 1; //当前页
		$pagesizes = $current == 1 ? $pagesize - 1 : $pagesize; //本页获取数据数
		$offset = ($current - 1) * $pagesize;
		$responds = $respondModel -> pageRows($offset,$pagesizes,$where);
		$total = $respondModel -> total($where) + 1; //总共的数据数
		//加载页面类
		$this->library('Page');
		$page = new Page($total,$pagesize,$current,"index.php",array('p'=>'home','c'=>'article','act'=>'showPage'));
		$pageinfo = $page -> showPage();

		//数据统计
		$article = $articleModel -> selectByPk($id);
		$images = $imagesModel->getImagesByArtiId($id);
		if ($article == null){
			$this->jump('index.php?c=index','页面访问错误！');
		}
		if ($article['power'] != 1 && $user['identity'] == 1){
			$this->jump('index.php?c=index','权限不足，注册为会员后再观看！',3);
		}
		$article['article'] = explode(PHP_EOL, $article['article']);
		$autor = $userInfoModel -> selectByPk($article['username']);
		$ans = array();
		foreach ($responds as $key => $value) {
			# code...
			$ans[$key]['respond'] = $value;
			$ans[$key]['respond']['content'] = explode(PHP_EOL, $value['content']);
			$ans[$key]['respond']['pre_number'] = $preModel -> total("resp_id = " . $value['resp_id']);
			$ans[$key]['user'] = $userInfoModel -> selectByPk($value['username']);
		}

		include CUR_VIEW_PATH . "articlepage.html";
	}

	public function showMyPageAction(){
		$user = $_SESSION['user'];
		//模型
		$respondModel = new RespondModel('respond_arti');
		$articleModel = new ArticleModel('article');
		$userInfoModel = new UserInfoModel('userinfo');
		$attentionModel = new AttentionModel('attention');
		$where = "username = {$user['username']}";
		//分页获取数据
		$pagesize = 10; //页面大小
		$current = isset($_GET['page']) ? $_GET['page'] : 1; //当前页
		$offset = ($current - 1) * $pagesize;
		$articles = $articleModel -> pageRows($offset,$pagesize,$where);
		$total = $articleModel -> total($where); //总共的数据数

		$this->library('Page');
		$page = new Page($total,$pagesize,$current,"index.php",array('p'=>'home','c'=>'article','act'=>'showMyPage'));
		$pageinfo = $page -> showPage();
		
		//读取数据
		$att_num = $attentionModel->total("username_att = '".$user['username']."'");
		$fens_num = $attentionModel->total("username_ed = '".$user['username']."'");
		$guanf = $articleModel -> getAll("classname = '官方公告'");
		$ans = array();

		foreach ($articles as $key => $value) {
			$ans[$key]['article'] = $value;
			$ans[$key]['userinfo'] = $userInfoModel -> selectByPk($value['username']);
			$whereone = "arti_id = {$value['arti_id']}";
			$whereTow = "arti_id = {$value['arti_id']} and starttime = '{$value['endTime']}'";
			$ans[$key]['respondnum'] = $respondModel -> total($whereone);
			$username = ($respondModel -> getUsernameByWhere($whereTow));
			$ans[$key]['user_respond'] = $ans[$key]['respondnum'] == 0 ? $user : $userInfoModel -> selectByPk($username[0]['username']);
		}
		include CUR_VIEW_PATH . 'articlelist.html';
	}

	public function deleteArticleAction(){
		$user = $_SESSION['user'];
		//获取数据
		$id = $_GET['id'] + 0;
		//调用模型
		$respondModel = new RespondModel('respond_arti');
		$articleModel = new ArticleModel('article');
		$preModel = new PreModel('pre_resp');
		$responds = $respondModel -> getColsByWhere("resp_id","arti_id = {$id}");

		if($articleModel -> delete($id)){
			foreach ($responds as $value) {
				# code...
				$respondModel -> delete($value);
				$pres = $preModel -> getColsByWhere("pre_id","resp_id = {$value['resp_id']}");
				foreach ($pres as $pre) {
					# code...
					$preModel -> delete($pre);
				}
			}
			$this->jump('index.php?c=article&act=showMyPage','删除成功！',2);
		}else{
			$this->jump('index.php?c=article&act=showMyPage','删除失败！');

		}
	}

	public function addImageAction(){
		/*$json = $_POST['data'];
		$res = json_decode($json);
		var_dump($res);*/
		echo "string";
		return "收到消息";
	}
}