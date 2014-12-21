<?php
	namespace entity{
	    class keeplove_user_user extends \system\entity{
            public function setTableDefinition(){
                $this->setTable('keeplove_user_user');
                $this->addColumn('user_id', array('autoincrement' => true,'primary' => true));
                $this->addColumn('user_username', array());
                $this->addColumn('user_email', array());
                $this->addColumn('user_password', array());
                $this->addColumn('user_token', array());
                $this->addColumn('user_enabled', array());
                $this->addColumn('user_profile_enabled', array());
                $this->addColumn('user_select_comment_discussion', array());
                $this->addColumn('user_name', array());
                $this->addColumn('user_firstname', array());
                $this->addColumn('user_last_login', array());
                $this->addColumn('user_time', array());
                $this->addColumn('user_role', array());
                $this->addColumn('user_suscribe_time', array());
                $this->addColumn('user_suscribe_time_action', array());
                $this->addColumn('user_coaching_time', array());
                $this->addColumn('user_suscribe_type', array());
                $this->addColumn('user_coaching_type', array());
                $this->addColumn('user_suscribe_paiement_auto', array());
                $this->addColumn('user_coaching_paimen_auto', array());
                $this->addColumn('user_avatar', array());
                $this->addColumn('user_country', array());
                $this->addColumn('user_city', array());
                $this->addColumn('user_birthday', array());
                $this->addColumn('user_gender', array());
                $this->addColumn('user_interested_by', array());
                $this->addColumn('user_video', array());
                $this->addColumn('user_figure', array());
                $this->addColumn('user_eye', array());
                $this->addColumn('user_hair', array());
                $this->addColumn('user_religion', array());
                $this->addColumn('user_study', array());
                $this->addColumn('user_job', array());
                $this->addColumn('user_height', array());
                $this->addColumn('user_weight', array());
                $this->addColumn('user_alcohol', array());
                $this->addColumn('user_smoke', array());
                $this->addColumn('user_marital', array());
                $this->addColumn('user_children', array());
                $this->addColumn('user_in_view', array());
                $this->addColumn('user_description', array());
                $this->addColumn('user_mail_present', array());
                $this->addColumn('user_mail_godfather', array());
                $this->addColumn('user_mail_partner', array());
            }
        }
    }