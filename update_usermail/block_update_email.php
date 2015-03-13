<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * @package   block_mail_update
 * @copyright 1999 onwards Martin Dougiamas (http://dougiamas.com)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

class block_mail_update extends block_base {

    function init() {
        $this->title = get_string('pluginname', 'block_mail_update');
    }

    function has_config() {
        return true;
    }

    function applicable_formats() {
        return array('all' => true);
    }

    function specialization() {
        $this->title = isset($this->config->title) ? format_string($this->config->title) : format_string(get_string('newhtmlblock', 'block_mail_update'));
    }

    function instance_allow_multiple() {
        return true;
    }
    
    // all block content
    public function get_content_for_output($output) {
        global $CFG, $USER, $DB;

        $bc = new block_contents($this->html_attributes());
        $bc->attributes['data-block'] = $this->name();
        $bc->blockinstanceid = $this->instance->id;
        $bc->blockpositionid = $this->instance->blockpositionid;

        if ($this->instance->visible) {
            $bc->content = $this->formatted_contents($output);
            if (!empty($this->content->footer)) {
                $bc->footer = $this->content->footer;
            }
        } else {
            $bc->add_class('invisible');
        }

        if (!$this->hide_header()) {
            $bc->title = $this->title;
        }

        if (empty($bc->title)) {
            $bc->arialabel = new lang_string('pluginname', get_class($this));
            $this->arialabel = $bc->arialabel;
        }

        if ($this->page->user_is_editing()) {
            $bc->controls = $this->page->blocks->edit_controls($this);
        } else {
            // we must not use is_empty on hidden blocks
            if ($this->is_empty() && !$bc->controls) {
                return null;
            }
        }

        if (empty($CFG->allowuserblockhiding)
                || (empty($bc->content) && empty($bc->footer))
                || !$this->instance_can_be_collapsed()) {
            $bc->collapsible = block_contents::NOT_HIDEABLE;
        } else if (get_user_preferences('block' . $bc->blockinstanceid . 'hidden', false)) {
            $bc->collapsible = block_contents::HIDDEN;
        } else {
            $bc->collapsible = block_contents::VISIBLE;
        }

        if ($this->instance_can_be_docked() && !$this->hide_header()) {
            $bc->dockable = true;
        }

        $bc->annotation = ''; // TODO MDL-19398 need to work out what to say here.
     
     
     
     
     
     $results = $DB->get_records_sql('SELECT DISTINCT * FROM {html_onetime} WHERE userid = :userid AND blockid= :blockid', array("userid"=>$USER->id, "blockid"=> $this->instance->id));
foreach($results as $result){
$quant = $result->quant;
}
    
     
    if ($DB->record_exists("html_onetime", array("userid"=>$USER->id, "blockid"=> $this->instance->id)) && !is_siteadmin()){
       $bc = "";
       } 
       
       if(!isloggedin()){
       $bc = "";
       }
       
       if ($quant > 2 ){
       $bc = "";
       }
       
       return $bc;
      
    }
     
         
     
     // inside of block
    function get_content() {
    //moodleform is defined in formslib.php
     
        global $CFG, $USER, $DB;

        require_once($CFG->libdir . '/filelib.php');
        $atual_url = new moodle_url('/blocks/update_email/img/OkAtualizado.png');
        $decepcionado_url = new moodle_url('/blocks/update_email/img/decepcionado.png');
        $atualize_url = new moodle_url('/blocks/update_email/img/Atualize.png');
         
        if ($this->content !== NULL) {
        //    return $this->content;
        }

        $filteropt = new stdClass;
        $filteropt->overflowdiv = true;
        if ($this->content_is_trusted()) {
            // fancy html allowed only on course, category and system blocks.
            $filteropt->noclean = true;
        }

        $this->content = new stdClass;
       // $this->content->text = '';
        $this->content->footer = '';
     
      if (isset($this->config->text)) {
            // rewrite url
            $this->config->text = file_rewrite_pluginfile_urls($this->config->text, 'pluginfile.php', $this->context->id, 'block_mail_update', 'content', NULL);
            // Default to FORMAT_HTML which is what will have been used before the
            // editor was properly implemented for the block.
            $format = FORMAT_HTML;
            // Check to see if the format has been properly set on the config
            if (isset($this->config->format)) {
                $format = $this->config->format;
            }
            
          
            
            $this->content->text = format_text($this->config->text, $format, $filteropt);
          
        } else {
            $this->content->text = '';
        } 
        
        $this->content->text .= html_writer::start_tag('div',array("class"=>"block_update_email"));
        
       $mform = new simplehtml_form();

       //Form processing and displaying is done here
       
            
       if ($mform->is_cancelled()) {
           //Handle form cancel operation, if cancel button is present on form
       } else if ($fromform = $mform->get_data() ) {
         //In this case you process validated data. $mform->get_data() returns data posted in form.
         // instantiate the class  
         $admin = get_admin();
         $sender = $admin->email;  
       // instantiate the class  
        $SMTP_Valid = new SMTP_validateEmail();  
       // do the validation  
        $result = $SMTP_Valid->validate($fromform->email, $sender);
        if ($result){
       
         $record = new stdClass();
         $record->userid         = $USER->id;
         $record->blockid        = $this->instance->id;
         $record->timemodified   = strtotime('now');
         $record->quant          = 1;
         
         $lastinsertid = $DB->insert_record('update_email', $record, false);
         $sql = "UPDATE {user} SET email = ? WHERE id = ?";
         $DB->execute($sql, array($fromform->email, $USER->id));
         
         
         
                  $this->content->text .= '<img src="'.$atual_url.'" width="150" height="391" style="display: block; margin-left: auto; margin-right: auto;" id="yui_3_13_0_2_1426254301126_1624">';
        } else {
                            $this->content->text .= '<img src="'.$decepcionado_url.'" width="150" height="391" style="display: block; margin-left: auto; margin-right: auto;" id="yui_3_13_0_2_1426254301126_1624">';

         //displays the form
                  $this->content->text .= '<script src="https://code.jquery.com/jquery-1.9.1.min.js"></script><script >$(document).ready(function(){ $("#mform1").attr("action", document.URL)}); </script>';

         $this->content->text .= $mform->render();
        }
        
       } else {
         // this branch is executed if the form is submitted but the data doesn't validate and the form should be redisplayed
         // or on the first display of the form.
         if ($DB->record_exists("html_onetime", array("userid"=>$USER->id, "blockid"=> $this->instance->id))) {
          $record = new stdClass();
         $record->userid         = $USER->id;
         $record->blockid        = $this->instance->id;
         $record->timemodified   = strtotime('now');
         
         $sql = "UPDATE {html_onetime} SET timemodified = ?, quant = quant+1 WHERE userid = ? AND blockid = ?";
         $DB->execute($sql, array($record->timemodified ,$record->userid,$record->blockid ));
                  $this->content->text .= '<img src="'.$atual_url.'" width="150" height="391" style="display: block; margin-left: auto; margin-right: auto;" id="yui_3_13_0_2_1426254301126_1624">';

         } else{
         //Set default data (if any)
        // $mform->set_data($toform);
        
         $this->content->text .= '<img src="'.$atualize_url.'" width="150" height="391" style="display: block; margin-left: auto; margin-right: auto;" id="yui_3_13_0_2_1426254301126_1624">';
         $this->content->text .= html_writer::start_span('label_atualizaemail') . "Este é seu e-mail?<br />" . html_writer::end_span();
							  $this->content->text .= html_writer::start_span('email_atual') . $USER->email . html_writer::end_span();
          $this->content->text .= html_writer::start_span('label_atualizaemail') . "<br /><br />Se estiver correto, digite-o novamente para confirmar.<br /><br />

Se estiver errado, substitua agora por um novo e-mail válido.       
." . html_writer::end_span();
         //displays the form
         
         $this->content->text .= '<style>.mform .fitem .felement {  margin-left: 0px !important; }</style><script src="https://code.jquery.com/jquery-1.9.1.min.js"></script><script >$(document).ready(function(){ $("#mform1").attr("action", document.URL)}); </script>';
         $this->content->text .= $mform->render();
         }
       }

        $this->content->text .= html_writer::end_tag('div');
        unset($filteropt); // memory footprint

        return $this->content;
    }


    /**
     * Serialize and store config data
     */
    function instance_config_save($data, $nolongerused = false) {
        global $DB;

        $config = clone($data);
        // Move embedded files into a proper filearea and adjust HTML links to match
        $config->text = file_save_draft_area_files($data->text['itemid'], $this->context->id, 'block_mail_update', 'content', 0, array('subdirs'=>true), $data->text['text']);
        $config->format = $data->text['format'];

        parent::instance_config_save($config, $nolongerused);
    }

    function instance_delete() {
        global $DB;
        $fs = get_file_storage();
        $fs->delete_area_files($this->context->id, 'block_mail_update');
        return true;
    }

    function content_is_trusted() {
        global $SCRIPT;

        if (!$context = context::instance_by_id($this->instance->parentcontextid, IGNORE_MISSING)) {
            return false;
        }
        //find out if this block is on the profile page
        if ($context->contextlevel == CONTEXT_USER) {
            if ($SCRIPT === '/my/index.php') {
                // this is exception - page is completely private, nobody else may see content there
                // that is why we allow JS here
                return true;
            } else {
                // no JS on public personal pages, it would be a big security issue
                return false;
            }
        }

        return true;
    }

    /**
     * The block should only be dockable when the title of the block is not empty
     * and when parent allows docking.
     *
     * @return bool
     */
    public function instance_can_be_docked() {
        return (!empty($this->config->title) && parent::instance_can_be_docked());
    }

    /*
     * Add custom html attributes to aid with theming and styling
     *
     * @return array
     */
    function html_attributes() {
        global $CFG;

        $attributes = parent::html_attributes();

        if (!empty($CFG->block_html_allowcssclasses)) {
            if (!empty($this->config->classes)) {
                $attributes['class'] .= ' '.$this->config->classes;
            }
        }

        return $attributes;
    }
}


require_once($CFG->libdir."/formslib.php");
 
class simplehtml_form extends moodleform {
 
  
    //Add elements to form
    public function definition() {
        global $CFG,$PAGE;
       
        $mform = $this->_form; // Don't forget the underscore! 
        // This will set the default value of the textfield to 'mydefaultstring'. (If $CFG->textfield 
        // exsts, it will be set to that when the form is loaded)
       
        
        $mform->addElement('text', 'email', '', array("placeholder"=> 'Atualizar Email')); // Add elements to your form
       // $mform->setType('email', PARAM_NOTAGS);                   //Set type of element
       // $mform->setDefault('email', 'Please enter email');        //Default value
        $mform->addElement('submit', 'enviar', get_string('submit')); 
     //   $mform->standard_coursemodule_elements();
 
      //  $mform->add_action_buttons();
    }
   
    //Custom validation should be added here
    function validation($data, $files) {
        return array();
    }
}


class SMTP_validateEmail {  
  
 /** 
  * PHP Socket resource to remote MTA 
  * @var resource $sock  
  */  
 var $sock;  
  
 /** 
  * Current User being validated 
  */  
 var $user;  
 /** 
  * Current domain where user is being validated 
  */  
 var $domain;  
 /** 
  * List of domains to validate users on 
  */  
 var $domains;  
 /** 
  * SMTP Port 
  */  
 var $port = 25;  
 /** 
  * Maximum Connection Time to an MTA  
  */  
 var $max_conn_time = 30;  
 /** 
  * Maximum time to read from socket 
  */  
 var $max_read_time = 5;  
   
 /** 
  * username of sender 
  */  
 var $from_user = 'user';  
 /** 
  * Host Name of sender 
  */  
 var $from_domain = 'localhost';  
   
 /** 
  * Nameservers to use when make DNS query for MX entries 
  * @var Array $nameservers  
  */  
 var $nameservers = array(  
 '10.1.1.14',
 '191.237.255.171'
);  
   
 var $debug = false;  
  
 /** 
  * Initializes the Class 
  * @return SMTP_validateEmail Instance 
  * @param $email Array[optional] List of Emails to Validate 
  * @param $sender String[optional] Email of validator 
  */  
 function SMTP_validateEmail($emails = false, $sender = false) {  
  if ($emails) {  
   $this->setEmails($emails);  
  }  
  if ($sender) {  
   $this->setSenderEmail($sender);  
  }  
 }  
   
 function _parseEmail($email) {  
  $parts = explode('@', $email);  
 $domain = array_pop($parts);  
 $user= implode('@', $parts);  
 return array($user, $domain);  
 }  
   
 /** 
  * Set the Emails to validate 
  * @param $emails Array List of Emails 
  */  
 function setEmails($emails) {  
 if (is_array($emails)){
  foreach($emails as $email) {  
  list($user, $domain) = $this->_parseEmail($email);  
  if (!isset($this->domains[$domain])) {  
    $this->domains[$domain] = array();  
  }  
  $this->domains[$domain][] = $user;  
 }  
 } else {
   list($user, $domain) = $this->_parseEmail($emails);  
  if (!isset($this->domains[$domain])) {  
    $this->domains[$domain] = array();  
  }  
  $this->domains[$domain][] = $user; 
 }
 }  
   
 /** 
  * Set the Email of the sender/validator 
  * @param $email String 
  */  
 function setSenderEmail($email) {  
 $parts = $this->_parseEmail($email);  
 $this->from_user = $parts[0];  
 $this->from_domain = $parts[1];  
 }  
   
 /** 
 * Validate Email Addresses 
 * @param String $emails Emails to validate (recipient emails) 
 * @param String $sender Sender's Email 
 * @return Array Associative List of Emails and their validation results 
 */  
 function validate($emails = false, $sender = false) {  
    
  $results = array();  
  
  if ($emails) {  
   $this->setEmails($emails);  
  }  
  if ($sender) {  
   $this->setSenderEmail($sender);  
  }  
  
  // query the MTAs on each Domain  
  foreach($this->domains as $domain=>$users) {  
     
  $mxs = array();  
    
   // retrieve SMTP Server via MX query on domain  
   list($hosts, $mxweights) = $this->queryMX($domain);  
  
   // retrieve MX priorities  
   for($n=0; $n < count($hosts); $n++){  
    $mxs[$hosts[$n]] = $mxweights[$n];  
   }  
   asort($mxs);  
   
   // last fallback is the original domain  
   array_push($mxs, $this->domain);  
     
   $this->debug(print_r($mxs, 1));  
     
   $timeout = $this->max_conn_time/count($hosts);  
      
   // try each host  
   while(list($host) = each($mxs)) {  
    // connect to SMTP server  
    $this->debug("try $host:$this->port\n");  
    if ($this->sock = @fsockopen($host, $this->port, $errno, $errstr, (float) $timeout)) {  
     stream_set_timeout($this->sock, $this->max_read_time);  
     break;  
    }  
   }  
    
   // did we get a TCP socket  
   if ($this->sock) {  
    $reply = fread($this->sock, 2082);  
    $this->debug("<<<\n$reply");  
      
    preg_match('/^([0-9]{3}) /ims', $reply, $matches);  
    $code = isset($matches[1]) ? $matches[1] : '';  
   
    if($code != '220') {  
     // MTA gave an error...  
     foreach($users as $user) {  
      $results[$user.'@'.$domain] = false;  
  }  
  continue;  
    }  
  
    // say helo  
    $this->send("HELO ".$this->from_domain);  
    // tell of sender  
    $this->send("MAIL FROM: <".$this->from_user.'@'.$this->from_domain.">");  
      
    // ask for each recepient on this domain  
    foreach($users as $user) {  
      
     // ask of recepient  
     $reply = $this->send("RCPT TO: <".$user.'@'.$domain.">");  
       
      // get code and msg from response  
     preg_match('/^([0-9]{3}) /ims', $reply, $matches);  
     $code = isset($matches[1]) ? $matches[1] : '';  
    
     if ($code == '250') {  
      // you received 250 so the email address was accepted  
      $results[$user.'@'.$domain] = true;  
     } elseif ($code == '451' || $code == '452') {  
   // you received 451 so the email address was greylisted (or some temporary error occured on the MTA) - so assume is ok  
   $results[$user.'@'.$domain] = true;  
     } else {  
      $results[$user.'@'.$domain] = false;  
     }  
      
    }  
      
    // quit  
    $this->send("quit");  
    // close socket  
    fclose($this->sock);  
     
   }  
  }  
 return $results;  
 }  
  
  
 function send($msg) {  
  fwrite($this->sock, $msg."\r\n");  
  
  $reply = fread($this->sock, 2082);  
  
  $this->debug(">>>\n$msg\n");  
  $this->debug("<<<\n$reply");  
    
  return $reply;  
 }  
   
 /** 
  * Query DNS server for MX entries 
  * @return  
  */  
 function queryMX($domain) {  
  $hosts = array();  
 $mxweights = array();  
  if (function_exists('getmxrr')) {  
   getmxrr($domain, $hosts, $mxweights);  
  } else {  
   // windows, we need Net_DNS  
  require_once 'Net/DNS.php';  
  
  $resolver = new Net_DNS_Resolver();  
  $resolver->debug = $this->debug;  
  // nameservers to query  
  $resolver->nameservers = $this->nameservers;  
  $resp = $resolver->query($domain, 'MX');  
  if ($resp) {  
   foreach($resp->answer as $answer) {  
    $hosts[] = $answer->exchange;  
    $mxweights[] = $answer->preference;  
   }  
  }  
    
  }  
 return array($hosts, $mxweights);  
 }  
   
 /** 
  * Simple function to replicate PHP 5 behaviour. http://php.net/microtime 
  */  
 function microtime_float() {  
  list($usec, $sec) = explode(" ", microtime());  
  return ((float)$usec + (float)$sec);  
 }  
  
 function debug($str) {  
  if ($this->debug) {  
   echo htmlentities($str);  
  }  
 }  
  
}  
  