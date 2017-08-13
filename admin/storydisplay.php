<?php

include("story.php");

abstract class Storydisplay extends PrepareInput
{

    private $params;
    private $iconlist;
    private $imageWidth = 90;
    private $imageHeight = 90;

    public function __construct()
    {
    }

    public function getPageStart()
    {
        return '<!DOCTYPE html>
<html>';
    }

    public function getPageHead()
    {
        $code = '
	<head>
		<title>' . ucwords($this->type) . '</title>
		<link rel="stylesheet" href="../css/normalize.css">
		<link rel="stylesheet" href="css/main.css">
	</head>';
        return $code;
    }

    public function getPageBodyStart()
    {
        $code = '<body>
		<h1>' . ucwords($this->type) . '</h1>';
        return $code;
    }

    public function getDisplaySite()
    {
        return $this->createDisplaySite();
    }

    public function getArticle($id)
    {
        return $this->createArticle($id);
    }

    public function getArchive()
    {
        return $this->createArchive();
    }

    public function getChooseForm()
    {
        $code = $this->createChooseForm();
        return $code;
    }

    private function createChooseForm()
    {
        $action = array("add", "update", "delete");
        $news = new Story($this->type);
        $titlelist = $news->getTitleSelect();
        $titlelist = $this->wrapInSelect($titlelist);
        $code = "";
        foreach ($action as $mode) {
            $code .= '<form name="' . $mode . 'select" action="" method="POST">
			<input type= "hidden" name="mode" value="' . $mode . '" />';
            if ($mode != "add") {
                $code .= $titlelist;
            }
            $code .= '<input type="submit" value = "' . $mode . ' Story" />
			</form>';
        }
        return $code;
    }

    public function getMainForm($mode)
    {
        if ($mode == "add" || $mode == "update" || $mode == "delete") {
            $code = $this->createMainForm($mode);
            return $code;
        }
    }

    public function getProcessResults($mode)
    {
        $result = $this->processForm($mode);
        return $result;
    }

    private function getFilesList()
    {
        $dir = '../' . $this->imgdir;
        $filelist = scandir($dir);
        $code = '<div class="imagelist">';
        foreach ($filelist as $flist) {
            if (!is_dir($flist)) {
                $code .= '<img class="iconlist" data-filename="' . $flist . '" src="' . '../' . $this->imgdir . $flist . '" height = "90" width="90" />';
            }
        }
        $code .= '</div>';
        $this->iconlist = $code;
    }

    private function createMainForm($mode)
    {
        if (isset($_POST['id'])) {
            $id = $_POST['id'];
        }
        if ($mode == "add") {
            $this->getFilesList();
            $code = '<form name="do' . $mode . '" action="#" method="POST" enctype="multipart/form-data">';
            $code .= '<input type = hidden name="mode" value="doadd" />';
            $code .= $this->linkExample();
            $code .= '<label>Title <input type = "text" class="newstitle" name="title" required="required" /></label>'; //title
            $code .= '<label>Status. Use this to archive articles <select name = "status"><option value="visible">Visible</option><option value="archived">Archived</option><option value="hidden">Hidden</option></select></label>'; //status hidden / visible / archived
            $code .= '<label>Summary <textarea name="summary" class="newssummary"></textarea></label>'; //summary
            $code .= "<label>Created. This is the date displayed at the bottom of the article for it's creation. Format dd/mm/yy. " . '<input type="text" name="created" /></label>'; //created
            $code .= '<label>Published. This is the date the article will first appear on the site. A date in the future will prevent publication until then. Format dd/mm/yy. <input type="text" name="published" required="required" placeholder="dd/mm/yy" /></label>'; //published
            if ($this->type == "news") {
                $code .= '<label>Writing credit <input type="text" class="articlecredit" name="credit" /></label>'; //credit
            }
            $code .= $this->iconlist;
            $code .= '<label> Image from above <input type="text" name="image" id="image" /></label>';
            $code .= '<p class="bold">OR</p>';
            $code .= '<label>New image <input type="file" name="newimage" accept="image/*" /></label>'; //image
            $code .= $this->imageInsertionInstruction();
            $code .= '<label>Main story <textarea class="newsstory" name="story" required="required"></textarea></label>'; //story
            if ($this->type == "reviews") {
                //$code .= '<label>What we say <textarea class="newsstory" name="story2" required="required"></textarea>';//story
                $code .= '<label>Rating <input type="text" name="rating" /></label>'; //rating
                $code .= '<label>Section <input type="text" name="section" /></label>'; //section
            }
            $code .= '<input type="submit" value="Add new story" />';
            $code .= '</form>';
        }
        if ($mode == "update") {
            $this->getFilesList();
            $story = new Story($this->type);
            $fullData = $story->getFullData($id);
            foreach ($fullData as $data) {
                $code = '<form name="do' . $mode . '" action="' . htmlentities(
                        $_SERVER['PHP_SELF']
                    ) . '" method="POST" enctype="multipart/form-data">';
                $code .= '<input type = hidden name="mode" value="do' . $mode . '" />';
                $code .= '<input type="hidden" name="id" value="' . $id . '" />';
                $code .= '<input type="hidden" name="currentimage" value="' . $data['image'] . '" />';
                $code .= $this->linkExample();
                $code .= '<label>Title <input type = "text" class="newstitle" name="title" value="' . $this->decode(
                        $data['title']
                    ) . '" required="required" /></label>'; //title
                $code .= '<label>Status. Use this to archive articles. <select name = "status"><option value="visible">Visible</option><option value="archived">Archived</option><option value="hidden">Hidden</option></select></label>'; //status hidden / visible
                $code .= '<label>Summary <textarea name="summary" class="newssummary">' . $this->adminDecodeParagraph(
                        $data['summary']
                    ) . '</textarea></label>'; //summary
                $code .= "<label>Created. This is the date displayed at the bottom of the article for it's creation. Format dd/mm/yy." . '<input type="text" name="created" value="' . $this->decode(
                        $data['created']
                    ) . '" /></label>'; //created
                $code .= '<label>Published. This is the date the article will first appear on the site. A date in the future will prevent publication until then. Format dd/mm/yy. <input type="text" name="published" value="' . $this->convertTimestampToDate(
                        "short",
                        $data['published']
                    ) . '" required="required" /></label>'; //published
                if ($this->type == "news" or $this->type == "offers") {
                    $code .= '<label>Writing credit <input type="text" class="articlecredit" name="credit" value="' . $this->decode(
                            $data['credit']
                        ) . '" /></label>'; //credit
                }
                if (is_null($data['image'])) {
                    $dataimage = $this->defaulticon;
                } else {
                    $dataimage = $data['image'];
                }
                $code .= '<label>Selected image<img src="' . SITE_ROOT . $this->imgdir . $dataimage . '" height="90" width = "90" /></label>'; //icon
                $code .= $this->iconlist;
                $code .= '<label> Image from above <input type="text" name="image" id="image" value="' . $dataimage . '" /></label>';
                $code .= '<p class="bold">OR</p>';
                $code .= '<label>New image <input type="file" name="newimage" accept="image/*" /></label>'; //image
                $code .= $this->imageInsertionInstruction();
                $code .= '<label>Story <textarea class="newsstory" name="story" required="required">' . $this->adminDecodeParagraph(
                        $data['story']
                    ) . '</textarea></label>'; //story
                if ($this->type == "reviews") {
                    //$code .= '<label>What we say <textarea class="newsstory" name="story2" required="required">' . $this->decode($data['story2']) . '</textarea>';//story
                    $code .= '<label>Rating <input type="text" name="rating" value="' . $this->decode(
                            $data['rating']
                        ) . '" /></label>'; //rating
                    $code .= '<label>Section <input type="text" name="section" value="' . $this->decode(
                            $data['section']
                        ) . '" /></label>'; //section
                }
                $code .= '<input type="submit" value="' . $mode . ' story" />';
                $code .= '</form>';
            }
        }
        if ($mode == "delete") {
            $code = $this->createArticle($id);
            $code .= '<form name="dodelete" action="' . htmlentities($_SERVER['PHP_SELF']) . '" method="POST">';
            $code .= '<input type = hidden name="mode" value="dodelete" />';
            $code .= '<input type="hidden" name="id" value="' . $id . '" />';
            $code .= '<input type="submit" value="Delete story" />';
            $code .= '</form>';
        }
        return $code;
    }

    private function createParams($mode, $id = "")
    {
        if ($mode == "dodelete") {
            $this->params = array($id);
            return;
        }
        /************* Common to all ******************/
        $title = $this->encode($this->convertSentence($_POST['title']));
        $status = $this->encode($this->convertSentence($_POST['status']));
        $summary = $this->encode($this->convertParagraph($_POST['summary']));
        $created = $this->encode($this->convertSentence($_POST['created']));
        $published = $this->convertDateToTimestamp($_POST['published']);
        $story = $_POST['story'];
        if (strpos($story, "<p>") !== 0) {
            $story = '<p>' . $story . "</p>";
        }
        $story = $this->encode(autolink($this->convertImageParagraph($story)));
        //$image
        $handle = new upload($_FILES['newimage']);
        if ($handle->uploaded) {
            $handle->image_resize = true;
            $handle->image_x = $this->imageWidth;
            $handle->image_y = $this->imageHeight;
            $handle->image_ratio = true;
            $handle->file_name_body_add = time();
            $handle->process(ROOT_DIR . $this->imgdir);
            if ($handle->processed) {
                $image = $handle->file_dst_name;
                $handle->clean();
            } else {
                echo 'error : ' . $handle->error;
            }
        } else {
            if ($_POST['image'] != "") {
                $image = $_POST['image'];
            } else {
                $image = $this->defaulticon;
            }
        }
        /************** Only for News and Offers *******************/
        if ($this->type == "news" || $this->type == "offers") {
            $credit = $this->encode($this->convertSentence($_POST['credit']));
        }
        /************** Only for Reviews *****************/
        if ($this->type == "reviews") {
            /*$story2 = $_POST['story2'];
            if(strpos($story2, "<p>") !== 0){
                $story2 = '<p>' . $story2 . "</p>";
            }
            $story2 = $this->encode($this->convertForInput($story2));*/
            $section = $_POST['section'];
            $rating = $_POST['rating'];
        }

        if ($this->type == "news" or $this->type == "offers") {
            $this->params = array($title, $status, $summary, $created, $published, $image, $story, $credit);
        } else {
            if ($this->type == "reviews") {
                $this->params = array(
                    $title,
                    $status,
                    $summary,
                    $created,
                    $published,
                    $image,
                    $story,
                    $section,
                    $rating
                );
            } else {
                if ($this->type == "announcements") {
                    $this->params = array($title, $status, $summary, $created, $published, $image, $story, $credit);
                }
            }
        }
        if ($mode == "doupdate") {
            array_push($this->params, $id);
        }
    }

    private function processForm($mode)
    {
        if (isset($_POST['id'])) {
            $id = $_POST['id'];
        }
        switch ($mode) {
            case 'doadd':
                $news = new Story($this->type);
                $this->createParams($mode);
                $result = $news->addStory($this->params);
                if ($result) {
                    unset($_POST['mode']);

                }
                break;
            case 'doupdate':
                $news = new Story($this->type);
                $this->createParams($mode, $id);
                $result = $news->updateStory($this->params);
                if ($result === true) {
                    unset($_POST['mode']);
                }
                break;
            case 'dodelete':
                $news = new Story($this->type);
                $this->createParams($mode, $id);
                $result = $news->deleteStory($this->params);
                if ($result) {
                    unset($_POST['mode']);
                }
                break;
        }
        return $result;
    }

    private function wrapInSelect($titles)
    {
        $code = '<select name="id">';
        $code .= '<option value = "0">Please choose a story</option>';
        foreach ($titles as $titlerow) {
            $code .= '<option value="' . $this->decode($titlerow['id']) . '">' . $this->decode(
                    $titlerow['title']
                ) . '</option>';
        }
        $code .= '</select>';
        return $code;
    }

    public function getPageBodyEnd()
    {
        return '
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
		<script src="../js/admin.js"></script>
		</body></html>';
    }

    private function createDisplaySite()
    {
        $story = new Story($this->type);
        $result = $story->getFullData("", "visible", "published", "DESC", "0,4", true);
        $count = count($result);
        $content = "";
        if ($count > 0) {
            $resultsection = new SectionContent();
            $content .= $resultsection->sectionStart($this->type);
            foreach ($result as $row) {
                $content .= $resultsection->newsitemStart();
                if ($row["image"]) {
                    $content .= $resultsection->newsIcon($row["image"], $this->type);
                } else {
                    $content .= $resultsection->newsIcon($this->defaulticon, $this->type);
                }
                if ($this->type == "reviews") {
                    $content .= $resultsection->newsSection($row['section']);
                }
                $content .= $resultsection->newsItemTitle($this->type, $row["id"], $row["title"]);
                $content .= $resultsection->newsItemSummary($row["summary"]);
                $content .= $resultsection->newsItemDate($row["published"]);
                $content .= $resultsection->newsitemEnd();
            }
            if ($this->type <> "announcements") {
                $content .= $resultsection->newsArchiveLink($this->type);
            }
            $content .= $resultsection->sectionend();
        }
        return $content;
    }

    private function createArticle($id)
    {
        $content = "";
        $story = new Story($this->type);
        $result = $story->getFullData($id);
        $resultsection = new SectionContent();
        foreach ($result as $row) {
            $content .= $resultsection->articleHead($row['title'], $row['summary']);
            $content .= $resultsection->articleBody($row['story']);
            if ($this->type <> "reviews" && $row['credit']) {
                $content .= $resultsection->articleCredit($row['credit']);
            }
            $content .= $resultsection->articleEnd($row['published']);
        }
        return $content;
    }

    private function createArchive()
    {
        $story = new Story($this->type);
        $result = $story->getFullData("", "archived", "published", "DESC");
        $resultsection = new SectionContent();
        $content = $resultsection->sectionArchiveStart();
        $count = count($result);
        if ($count > 0) {
            foreach ($result as $row) {
                $content .= $resultsection->newsitemStart();
                if ($this->type == "reviews") {
                    $content .= $resultsection->newsSection($row['section']);
                }
                $content .= $resultsection->newsItemTitle($this->type, $row["id"], $row["title"]);
                $content .= $resultsection->newsItemSummary($row["summary"]);
                $content .= $resultsection->newsItemDate($row["published"]);
                $content .= $resultsection->newsitemEnd();
            }
        }
        $content .= $resultsection->sectionend();
        return $content;
    }

    /**
     * @return string
     */
    private function imageInsertionInstruction()
    {
        $code = "";
        $code .= '<p>To add an image to the text below:<br />
 Upload the image to the <em>' . $this->type . 'images</em> folder inside the <em>images</em> folder then copy and paste this code below into the story:<br />';
        $tempCode = '<figure class="photo">
<img alt="description of the image" src="/images/' . $this->type . 'images/myphoto.jpg" height="" width="" />
<figcaption style="width:px;">
<span class="bold">caption</span>
©credit
<a href="http://url.co.uk">Website</a>
</figcaption>
</figure>';
        $code .= '<code>' . $this->encode($tempCode) . '</code>';
        $code .= '<br />
Then replace myphoto.jpg with the actual filename and add the width and height in pixels to the relevant attributes just after the name<br />
If there is a caption, add the same image width to this line ';
        $code .= '<code>' . $this->encode('<figcaption style="width:px;">') . '</code>';
        $code .= ' between the : and the px<br />';
        $code .= 'Then change the caption and link as necessary<br />';
        $code .= 'If there is no caption, remove the entire section from ';
        $code .= '<code>' . $this->encode("<figcaption>") . '</code> to <code> ' . $this->encode("</figcaption>") . '</code> inclusive<br />';
        $code .= 'To place the image on the right of the page, replace "photo" with "photoright" in this line';
        $code .= '<code>' . $this->encode('<figure class="photo">') . '</code>';
        $code .= '</p>';
        return $code;
    }

    private function linkExample(){
        $code = '<p>Link example<br />';
        $tempCode = '<a href="http://" target="_blank"></a>';
        $code .= '<code>' . $this->encode($tempCode) . '</code></p>';
        return $code;
    }

}

class News extends Storydisplay
{

    protected $type = "news";
    protected $imgdir = NEWS_ICON_DIR;
    protected $defaulticon = "aaNews.jpg";
}

class Review extends Storydisplay
{

    protected $type = "reviews";
    protected $imgdir = REVIEWS_ICON_DIR;
    protected $defaulticon = "";
}

class Announcement extends Storydisplay
{

    protected $type = "announcements";
    protected $imgdir = ANNOUNCEMENTS_ICON_DIR;
    protected $defaulticon = "announcements.jpg";
}

class Offers extends Storydisplay
{

    protected $type = "offers";
    protected $imgdir = OFFERS_ICON_DIR;
    protected $defaulticon = "offers.jpg";
}

?>