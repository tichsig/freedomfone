<?php
/****************************************************************************
 * index.ctp	- Form for selecting Freedom Fone logs
 * version 	- 3.0.1500
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
 
echo $this->Html->addCrumb(__('Dashboard',true), '');
echo $this->Html->addCrumb(__('Logs',true), '/logs');

echo "<h1>".__("Logs",true)."</h1>";
echo $this->Html->div('instructions',__('Select log file to view.',true));
echo $this->Form->create("Log");

     $system_log = Configure::read('SYSTEM_LOG');
     $opt = $system_log['gui'] + $system_log['system'];


	echo $this->Form->input("type",array("id"=>"LogType","type"=>"select","options"=>$opt,"label"=> false,"empty" => '-- '.__("Select log file",true).' --'));
	$this->Js->get('#LogType');
	$this->Js->event('change', $this->Js->request(array('controller'=>'logs','action' => 'disp'),array('async' => true,'update' => '#log_div','method' => 'post','dataExpression'=>true,'data'=> $this->Js->serializeForm(array('isForm' => true,'inline' => true)))));
	echo $this->Form->end();

	echo $this->Html->div('log_div',false,array('id' => 'log_div'));
	echo "</div>";

?>






