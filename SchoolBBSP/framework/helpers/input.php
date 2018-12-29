<?php
//批量实体转义
function deepSpecialChars($data){
	if (empty($data)){
		return $data;
	}
	if (is_array($data)){
		foreach ($data as $k => $v) {
			# code...
			$data[$k] = deepSpecialChars($v);
		}
		return $data;
	}else{
		return htmlspecialchars($data);
	}
}