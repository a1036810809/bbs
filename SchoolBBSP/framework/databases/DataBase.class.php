<?php
interface DataBase{
	public function getAffectedRows();
	public function query($sql);
	public function getOne($sql);
	public function getRow($sql);
	public function getAll($sql);
	public function getCol($sql);
	public function getInsertId();
	public function errno();
	public function error();
}