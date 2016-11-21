<?php

/**
 * PHP item based filtering
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * @package   PHP item based filtering
 */

class Recommend {

    //voorkeurs afstand uitrekenen:
	//	volledige array, persoon1, persoon2
    public function similarityDistance($preferences, $person1, $person2)
    {
		// array met overeenkomende games
        $similar = array();
		// afstand in n demensies tussen de twee personen
        $sum = 0;
		
		//loop door alle games van persoon1
		// key = game , value = rating(speeltijd)
        foreach($preferences[$person1] as $key=>$value)
        {
			// als de game ook bestaat bij persoon2
            if(array_key_exists($key, $preferences[$person2]))
                // als  de game bij persoon2 er ook is is similar[game naam] = 1
				$similar[$key] = 1;
        }
        
		// als er geen games zijn die persoon2 ook heeft return 0 (aka $similar array is leeg)
        if(count($similar) == 0) return 0;
        
		//loop door alle games  van persoon1
        foreach($preferences[$person1] as $key=>$value)
        {
			// als persoon2 ook de game heeft
            if(array_key_exists($key, $preferences[$person2]))
				// tell op bij sum:    (rating van persoon 1 - rating van persoon 2)^2
				// afstand uitrekeken tussen de twee personen in n demensies waar n het aantal overeenkomende games zijn
                $sum = $sum + pow($value - $preferences[$person2][$key], 2);
        }
        //  ( afstand + 1 ) / 1 
		// als het een perfecte match is is het getal 1
		// hoe groter de afstand hoe dichter bij 0 de return zal zijn.
        return  1/(1 + sqrt($sum));     
    }
    
    
    	
	//aanbeveling binnen halen
    public function getRecommendations($preferences, $person)
    {
	
        $total = array();
        $simSums = array();
        $simCount = array();
        $ranks = array();
        $sim = 0;
        $all_sims = array();
        
		// voor elk persoon uit de lijst
        foreach($preferences as $otherPerson=>$values)
        {
			//als persoon niet gelijk aan de vergelijkings persoon
            if($otherPerson != $person)
            {
				
				// sim = afstand uitrekenen tussen ingegeven persoon en huidige andere persoon
                $sim = $this->similarityDistance($preferences, $person, $otherPerson);

                $all_sims[$otherPerson] = $sim;

            }
            
			// als er meer dan 0 orvereenkomst is
            if($sim > 0)
            {
				//voor elk game van de andere persoon
                foreach($preferences[$otherPerson] as $key=>$value)
                {
					// als de game niet bestaat bij ingegeven persoon
                    if(!array_key_exists($key, $preferences[$person]))
                    {
						// als de game nog niet bekend is in total
                        if(!array_key_exists($key, $total)) {
                            // in "totaal" het niet bekende game een 0 score geven
							$total[$key] = 0;
                        }
						
						// als het game wel bestaat in "total" de score/rating ophogen met:
						//  rating van andere persoon  *  met de overeenkomst tussen die persoon
						//  hoe dichter bij 0 hoe minder deze rating meeteld
                        $total[$key] += $preferences[$otherPerson][$key] * $sim;


						// als de optelling van het aantal overeenkomstigheid per game nog niet bestaat
                        if(!array_key_exists($key, $simSums)) {
							// dan: aanmaken van nieuwe game met rating 0
                            $simSums[$key] = 0;
                        }

						// aantal overeenkomstigheid ophogen met de overeenkomst van de huidige persoon
                        $simSums[$key] += $sim;

                        if(!array_key_exists($key, $simCount)) {
                            // dan: aanmaken van nieuwe game met rating 0
                            $simCount[$key] = 0;
                        }

                        $simCount[$key] += 1;

                    }
                }
                
            }
        }
		
		// voor elke uitgerekende game
        foreach($total as $key=>$value)
        {
			// ranking = (opgetelde rating van de game) / ( opgetelde overeenkomstigheid )
            //$ranks[$key] = $value / $simSums[$key] ;
			//$ranks[$key] = $value / $simCount[$key] ;
            $ranks[$key] = $value ;
			//error_log( "Ranking van " .$key. " is " . $ranks[$key] . "omdat: " . $value  . " value / " . $simSums[$key] , 0 ); 
			
        }
        
		
	
	// sorteren hoogste waarden bovenaan
    //array_multisort($ranks, SORT_DESC);  

	//error_log( print_R($all_sims, true) , 0 );
	// terug sturen
    return $ranks;
        
    }
   
}

?>