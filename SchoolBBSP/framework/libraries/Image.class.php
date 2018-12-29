<?php
class Image{
	private $thumbPrefix = 'thumb_'; //缩略图前缀
	private $waterPrefix = 'water_'; //水印图片前缀

	//图片类型和对应创建画布资源的函数名
	private $from = array(
		'image/gif'  => 'imagecreatefromgif',
		'image/png'  => 'imagecreatefrompng',
		'image/jpeg' => 'imagecreatefromjpeg'
	);

	//图片类型和对应生成图片的函数名
	private $to = array(
		'image/gif'  => 'imagegif',
		'image/png'  => 'imagepng',
		'image/jpeg' => 'imagejpeg'
	);


	//构造函数
	public function __construct(){

	}
	/**
	 * 添加水印功能
	 * @access public
	 * @param $image string 目标图片
	 * @param $water string 水印图片
	 * @param $postion number 添加水印位置，默认9，右下角
	 * @param $path string 水印图片存放路径,默认为空，表示在当前目录
	 * @return 
	 */
	public function watermark($image,$water,$postion=9,$path=''){
		//获取源图和水印图片信息
		$dst_info = getimagesize($image);
		$water_info = getimagesize($water);
		$dst_w = $dst_info[0];
		$dst_h = $dst_info[1];
		$src_w = $water_info[0];
		$src_h = $water_info[1];

		//获取各图片对应的创建函数名
		$dst_create_fname = $this->from[$dst_info['mime']];
		$src_create_fname = $this->from[$water_info['mime']];

		//使用可变函数来创建画布资源
		$dst_img = $dst_create_fname($image); 
		$src_img = $src_create_fname($water);

		//水印位置
		switch ($postion) {
			//左上
			case 1:
				$dst_x = 0;
				$dst_y = 0;
				break;
			//中上
			case 2:
				$dst_x = ($dst_w - $src_w)/2;
				$dst_y = 0;
				break;
			//右上
			case 3:
				$dst_x = $dst_w - $src_w;
				$dst_y = 0;
				break;
			//中左
			case 4:
				$dst_x = 0;
				$dst_y = ($dst_h - $src_h)/2;
				break;
			//中中
			case 5:
				$dst_x = ($dst_w - $src_w)/2;
				$dst_y = ($dst_h - $src_h)/2;
				break;
			//中右
			case 6:
				$dst_x = $dst_w - $src_w;
				$dst_y = ($dst_h - $src_h)/2;
				break;
			//下左
			case 7:
				$dst_x = 0;
				$dst_y = $dst_h - $src_h;
				break;
			//下中
			case 8:
				$dst_x = ($dst_w - $src_w)/2;
				$dst_y = $dst_h - $src_h;
				break;
			//下右
			case 9:
				$dst_x = $dst_w - $src_w;
				$dst_y = $dst_h - $src_h;
				break;
			//随机
			case 0:
				$dst_x = rand(0,$dst_w - $src_w);
				$dst_y = rand(0,$dst_h - $src_h);
				break;
			default:
				# code...
				break;
		}
		
		//将水印图片添加到目标图标上
		imagecopy($dst_img, $src_img, $dst_x, $dst_y, 0, 0, $src_w, $src_h);

		//生成带水印的图片
		$waterfile = $path.$this->waterPrefix.basename($image);
		$generate_fname = $this->to[$dst_info['mime']];
		
		if ($generate_fname($dst_img,$waterfile)){
			return $waterfile;
		} else {
			return false;
		}


	}

	/**
	 * 生成缩略图,等比例缩放,有补白效果
	 * @access public
	 * @param $image string 目标图片,
	 * @param $max_width number 缩略图最大宽度
	 * @param $max_height number  缩略图最大高度
	 * @return 成功返回缩略图名称，失败返回false
	 */
	public function thumbnail($image,$max_width,$max_height,$path=''){
		//获取图片信息
		$info = getimagesize($image);
		$src_width = $info[0];
		$src_height = $info[1];
		//echo $src_width,$src_height;
		//通过计算比例，得到缩略图的大小
		if ($src_width / $max_width > $src_height / $max_height) {
			# 此时应该以宽为准
			$dst_width = $max_width;
			$dst_height = ($max_width / $src_width) * $src_height;
		} else {
			# 此时应该以高为准
			$dst_height = $max_height;
			$dst_width = ($max_height / $src_height) * $src_width;
		}
		//使用可变函数创建源图资源
		$src_create_fname = $this->from[$info['mime']];
		$src_img = $src_create_fname($image);
		//创建缩略图资源，大小为$max_width x $max_height;
		$dst_img = imagecreatetruecolor($max_width, $max_height);
		//填充白色背景
		imagefill($dst_img, 0, 0, imagecolorallocate($dst_img, 255, 255, 255));
		//计算缩略图在画布上的位置,保证比例不等时图片能居中
		$dst_x = ($max_width - $dst_width)/2;
		$dst_y = ($max_height - $dst_height)/2;
		//将按比例将缩略图重新采样，调整其位置
		imagecopyresampled($dst_img, $src_img, $dst_x, $dst_y, 0, 0, $dst_width, $dst_height, $src_width, $src_height);
		$thumbfile = $this->thumbPrefix . pathinfo($image,PATHINFO_BASENAME);

		$generate_fname = $this->to[$info['mime']];
		if ($generate_fname($dst_img,$path . $thumbfile)) {
			# 成功返回缩略图名称,注意返回的名称,不同地方上传方案会有不同的路径
			return date('Ymd') . '/' . $thumbfile;
		} else {
			# 失败返回false
			return false;
		}
	}
}

//调用实例
//$img = new image;
//$img->thumbnail('a.jpg',200,200,'D:/amp/www/shopcz/');
//$img->watermark('a.jpg','sina.png',9,'D:/amp/www/shopcz/');