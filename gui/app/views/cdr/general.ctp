<?php
/****************************************************************************
 * general.ctp	- Data mining of LAM and IVR calls
 * version 	- 1.0.353
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



 //If action = Export, create CSV file 
 if(isset($this->params['form']['action'])) {	
	     if ($this->params['form']['action']==__('Export',true)){

		$line = array(__('Date (Y-m-d)',true),__('Year',true),__('Month',true),__('Day',true),__('Time',true),__('Title',true),__('Caller',true),__('Protocol',true),__('Length',true));
		$csv->addRow($line);


	if($cdr){

		foreach($cdr as $key => $entry){

		$line = array( date('Y-m-d',$entry['Cdr']['epoch']),
		               date('Y',$entry['Cdr']['epoch']),
		      	       date('m',$entry['Cdr']['epoch']),
			       date('d',$entry['Cdr']['epoch']),
			       date('H:i:s',$entry['Cdr']['epoch']),
			       $entry['Cdr']['title'],
			       $entry['Cdr']['caller_name'],
			       $entry['Cdr']['proto'],
			       $entry['Cdr']['length']);

		$csv->addRow($line);

		}

	}
		$prefix=date('Y-m-d_');
		echo $csv->render($prefix.__('CDR',true).'.csv');  
		$csv->render(false);
	
	} //export
} //action 
  
	//** START: Search form **/
	echo "<h1>".__("Data mining: Leave-a-message and Voice menu calls",true)."</h1>";
	echo $form->create('Cdr',array('type' => 'post','action'=> 'general'));
	$options1=array('lam' =>'');
	$options2=array('ivr' =>'');


	//Set application default value (IVR)
	if( ! $default = $this->data['Cdr']['application']){
    	    $default= 'ivr';
    	    }

	    $radio1 = $form->radio('application',$options1,array('legend'=>false,'value'=>$default));
	    $radio2 = $form->radio('application',$options2,array('legend'=>false,'value'=>$default));

	    $menu_lam = $form->input('title_lam',array('type'=>'select','options' =>$lam,'label'=>'','empty'=>'- '.__('All Leave-a-message',true).' -'));
	    $menu_ivr = $form->input('title_ivr',array('type'=>'select','options' =>$ivr,'label'=>'','empty'=>'- '.__('All IVR',true).' -'));

	    echo "<table>";
	    echo $html->tableCells(array (
     	    	 array(__('Application',true),$radio1,$menu_lam,$radio2,$menu_ivr)
      		 ));
            echo "</table>";

	    echo "<table>";
	    echo $html->tableCells(array (
     	    	 array(__("Start time",true),	$form->input('start_time',array('label'=>false,'type' => 'datetime', 'interval' => 15))),
     		 array(__("End time",true),		$form->input('end_time',array('label'=>false,'type' => 'datetime','interval' => 15))),
      		 ));
	    echo "</table>";

	    echo "<table>";
	    $buttons=array();
	    $buttons[]= $form->submit(__('Submit',true),array('name'=>'action'));
     	    if($cdr){ 
	    	      $buttons[] = $form->submit(__('Export',true),array('name'=>'action'));
             }
	     echo $html->tableCells($buttons);
	    echo "</table>";
	     echo $form->end();
	     //** END: Search form **/


	    //** START: List CDR **/
    	    if($cdr){

		foreach($cdr as $key => $entry){
	    		     $data = $entry['Cdr'];
	  		     $rows[]=array($data['title'],date('M d Y',$data['epoch']),date('H:i:s A',$data['epoch']),$data['caller_name'],$data['proto'],$formatting->epochToWords($data['length']));
	     		     }

	     $headers = array(__('Title',true),__('Date',true),__('Time',true),__('Sender',true),__('Protocol',true),__('Length',true));
	     echo "<table>";
	     echo $html->tableHeaders($headers);
	     echo $html->tableCells($rows);
	     echo "</table>";
	     } 
	     //** END: List CDR **/
	 
?>