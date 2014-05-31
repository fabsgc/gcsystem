<?php
	class formsGC {
		
		/* -------- FORMULAIRE -------- */
		
		private $GCname               = "formulaire"         ;
		private $GCtoken              = ""                   ;
		private $GCaction             = "#"                  ;
		private $GCmethod             = "GET"                ;
		private $GCenctype            = "multipart/form-data";
		private $GCaccept_charset     = "UTF-8"              ;
		private $GCaccept_file        = ""                   ;
		private $GCcontent            = ""                   ;
		private $GCdate_id            = 0                    ;
		private $GClegend_id          = 0                    ;
		
		/* -------- ELEMENT -------- */

		private $GCfieldset = array();
		private $GCelements = array();
		
		/* -------- VALIDITE-ELEMENT -------- */

		private $GCname_uniq = array();
		private $GCid_uniq   = array();
		private $GCuniq_attr = 0      ; 
		
		/**
		 * création d'un formulaire
		 * @access public
		 * @param array $info : information de base du formulaire
		 * name, action, method, enctype, accept_charset, accept_file
		 * @return void
		 * @since 2.0
		*/
		
		public  function __construct($info = array()){
			foreach($info as $cle=>$info){
				switch($cle){
					case'name':
						$this->GCname=$info;
					break;
					
					case'action':
						$this->GCaction=$info;
					break;
					
					case'method':
						$this->GCmethod=$info;
					break;
					
					case'enctype':
						$this->GCmethod=$info;
					break;
					
					case'accept_charset':
						$this->GCaccept_charset=$info;
					break;
					
					case'accept_file':
						$this->GCaccept_file=$info;
					break;
				}
			}
		}

		/**
		 * sécurité du formulaire avec un token POST
		 * @access public
		 * @param string $tokenName : nom de la variable post qui sera créée
		 * @param string $tokenValue : valeur donnée à la variable
		 * @return void
		 * @since 2.0
		*/

		public function setToken($tokenName, $tokenValue){
			$this->GCtoken = true;
			$this->GCtokenName =  $tokenName;
			$this->GCtokenValue =  $tokenValue;
		}

		/**
		 * les éléments sont regroupés par fieldset, on peut donc en créer un
		 * @access public
		 * @param string $legend : nom du fieldset, sert d'identificateur
		 * @return void
		 * @since 2.0
		*/
		
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

		/**
		 * input text, password, mail etc.
		 * @access public
		 * @param string $fieldset : nom du fieldset qui doit contenir l'élement
		 * @param string $label : label de l'élément
		 * @param array $attribute : tous les attributs que l'on peut rajouter à l'input
		 * @param int $br : nombre de sauts de lignes
		 * @return void
		 * @since 2.0
		*/
		
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

		/**
		 * input button
		 * @access public
		 * @param string $fieldset : nom du fieldset qui doit contenir l'élement
		 * @param string $label : label de l'élément
		 * @param array $attribute : tous les attributs que l'on peut rajouter à l'input
		 * @param int $br : nombre de sauts de lignes
		 * @return void
		 * @since 2.0
		*/

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

		/**
		 * input file
		 * @access public
		 * @param string $fieldset : nom du fieldset qui doit contenir l'élement
		 * @param string $label : label de l'élément
		 * @param array $attribute : tous les attributs que l'on peut rajouter à l'input
		 * @param int $br : nombre de sauts de lignes
		 * @return void
		 * @since 2.0
		*/
		
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
				
				$element= new FilesGc();
				array_push($this->GCelements, $element->showFile($label, $attribute, $br).'label['.$fieldset.']');
				unset($element);
			}
		}

		/**
		 * textarea
		 * @access public
		 * @param string $fieldset : nom du fieldset qui doit contenir l'élement
		 * @param string $label : label de l'élément
		 * @param string $value : contenu du textarea
		 * @param array $attribute : tous les attributs que l'on peut rajouter au textarea
		 * @param int $br : nombre de sauts de lignes
		 * @return void
		 * @since 2.0
		*/
		
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

		/**
		 * insertion de html pur
		 * @access public
		 * @param string $fieldset : nom du fieldset qui doit contenir l'élement
		 * @param string $texte : contenu qui sera rajouté
		 * @return void
		 * @since 2.0
		*/
		
		public function addHtml($fieldset, $texte=""){
			if (in_array($fieldset, $this->GCfieldset)) {
				array_push($this->GCelements, $texte.'label['.$fieldset.']');
				unset($element);
			}
		}

		/**
		 * checkbox
		 * @access public
		 * @param string $fieldset : nom du fieldset qui doit contenir l'élement
		 * @param string $label : label de l'élément
		 * @param array $attribute : tous les attributs que l'on peut rajouter à l'input
		 * @param int $br : nombre de sauts de lignes
		 * @return void
		 * @since 2.0
		*/
		
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
		
		/**
		 * radios
		 * @access public
		 * @param string $fieldset : nom du fieldset qui doit contenir l'élement
		 * @param string $label : label de l'élément
		 * @param array $attribute : tous les attributs que l'on peut rajouter à l'input
		 * @param int $br : nombre de sauts de lignes
		 * @return void
		 * @since 2.0
		*/

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

		/**
		 * radios - requête sql
		 * @access public
		 * @param string $fieldset : nom du fieldset qui doit contenir l'élement
		 * @param string $label : label de l'élément
		 * @param PDO $bdd : connexion a une base de données PDO
		 * @param string $query : requête sous la forme '[select * from machin where id = ? AND id2 = ?] [''.$var1.'', ''.$var2.'']'
		 * @param string $name : nom donné au groupe de bouton radio
		 * @param string $value : valeur donné au bouton radio : nom d'une des colonnes retournées par la requête sql
		 * @param string $checked : valeur en rapport avec $value du bouton pour qu'il soit coché
		 * @param array $attribute : tous les attributs que l'on peut rajouter à l'input
		 * @param int $br : nombre de sauts de lignes
		 * @return void
		 * @since 2.0
		*/
		
		public function addRadio_sql($fieldset, $bdd, $query="", $label="",  $name="", $value="", $checked="", $attribute = array(), $br=0){
			if (in_array($fieldset, $this->GCfieldset)) {
				$element= new RadioGC();
				array_push($this->GCelements, $element->showRadio_sql($bdd, $query, $label, $name, $value, $checked, $attribute, $br).'label['.$fieldset.']');
				unset($element);
			}
		}
		
		/**
		 * liste déroulante - nombre
		 * @access public
		 * @param string $fieldset : nom du fieldset qui doit contenir l'élement
		 * @param string $label : label de l'élément
		 * @param array $attribute : tous les attributs que l'on peut rajouter à l'input
		 * @param int $begin : valeur de départ de la liste
		 * @param int $end : valeur de fin de la liste
		 * @param int $pas : différence entre 2 nombres
		 * @param array selected : liste des valeurs sélectionnées par défaut
		 * @param int $br : nombre de sauts de lignes
		 * @return void
		 * @since 2.0
		*/

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

		/**
		 * liste déroulante - date en 3 champs : jours / mois / années
		 * @access public
		 * @param string $fieldset : nom du fieldset qui doit contenir l'élement
		 * @param string $label : label de l'élément
		 * @param array $attribute : tous les attributs que l'on peut rajouter à l'input
		 * @param array $attribute : tous les attributs que l'on peut rajouter à l'input
		 * @param string $date : jj-mm-aaaa (j ou jj, m oui mm)
		 * @param int $br : nombre de sauts de lignes
		 * @return void
		 * @since 2.0
		*/
		
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

		/**
		 * liste déroulante - date en 6 champs : jours / mois / années / heures / minutes / secondes
		 * @access public
		 * @param string $fieldset : nom du fieldset qui doit contenir l'élement
		 * @param string $label : label de l'élément
		 * @param array $attribute : tous les attributs que l'on peut rajouter à l'input
		 * @param array $attribute : tous les attributs que l'on peut rajouter à l'input
		 * @param string $date : jj-mm-aaaa (j ou jj, m oui mm)
		 * @param int $br : nombre de sauts de lignes
		 * @return void
		 * @since 2.0
		*/
		
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

		/**
		 * liste déroulante statique avec groupe de données
		 * @access public
		 * @param string $fieldset : nom du fieldset qui doit contenir l'élement
		 * @param string $label : label de l'élément
		 * @param array $attribute : tous les attributs que l'on peut rajouter à l'input
		 * @param array $value : tous les éléments qui seront affichés
		 * @param array $content_value : la valeur tous les éléments qui seront affichés+
		 * @param array $group : la liste des différents groupes
		 * @param array array $group_content : [index_group][index_value]
		 * @param array $selected : liste des options sélectionnées
		 * @param int $br : nombre de sauts de lignes
		 * @return void
		 * @since 2.0
		*/
		
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

		/**
		 * liste déroulante statique
		 * @access public
		 * @param string $fieldset : nom du fieldset qui doit contenir l'élement
		 * @param string $label : label de l'élément
		 * @param array $attribute : tous les attributs que l'on peut rajouter à l'input
		 * @param array $value : tous les éléments qui seront affichés
		 * @param array $content_value : la valeur tous les éléments qui seront affichés
		 * @param array $selected : liste des options sélectionnées
		 * @param int $br : nombre de sauts de lignes
		 * @return void
		 * @since 2.0
		*/
		
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

		/**
		 * liste déroulante sql
		 * @access public
		 * @param string $fieldset : nom du fieldset qui doit contenir l'élement
		 * @param string $label : label de l'élément
		 * @param PDO $bdd : connexion a une base de données PDO
		 * @param string $query : requête sous la forme '[select * from machin where id = ? AND id2 = ?] [''.$var1.'', ''.$var2.'']'
		 * @param string $value : valeur donnée aux options : nom d'une des colonnes retournées par la requête sql
		 * @param string $content_value : valeur affichée par les options : nom d'une des colonnes retournées par la requête sql
		 * @param array $attribute : tous les attributs que l'on peut rajouter à l'input
		 * @param array $selected : liste des options sélectionnées
		 * @param int $br : nombre de sauts de lignes
		 * @return void
		 * @since 2.0
		*/
		
		public function addListebox_sql($fieldset, $label="", $bdd, $query="", $value="", $content_value="", $attribute = array(), $selected=array(), $br=0){
			if (in_array($fieldset, $this->GCfieldset)) {
				$element= new ListeboxGC_sql();
				array_push($this->GCelements, $element->showListeBox_sql($label, $bdd, $query, $value, $content_value, $attribute, $selected, $br).'label['.$fieldset.']');
				unset($element);
			}
		}

		/**
		 * liste déroulante sql - groupe
		 * @access public
		 * @param string $fieldset : nom du fieldset qui doit contenir l'élement
		 * @param string $label : label de l'élément
		 * @param PDO $bdd : connexion a une base de données PDO
		 * @param string $query1 : requête ramenant les groupes sous la forme '[select * from machin where id = ? AND id2 = ?] [''.$var1.'', ''.$var2.'']'
		 * @param string $query2 : requête ramenant les options sous la forme '[select * from machin where id = ? AND id2 = ?] [''.$var1.'', ''.$var2.'']'
		 * @param string $optgroup_value : nom de la colonne sql de la table contenant les groupes qui contient le nom des groupes (label)
		 * @param string $optgroup_content : nom de la colonne sql de la table contenant les groupes qui sert de clé
		 * @param string $option_optgroup_content : nom de la colonne de la table contenant les options qui sert de clé
		 * @param string $option_value : nom de la colonne de la table contenant les options qui contient la valeur des options
		 * @param string $option_content_value : nom de la colonne de la table contenant les options qui contient la valeur affichée des options
		 * @param array $attribute : tous les attributs que l'on peut rajouter à l'input
		 * @param array $selected : liste des options sélectionnées
		 * @param int $br : nombre de sauts de lignes
		 * @return void
		 * @since 2.0
		*/
		
		public function addListebox_sql_group($fieldset, $label="", $bdd, $query1="", $query2="", $optgroup_value="", $optgroup_content="", $option_optgroup_content="", $option_value="", $option_content_value="", $attribute = array(), $selected=array(), $br=0){
			if (in_array($fieldset, $this->GCfieldset)) {
				$element= new ListeboxGC_sql();
				array_push($this->GCelements, $element->showListeBox_sql_group($label, $bdd, $query1, $query2, $optgroup_value, $optgroup_content, $option_optgroup_content, $option_value, $option_content_value, $attribute, $selected, $br).'label['.$fieldset.']');
				unset($element);
			}
		}

		/**
		 * affiche un bouton
		 * @access public
		 * @param int $type : type de bouton
		 * @param array $attribute : tous les attributs que l'on peut rajouter à l'input
		 * @param int $br : nombre de sauts de lignes
		 * @return void
		 * @since 2.0
		*/
		
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

		/**
		 * affiche le formulaire
		 * @access public
		 * @return string
		 * @since 2.0
		*/
		
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

			/* champs cache avec name */
			$this->GCcontent.="    <input type=\"hidden\" name=\"".$this->GCname."\" />\n";

			/* champs cache avec token */
			if($this->GCtoken == true){
				$this->GCcontent.="    <input type=\"hidden\" name=\"".$this->GCtokenName."\" value=\"".$this->GCtokenValue."\" />\n";
			}
			
			foreach($this->GCelements as $element){
				if (preg_match('#submit\[\]#', $element)) {
					$element= preg_replace('#submit\[\]#isU', '', $element);
					$this->GCcontent.="  ".$element."\n";
				}
			}
			
			$this->GCcontent.="</form>";
			
			return $this->GCcontent;
		}
		
		/**
		 * affiche le formulaire PHPiser
		 * @access public
		 * @return string
		 * @since 2.0
		*/

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
				$echo .="\n";
				$i++;
			}

			$echo .='name => '.$this->GCname;
			$echo .="\n";
			$echo .="\n";
			$echo .='token => '.$this->GCtoken;

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
		
		public function showInputText($label="", $type="", $attribute = array(), $br=0){
			if($label!="") $this->GCtypeInput.="<label>".$label."</label>";
			switch($type){
				case 'hidden':
					if(isset($_POST[''.$attribute['name'].'']) && $_POST[''.$attribute['name'].''] != ''){ $this->GCtypeInput.="<input type=\"".$type."\" value=\"".@$_POST[''.$attribute['name'].'']."\""; }
					elseif(isset($_GET[''.$attribute['name'].'']) && $_GET[''.$attribute['name'].''] != ''){ $this->GCtypeInput.="<input type=\"".$type."\" value=\"".@$_GET[''.$attribute['name'].'']."\""; }
					elseif(isset($attribute['value']) && $attribute['value'] != ''){ $this->GCtypeInput.="<input type=\"".$type."\" value=\"".$attribute['value']."\" "; }
					else{ $this->GCtypeInput.="<input type=\"".$type."\" value=\"\" "; }
				break;
				
				case 'password':
					if(isset($_POST[''.$attribute['name'].'']) && $_POST[''.$attribute['name'].''] != ''){ $this->GCtypeInput.="<input type=\"".$type."\""; }
					elseif(isset($_GET[''.$attribute['name'].'']) && $_GET[''.$attribute['name'].''] != ''){ $this->GCtypeInput.="<input type=\"".$type."\""; }
					elseif(isset($attribute['value']) && $attribute['value'] != ''){ $this->GCtypeInput.="<input type=\"".$type."\" value=\"".$attribute['value']."\" "; }
					else{ $this->GCtypeInput.="<input type=\"".$type."\" value=\"\" "; }
				break;

				case 'text':
					if(isset($_POST[''.$attribute['name'].'']) && $_POST[''.$attribute['name'].''] != ''){ $this->GCtypeInput.="<input type=\"".$type."\" value=\"".@$_POST[''.$attribute['name'].'']."\""; }
					elseif(isset($_GET[''.$attribute['name'].'']) && $_GET[''.$attribute['name'].''] != ''){ $this->GCtypeInput.="<input type=\"".$type."\" value=\"".@$_GET[''.$attribute['name'].'']."\""; }
					elseif(isset($attribute['value']) && $attribute['value'] != ''){ $this->GCtypeInput.="<input type=\"".$type."\" value=\"".$attribute['value']."\" "; }
					else{ $this->GCtypeInput.="<input type=\"".$type."\" value=\"\" "; }
				break;
				
				case 'email':
					if(isset($_POST[''.$attribute['name'].'']) && $_POST[''.$attribute['name'].''] != ''){ $this->GCtypeInput.="<input type=\"".$type."\" value=\"".@$_POST[''.$attribute['name'].'']."\""; }
					elseif(isset($_GET[''.$attribute['name'].'']) && $_GET[''.$attribute['name'].''] != ''){ $this->GCtypeInput.="<input type=\"".$type."\" value=\"".@$_GET[''.$attribute['name'].'']."\""; }
					elseif(isset($attribute['value']) && $attribute['value'] != ''){ $this->GCtypeInput.="<input type=\"".$type."\" value=\"".$attribute['value']."\" "; }
					else{ $this->GCtypeInput.="<input type=\"".$type."\" value=\"\" "; }
				break;
				
				case 'date':
					if(isset($_POST[''.$attribute['name'].'']) && $_POST[''.$attribute['name'].''] != ''){ $this->GCtypeInput.="<input type=\"".$type."\" value=\"".@$_POST[''.$attribute['name'].'']."\""; }
					elseif(isset($_GET[''.$attribute['name'].'']) && $_GET[''.$attribute['name'].''] != ''){ $this->GCtypeInput.="<input type=\"".$type."\" value=\"".@$_GET[''.$attribute['name'].'']."\""; }
					elseif(isset($attribute['value']) && $attribute['value'] != ''){ $this->GCtypeInput.="<input type=\"".$type."\" value=\"".$attribute['value']."\" "; }
					else{ $this->GCtypeInput.="<input type=\"".$type."\" value=\"\" "; }
				break;
				
				case 'datetime':
					if(isset($_POST[''.$attribute['name'].'']) && $_POST[''.$attribute['name'].''] != ''){ $this->GCtypeInput.="<input type=\"".$type."\" value=\"".@$_POST[''.$attribute['name'].'']."\""; }
					elseif(isset($_GET[''.$attribute['name'].'']) && $_GET[''.$attribute['name'].''] != ''){ $this->GCtypeInput.="<input type=\"".$type."\" value=\"".@$_GET[''.$attribute['name'].'']."\""; }
					elseif(isset($attribute['value']) && $attribute['value'] != ''){ $this->GCtypeInput.="<input type=\"".$type."\" value=\"".$attribute['value']."\" "; }
					else{ $this->GCtypeInput.="<input type=\"".$type."\" value=\"\" "; }
				break;
				
				case 'month':
					if(isset($_POST[''.$attribute['name'].'']) && $_POST[''.$attribute['name'].''] != ''){ $this->GCtypeInput.="<input type=\"".$type."\" value=\"".@$_POST[''.$attribute['name'].'']."\""; }
					elseif(isset($_GET[''.$attribute['name'].'']) && $_GET[''.$attribute['name'].''] != ''){ $this->GCtypeInput.="<input type=\"".$type."\" value=\"".@$_GET[''.$attribute['name'].'']."\""; }
					elseif(isset($attribute['value']) && $attribute['value'] != ''){ $this->GCtypeInput.="<input type=\"".$type."\" value=\"".$attribute['value']."\" "; }
					else{ $this->GCtypeInput.="<input type=\"".$type."\" value=\"\" "; }
				break;
				
				case 'number':
					if(isset($_POST[''.$attribute['name'].'']) && $_POST[''.$attribute['name'].''] != ''){ $this->GCtypeInput.="<input type=\"".$type."\" value=\"".@$_POST[''.$attribute['name'].'']."\""; }
					elseif(isset($_GET[''.$attribute['name'].'']) && $_GET[''.$attribute['name'].''] != ''){ $this->GCtypeInput.="<input type=\"".$type."\" value=\"".@$_GET[''.$attribute['name'].'']."\""; }
					elseif(isset($attribute['value']) && $attribute['value'] != ''){ $this->GCtypeInput.="<input type=\"".$type."\" value=\"".$attribute['value']."\" "; }
					else{ $this->GCtypeInput.="<input type=\"".$type."\" value=\"\" "; }
				break;
				
				case 'range':
					if(isset($_POST[''.$attribute['name'].'']) && $_POST[''.$attribute['name'].''] != ''){ $this->GCtypeInput.="<input type=\"".$type."\" value=\"".@$_POST[''.$attribute['name'].'']."\""; }
					elseif(isset($_GET[''.$attribute['name'].'']) && $_GET[''.$attribute['name'].''] != ''){ $this->GCtypeInput.="<input type=\"".$type."\" value=\"".@$_GET[''.$attribute['name'].'']."\""; }
					elseif(isset($attribute['value']) && $attribute['value'] != ''){ $this->GCtypeInput.="<input type=\"".$type."\" value=\"".$attribute['value']."\" "; }
					else{ $this->GCtypeInput.="<input type=\"".$type."\" value=\"\" "; }
				break;
				
				case 'search':
					if(isset($_POST[''.$attribute['name'].'']) && $_POST[''.$attribute['name'].''] != ''){ $this->GCtypeInput.="<input type=\"".$type."\" value=\"".@$_POST[''.$attribute['name'].'']."\""; }
					elseif(isset($_GET[''.$attribute['name'].'']) && $_GET[''.$attribute['name'].''] != ''){ $this->GCtypeInput.="<input type=\"".$type."\" value=\"".@$_GET[''.$attribute['name'].'']."\""; }
					elseif(isset($attribute['value']) && $attribute['value'] != ''){ $this->GCtypeInput.="<input type=\"".$type."\" value=\"".$attribute['value']."\" "; }
					else{ $this->GCtypeInput.="<input type=\"".$type."\" value=\"\" "; }
				break;
				
				case 'tel':
					if(isset($_POST[''.$attribute['name'].'']) && $_POST[''.$attribute['name'].''] != ''){ $this->GCtypeInput.="<input type=\"".$type."\" value=\"".@$_POST[''.$attribute['name'].'']."\""; }
					elseif(isset($_GET[''.$attribute['name'].'']) && $_GET[''.$attribute['name'].''] != ''){ $this->GCtypeInput.="<input type=\"".$type."\" value=\"".@$_GET[''.$attribute['name'].'']."\""; }
					elseif(isset($attribute['value']) && $attribute['value'] != ''){ $this->GCtypeInput.="<input type=\"".$type."\" value=\"".$attribute['value']."\" "; }
					else{ $this->GCtypeInput.="<input type=\"".$type."\" value=\"\" "; }
				break;
				
				case 'time':
					if(isset($_POST[''.$attribute['name'].'']) && $_POST[''.$attribute['name'].''] != ''){ $this->GCtypeInput.="<input type=\"".$type."\" value=\"".@$_POST[''.$attribute['name'].'']."\""; }
					elseif(isset($_GET[''.$attribute['name'].'']) && $_GET[''.$attribute['name'].''] != ''){ $this->GCtypeInput.="<input type=\"".$type."\" value=\"".@$_GET[''.$attribute['name'].'']."\""; }
					elseif(isset($attribute['value']) && $attribute['value'] != ''){ $this->GCtypeInput.="<input type=\"".$type."\" value=\"".$attribute['value']."\" "; }
					else{ $this->GCtypeInput.="<input type=\"".$type."\" value=\"\" "; }
				break;
				
				case 'url':
					if(empty($attribute['value'])){
						if(isset($_POST[''.$attribute['name'].'']) && $_POST[''.$attribute['name'].''] != ''){ $this->GCtypeInput.="<input type=\"".$type."\" value=\"".@$_POST[''.$attribute['name'].'']."\""; }
						elseif(isset($_GET[''.$attribute['name'].'']) && $_GET[''.$attribute['name'].''] != ''){ $this->GCtypeInput.="<input type=\"".$type."\" value=\"".@$_GET[''.$attribute['name'].'']."\""; }
						elseif(isset($attribute['value']) && $attribute['value'] != ''){ $this->GCtypeInput.="<input type=\"".$type."\" value=\"".$attribute['value']."\" "; }
						else{ $this->GCtypeInput.="<input type=\"".$type."\" value=\"\" "; }
					}
					else{
						$this->GCtypeInput.="<input type=\"".$type."\" value=\"".$attribute['value']."\"";
					}
				break;
				
				case 'week':
					if(isset($_POST[''.$attribute['name'].'']) && $_POST[''.$attribute['name'].''] != ''){ $this->GCtypeInput.="<input type=\"".$type."\" value=\"".@$_POST[''.$attribute['name'].'']."\""; }
					elseif(isset($_GET[''.$attribute['name'].'']) && $_GET[''.$attribute['name'].''] != ''){ $this->GCtypeInput.="<input type=\"".$type."\" value=\"".@$_GET[''.$attribute['name'].'']."\""; }
					elseif(isset($attribute['value']) && $attribute['value'] != ''){ $this->GCtypeInput.="<input type=\"".$type."\" value=\"".$attribute['value']."\" "; }
					else{ $this->GCtypeInput.="<input type=\"".$type."\" value=\"\" "; }
				break;
				
				case 'datetime-local':
					if(isset($_POST[''.$attribute['name'].'']) && $_POST[''.$attribute['name'].''] != ''){ $this->GCtypeInput.="<input type=\"".$type."\" value=\"".@$_POST[''.$attribute['name'].'']."\""; }
					elseif(isset($_GET[''.$attribute['name'].'']) && $_GET[''.$attribute['name'].''] != ''){ $this->GCtypeInput.="<input type=\"".$type."\" value=\"".@$_GET[''.$attribute['name'].'']."\""; }
					elseif(isset($attribute['value']) && $attribute['value'] != ''){ $this->GCtypeInput.="<input type=\"".$type."\" value=\"".$attribute['value']."\" "; }
					else{ $this->GCtypeInput.="<input type=\"".$type."\" value=\"\" "; }
				break;
				
				default:
					if(isset($_POST[''.$attribute['name'].'']) && $_POST[''.$attribute['name'].''] != ''){ $this->GCtypeInput.="<input type=\"".$type."\" value=\"".@$_POST[''.$attribute['name'].'']."\""; }
					elseif(isset($_GET[''.$attribute['name'].'']) && $_GET[''.$attribute['name'].''] != ''){ $this->GCtypeInput.="<input type=\"".$type."\" value=\"".@$_GET[''.$attribute['name'].'']."\""; }
					elseif(isset($attribute['value']) && $attribute['value'] != ''){ $this->GCtypeInput.="<input type=\"".$type."\" value=\"".$attribute['value']."\" "; }
					else{ $this->GCtypeInput.="<input type=\"".$type."\" value=\"\" "; }
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

				case 'submit':
					$this->GCtypeButton.="<input type=\"".$type."\" ";
				break;

				case 'reset':
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
	
	class FilesGc {
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

			if (isset($_POST[''.$attribute['name'].'']) && $_POST[''.$attribute['name'].''] !='') $this->GCtypeTextarea.=@$_POST[''.$attribute['name'].''];
			elseif (isset($_GET[''.$attribute['name'].'']) && $_GET[''.$attribute['name'].''] !='') $this->GCtypeTextarea.=@$_GET[''.$attribute['name'].''];
			else if($value!="") $this->GCtypeTextarea.=$value;
			else $this->GCtypeTextarea.='';
			
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
			$sql= preg_replace('#\[(.*)\] \[(.*)\]#isU', '$1', $query); 
			$vars = preg_replace('#\[(.*)\] \[(.*)\]#isU', '$2', $query);
			$var = explode(',', $vars);
			$this->query = $bdd->prepare(''.$sql.'');

			foreach ($var as $key => $values) {
				$this->query->bindParam(1+$key, trim($values));
			}

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
					if((count($group_content)-1)>=$this->j){ //on verifie que toutes les options sont bien dans des groupes
						if($group_content[$this->j]==$this->i){ //l'option est contenue dans ce groupe
							if(!in_array($this->j, $selected)){
								$this->GCtypeListebox.="          <option value=\"".$value[$this->j]."\">".$content_value[$this->j]."</option>\n"; 
							}
							else {
								$this->GCtypeListebox.="          <option value=\"".$value[$this->j]."\" selected=\"selected\">".$content_value[$this->j]."</option>\n";
							}

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
		private $query2;
		private $data2;
		
		/* ---------- CONSTRUCTEURS --------- */
		
		public  function __construct(){
		}
		
		public function showListeBox_sql($label="", $bdd, $query="", $value="", $content_value="", $attribute = array(), $selected=array(), $br=0){
			$sql= preg_replace('#\[(.*)\] \[(.*)\]#isU', '$1', $query); 
			$vars = preg_replace('#\[(.*)\] \[(.*)\]#isU', '$2', $query);
			$var = explode(',', $vars);
			$this->query = $bdd->prepare(''.$sql.'');

			foreach ($var as $key => $values) {
				$this->query->bindParam(1+$key, trim($values));
			}

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
		
		public function showListeBox_sql_group($label="", $bdd, $query1="", $query2="", $optgroup_value="", $optgroup_content="",  $option_optgroup_content="", $option_value="", $option_content_value="", $attribute = array(), $selected=array(), $br=0){
			/* ### requete 1 : optgroup ### */
			$sql1= preg_replace('#\[(.*)\] \[(.*)\]#isU', '$1', $query1); 
			$vars1 = preg_replace('#\[(.*)\] \[(.*)\]#isU', '$2', $query1);
			$var1 = explode(',', $vars1);
			$this->query1 = $bdd->prepare(''.$sql1.'');

			foreach ($var1 as $key1 => $values1) {
				$this->query1->bindParam(1+$key1, trim($values1));
			}

			$this->query1->execute();
			
			if($label!="") $this->GCtypeListebox.="<label>".$label."</label>\n";
			$this->GCtypeListebox.="      <select ";
			foreach($attribute as $attribut => $valeur){
				$this->GCtypeListebox.=$attribut."=\"".$valeur."\" ";
			}
			$this->GCtypeListebox.=">\n";
			
			while($this->data=$this->query1->fetch()){
				$this->GCtypeListebox.="        <optgroup label=\"".$this->data[$optgroup_value]."\">\n";


				/* ### requete 2 : option ### */
				$sql2= preg_replace('#\[(.*)\] \[(.*)\]#isU', '$1', $query2); 
				$vars2 = preg_replace('#\[(.*)\] \[(.*)\]#isU', '$2', $query2);
				$var2 = explode(',', $vars2);

				if(preg_match('#WHERE#', $sql2)){
					$sql2 = preg_replace('#WHERE#isU','WHERE '.$option_optgroup_content.' = '.$this->data[$optgroup_content].' AND ',$sql2);
					$this->query2=$bdd->prepare($sql2);
				}
				else{
					$this->query2=$bdd->prepare($sql2.' WHERE '.$option_optgroup_content.' = '.$this->data[$optgroup_content]);
				}

				foreach ($var2 as $key2 => $values2) {
					$this->query2->bindParam(1+$key2, trim($values2));
				}

				$this->query2->execute();
				
				while($this->data2=$this->query2->fetch()){
					if($this->data[$optgroup_content]==$this->data2[$option_optgroup_content]){
						if(!in_array($this->data2[$option_value], $selected)) $this->GCtypeListebox.="        <option value=\"".$this->data2[$option_value]."\">".$this->data2[$option_content_value]."</option>\n"; else $this->GCtypeListebox.="        <option value=\"".$this->data2[$option_value]."\" selected=\"selected\">".$this->data2[$option_content_value]."</option>\n";
					}
				}
				/* ### requete 2 : option ### */

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