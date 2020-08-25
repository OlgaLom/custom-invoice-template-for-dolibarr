<?php

/** 	Function called to complete substitution array for lines (before generating on ODT, or a personalized email)
 * 		functions xxx_completesubstitutionarray_lines are called by make_substitutions() if file
 * 		is inside directory htdocs/core/substitutions
 * 
 *		@param	array		$substitutionarray	Array with substitution key=>val
 *		@param	Translate	$langs			Output langs
 *		@param	Object		$object			Object to use to get values
 *      @param  Object      $line           Current line being processed, use this object to get values
 * 		@return	void					The entry parameter $substitutionarray is modified
 */

 // Custom tags for lines
function mymodule_completesubstitutionarray_lines(&$substitutionarray,$langs,$object,$line)
{
 	global $conf, $db;


	/**
	 *
 	 *  Check if product has no title then display the description 
 	 *			We do this escpecially for the shipping trick, because we inserting it like a service manually .......
	 **/
	
	$ProductTitle = $line->product_label;
 
 	if( isset( $ProductTitle ) ) { 
    	$substitutionarray['line_product_title_OR_desc_value']= $ProductTitle;
    }else{
    	$substitutionarray['line_product_title_OR_desc_value']= $line->desc;
    }
  
	/**
	 *
 	 * Get the pre discount value.
 	 *  	qty => quantity | subprice => is the price of one product
 	 *
	 **/

	//Multiple the quantity with the price of the product 
    $PreDiscountValue = $line->qty * $line->subprice;
    $FloatNum = floatval($PreDiscountValue);
    // Properly format the result 
	$FormatedValue = number_format($FloatNum, 2, ',', '.');
	$substitutionarray['line_pre_discount_value']= $FormatedValue;
	
	/**
	 *
	 * Get numeric discount 
	 *		remise_percent => percent discount
	 *
	 **/
	// get the discount perscent and convert it into float 
	$DiscPer = floatval($line->remise_percent);
	// Check if an discount exist and is not zero 
	if($DiscPer>0){
		$DiscNumber = 100 / $DiscPer;
		// Do the super math
   		$NumericDiscount = $PreDiscountValue / $DiscNumber;
	}	
   	// explanation of calculation 
   	// $substitutionarray['line_discount_numeric_exp']= $PreDiscountValue." / ". $DiscNumber ."= ". $NumericDiscount;

   	$substitutionarray['line_discount_numeric'] = number_format($NumericDiscount, 2, ',', '.');

	/**
	 *
 	 *		Get the vat and covert it from percentage to simple integer   
 	 *			
	 **/ 
  		//convert the tax percentage to float  [$line->tva_tx => is the line vat_rate (vat) ]
 		$Line_Vat_Perc = 100 / $line->tva_tx;
		// Do the super math
   		$VatInNum = $line->subprice / $Line_Vat_Perc;
     	$substitutionarray['line_vatrate_number'] = number_format($VatInNum, 2, ',', '.');

    	
}

// Custom tags for object
function mymodule_completesubstitutionarray(&$substitutionarray,$langs,$object)
{
   	global $conf,$db;

  	/**
	 *
  	 * Display the paying value in words 
  	 * 
  	 **/

  	// Create an object of NumberFormatter, in order to convert a numeric value to a sentence
	$Words_Function = new NumberFormatter("el", NumberFormatter::SPELLOUT);

   	$AmountToPay = $object->total_ttc;
   	// Seperate the AmountToPay value into two parts
   	// the intiger part and the decimal part

   	// Intiger Part 
   	$IntPart = intval($AmountToPay);
	
	// Decimal Part
	//  first check if there is a decimal part
	$Result = fmod(floatval($AmountToPay),1); 
 	if( $Result != 0 ){
	 
		// in order to get the decimal part we must round down the number and sabstract it from the whole number.
	   	$DecimalPart = $AmountToPay - floor($AmountToPay); 
	   	// format the value in order to get 2 decimal point
	   	 $DecimalPart = round($DecimalPart,2); 
	   	// convert it into int in order to call the spellout function
 	   	$DecimalPart_AsAnInt = $DecimalPart * 100;

	   	// Call spellout function for the int part	   	
		$Sentence = $Words_Function->format($IntPart);
   		// Call spellout function for the decimal part
   		$Sentence .= " Ευρώ Και ". $Words_Function->format($DecimalPart_AsAnInt)." Λεπτά";

   		// Capitalize the first letter of the every single word in the sentence
		$Sentence = mb_convert_case($Sentence, MB_CASE_TITLE, "UTF-8");


   		$substitutionarray['object_remain_to_pay_in_words'] = $Sentence;
   	}else{

   		
		$Sentence = $Words_Function->format($AmountToPay) ." Ευρώ";

		// Capitalize the first letter of the every single word in the sentence
		$Sentence = mb_convert_case($Sentence, MB_CASE_TITLE, "UTF-8");

		$substitutionarray['object_remain_to_pay_in_words'] = $Sentence;

   	}

  

	/**
	 *
     * Get the pre discount total value of the object
	 *
   	 **/

   	// Count how many products there are in the invoice 
 	$num = count($object->lines);
 	$Amount=0;
 	// loop through them
 	for ($i = 0; $i < $num; $i++)
	{
		// calculate again the quantity and the subprice of every line
		$qty = $object->lines[$i]->qty;
		$subprice = $object->lines[$i]->subprice;
		// add every pre-discount value into the $Amount
		$Amount = $Amount + ( $qty * $subprice );

	}
	// Properly format the value
	$Amount= number_format($Amount, 2,',', '.');
	$substitutionarray['object_pre_discount_value']= $Amount;

	/**
	 *
   	 * Get the previous total value [Προηγούμενο υπόλοιπο]
	 *
   	 **/

   	// $test = $object->total_ttc;
   	$test = $object->total_ttc;
	$substitutionarray['object_prev_total_amount']= $test;

}

?>

