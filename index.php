<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
?>
<?php
 if(isset($_GET['streetAddress'],$_GET['city'],$_GET['state']))
            {
                $streetAddress = $_GET['streetAddress'];
                $city = $_GET['city'];
                $state = $_GET['state'];
              
                $url ="http://www.zillow.com/webservice/GetDeepSearchResults.htm?".
                "zws-id=X1-ZWz1dxu1hs8d8r_4dboj&address=".$streetAddress."&citystatezip=".$city."+".$state."&rentzestimate=true";
    class zillowValues{
            public $errorMsg = "";
            public $message = "";
            public $homeDetails= "";
            public $street= "";
            public $city= "";
            public $state= "";
            public $zipcode= "";
            public $useCode= "";
            public $lastSold= "";
            public $yearBuilt= "";
            public $lSoldDate= "";
            public $lotSize= "";
            public $zesLastUpdated= "";
            public $Zamnt= "";
            public $fArea= "";
            public $zesImg= "";
            public $zesValueChnge= "";
            public $bathrooms= "";
            public $ZesValRange= "";
            public $bedRooms= "";
            public $rZentDate= "";
            public $rZestAmnt= "";
            public $TxAssesYr= "";
            public $renImg= "";
            public $renValueChge= "";
            public $taxAssess= ""; 
            public $rentValRange= "";
            public $oChangeVal = "";
            public $zPid= ""; 
            public $chart5yrs ="";
            public $chart10yrs = "";
            public $chart1yr ="";
    }
     
     function checkBlankInput($data){
				if($data == ""){
					$data = "N/A";
				}else if ($data == "0"){
					$data = "N/A";
				}else if ($data == "01/01/1970"){
					$data = "N/A";
				}else if ($data == "$0"){
					$data = "N/A"; 
				}else if ($data == "0 sq.ft."){
					$data = "N/A"; 
				}
                else if ($data == "0sq.ft."){
					$data = "N/A"; 
				}else if ($data == "$0-$0"){
					$data = "N/A"; 
				}else if ($data == "$0 -$0"){
					$data = "N/A"; 
				}
				return $data;
			}
      
        $zValues = new zillowValues();
        $xml= simplexml_load_file($url);
        $message = $xml->message->text;
        $zValues->message=(string)$message;
        $zPid = $xml->response->results->result->zpid;
        $zValues->zPid=(string)$zPid;
     
         $url1 ="http://www.zillow.com/webservice/GetChart.htm?zws-id=X1-ZWz1dxu1hs8d8r_4dboj&unit-type=percent&zpid=".$zPid."&width=600&height=300";
         $xmlC = simplexml_load_file($url1);
         $chart1yr = $xmlC->response->url;
         $chart5yrs = str_replace('chartDuration=1year','chartDuration=5years',$chart1yr);
         $chart10yrs = str_replace('chartDuration=1year','chartDuration=10years',$chart1yr);
         $zValues->chart5yrs =(string)$chart5yrs;
         $zValues->chart10yrs =(string)$chart10yrs;
         $zValues->chart1yr =(string)$chart1yr;
          
    $error = "Error: no exact match found for input address";
    if($xml == null || $message == $error)
    {
        $errorMsg =  "No exact match found -- Verify that the given address is correct.";
        $zValues->errorMsg=(string)$errorMsg;
    }
    
        $homeDetails = $xml->response->results->result->links->homedetails;
        $zValues->homeDetails=(string)$homeDetails;
          
        $street = $xml->response->results->result->address->street;
        $zValues->street=checkBlankInput((string)$street);
        $city = $xml->response->results->result->address->city;
        $zValues->city=checkBlankInput((string)$city);
        $state = $xml->response->results->result->address->state;
        $zValues->state=checkBlankInput((string)$state);
        $zipcode = $xml->response->results->result->address->zipcode; 
        $zValues->zipcode=checkBlankInput((string)$zipcode);
        $useCode= $xml->response->results->result->useCode;
        $zValues->useCode=checkBlankInput((string)$useCode);
        
        $lastSold = $xml->response->results->result->lastSoldPrice;
        $lastSold = number_format((double)$lastSold);
        $lastSold = "$".$lastSold;
        $zValues->lastSold=checkBlankInput((string)$lastSold);        
        $yearBuilt = $xml->response->results->result->yearBuilt;
        $zValues->yearBuilt=checkBlankInput((string)$yearBuilt);        
        $lSoldDate = $xml->response->results->result->lastSoldDate;
        $city = $xml->response->results->result->address->city;
        $cityNew = str_replace(' ','_',$city);
        date_default_timezone_set("America/Los_Angeles");
        $timestamp2 = strtotime($lSoldDate);
        $lSoldDate =  date("d-M-Y", $timestamp2);//use this
        $zValues->lSoldDate=checkBlankInput((string)$lSoldDate); 
        
        $lotSize = $xml->response->results->result->lotSizeSqFt;
        $lotSize=number_format((double)$lotSize);
        $lotSize =  $lotSize." sq.ft."; 
        $zValues->lotSize=checkBlankInput((string)$lotSize); 
        
        $ZentDate = $xml->response->results->result->zestimate->{'last-updated'}; 
        $city = $xml->response->results->result->address->city;
        $cityNew = str_replace(' ','_',$city);
        date_default_timezone_set("America/Los_Angeles");
        $timestamp1 = strtotime($ZentDate);
        $zesLastUpdated =  date("d-M-Y", $timestamp1).":";//use this
        $zValues->zesLastUpdated=checkBlankInput((string)$zesLastUpdated);
        
        $Zamnt = $xml->response->results->result->zestimate->amount;
        $Zamnt=number_format((double)$Zamnt);
        $Zamnt= "$".$Zamnt; 
        $zValues->Zamnt=checkBlankInput((string)$Zamnt);
        
        $fArea = $xml->response->results->result->finishedSqFt; 
        $fArea=number_format((double)$fArea);
        $fArea =  $fArea." sq.ft.";
        $zValues->fArea=checkBlankInput((string)$fArea);
    ?>
    <?php
         $oChangeVal = $xml->response->results->result->zestimate->valueChange;
          if( $oChangeVal < 0 )
             {     
                $zesDownImg = "http://cs-server.usc.edu:45678/hw/hw6/down_r.gif";
                $zValues->zesImg=(string)$zesDownImg;                       
              } 
              else
              {                        
                $zesUpImg = "http://cs-server.usc.edu:45678/hw/hw6/up_g.gif";
                $zValues->zesImg=(string)$zesUpImg;                           
               }
     ?>  
    <?php
         $oChangeVal = $xml->response->results->result->zestimate->valueChange;
         $oChangeVal=number_format((double)$oChangeVal);
         $oChangeVal = str_replace("-"," ",$oChangeVal);
         $zesValueChnge = "$".$oChangeVal;
         $zValues->zesValueChnge=checkBlankInput((string)$zesValueChnge);
        
         $bathrooms =  $xml->response->results->result->bathrooms;
         $zValues->bathrooms=checkBlankInput((string)$bathrooms);
         
        if(isset($xml->response->results->result->zestimate->valuationRange->low)){
             $tPrpRange1 = $xml->response->results->result->zestimate->valuationRange->low;
             $tPrpRange2 = $xml->response->results->result->zestimate->valuationRange->high;
             $tPrpRange1=number_format((double)$tPrpRange1);
             $tPrpRange2=number_format((double)$tPrpRange2);
             $ZesValRange =  "$".$tPrpRange1 ."-"."$".$tPrpRange2;
             $zValues->ZesValRange=checkBlankInput((string)$ZesValRange);
            }
        
        $bedRooms = $xml->response->results->result->bedrooms;
        $zValues->bedRooms=checkBlankInput((string)$bedRooms);
        
        $rZentDate = $xml->response->results->result->rentzestimate->{'last-updated'};
        $city = $xml->response->results->result->address->city;
        $cityNew = str_replace(' ','_',$city);
        date_default_timezone_set("America/Los_Angeles");
        $timestamp = strtotime($rZentDate);
        $rZentDate =  date("d-M-Y", $timestamp).":";
        $zValues->rZentDate=checkBlankInput((string)$rZentDate);
        
        $rZest = $xml->response->results->result->rentzestimate->amount;
        $rZest=number_format((double)$rZest);
        $rZestAmnt = "$".$rZest;//use this
        $zValues->rZestAmnt=checkBlankInput((string)$rZestAmnt);
        
        $TxAssesYr = $xml->response->results->result->taxAssessmentYear;
        $zValues->TxAssesYr=checkBlankInput((string)$TxAssesYr);
        
        $renChnge = $xml->response->results->result->rentzestimate->valueChange;
         if( $renChnge < 0 )
             {
                $renDownImg ="http://cs-server.usc.edu:45678/hw/hw6/down_r.gif";
                $zValues->renImg=(string)$renDownImg;
              } 
             else
               {
                $renUpImg ="http://cs-server.usc.edu:45678/hw/hw6/up_g.gif"; 
                $zValues->renImg=(string)$renUpImg;
               }
        
          $renChnge = $xml->response->results->result->rentzestimate->valueChange;
          $renChnge=number_format((double)$renChnge);
          $renChnge = str_replace("-"," ",$renChnge);
          $renValueChge= "$".$renChnge;
          $zValues->renValueChge=checkBlankInput((string)$renValueChge);
        
          $taxAssess=$xml->response->results->result->taxAssessment;
          $taxAssess=number_format((double)$taxAssess);
          $taxAssess = "$".$taxAssess;
          $zValues->taxAssess=checkBlankInput((string)$taxAssess);    
        
        if(isset($xml->response->results->result->rentzestimate->valuationRange->low)){
            $tRent1 = $xml->response->results->result->rentzestimate->valuationRange->low;
            $tRent2 = $xml->response->results->result->rentzestimate->valuationRange->high;
            $tRent1=number_format((double)$tRent1);
            $tRent2=number_format((double)$tRent2);
            $rentValRange = "$".$tRent1 ."-"."$".$tRent2;
            $zValues->rentValRange=checkBlankInput((string)$rentValRange);
        }            
    ?>        
    <?php
        echo json_encode($zValues);
 }
    ?>
