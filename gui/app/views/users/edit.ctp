<?php 
/****************************************************************************
 * edit.ctp	- Edits user data in phone book
 * version 	- 1.0.362
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


 if($data = $this->data){


     $button = $form->submit(__('Save',true),  array('name' =>'data[Submit]', 'class' => 'button'));

     echo "<fieldset>";

     echo "<h1>".__("Edit contact",true)."</h1>";

     echo "<div id='page'>";

     echo "</div>";

     echo $form->create('User',array('type' => 'post','action'=> 'edit'));
     echo $form->hidden('new',array('value'=>0));
     echo $form->hidden('id');
     echo $form->hidden('created');
     echo $form->hidden('first_app');
     echo $form->hidden('last_app');
     echo $form->hidden('count_bin');
     echo $form->hidden('count_poll');
     echo $form->hidden('count_lam');
     echo $form->hidden('count_ivr');
     echo $form->hidden('last_seen');
     echo $form->hidden('last_epoch');

     $skype = $form->input('skype',array('label'=>false));


     $last_seen = __('Never',true);
        if($value = $data['User']['last_epoch']) {
                  $last_seen = $value;
         }
   


     echo "<div class='frameLeft'>";

     $row[] = array(array($html->div('table_sub_header',__('User data',true)),array('colspan'=>2,'height'=>30,'valign'=>'bottom')));
     $row[] = array(__("Name",true),			$form->input('name',array('label'=>false)));
     $row[] = array(__("Surname",true),			$form->input('surname',array('label'=>false)));
     $row[] = array(__("Email",true),			$form->input('email',array('label'=>false)));
     $row[] = array(__("Skype",true),			$skype);
     $row[] = array(__("Organization",true),		$form->input('organization',array('label'=>false)));
     $row[] = array(__("ACL",true),			$form->input('acl_id',array('type'=>'select','options'=> $acls, 'label'=>false)));
     $row[] = array(__("Phone book",true),		$form->input('PhoneBook',array('type'=>'select','multiple' => true, 'options'=>$phonebook, 'empty'=>'- '.__('Select phone book',true).' -','label'=>false)));


     foreach($data['PhoneNumber'] as $key => $number){

$href = array( 'controller' => 'users', 'action' => 'disp/'.$data['User']['id'].'/'.$number['id'] );
$opt = array( 'update' => 'page' );
$delete = $ajax->link('Delete Post', $href, $opt , 'Do you want to delete this post?' ); 





         echo $form->input('PhoneNumber.'.$key.'.id',array('type'=>'hidden','value' => $number['id']));
         $row[] = array (__('Phone number',true).' '.($key+1), $form->input('PhoneNumber.'.$key.'.number',array('label'=>false,'value' => $number['number'])).$delete);
         

     }

    $row[] = array( $button,'');

    
     echo "<table>";
     echo $html->tableCells($row);
     echo "</table>";


     echo $form->end();
echo "</div>"; 




     echo "<div class='frameRight'>";
     echo "<table>";
     echo $html->tableCells(array (
     array(array($html->div('table_sub_header',__('Statistics',true)),array('colspan'=>2,'height'=>30,'valign'=>'bottom'))),
     array(__("In the system since",true),	date('Y-m-d',$data['User']['created'])),
     array(__("Last seen",true),		date('Y-m-d',$last_seen)),
     array(__("First application",true),	$formatting->appMatch($data['User']['first_app'])),
     array(__("Last application",true),	     	$formatting->appMatch($data['User']['last_app'])),
     array(array($html->div('table_sub_header',__('Activity',true)),array('colspan'=>2,'height'=>30,'valign'=>'bottom'))),
     array(__("Poll",true),			$data['User']['count_poll']),
     array(__("Voice Menu",true),		$data['User']['count_ivr']),
     array(__("Leave-a-message",true),		$data['User']['count_lam']),
     array(__("SMS",true),		        $data['User']['count_bin'])
     ));
     echo "</table>";
     echo "</div>";

    echo "</fieldset>";
    }
    else {

    echo "<h1>".__("No user with this id exists",true)."</h1>";
    }

 

?>

