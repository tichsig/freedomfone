<?
   echo "<div id='campaign_div' class='campaign_div'>";

   if($campaign){

           $row[] = array(__('Start time',true), $campaign['Campaign']['start_time']);
           $row[] = array(__('End time',true), $campaign['Campaign']['end_time']);
           $row[] = array(__('Initialization time',true), date('Y-m-d H:i:s',$campaign['Campaign']['created']));
           $row[] = array(__('Max duration',true), $formatting->epochToWords($campaign['Campaign']['max_duration']));
           $row[] = array(__('Max retries',true), $campaign['Campaign']['max_retries']);


       foreach($campaign['Callback'] as $key => $entry){

              $status[] = $entry['status'];

        }

        $total   = sizeof($status);
        $pending = $number->toPercentage(100*sizeof(array_keys($status,1))/$total,0);
        $failure = $number->toPercentage(100*sizeof(array_keys($status,2))/$total,0);
        $retry   = $number->toPercentage(100*sizeof(array_keys($status,3))/$total,0);
        $success = $number->toPercentage(100*sizeof(array_keys($status,4))/$total,0);
        $abort   = $number->toPercentage(100*sizeof(array_keys($status,5))/$total,0);
        $pause   = $number->toPercentage(100*sizeof(array_keys($status,6))/$total,0);
        $process = $number->toPercentage(100*sizeof(array_keys($status,7))/$total,0);


           $row[] = array(__('Pending',true), $pending);
           $row[] = array(__('Failure',true), $failure);
           $row[] = array(__('Retry',true), $retry);
           $row[] = array(__('Success',true), $success);
           $row[] = array(__('Abort',true), $abort);
           $row[] = array(__('Pause',true), $pause);
           $row[] = array(__('Processing',true), $process);

 
           echo "<table width='95%' cellspacing  = '0' class = 'none'>";
           echo $html->tableCells($row,array('class'=>'none'),array('class'=>'none'));
           echo "</table>";

           }

  echo "</div>";


?>