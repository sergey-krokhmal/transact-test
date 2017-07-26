<?php
namespace Application\Core;

class BaseController
{
	public function index()
	{
	}
	
	public function loadTemplate(string $template_name = 'index', $data = array())
	{
		$reflection = new \ReflectionClass($this);
		$dir_name = $reflection->getShortName();
		$doc_root = $_SERVER['DOCUMENT_ROOT'];
		$template_path_name = "$doc_root/Application/Templates/$dir_name/$template_name.php";
		
		foreach ($data as $key => $val) {
			$$key = $val;
		}
		
		if (file_exists($template_path_name)) {
			require_once($template_path_name);
		} else {
			echo "Шаблон '/Application/Templates/$dir_name/$template_name.php' не найден";
		}
	}
}