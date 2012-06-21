<?php
	class formsGC {
		
		/* -------- FORMULAIRE -------- */
		
		private $GCname;
		private $GCaction;
		private $GCmethod;
		private $GCenctype;
		private $GCaccept_charset;
		private $GCaccept_file;
		private $GCcontent;
		private $GCdate_id=0;
		private $GClegend_id=0;
		
		/* -------- ELEMENT -------- */

		private $GCfieldset= array();
		private $GCelements= array();
		
		/* -------- VALIDITE-ELEMENT -------- */

		private $GCname_uniq= array();
		private $GCid_uniq= array();
		private $GCuniq_attr= 0;
		
		/* ---------- CONSTRUCTEURS --------- */
		
		public  function __construct($name="", $action="", $method="", $enctype="", $accept_charset="", $accept_file=""){
			if($name!="") $this->GCname=$name; else $this->GCname="formulaire";
			if($action!="") $this->GCaction=$action; else $this->GCaction="action.html";
			if($method!="") $this->GCmethod=$method; else $this->GCmethod="get";
			if($enctype!="") $this->GCenctype=$enctype; else $this->GCenctype="multipart/form-data";
			if($accept_charset!="") $this->GCaccept_charset=$accept_charset; else $this->GCaccept_charset="";
			if($accept_file!="") $this->GCaccept_file=$accept_file; else $this->GCaccept_file="";
		}
		
		public function addFieldset($legend=""){
			if($legend!=""){
				if (!in_array($legend, $this->GCfieldset)) {
					array_push($this->GCfieldset,$legend);
				}
			}
			else{
				array_push($this->GCfieldset,"legend_".$this->GClegend_id);
				$this->GClegend_id++;
			}
		}
		
		public function addInputText($fieldset, $label="", $type, $attribute = array(), $br=0){
			if (in_array($fieldset, $this->GCfieldset)) {
				/* ------------ verification attr name et id ---------------- */
				if(isset($attribute['name']) && in_array($attribute['name'], $this->GCname_uniq)){
					$attribute['name'].=$this->GCuniq_attr;
					$this->GCuniq_attr++;
				}
				elseif(isset($attribute['name'])){
					array_push($this->GCname_uniq, $attribute['name']);
				}
				if(isset($attribute['id']) && in_array($attribute['id'], $this->GCid_uniq)){
					$attribute['id'].=$this->GCuniq_attr;
					$this->GCuniq_attr++;
				}
				elseif(isset($attribute['id'])){
					array_push($this->GCid_uniq, $attribute['id']);
				}
			
				$element= new InputTextGC();
				array_push($this->GCelements, $element->showInputText($label, $type, $attribute, $br).'label['.$fieldset.']');
				unset($element);
			}
		}

		public function addButton($fieldset, $type="", $attribute = array(), $br=0){
			if (in_array($fieldset, $this->GCfieldset)) {
				/* ------------ verification attr name et id ---------------- */
				if(isset($attribute['name']) && in_array($attribute['name'], $this->GCname_uniq)){
					$attribute['name'].=$this->GCuniq_attr;
					$this->GCuniq_attr++;
				}
				elseif(isset($attribute['name'])){
					array_push($this->GCname_uniq, $attribute['name']);
				}
				if(isset($attribute['id']) && in_array($attribute['id'], $this->GCid_uniq)){
					$attribute['id'].=$this->GCuniq_attr;
					$this->GCuniq_attr++;
				}
				elseif(isset($attribute['id'])){
					array_push($this->GCid_uniq, $attribute['id']);
				}
				
				$element= new ButtonGC();
				array_push($this->GCelements, $element->showButton($type, $attribute, $br).'label['.$fieldset.']');
				unset($element);
			}
		}
		
		public function addFile($fieldset, $label="", $attribute = array(), $br=0){
			if (in_array($fieldset, $this->GCfieldset)) {
				/* ------------ verification attr name et id ---------------- */
				if(isset($attribute['name']) && in_array($attribute['name'], $this->GCname_uniq)){
					$attribute['name'].=$this->GCuniq_attr;
					$this->GCuniq_attr++;
				}
				elseif(isset($attribute['name'])){
					array_push($this->GCname_uniq, $attribute['name']);
				}
				if(isset($attribute['id']) && in_array($attribute['id'], $this->GCid_uniq)){
					$attribute['id'].=$this->GCuniq_attr;
					$this->GCuniq_attr++;
				}
				elseif(isset($attribute['id'])){
					array_push($this->GCid_uniq, $attribute['id']);
				}
				
				$element= new FileGC();
				array_push($this->GCelements, $element->showFile($label, $attribute, $br).'label['.$fieldset.']');
				unset($element);
			}
		}
		
		public function addTextarea($fieldset, $label="", $value="", $attribute = array(), $br=0){
			if (in_array($fieldset, $this->GCfieldset)) {
				/* ------------ verification attr name et id ---------------- */
				if(isset($attribute['name']) && in_array($attribute['name'], $this->GCname_uniq)){
					$attribute['name'].=$this->GCuniq_attr;
					$this->GCuniq_attr++;
				}
				elseif(isset($attribute['name'])){
					array_push($this->GCname_uniq, $attribute['name']);
				}
				if(isset($attribute['id']) && in_array($attribute['id'], $this->GCid_uniq)){
					$attribute['id'].=$this->GCuniq_attr;
					$this->GCuniq_attr++;
				}
				elseif(isset($attribute['id'])){
					array_push($this->GCid_uniq, $attribute['id']);
				}
				
				$element= new TextareaGC();
				array_push($this->GCelements, $element->showTextarea($label, $value, $attribute, $br).'label['.$fieldset.']');
				unset($element);
			}
		}
		
		public function addHtml($fieldset, $texte=""){
			if (in_array($fieldset, $this->GCfieldset)) {
				array_push($this->GCelements, $texte.'label['.$fieldset.']');
				unset($element);
			}
		}
		
		public function addCheckbox($fieldset, $label="", $attribute = array(), $br=0){
			if (in_array($fieldset, $this->GCfieldset)) {
				/* ------------ verification attr name et id ---------------- */
				if(isset($attribute['name']) && in_array($attribute['name'], $this->GCname_uniq)){
					$attribute['name'].=$this->GCuniq_attr;
					$this->GCuniq_attr++;
				}
				elseif(isset($attribute['name'])){
					array_push($this->GCname_uniq, $attribute['name']);
				}
				if(isset($attribute['id']) && in_array($attribute['id'], $this->GCid_uniq)){
					$attribute['id'].=$this->GCuniq_attr;
					$this->GCuniq_attr++;
				}
				elseif(isset($attribute['id'])){
					array_push($this->GCid_uniq, $attribute['id']);
				}
				
				$element= new CheckboxGC();
				array_push($this->GCelements, $element->showCheckbox($label, $attribute, $br).'label['.$fieldset.']');
				unset($element);
			}
		}
		
		public function addRadio($fieldset, $label="", $attribute = array(), $br=0){
			if (in_array($fieldset, $this->GCfieldset)) {
				/* ------------ verification attr name et id ---------------- */
				if(isset($attribute['name']) && in_array($attribute['name'], $this->GCname_uniq)){
					$attribute['name'].=$this->GCuniq_attr;
					$this->GCuniq_attr++;
				}
				elseif(isset($attribute['name'])){
					array_push($this->GCname_uniq, $attribute['name']);
				}
				if(isset($attribute['id']) && in_array($attribute['id'], $this->GCid_uniq)){
					$attribute['id'].=$this->GCuniq_attr;
					$this->GCuniq_attr++;
				}
				elseif(isset($attribute['id'])){
					array_push($this->GCid_uniq, $attribute['id']);
				}
				
				$element= new RadioGC();
				array_push($this->GCelements, $element->showRadio($label, $attribute, $br).'label['.$fieldset.']');
				unset($element);
			}
		}
		
		public function addRadio_sql($fieldset, $bdd, $query="", $label="",  $name="", $value="", $checked="", $attribute = array(), $br=0){
			if (in_array($fieldset, $this->GCfieldset)) {
				$element= new RadioGC();
				array_push($this->GCelements, $element->showRadio_sql($bdd, $query, $label, $name, $value, $checked, $attribute, $br).'label['.$fieldset.']');
				unset($element);
			}
		}
		
		public function addListebox_static_number($fieldset, $label="", $attribute = array(), $begin=0, $end=10, $pas=1, $selected= array(), $br=0){
			if (in_array($fieldset, $this->GCfieldset)) {
				/* ------------ verification attr name et id ---------------- */
				if(isset($attribute['name']) && in_array($attribute['name'], $this->GCname_uniq)){
					$attribute['name'].=$this->GCuniq_attr;
					$this->GCuniq_attr++;
				}
				elseif(isset($attribute['name'])){
					array_push($this->GCname_uniq, $attribute['name']);
				}
				if(isset($attribute['id']) && in_array($attribute['id'], $this->GCid_uniq)){
					$attribute['id'].=$this->GCuniq_attr;
					$this->GCuniq_attr++;
				}
				elseif(isset($attribute['id'])){
					array_push($this->GCid_uniq, $attribute['id']);
				}
				
				$element= new ListeboxGC_static();
				array_push($this->GCelements, $element->showListeBox_number($label, $attribute, $begin, $end, $pas, $selected, $br).'label['.$fieldset.']');
				unset($element);
			}
		}
		
		public function addListebox_static_date($fieldset, $label="", $attribute = array() ,$date="20-7-1995",  $br=0){
			if (in_array($fieldset, $this->GCfieldset)) {
				/* ------------ verification attr name et id ---------------- */
				if(isset($attribute['name']) && in_array($attribute['name'], $this->GCname_uniq)){
					$attribute['name'].=$this->GCuniq_attr;
					$this->GCuniq_attr++;
				}
				elseif(isset($attribute['name'])){
					array_push($this->GCname_uniq, $attribute['name']);
				}
				if(isset($attribute['id']) && in_array($attribute['id'], $this->GCid_uniq)){
					$attribute['id'].=$this->GCuniq_attr;
					$this->GCuniq_attr++;
				}
				elseif(isset($attribute['id'])){
					array_push($this->GCid_uniq, $attribute['id']);
				}
				
				$element= new ListeboxGC_static();
				array_push($this->GCelements, $element->showListeBox_date($label, $attribute, $date, $this->GCdate_id, $br).'label['.$fieldset.']');
				unset($element);
				$this->GCdate_id++;
			}
		}
		
		public function addListebox_static_time($fieldset, $label="", $attribute = array() ,$date="20-7-1995-00-00-00",  $br=0){
			if (in_array($fieldset, $this->GCfieldset)) {
				/* ------------ verification attr name et id ---------------- */
				if(isset($attribute['name']) && in_array($attribute['name'], $this->GCname_uniq)){
					$attribute['name'].=$this->GCuniq_attr;
					$this->GCuniq_attr++;
				}
				elseif(isset($attribute['name'])){
					array_push($this->GCname_uniq, $attribute['name']);
				}
				if(isset($attribute['id']) && in_array($attribute['id'], $this->GCid_uniq)){
					$attribute['id'].=$this->GCuniq_attr;
					$this->GCuniq_attr++;
				}
				elseif(isset($attribute['id'])){
					array_push($this->GCid_uniq, $attribute['id']);
				}
				
				$element= new ListeboxGC_static();
				array_push($this->GCelements, $element->showListeBox_time($label, $attribute, $date, $this->GCdate_id, $br).'label['.$fieldset.']');
				unset($element);
				$this->GCdate_id++;
			}
		}
		
		public function addListebox_static_group($fieldset, $label="", $attribute = array(), $value= array(), $content_value = array(), $group = array(), $group_content = array(), $selected= array(), $br=0){
			if (in_array($fieldset, $this->GCfieldset)) {
				/* ------------ verification attr name et id ---------------- */
				if(isset($attribute['name']) && in_array($attribute['name'], $this->GCname_uniq)){
					$attribute['name'].=$this->GCuniq_attr;
					$this->GCuniq_attr++;
				}
				elseif(isset($attribute['name'])){
					array_push($this->GCname_uniq, $attribute['name']);
				}
				if(isset($attribute['id']) && in_array($attribute['id'], $this->GCid_uniq)){
					$attribute['id'].=$this->GCuniq_attr;
					$this->GCuniq_attr++;
				}
				elseif(isset($attribute['id'])){
					array_push($this->GCid_uniq, $attribute['id']);
				}
				
				$element= new ListeboxGC_static();
				array_push($this->GCelements, $element->addListebox_static_group($label, $attribute, $value, $content_value, $group, $group_content, $selected, $br).'label['.$fieldset.']');
				unset($element);
			}
		}
		
		public function addListebox_static($fieldset, $label="", $attribute = array(), $value = array(), $content_value = array(), $selected= array(), $br=0){
			if (in_array($fieldset, $this->GCfieldset)) {
				/* ------------ verification attr name et id ---------------- */
				if(isset($attribute['name']) && in_array($attribute['name'], $this->GCname_uniq)){
					$attribute['name'].=$this->GCuniq_attr;
					$this->GCuniq_attr++;
				}
				elseif(isset($attribute['name'])){
					array_push($this->GCname_uniq, $attribute['name']);
				}
				if(isset($attribute['id']) && in_array($attribute['id'], $this->GCid_uniq)){
					$attribute['id'].=$this->GCuniq_attr;
					$this->GCuniq_attr++;
				}
				elseif(isset($attribute['id'])){
					array_push($this->GCid_uniq, $attribute['id']);
				}
				
				$element= new ListeboxGC_static();
				array_push($this->GCelements, $element->showListeBox($label, $attribute, $value, $content_value, $selected, $br).'label['.$fieldset.']');
				unset($element);
			}
		}
		
		public function addListebox_sql($fieldset, $label="", $bdd, $query="", $value="", $content_value="", $attribute = array(), $selected=array(), $br=0){
			if (in_array($fieldset, $this->GCfieldset)) {
				$element= new ListeboxGC_sql();
				array_push($this->GCelements, $element->showListeBox_sql($label, $bdd, $query, $value, $content_value, $attribute, $selected, $br).'label['.$fieldset.']');
				unset($element);
			}
		}
		
		public function addListebox_sql_group($fieldset, $label="", $bdd, $query1="", $query2="", $optgroup_value="", $optgroup_content="", $option_optgroup_content="", $option_value="", $option_content_value="", $attribute = array(), $selected=array(), $br=0){
			if (in_array($fieldset, $this->GCfieldset)) {
				$element= new ListeboxGC_sql();
				array_push($this->GCelements, $element->showListeBox_sql_group($label, $bdd, $query1, $query2, $optgroup_value, $optgroup_content, $option_optgroup_content, $option_value, $option_content_value, $attribute, $selected, $br).'label['.$fieldset.']');
				unset($element);
			}
		}
		
		public function addSubmitReset($type="", $attribute = array(), $br=0){
			/* ------------ verification attr name et id ---------------- */
			if(isset($attribute['name']) && in_array($attribute['name'], $this->GCname_uniq)){
				$attribute['name'].=$this->GCuniq_attr;
				$this->GCuniq_attr++;
			}
			elseif(isset($attribute['name'])){
				array_push($this->GCname_uniq, $attribute['name']);
			}
			if(isset($attribute['id']) && in_array($attribute['id'], $this->GCid_uniq)){
				$attribute['id'].=$this->GCuniq_attr;
				$this->GCuniq_attr++;
			}
			elseif(isset($attribute['id'])){
				array_push($this->GCid_uniq, $attribute['id']);
			}
			
			$element= new SubmitGC();
			array_push($this->GCelements, $element->showButton($type, $attribute, $br).'submit[]');
			unset($element);
		}
		
		public function showForms(){
			$this->GCcontent.="<form name=\"".$this->GCname."\" action=\"".$this->GCaction."\" method=\"".$this->GCmethod."\" enctype=\"".$this->GCenctype."\">\n";			
			
			foreach($this->GCfieldset as $fieldset){
				$this->GCcontent.="  <fieldset>\n";
				$this->GCcontent.="    <legend>".$fieldset."</legend>\n";
				
				foreach($this->GCelements as $element){
					if (preg_match('#'.$fieldset.'\]#', $element)) {
						$element= preg_replace('#label\['.$fieldset.'\]#isU', '', $element);
						$this->GCcontent.="    ".$element."\n";
					}
				}
				
				$this->GCcontent.="  </fieldset>\n";
			}
			
			foreach($this->GCelements as $element){
				if (preg_match('#submit\[\]#', $element)) {
					$element= preg_replace('#submit\[\]#isU', '', $element);
					$this->GCcontent.="  ".$element."\n";
				}
			}
			
			$this->GCcontent.="</form>";
			
			echo $this->GCcontent;
		}
		
		public function showPHPForms(){
			$echo .='Informations sur le formulaire';
			$echo .="\n";
			$echo .="\n";
			$echo .='name => '.$this->GCname;
			$echo .="\n";
			$echo .='action => '.$this->GCaction;
			$echo .="\n";
			$echo .='method => '.$this->GCmethod;
			$echo .="\n";
			$echo .='enctype => '.$this->GCenctype;
			$echo .="\n";
			$echo .="\n";
			
			$i=0;
			
			foreach($this->GCfieldset as $fieldset){
				$echo .='fieldset 1 => '.$fieldset;
				$i++;
			}
			
			echo $echo;
		}
		
		/* ---------- DESCTRUCTEURS --------- */
		
		public  function __desctruct(){
		}
	}
	
	class InputTextGC {
		private $GCtypeInput;
		private $i;
		
		/* ---------- CONSTRUCTEURS --------- */
		
		public  function __construct(){
		}
		
		// $name, $size, $value, $readonly, $maxlenght, $disabled, $alt
		
		public function showInputText($label="", $type="", $attribute = array(), $br=0){
			if($label!="") $this->GCtypeInput.="<label>".$label."</label>";
			switch($type){
				case 'hidden':
					if(isset($attribute['value'])){
						$this->GCtypeInput.="<input type=\"".$type."\" value=\"".$attribute['value']."\"";
					}
					else{
						if(isset($_POST[''.$attribute['name'].''])){ $this->GCtypeInput.="<input type=\"".$type."\" value=\"".@$_POST[''.$attribute['name'].'']."\""; }
						elseif(isset($_GET[''.$attribute['name'].''])){ $this->GCtypeInput.="<input type=\"".$type."\" value=\"".@$_GET[''.$attribute['name'].'']."\""; }
						else{ $this->GCtypeInput.="<input type=\"".$type."\" value=\"\""; }
					}
				break;
				
				case 'password':
					if(isset($attribute['value'])){
						$this->GCtypeInput.="<input type=\"".$type."\" value=\"".$attribute['value']."\"";
					}
					else{
						if(isset($_POST[''.$attribute['name'].''])){ $this->GCtypeInput.="<input type=\"".$type."\" value=\"".@$_POST[''.$attribute['name'].'']."\""; }
						elseif(isset($_GET[''.$attribute['name'].''])){ $this->GCtypeInput.="<input type=\"".$type."\" value=\"".@$_GET[''.$attribute['name'].'']."\""; }
						else{ $this->GCtypeInput.="<input type=\"".$type."\" value=\"\""; }
					}
				break;

				case 'text':
					if(isset($attribute['value'])){
						$this->GCtypeInput.="<input type=\"".$type."\" value=\"".$attribute['value']."\"";
					}
					else{
						if(isset($_POST[''.$attribute['name'].''])){ $this->GCtypeInput.="<input type=\"".$type."\" value=\"".@$_POST[''.$attribute['name'].'']."\""; }
						elseif(isset($_GET[''.$attribute['name'].''])){ $this->GCtypeInput.="<input type=\"".$type."\" value=\"".@$_GET[''.$attribute['name'].'']."\""; }
						else{ $this->GCtypeInput.="<input type=\"".$type."\" value=\"\""; }
					}
				break;
				
				case 'email':
					if(isset($attribute['value'])){
						$this->GCtypeInput.="<input type=\"".$type."\" value=\"".$attribute['value']."\"";
					}
					else{
						if(isset($_POST[''.$attribute['name'].''])){ $this->GCtypeInput.="<input type=\"".$type."\" value=\"".@$_POST[''.$attribute['name'].'']."\""; }
						elseif(isset($_GET[''.$attribute['name'].''])){ $this->GCtypeInput.="<input type=\"".$type."\" value=\"".@$_GET[''.$attribute['name'].'']."\""; }
						else{ $this->GCtypeInput.="<input type=\"".$type."\" value=\"\""; }
					}
				break;
				
				default:
					if(isset($attribute['value'])){
						$this->GCtypeInput.="<input type=\"text\" value=\"".$attribute['value']."\"";
					}
					else{
						if(isset($_POST[''.$attribute['name'].''])){ $this->GCtypeInput.="<input type=\"".$type."\" value=\"".@$_POST[''.$attribute['name'].'']."\""; }
						elseif(isset($_GET[''.$attribute['name'].''])){ $this->GCtypeInput.="<input type=\"".$type."\" value=\"".@$_GET[''.$attribute['name'].'']."\""; }
						else{ $this->GCtypeInput.="<input type=\"".$type."\" value=\"\""; }
					}
				break;
			}
			
			foreach($attribute as $attribut => $valeur){
				$this->GCtypeInput.=$attribut."=\"".$valeur."\" ";
			}
			
			$this->GCtypeInput.="/>";
			
			for($this->i=1;$this->i<=$br;$this->i++){
				$this->GCtypeInput.="<br />";
			}
			return $this->GCtypeInput;
		}
		
		/* ---------- DESCTRUCTEURS --------- */
		
		public  function __desctruct(){
		}
	}
	
	class ButtonGC {
		private $GCtypeButton;
		private $i;		
		
		/* ---------- CONSTRUCTEURS --------- */
		
		public  function __construct(){
		}

		public function showButton($type="", $attribute = array(), $br=0){
			switch($type){
				case 'image':
					$this->GCtypeButton.="<input type=\"".$type."\" ";
				break;
				
				case 'button':
					$this->GCtypeButton.="<input type=\"".$type."\" ";
				break;
				
				default:
					$this->GCtypeButton.="<input type=\"button\" ";
				break;
			}
			
			foreach($attribute as $attribut => $valeur){
				$this->GCtypeButton.=$attribut."=\"".$valeur."\" ";
			}
			
			$this->GCtypeButton.="/>";
			
			for($this->i=1;$this->i<=$br;$this->i++){
				$this->GCtypeButton.="<br />";
			}
			return $this->GCtypeButton;
		}
		
		/* ---------- DESCTRUCTEURS --------- */
		
		public  function __desctruct(){
		}
	}
	
	class SubmitGC {
		private $GCtypeSubmit;
		private $i;
		
		/* ---------- CONSTRUCTEURS --------- */
		
		public  function __construct(){
		}
		
		public function showButton($type="", $attribute = array(), $br=0){
			switch($type){
				case 'submit':
					$this->GCtypeSubmit.="<input type=\"".$type."\" ";
				break;
				
				case 'reset':
					$this->GCtypeSubmit.="<input type=\"".$type."\" ";
				break;
				
				case 'button':
					$this->GCtypeSubmit.="<input type=\"".$type."\" ";
				break;
				
				default:
					$this->GCtypeSubmit.="<input type=\"submit\" ";
				break;
			}
			
			foreach($attribute as $attribut => $valeur){
				$this->GCtypeSubmit.=$attribut."=\"".$valeur."\" ";
			}
			
			$this->GCtypeSubmit.="/>";
			for($this->i=1;$this->i<=$br;$this->i++){
				$this->GCtypeSubmit.="<br />";
			}
			return $this->GCtypeSubmit;
		}
		
		/* ---------- DESCTRUCTEURS --------- */
		
		public  function __desctruct(){
		}
	}
	
	class FileGC {
		private $GCtypeFile;
		private $i;
		
		/* ---------- CONSTRUCTEURS --------- */
		
		public  function __construct(){
		}

		public function showFile($label, $attribute = array(), $br=0){
			if($label!="") $this->GCtypeFile.="<label>".$label."</label>";
			$this->GCtypeFile.="<input type=\"file\" ";
			
			foreach($attribute as $attribut => $valeur){
				$this->GCtypeFile.=$attribut."=\"".$valeur."\" ";
			}
			
			$this->GCtypeFile.="/>";
			for($this->i=1;$this->i<=$br;$this->i++){
				$this->GCtypeFile.="<br />";
			}
			return $this->GCtypeFile;
		}
		
		/* ---------- DESCTRUCTEURS --------- */
		
		public  function __desctruct(){
		}
	}
	
	class TextareaGC {
		private $GCtypeTextarea;
		private $i;
		
		/* ---------- CONSTRUCTEURS --------- */
		
		public  function __construct(){
		}
		
		public function showTextarea($label="", $value="", $attribute = array(), $br=0){
			if($label!="") $this->GCtypeTextarea.="<label>".$label."</label>";
			$this->GCtypeTextarea.="<textarea ";
			foreach($attribute as $attribut => $valeur){
				$this->GCtypeTextarea.=$attribut."=\"".$valeur."\" ";
			}
			$this->GCtypeTextarea.=">";
			if($value!="" && !@$_POST[''.$attribute['name'].''] && !@$_POST[''.$attribute['name'].'']) $this->GCtypeTextarea.=$value;
			if (isset($_POST[''.$attribute['name'].''])) $this->GCtypeTextarea.=@$_POST[''.$attribute['name'].''];
			if (isset($_GET[''.$attribute['name'].''])) $this->GCtypeTextarea.=@$_GET[''.$attribute['name'].''];
			$this->GCtypeTextarea.="</textarea>";
			for($this->i=1;$this->i<=$br;$this->i++){
				$this->GCtypeTextarea.="<br />";
			}
			return $this->GCtypeTextarea;
		}
		
		/* ---------- DESCTRUCTEURS --------- */
		
		public  function __desctruct(){
		}
	}
	
	class CheckboxGC {
		private $GCtypeCheckbox;
		private $i;
		
		/* ---------- CONSTRUCTEURS --------- */
		
		public  function __construct(){
		}
		
		public function showCheckbox($label="", $attribute = array(), $br=0){
			if($label!="")$this->GCtypeCheckbox.="<label>".$label."</label>";
			$this->GCtypeCheckbox.="<input type=\"checkbox\" ";
			foreach($attribute as $attribut => $valeur){
				$this->GCtypeCheckbox.=$attribut."=\"".$valeur."\" ";
			}
			$this->GCtypeCheckbox.=" />";
			for($this->i=1;$this->i<=$br;$this->i++){
				$this->GCtypeCheckbox.="<br />";
			}
			return $this->GCtypeCheckbox;
		}
		
		/* ---------- DESCTRUCTEURS --------- */
		
		public  function __desctruct(){
		}
	}
	
	class RadioGC {
		private $GCtypeRadio;
		private $i;
		private $j;
		
		private $query;
		private $data;
		
		/* ---------- CONSTRUCTEURS --------- */
		
		public  function __construct(){
		}
		
		public function showRadio($label=array(), $attribute = array(), $br=0){
			$this->i=0;
			foreach($label as $radio){
				if($radio!="") $this->GCtypeRadio.="<label>".$radio."</label>";
				$this->GCtypeRadio.="<input type=\"radio\" ";
				foreach($attribute as $attribut => $valeur){
					$this->GCtypeRadio.=$attribut."=\"".$valeur[$this->i]."\" ";
				}
				$this->GCtypeRadio.=" />\n";
				
				$this->i++;
				for($this->j=1;$this->j<=$br;$this->j++){
					$this->GCtypeRadio.="<br />";
				}
			}
			
			for($this->j=1;$this->j<=$br;$this->j++){
				$this->GCtypeRadio.="<br />";
			}
				
			return $this->GCtypeRadio;
		}
		
		public function showRadio_sql($bdd, $query="", $label="",  $name="", $value="", $checked="", $attribute = array(), $br=0){
			$this->query=$bdd->query($query);
			$this->query->execute();
			
			while($this->data=$this->query->fetch()){
				if($this->data[$label]!="") $this->GCtypeRadio.="<label>".$this->data[$label]."</label>";
				$this->GCtypeRadio.="<input type=\"radio\" ";
				$this->GCtypeRadio.="name=\"".$name."\" ";
				$this->GCtypeRadio.="value=\"".$this->data[$value]."\" ";
				if($this->data[$value]==$checked){
					$this->GCtypeRadio.="checked=\"checked\" ";
				}
				
				$this->i=0;
				foreach($attribute as $attribut => $valeur){
					$this->GCtypeRadio.=$attribut."=\"".$valeur[$this->i]."\" ";
					$this->i++;
				}
				$this->GCtypeRadio.=" />\n";
				
				for($this->i=1;$this->i<=$br;$this->i++){
					$this->GCtypeRadio.="<br />";
				}
			}
			
			for($this->i=1;$this->i<=$br;$this->i++){
				$this->GCtypeRadio.="<br />";
			}
			
							
			return $this->GCtypeRadio;
		}
		
		/* ---------- DESCTRUCTEURS --------- */
		
		public  function __desctruct(){
			unset($bdd);
		}
	}
	
	class ListeboxGC_static {
		private $GCtypeListebox;
		private $i;
		private $j;
		
		/* ---------- CONSTRUCTEURS --------- */
		
		public  function __construct(){
		}
		
		public function showListeBox($label="", $attribute = array(), $value = array(), $content_value = array(), $selected= array(), $br=0){
			$this->i=0;
			if($label!="") $this->GCtypeListebox.="<label>".$label."</label>\n";
			$this->GCtypeListebox.="      <select ";
			foreach($attribute as $attribut => $valeur){
				$this->GCtypeListebox.=$attribut."=\"".$valeur."\" ";
			}
			$this->GCtypeListebox.=">\n";
			
			foreach($value as $valeur){
				if(!in_array($this->i, $selected)) $this->GCtypeListebox.="        <option value=\"".$valeur."\">".$content_value[$this->i]."</option>\n"; else $this->GCtypeListebox.="        <option value=\"".$valeur."\" selected=\"selected\">".$content_value[$this->i]."</option>\n";
				$this->i++;
			}
			
			$this->GCtypeListebox.="      </select>";
			for($this->j=1;$this->j<=$br;$this->j++){
				$this->GCtypeListebox.="<br />";
			}
				
			return $this->GCtypeListebox;
		}
		
		public function addListebox_static_group($label="", $attribute = array(), $value = array(), $content_value = array(), $group = array(), $group_content = array(), $selected= array(), $br=0){
			$this->i=0;
			$this->j=0;
			
			if($label!="")$this->GCtypeListebox.="<label>".$label."</label>\n";
			$this->GCtypeListebox.="      <select ";
			foreach($attribute as $attribut => $valeur){
				$this->GCtypeListebox.=$attribut."=\"".$valeur."\" ";
			}
			$this->GCtypeListebox.=">\n";
			
			foreach($group as $groups){
				$this->GCtypeListebox.="        <optgroup label=\"".$groups."\">\n";
				foreach($value as $valeur){
					if((count($group_content)-1)>=$this->j){
						if($group_content[$this->j]==$this->i){
							if(!in_array($this->j, $selected)) $this->GCtypeListebox.="          <option value=\"".$value[$this->j]."\">".$content_value[$this->j]."</option>\n"; else $this->GCtypeListebox.="          <option value=\"".$value[$this->j]."\" selected=\"selected\">".$content_value[$this->j]."</option>\n";
							$this->j++;
						}
					}
				}
				$this->GCtypeListebox.="        </optgroup>\n";
				
				$this->i++;
			}

			$this->GCtypeListebox.="      </select>";

			for($this->j=1;$this->j<=$br;$this->j++){
				$this->GCtypeListebox.="<br />";
			}
				
			return $this->GCtypeListebox;
		}
		
		public function showListeBox_number($label="", $attribute = array(), $begin=0, $end=10, $pas=1, $selected= array(), $br=0){
			if($label!="") $this->GCtypeListebox.="<label>".$label."</label>\n";
			$this->GCtypeListebox.="      <select ";
			foreach($attribute as $attribut => $valeur){
				$this->GCtypeListebox.=$attribut."=\"".$valeur."\" ";
			}
			$this->GCtypeListebox.=">\n";
			for($this->i=$begin;$this->i<=$end;$this->i+=$pas){
				if(!in_array($this->i, $selected)) $this->GCtypeListebox.="        <option value=\"".$this->i."\">".$this->i."</option>\n"; else $this->GCtypeListebox.="        <option value=\"".$this->i."\" selected=\"selected\">".$this->i."</option>\n";
			}
			$this->GCtypeListebox.="      </select>";
			
			for($this->j=1;$this->j<=$br;$this->j++){
				$this->GCtypeListebox.="<br />";
			}
				
			return $this->GCtypeListebox;
		}

		public function showListeBox_date($label="", $attribute = array(), $date="20-07-1995", $id_uniq=1, $br=0){
			$dates=explode("-",$date);
			
			for($this->i=0;$this->i<=2;$this->i++){
				if(empty($dates[$this->i])){
					$dates[$this->i]=1;
				}
			}

			if($label!="") $this->GCtypeListebox.="<label>".$label."</label>\n";
			
			/*------ jour --- */
			$this->GCtypeListebox.="      <select ";
			$this->GCtypeListebox.="name=\"jour_".$id_uniq."\" ";
			foreach($attribute as $attribut => $valeur){
				$this->GCtypeListebox.=$attribut."=\"".$valeur."\" ";
			}
			$this->GCtypeListebox.=">\n";
			for($this->i=1;$this->i<=31;$this->i++){
				if($dates[0]!=$this->i) $this->GCtypeListebox.="        <option value=\"".$this->i."\">".$this->i."</option>\n"; else $this->GCtypeListebox.="        <option value=\"".$this->i."\" selected=\"selected\">".$this->i."</option>\n";    
			}
			$this->GCtypeListebox.="      </select>";

			/*------ mois --- */
			$this->GCtypeListebox.="      <select ";
			$this->GCtypeListebox.="name=\"mois_".$id_uniq."\" ";
			foreach($attribute as $attribut => $valeur){
				$this->GCtypeListebox.=$attribut."=\"".$valeur."\" ";
			}
			$this->GCtypeListebox.=">\n";
			for($this->i=1;$this->i<=12;$this->i++){
				if($dates[1]!=$this->i) $this->GCtypeListebox.="        <option value=\"".$this->i."\">".$this->i."</option>\n"; else $this->GCtypeListebox.="        <option value=\"".$this->i."\" selected=\"selected\">".$this->i."</option>\n";    
			}
			$this->GCtypeListebox.="      </select>";
			
			/*------ annee --- */
			$this->GCtypeListebox.="      <select ";
			$this->GCtypeListebox.="name=\"annee_".$id_uniq."\" ";
			foreach($attribute as $attribut => $valeur){
				$this->GCtypeListebox.=$attribut."=\"".$valeur."\" ";
			}
			$this->GCtypeListebox.=">\n";
			for($this->i=1970;$this->i<=2030;$this->i++){
				if($dates[2]!=$this->i) $this->GCtypeListebox.="        <option value=\"".$this->i."\">".$this->i."</option>\n"; else $this->GCtypeListebox.="        <option value=\"".$this->i."\" selected=\"selected\">".$this->i."</option>\n";    
			}
			$this->GCtypeListebox.="      </select>";
			
			for($this->j=1;$this->j<=$br;$this->j++){
				$this->GCtypeListebox.="<br />";
			}
				
			return $this->GCtypeListebox;
		}
		
		public function showListeBox_time($label="", $attribute = array(), $date="20-07-1995-00-00-00", $id_uniq=1, $br=0){
			$dates=explode("-",$date);
			
			for($this->i=0;$this->i<=5;$this->i++){
				if(empty($dates[$this->i])){
					$dates[$this->i]=1;
				}
			}
			
			if($label!="") $this->GCtypeListebox.="<label>".$label."</label>\n";
			
			/*------ jour --- */
			$this->GCtypeListebox.="      <select ";
			$this->GCtypeListebox.="name=\"jour_".$id_uniq."\" ";
			foreach($attribute as $attribut => $valeur){
				$this->GCtypeListebox.=$attribut."=\"".$valeur."\" ";
			}
			$this->GCtypeListebox.=">\n";
			for($this->i=1;$this->i<=31;$this->i++){
				if($dates[0]!=$this->i) $this->GCtypeListebox.="        <option value=\"".$this->i."\">".$this->i."</option>\n"; else $this->GCtypeListebox.="        <option value=\"".$this->i."\" selected=\"selected\">".$this->i."</option>\n";    
			}
			$this->GCtypeListebox.="      </select> - ";

			/*------ mois --- */
			$this->GCtypeListebox.="      <select ";
			$this->GCtypeListebox.="name=\"mois_".$id_uniq."\" ";
			foreach($attribute as $attribut => $valeur){
				$this->GCtypeListebox.=$attribut."=\"".$valeur."\" ";
			}
			$this->GCtypeListebox.=">\n";
			for($this->i=1;$this->i<=12;$this->i++){
				if($dates[1]!=$this->i) $this->GCtypeListebox.="        <option value=\"".$this->i."\">".$this->i."</option>\n"; else $this->GCtypeListebox.="        <option value=\"".$this->i."\" selected=\"selected\">".$this->i."</option>\n";    
			}
			$this->GCtypeListebox.="      </select>  - ";
			
			/*------ annee --- */
			$this->GCtypeListebox.="      <select ";
			$this->GCtypeListebox.="name=\"annee_".$id_uniq."\" ";
			foreach($attribute as $attribut => $valeur){
				$this->GCtypeListebox.=$attribut."=\"".$valeur."\" ";
			}
			$this->GCtypeListebox.=">\n";
			for($this->i=1970;$this->i<=2030;$this->i++){
				if($dates[2]!=$this->i) $this->GCtypeListebox.="        <option value=\"".$this->i."\">".$this->i."</option>\n"; else $this->GCtypeListebox.="        <option value=\"".$this->i."\" selected=\"selected\">".$this->i."</option>\n";    
			}
			$this->GCtypeListebox.="      </select>  - ";
			
			/*------ heure --- */
			$this->GCtypeListebox.="      <select ";
			$this->GCtypeListebox.="name=\"heure_".$id_uniq."\" ";
			foreach($attribute as $attribut => $valeur){
				$this->GCtypeListebox.=$attribut."=\"".$valeur."\" ";
			}
			$this->GCtypeListebox.=">\n";
			for($this->i=0;$this->i<=23;$this->i++){
				if($dates[3]!=$this->i) $this->GCtypeListebox.="        <option value=\"".$this->i."\">".$this->i."</option>\n"; else $this->GCtypeListebox.="        <option value=\"".$this->i."\" selected=\"selected\">".$this->i."</option>\n";    
			}
			$this->GCtypeListebox.="      </select> h ";
			
			/*------ minute --- */
			$this->GCtypeListebox.="      <select ";
			$this->GCtypeListebox.="name=\"minute_".$id_uniq."\" ";
			foreach($attribute as $attribut => $valeur){
				$this->GCtypeListebox.=$attribut."=\"".$valeur."\" ";
			}
			$this->GCtypeListebox.=">\n";
			for($this->i=0;$this->i<=59;$this->i++){
				if($dates[4]!=$this->i) $this->GCtypeListebox.="        <option value=\"".$this->i."\">".$this->i."</option>\n"; else $this->GCtypeListebox.="        <option value=\"".$this->i."\" selected=\"selected\">".$this->i."</option>\n";    
			}
			$this->GCtypeListebox.="      </select> m ";
			
			/*------ seconde --- */
			$this->GCtypeListebox.="      <select ";
			$this->GCtypeListebox.="name=\"seconde_".$id_uniq."\" ";
			foreach($attribute as $attribut => $valeur){
				$this->GCtypeListebox.=$attribut."=\"".$valeur."\" ";
			}
			$this->GCtypeListebox.=">\n";
			for($this->i=0;$this->i<=59;$this->i++){
				if($dates[5]!=$this->i) $this->GCtypeListebox.="        <option value=\"".$this->i."\">".$this->i."</option>\n"; else $this->GCtypeListebox.="        <option value=\"".$this->i."\" selected=\"selected\">".$this->i."</option>\n";    
			}
			$this->GCtypeListebox.="      </select> s ";
			
			for($this->j=1;$this->j<=$br;$this->j++){
				$this->GCtypeListebox.="<br />";
			}
				
			return $this->GCtypeListebox;
		}
		
		/* ---------- DESCTRUCTEURS --------- */
		
		public  function __desctruct(){
		}
	}
	
	class ListeboxGC_sql {
		private $GCtypeListebox;
		private $i;
		private $query;
		private $data;
		private $query_2;
		private $data_2;
		
		/* ---------- CONSTRUCTEURS --------- */
		
		public  function __construct(){
		}
		
		public function showListeBox_sql($label="", $bdd, $query="", $value="", $content_value="", $attribute = array(), $selected=array(), $br=0){			
			$this->query=$bdd->query($query);
			$this->query->execute();
			
			if($label!="") $this->GCtypeListebox.="<label>".$label."</label>\n";
			$this->GCtypeListebox.="      <select ";
			foreach($attribute as $attribut => $valeur){
				$this->GCtypeListebox.=$attribut."=\"".$valeur."\" ";
			}
			$this->GCtypeListebox.=">\n";
			
			while($this->data=$this->query->fetch()){
				if(!in_array($this->data[$value], $selected)) $this->GCtypeListebox.="        <option value=\"".$this->data[$value]."\">".$this->data[$content_value]."</option>\n"; else $this->GCtypeListebox.="        <option value=\"".$this->data[$value]."\" selected=\"selected\">".$this->data[$content_value]."</option>\n";
			}
			
			$this->GCtypeListebox.="      </select>";
			for($this->i=1;$this->i<=$br;$this->i++){
				$this->GCtypeListebox.="<br />";
			}
				
			return $this->GCtypeListebox;
		}
		
		public function showListeBox_sql_group($label="", $bdd, $query1="", $query2, $optgroup_value="", $optgroup_content="",  $option_optgroup_content="", $option_value="", $option_content_value="", $attribute = array(), $selected=array(), $br=0){
			$this->query=$bdd->query($query1);
			$this->query->execute();
			
			if($label!="") $this->GCtypeListebox.="<label>".$label."</label>\n";
			$this->GCtypeListebox.="      <select ";
			foreach($attribute as $attribut => $valeur){
				$this->GCtypeListebox.=$attribut."=\"".$valeur."\" ";
			}
			$this->GCtypeListebox.=">\n";
			
			while($this->data=$this->query->fetch()){
				$this->GCtypeListebox.="        <optgroup label=\"".$this->data[$optgroup_value]."\">\n";
				
				if(preg_match('#WHERE#', $query2)){
					$query2 = preg_replace('#WHERE#isU','WHERE '.$option_optgroup_content.' = '.$this->data[$optgroup_content].' AND ',$query2);
					$this->query2=$bdd->query($query2);
					$this->query2->execute();
				}
				else{
					$this->query2=$bdd->query($query2.' WHERE '.$option_optgroup_content.' = '.$this->data[$optgroup_content]);
					$this->query2->execute();
				}
				
				while($this->data2=$this->query2->fetch()){
					if($this->data[$optgroup_content]==$this->data2[$option_optgroup_content]){
						if(!in_array($this->data2[$option_value], $selected)) $this->GCtypeListebox.="        <option value=\"".$this->data2[$option_value]."\">".$this->data2[$option_content_value]."</option>\n"; else $this->GCtypeListebox.="        <option value=\"".$this->data2[$option_value]."\" selected=\"selected\">".$this->data2[$option_content_value]."</option>\n";
					}
				}
				$this->GCtypeListebox.="        </optgroup>\n";
			}
			
			$this->GCtypeListebox.="      </select>";
			for($this->i=1;$this->i<=$br;$this->i++){
				$this->GCtypeListebox.="<br />";
			}
			
							
			return $this->GCtypeListebox;
		}
		
		/* ---------- DESCTRUCTEURS --------- */
		
		public  function __desctruct(){
			unset($bdd);
		}
		
	}