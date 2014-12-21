<gc:variable var ="<?php
	namespace entity{
	    class ".$class." extends \system\\entity{
            public function setTableDefinition(){
                \$this->setTable('".$class."');
".$column."            }
        }
    }" />
{var}