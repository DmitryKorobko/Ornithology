<?php

class Storydisplay extends ConvertInput{

	public function __construct(){

	}

	public function getPageStart(){
		return '<!DOCTYPE html>
<html>';
	}

	public function getPageHead($title){
		$code = '
	<head>
		<title>' . $title . '</title>
	</head>';
		return $code;
	}

	public function getPageBodyStart($title){
		$code = '<body>
		<h1>' . $title . '</h1>';
		return $code;
	}

	public function getChooseForm(){
		$code = $this->createChooseForm();
		return $code;
	}

	private function createChooseForm(){
		$action = array("add", "update", "delete");
		$news = new News();
		$titlelist .= $news->getTitleSelect();
		foreach($action as $mode){
			$code .= '<form name="' . $mode . 'select" action="" method="POST">
			<input type= "hidden" name="mode" value="' . $mode . '" />';
			if($mode != "add"){
				$code .= $titlelist;
			}
			$code .= '<input type="submit" value = "' . $mode . ' Story" />
			</form>';
		}
		return $code;
	}

	public function getMainForm($mode){
		if($mode == "add" || $mode == "update" || $mode == "delete"){
			$code = $this->createMainForm($mode);
			return $code;
		}else{
			if($mode == "doadd"){
				$this->processForm($mode);
			}else{
				if($mode == "doupdate" || $mode =="dodelete"){
					if($_POST['id'] != ""){
						$this->processForm($mode, $_POST['id']);
					}
				}
			}
		}
	}

	private function createMainForm($mode){
		if($mode == "add"){
			$code = '<form name="do' . $mode . '" action="#" method="POST">';
			$code .= '<input type = hidden name="mode" value="doadd" />';
			$code .= '<label>Title <input type = "text" class="newstitle" name="title" /></label>';//title
			$code .= '<label>Status <select name = "status"><option value="archived">Archived</option><option value="hidden">Hidden</option><option value="visible">Visible</option></select></label>';//status hidden / archived / visible
			$code .= '<label>Summary <textarea name="summary" class="newssummary"></textarea></label>';//summary
			$code .= '<label>Sub-heading 1 <input type="text" class="newssubhead" name="desc_1" /></label>';//sub-heading 1
			$code .= '<label>Sub-heading 2 <input type="text" class="newssubhead" name="desc_2" /></label>';//sub-heading 2
			$code .= '<label>Sub-heading 3 <input type="text" class="newssubhead" name="desc_3" /></label>';//sub-heading 3
			$code .= '<label>Created <input type="date" name="created" /></label>';//created
			$code .= '';//image
			$code .= '<label>Story <textarea class="newsstory" name="text"></textarea>';//story
			$code .= '</form>';
		}
		if($mode == "update" || $mode == "delete"){
			$newsobj = new News();
			$data = $newsobj->getDataById($id);
			$code = '<form name="do' . $mode . '" action="#" method="POST">';
			$code .= '<input type = hidden name="mode" value="do' . $mode . '" />';
			$code .= '<label>Title <input type = "text" class="newstitle" name="title" value="' . $this->decode($data['title']) . '" /></label>';//title
			$code .= '<label>Status <select name = "status"><option value="archived">Archived</option><option value="hidden">Hidden</option><option value="visible">Visible</option></select></label>';//status hidden / archived / visible
			$code .= '<label>Summary <textarea name="summary" class="newssummary" value = "' . $this->decode($data['summary']) . '"></textarea></label>';//summary
			$code .= '<label>Sub-heading 1 <input type="text" class="newssubhead" name="desc_1" value="' . $this->decode($data['desc_1']) . '" /></label>';//sub-heading 1
			$code .= '<label>Sub-heading 2 <input type="text" class="newssubhead" name="desc_2"' . $this->decode($data['desc_2']) . ' /></label>';//sub-heading 2
			$code .= '<label>Sub-heading 3 <input type="text" class="newssubhead" name="desc_3"' . $this->decode($data['desc_3']) . ' /></label>';//sub-heading 3
			$code .= '<label>Created <input type="date" name="created" value="' . $this->decode($data['created']) . '" /></label>';//created
			$code .= '';//image
			$code .= '<label>Story <textarea class="newsstory" name="text" value="' . $this->decode($data['text']) . '"></textarea>';//story
			$code .= '</form>';
		}
		return $code;
	}

	private function processForm($mode, $id=""){
		switch($mode){
			case 'doadd':
				$news = new News();
				$result = $news->addStory();
				if($result){
					//empty post
				}else{
					//handle error
				}
			break;
			case 'doupdate':
				$news = new News();
				$result = $news->updateStory($id);
				if($result){
					//empty post
				}else{
					//handle error
				}
			break;
			case 'dodelete':
				$news = new News();
				$result = $news->deleteStory($id);
				if($result){
					//empty post
				}else{
					//handle error
				}
			break;
		}

	}

	public function getPageBodyEnd(){
		return '</body></html>';
	}


}

?>