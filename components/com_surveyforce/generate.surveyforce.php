<?php 
/**
* Survey Force component for Joomla
* @version $Id: generate.surveyforce.php 2009-11-16 17:30:15
* @package Survey Force
* @subpackage generate.surveyforce.php
* @author JoomPlace Team
* @Copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

// no direct access
defined( '_VALID_MOS' ) or die( 'Restricted access' );

require ( $mosConfig_absolute_path . '/components/com_surveyforce/includes/libchart/libchart.php' );	

class sf_ImageGenerator extends SF_Object { 
	
	var $options = array('Bar');

	var $height = 200; 
	var $width = 600;
	var $image_path = '';
	
	function getBarChartImage($options_v, $options, $filename) {		
		// Standard inclusions   
		include_once(dirname(__FILE__)."/includes/pchart/pChart/pData.class.php");
		include_once(dirname(__FILE__)."/includes/pchart/pChart/pChart.class.php");

		$total = 0;
		$xlabels = array();
		foreach($options_v as $i=>$option_v) {
			$total += $option_v;
			$xlabels[] = $i+1;
		}
		if ($total)
		foreach($options_v as $f=>$option_v) {
			$options_v[$f] = ceil($option_v/$total*100);
		}
		
		$rows = count($options);
		foreach($options as $option) 
			$rows += substr_count($option, "\n");
		
		$rows += 1;
		
		// Dataset definition 
		$DataSet = new pData;
		$DataSet->Data = array();
		$DataSet->AddPoint($options_v,"Serie1");
		$DataSet->AddPoint($xlabels,"Serie2");
		
		$DataSet->AddSerie("Serie1"); 
		$DataSet->SetAbsciseLabelSerie("Serie2");

		$font_size = 8;
		
		$bar_height = $this->height;
		$width = $this->width;
		
		$height = $bar_height + 2.1*$rows*$font_size;
		
		$margin = 20;
		$padding1 = 7;
		$padding2 = 5;
		
		// Initialise the graph
		$barchart = new pChart($width, $height);
		$barchart->setFontProperties(dirname(__FILE__)."/includes/pchart/Fonts/tahoma.ttf", $font_size);
		$barchart->setGraphArea(intval($margin*2), intval($margin*1.5), $width - $margin, $bar_height - $margin);
		$barchart->drawFilledRoundedRectangle($padding1, $padding1, $width-$padding1, $height-$padding1, 5, 240, 240, 240);
		$barchart->drawRoundedRectangle(5, 5, $width-$padding2, $height-$padding2, 5, 230, 230, 230);
		$barchart->drawGraphArea(255, 255, 255, TRUE);
		$barchart->setFixedScale(0, 100);
		$barchart->drawScale($DataSet->GetData(), $DataSet->GetDataDescription(), SCALE_NORMAL, 100, 100, 100, TRUE, 0, 2, TRUE);
		$barchart->drawGrid(4, TRUE, 230, 230, 230, 50);
		
		// Draw the 0 line
		$barchart->setFontProperties(dirname(__FILE__)."/includes/pchart/Fonts/tahoma.ttf",6);
		$barchart->drawTreshold(0,143,55,72,TRUE,TRUE);
		
		// Draw the bar graph
		$barchart->drawStackedBarGraph($DataSet->GetData(),$DataSet->GetDataDescription(),100);
		
		// Finish the graph
		$barchart->setFontProperties(dirname(__FILE__)."/includes/pchart/Fonts/tahoma.ttf", $font_size);
	
		$legend = '';
		foreach($options as $k=>$option) 
			$legend .= ($k+1).' - '.str_replace("\n", "\n     ", $option)."\n";
		
		$barchart->drawTextBox($margin*2,$bar_height+$padding1,$width,$height,$legend,0,25,25,25,ALIGN_TOP_LEFT, false);
		
		$barchart->Render($filename);
		
		return $filename;
	}

	function getPieChartImage($numbers, $options, $filename) {
		// Standard inclusions   
		include_once(dirname(__FILE__)."/includes/pchart/pChart/pData.class.php");
		include_once(dirname(__FILE__)."/includes/pchart/pChart/pChart.class.php");
				
		$rows = count($options);
		foreach($options as $option) 
		$rows += substr_count($option, "\n");
		
		$rows += 1;
		// Dataset definition 
		$DataSet = new pData;
		$DataSet->Data = array();
		$DataSet->AddPoint($numbers,"Serie1");
		$DataSet->AddPoint($options ,"Serie2");
		$DataSet->AddAllSeries();
		$DataSet->SetAbsciseLabelSerie("Serie2");
		
		$font_size = 8;
		
		$pie_height = $this->height;
		$height = $pie_height + 2.1*$rows*$font_size;
		
		$width = $this->width;;
		
		$padding1 = 7;
		$padding2 = 5;
		
		// Initialise the graph
		$piechart = new pChart($width, $height);
		$piechart->drawFilledRoundedRectangle($padding1, $padding1, $width - $padding1, $height - $padding1,5,240,240,240);
		$piechart->drawRoundedRectangle($padding2, $padding2, $width - $padding2, $height - $padding2,5,230,230,230);
		
		
		// Draw the pie chart
		$piechart->setFontProperties(dirname(__FILE__)."/includes/pchart/Fonts/tahoma.ttf", $font_size);
		$piechart->AntialiasQuality = 0;
		$piechart->drawPieGraph($DataSet->GetData(),$DataSet->GetDataDescription(),intval($width/2), intval($pie_height/2.2), intval($pie_height/1.8), PIE_PERCENTAGE,FALSE, 50, 20, 5);
		
		$piechart->drawPieLegend(2*$padding1, $pie_height, $DataSet->GetData(),$DataSet->GetDataDescription(),250,250,250);
		
		$piechart->Render($filename);
		
		return $filename;
	}

	function sf_ImageGenerator($options = null, $path = null) {
		global $mosConfig_absolute_path;
		if (!empty($options)) {
			$this->options = $options;
		}
		
		if ($path == null)
			$this->image_path = $mosConfig_absolute_path."/images/surveyforce/gen_images/";
		else
			$this->image_path = $path;
	}


	function __construct($options = null, $path = null, $src = null) {
		global $mosConfig_absolute_path, $sf_lang;
		if (!empty($options)) {
			$this->options = $options;
		}
		
		if (!isset($sf_lang) || !count($sf_lang)) {
			include_once(dirname(__FILE__)."/language/default.php");
		}
		
		if ($path == null)
			$this->image_path = $mosConfig_absolute_path."/images/surveyforce/gen_images/";
		else
			$this->image_path = $path;
			
		if ($src == null)
			$this->image_src = JURI::root()."/images/surveyforce/gen_images/";
		else
			$this->image_src = $src;
	}

	//
	# Collect data -> call to createImage function -> return image filename
	//
	function getImage($survey_id = 0, $quest_id = 0, $start_id = 0) {
		global $my, $database, $mainframe, $mosConfig_absolute_path, $mosConfig_live_site, $sf_lang;		
		
		if ($quest_id && $survey_id && $start_id) {
			$query = "SELECT sf_qtype from #__survey_force_quests WHERE published = 1 AND id = '".$quest_id."'";
			$database->SetQuery( $query );
			$qtype = $database->LoadResult();
			
			$query	= "SELECT `id` FROM `#__survey_force_user_starts` WHERE `survey_id` = '{$survey_id}'";
			$database->SetQuery( $query );
			$start_ids = $database->LoadResultArray();
			switch ($qtype) {
				case '1':				
					
					$query = "SELECT id, ftext from #__survey_force_fields WHERE quest_id = $quest_id ORDER BY id";
					$database->SetQuery( $query );
					$questions = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
					
					$query = "SELECT id, stext from #__survey_force_scales WHERE quest_id = $quest_id ";
					$database->SetQuery( $query );
					$fields = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
					$tmp = new stdClass();
					$tmp->id = 0;
					$tmp->stext = $sf_lang['SURVEY_NO_ANSWER'];
					$fields[] = $tmp;
	
					$tmp = null;
					$sections = array();
					$titles = array();
					$usr_answers = array();
					$i = 0;
	
					$query = "SELECT start_id FROM `#__survey_force_user_answers` WHERE `quest_id` = $quest_id AND `start_id` IN ('".implode("','", $start_ids)."') GROUP BY start_id";
					$database->SetQuery( $query );
					$maxval = count($database->LoadResultArray());
					
					foreach($questions as $question) {
						$tmp = null;
						$rows = array();
						$i++;					
						
						$query = "SELECT ans_field from #__survey_force_user_answers WHERE survey_id = $survey_id AND `start_id` IN ('".implode("','", $start_ids)."') AND quest_id = $quest_id AND answer = ".$question->id;
						$database->SetQuery( $query );
						$ans_fields = $database->LoadResultArray();
						$j = count($ans_fields);
						for($ii = $j; $ii < $maxval; $ii++) {
							$ans_fields[] = 0;
						}
						
						$results = array_count_values($ans_fields);										
						foreach($fields as $field) {
							$tmp = new stdClass();
							$tmp->label = trim(strip_tags($field->stext));
							$tmp->percent = (isset($results[$field->id])? intval((100*$results[$field->id])/$maxval): 0);
							$tmp->number = (isset($results[$field->id])? intval($results[$field->id]): 0);
							$rows[] = $tmp;
						}					
						
						$sections[$i] = $rows;
						$titles[$i] = trim(strip_tags($question->ftext));						
						$query ="SELECT b.stext FROM #__survey_force_user_answers AS a, #__survey_force_scales AS b WHERE a.start_id = $start_id AND a.survey_id = $survey_id AND a.quest_id = $quest_id AND a.answer = {$question->id} AND b.id = a.ans_field";
						$database->SetQuery( $query );
						$usr_answers[$i][] = trim(strip_tags($database->LoadResult()));		
					}
					
					$query = "SELECT sf_qtext FROM `#__survey_force_quests` WHERE published = 1 AND `id` = $quest_id ";
					$database->SetQuery( $query );
					$maintitle = trim($database->LoadResult());		
					return $this->createImage($sections, $titles, $usr_answers, $maintitle, $maxval, $i);
					break;
				case '2':
					$query = "SELECT start_id FROM `#__survey_force_user_answers` WHERE `quest_id` = $quest_id AND `start_id` IN ('".implode("','", $start_ids)."') GROUP BY start_id";
					$database->SetQuery( $query );
					$maxval = count($database->LoadResultArray());
					
					$query = "SELECT answer from #__survey_force_user_answers WHERE survey_id = $survey_id AND `start_id` IN ('".implode("','", $start_ids)."')  AND quest_id = $quest_id ";
					$database->SetQuery( $query );
					$answers =  $database->LoadResultArray();
	
					$query = "SELECT id, ftext FROM #__survey_force_fields WHERE quest_id = $quest_id ORDER BY id";
					$database->SetQuery( $query );
					$fields = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
					$tmp = new stdClass();
					$tmp->id = 0;
					$tmp->ftext = $sf_lang['SURVEY_NO_ANSWER'];
					$fields[] = $tmp;
					
					$query = "SELECT sf_qtext from #__survey_force_quests WHERE published = 1 AND id = $quest_id ORDER BY id";
					$database->SetQuery( $query );
					$title =  $database->LoadResult();
					
					$results = array_count_values($answers);
					$rows = array();
					
					foreach($fields as $field) {
						$tmp = new stdClass();
						$tmp->label = trim(strip_tags($field->ftext));
						$tmp->percent = (isset($results[$field->id])? intval((100*$results[$field->id])/$maxval): 0);
						$tmp->number = (isset($results[$field->id])? intval($results[$field->id]): 0);
						$rows[] = $tmp;
					}
					$sections[1] = $rows;
					$titles[1] = '';
					
					$query ="SELECT b.ftext FROM #__survey_force_user_answers AS a, #__survey_force_fields AS b WHERE a.start_id = $start_id AND a.survey_id = $survey_id AND a.quest_id = $quest_id AND b.id = a.answer";
					$database->SetQuery( $query );
					$usr_answers[1][] = trim(strip_tags($database->LoadResult()));		
					
					$query = "SELECT sf_qtext FROM `#__survey_force_quests` WHERE published = 1 AND `id` = $quest_id ";
					$database->SetQuery( $query );
					$maintitle = trim($database->LoadResult());
					return $this->createImage($sections, $titles, $usr_answers, $maintitle, $maxval, 1);
					break;
					
				case '3':			
					$query = "SELECT start_id FROM `#__survey_force_user_answers` WHERE `quest_id` = $quest_id AND `start_id` IN ('".implode("','", $start_ids)."') GROUP BY start_id";
					$database->SetQuery( $query );
					$maxval = count($database->LoadResultArray());
					
					$query = "SELECT id, ftext from #__survey_force_fields WHERE quest_id = $quest_id ";
					$database->SetQuery( $query );
					$fields = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
					$tmp = new stdClass();
					$tmp->id = 0;
					$tmp->ftext = $sf_lang['SURVEY_NO_ANSWER'];
					$fields[] = $tmp;
					
					$query = "SELECT answer from #__survey_force_user_answers WHERE survey_id = $survey_id AND `start_id` IN ('".implode("','", $start_ids)."') AND quest_id = $quest_id ";
					$database->SetQuery( $query );
					$answers =  $database->LoadResultArray();
					
					$sections = array();
					$titles = array();
					$usr_answers = array();				
					$rows = array();
					
					$results = array_count_values($answers);										
					foreach($fields as $field) {
						$tmp = new stdClass();
						$tmp->label = trim(strip_tags($field->ftext));
						$tmp->percent = (isset($results[$field->id])? intval((100*$results[$field->id])/$maxval): 0);
						$tmp->number = (isset($results[$field->id])? intval($results[$field->id]): 0);
						$rows[] = $tmp;
					}				
						
					$sections[1] = $rows;
					$titles[1] = '';
					$query ="SELECT b.ftext FROM #__survey_force_user_answers AS a, #__survey_force_fields AS b WHERE a.start_id = $start_id AND a.survey_id = $survey_id AND a.quest_id = $quest_id AND b.id = a.answer";
					$database->SetQuery( $query );
					$usr_answers[1] = $database->LoadResultArray();	
	
					$query = "SELECT sf_qtext FROM `#__survey_force_quests` WHERE published = 1 AND `id` = $quest_id ";
					$database->SetQuery( $query );
					$maintitle = trim($database->LoadResult());
					return $this->createImage($sections, $titles, $usr_answers, $maintitle, $maxval, 1);
					break;
				case '5':				
				case '6':	
				case '9':
					$query = "SELECT id, ftext from #__survey_force_fields WHERE quest_id = $quest_id AND is_main = 1 ORDER BY id";
					$database->SetQuery( $query );
					$questions = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
					
					$query = "SELECT id, ftext AS stext from #__survey_force_fields WHERE quest_id = $quest_id AND is_main = 0 ORDER BY id";
					$database->SetQuery( $query );
					$fields = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
					$tmp = new stdClass();
					$tmp->id = 0;
					$tmp->stext = ($qtype !=9?$sf_lang['SURVEY_NO_ANSWER']:$sf_lang['SURVEY_NOT_RANKED']);
					$fields[] = $tmp;
	
					$tmp = null;
					$sections = array();
					$titles = array();
					$usr_answers = array();
					$i = 0;
	
					$query = "SELECT start_id FROM `#__survey_force_user_answers` WHERE `quest_id` = $quest_id AND `start_id` IN ('".implode("','", $start_ids)."') GROUP BY start_id";
					$database->SetQuery( $query );
					$maxval = count($database->LoadResultArray());
					
					foreach($questions as $question) {
						$tmp = null;
						$rows = array();
						$i++;					
						
						$query = "SELECT ans_field from #__survey_force_user_answers WHERE survey_id = $survey_id AND `start_id` IN ('".implode("','", $start_ids)."') AND  quest_id = $quest_id AND answer = ".$question->id;
						$database->SetQuery( $query );
						$ans_fields = $database->LoadResultArray();
						$j = count($ans_fields);
						for($ii = $j; $ii < $maxval; $ii++) {
							$ans_fields[] = 0;
						}
						
						$results = array_count_values($ans_fields);										
						foreach($fields as $field) {
							$tmp = new stdClass();
							$tmp->label = trim(strip_tags($field->stext));
							$tmp->percent = (isset($results[$field->id])? intval((100*$results[$field->id])/$maxval): 0);
							$tmp->number = (isset($results[$field->id])? intval($results[$field->id]): 0);
							$rows[] = $tmp;
						}					
						
						$sections[$i] = $rows;
						$titles[$i] = trim(strip_tags($question->ftext));
						
						$query ="SELECT b.ftext FROM #__survey_force_user_answers AS a, #__survey_force_fields AS b WHERE a.start_id = $start_id AND a.survey_id = $survey_id AND a.quest_id = $quest_id AND a.answer = {$question->id} AND b.id = a.ans_field";
						$database->SetQuery( $query );
						$usr_answers[$i][] = trim(strip_tags($database->LoadResult()));	
					}
					$query = "SELECT sf_qtext FROM `#__survey_force_quests` WHERE published = 1 AND `id` = $quest_id ";
					$database->SetQuery( $query );
					$maintitle = trim($database->LoadResult());
					return $this->createImage($sections, $titles, $usr_answers, $maintitle, $maxval, $i);
					break;
			}
		}
		
		return false;	
	}

	# sections - array of section (section = question), each section is array of rows, each row (row = answer) is object with properties:
	# label - answer text (or scale option)
	# percent - % value
	# number - absolute value
	# titles - array with titles of each section
	# answers - array with label of user answer of section(question)
	# maintitle - title for sections
	# max_value - absolute number of given answers from all users (percent = number/max_value *100%)
	function createImage(&$sectionsz, &$titlesz, &$answersz, $maintitle, $max_value){	
		$html = '<div class="sf_graphs_container" style="font-family: Tahoma;">';
		if (count($sectionsz) > 1) {
			$html .= '<div class="sf_graph_container">'.$maintitle.'<br/>';
			foreach($sectionsz as $i =>$section) {
				$html .= $titlesz[$i].'<br/>';	
				$options = array();
				$numbers = array();
				foreach($section as $item) {
					$options[] = sf_SafeSplit($item->label, intval(($this->width-50)/8));
					$numbers[] = $item->number;
				}
				$filename = (strlen(date('d')) < 2? '0'.date('d'): date('d')).'_'.md5(uniqid(mktime())).'.png';
				if (in_array('Bar',$this->options))
					$this->getBarChartImage($numbers, $options, $this->image_path.$filename);
				else
					$this->getPieChartImage($numbers, $options, $this->image_path.$filename);
					
				$html .= '<img src="'.$this->image_src.$filename.'" alt="" title="" /><br/>';
			}
			
			$html .= '</div>';
			
		}else {		
			$html .= '<div class="sf_graph_container">'.$maintitle.'<br/>';
			foreach($sectionsz as $section) {	
				$options = array();
				$numbers = array();
				foreach($section as $item) {
					$options[] = sf_SafeSplit($item->label, intval(($this->width-50)/8));
					$numbers[] = $item->number;
				}
				$filename = (strlen(date('d')) < 2? '0'.date('d'): date('d')).'_'.md5(uniqid(mktime())).'.png';
				if (in_array('Bar',$this->options))
					$this->getBarChartImage($numbers, $options, $this->image_path.$filename);
				else
					$this->getPieChartImage($numbers, $options, $this->image_path.$filename);
			}
			$html .= '<img src="'.$this->image_src.$filename.'" alt="" title="" /><br/>';
			$html .= '</div>';
		}
		$html .= '</div>';
		return $html;
	}

		
	function clearOldImages($day = null){
		if ($day == null)
			$day = (strlen(date('d')) < 2? '0'.date('d'): date('d'));
		elseif (strlen($day) < 2 )
			$day = '0'.$day;
			
		$current_dir = opendir( $this->image_path );
		$old_umask = umask(0);
		while (false !== ($entryname = readdir( $current_dir ))) {
			if ($entryname != '.' and $entryname != '..') {
				if (!is_dir( $this->image_path . $entryname ) && substr($entryname, 0, 2) != $day) {
					@chmod( $this->image_path . $entryname, 0757);
					unlink( $this->image_path . $entryname );
				}
			}
		}
		umask($old_umask);
		closedir( $current_dir );
	}
}

function sf_SafeSplit($html,$size,$delim="\n") {
	$pos = 0;
	$out = '';
	if (strpos($html, "\n") !== false) {
		$html = str_replace("\n\r", "\n", $html);
		$html = str_replace("\r\n", "\n", $html);
		$htmls = explode("\n", $html);
		$out_r = '';
		foreach($htmls as $html) {
			$words = explode(" ", $html);	
			$out = '';	
			$pos = 0;
			foreach($words as $word) {
				if (!trim($word)) continue;
				$word = trim($word);
				if ($pos >= $size || ($pos+strlen($word.' ')) >= $size) {
					$out .= $delim;
					$pos = 0;
				}
				if (strlen($word) > $size*1.1) {
					$word_pieses = @explode($delim, @substr(@chunk_split($word, $size, $delim), 0, -strlen($delim)));
		
					$out .= @substr(@chunk_split($word, $size, $delim), 0, -strlen($delim)).' ';
					$pos = @strlen(@$word_pieses[count($word_pieses)-1]) + 1; 
				} else {
				
					$out .= $word.' ';
					$pos += strlen($word.' ');
				}
			}
			$out_r .= $out."\n";
		}
		$out = $out_r;
	} else  {
		$words = explode(" ", $html);
		
		foreach($words as $word) {
			if (!trim($word)) continue;
			$word = trim($word);
			if ($pos >= $size || ($pos+strlen($word.' ')) >= $size) {
				$out .= $delim;
				$pos = 0;
			}
			if (strlen($word) > $size*1.1) {
				$word_pieses = @explode($delim, @substr(@chunk_split($word, $size, $delim), 0, -strlen($delim)));
	
				$out .= @substr(@chunk_split($word, $size, $delim), 0, -strlen($delim)).' ';
				$pos = @strlen(@$word_pieses[count($word_pieses)-1]) + 1; 
			} else {
				$out .= $word.' ';
				$pos += strlen($word.' ');
			}
		}
	}
	
	return $out;
}
?>