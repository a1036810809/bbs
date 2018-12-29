<?php
class NotFoundController extends Controller
{
	
	function __construct($msg = "404 Not Found")
	{
		include CUR_VIEW_PATH . "404.html";
	}
}