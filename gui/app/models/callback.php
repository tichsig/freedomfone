<?php
/****************************************************************************
 * callback.php         - Model for callback requests. Manages outgoing calls, and user limits.
 * version 		- 1.0.353
 * 
 * Version: MPL 1.1
 *
 * The contents of this file are subject to the Mozilla Public License Version
 * 1.1 (the "License"); you may not use this file except in compliance with
 * the License. You may obtain a copy of the License at
 * http://www.mozilla.org/MPL/
 *
 * Software distributed under the License is distributed on an "AS IS" basis,
 * WITHOUT WARRANTY OF ANY KIND, either express or implied. See the License
 * for the specific language governing rights and limitations under the
 * License.
 *
 *
 * The Initial Developer of the Original Code is
 *   Louise Berthilson <louise@it46.se>
 *
 *
 ***************************************************************************/

App::import('Core', 'HttpSocket');


class Callback extends AppModel{


      var $name = 'Callback';

      var $belongsTo = array(
      	  'Campaign' => array(
 	  	 'className' => 'Campaign',
 		 'foreignKey' => 'campaign_id'
 		 ));


function __construct($id = false, $table = null, $ds = null) {
        parent::__construct($id, $table, $ds);

       $this->validate = array(
                'extension' => array(
                            'rule' => 'notEmpty',
                            'message'  => __('You must select a service.',true),
                            ));

}


/*
 * Fetches new data from spooler
 *
 *
 */

    function refresh(){


      $array       = Configure::read('callback_in');
      $dialer       = Configure::read('DIALER');
      $application = 'callback_in';
      $type = 'IN';
      $obj         = new ff_event($array);	       
      $update      = 'count_callback'; 

       	   if ($obj -> auth != true) {
  	       	  die(printf("Unable to authenticate\r\n"));
           }

     	   while ($entry = $obj->getNext('update')){

              debug($entry);

	      $created  = floor($entry['Event-Date-Timestamp']/1000000);
	      $sender	= $this->sanitizePhoneNumber($entry['from']);
              $this->bindModel(array('hasMany' => array('User' => array('className' => 'User','foreignKey' => 'user_id'))));
              $userData = $this->User->PhoneNumber->find('first',array('conditions' => array('PhoneNumber.number' => $sender)));

              //** Update user information **//

              //If user exists in system: update statistics
              if ($userData){

                 debug($userData);
		 $count = $userData['User'][$update]+1;
                 $user_id = $userData['User']['id'];
                 $this->User->read(null, $user_id);
	         $this->User->set(array($update => $count,'last_app'=>$application,'last_epoch'=>time()));
                 $this->User->save();
               } 

               //If user does NOT exist in system: add user and phone number
               else {
                 $created = time();
                 $user =array('created'=> $created,'new'=>1,$update=>1,'first_app'=>$application,'first_epoch' => $created, 'last_app'=>$application,'last_epoch'=>$created,'acl_id'=>1,'name' => __('Callback SMS',true));
                 $this->User->create(); 
                if ($this->User->save($user)){

                       $user_id = $this->User->getLastInsertId();
                       debug($user);
                       debug($user_id);
                       $phonenumber = array('user_id' => $user_id, 'number' => $sender);
                       $this->User->PhoneNumber->saveAll($phonenumber);
                  }

                }

                //** Create Newfie contact (contact::write) **//
                $callback_service = $this->getCallbackService($entry['Body']);  
                $contact = array('phonebook_id' => $callback_service['dialer_id'], 'contact' =>  $sender);
                $HttpSocket = new HttpSocket();
                $request    = array('auth' => array('method' => 'Basic','user' => $dialer['user'],'pass' => $dialer['pwd']));
                
                $results = $HttpSocket->post($dialer['host'].$dialer['contact'], $contact, $request); 
                $results = json_decode($results);
                $header  = $HttpSocket->response['raw']['status-line'];

                if ($this->headerGetStatus($header) == 1) {

                  $callback['callback_service_id'] = $callback_service['id'];
                  $callback['job_id'] = '';
                  $callback['user_id'] = $user_id;
                  $callback['type'] = $type;
                  $callback['retries'] = 0; 
                  $callback['status'] = 1;
                  $callback['state'] = 1; 
                  $callback['phone_number'] = $sender;
                  $callback['last_attempts'] = false; 
                  $callback['epoch'] = $created;

                  debug($callback);
                  } else {

	            $this->log('ERROR Newfie contact::post FAILED', 'callback');		       
                  
                  }

            }

      }

/**
 * Check if the number of callbacks allowed (within a certain time limit) for a user has exceeded its maximum value or not
 * return true| false
 *
 */
	function withinLimit($sender){

		 $data = $this->query("select * from callback_settings limit 0,1");

		       if ($data[0]['callback_settings']['limit_time']==0 | $data[0]['callback_settings']['limit_user'] ==0 ){
	    	       	  return true;
			  }
		       else {

		       	$epoch_limit = time()-$data[0]['callback_settings']['limit_time']*3600;
			$user_limit  = $data[0]['callback_settings']['limit_user'];
			$response = $this->find('count', array('conditions' => array('Callback.sender' => $sender,'Callback.created >' => $epoch_limit,'Callback.status' =>'1')));

		    		  if($response >= $user_limit){
		        	  	       return false;
		     			       }
		     			       else {
		     			       return true;
		     	             }
		   }

	}



}

?>
