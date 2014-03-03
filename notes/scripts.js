// basic functions for Keith's Auction Site

"use strict";

function q1changed() {
	alert("Changed event fired!");

	var feedbackElement = document.getElementById("fb1");
	if (document.getElementById("q1").value == "Gravity acting on the mass of the aircraft."){
		// there is no difference between 'string' and "string"
		feedbackElement.textContent = "Correct!";
	} else {
		feedbackElement.textContent = "Try again!";
	}

	return true;
}

// input validation
function checkPayForm() {
// if (strlen($_POST['bill_zip']) != 5){
// ?>
//       <script>
//         alert("You entered an incorrect length zip code for your billing address.\nPlease enter a five digit zip code.");
//         history.back();
//       </script>
// <?php
// } elseif (!is_numeric($_POST['bill_zip'])){
// ?>
//       <script>
//         alert("You entered a non-numeric zip code for your billing address.\nPlease enter a five digit zip code.");
//         history.back();
//       </script>
// <?php
// } elseif (strlen($_POST['ship_zip']) != 5){
// ?>
//       <script>
//         alert("You entered an incorrect length zip code for your shipping address.\nPlease enter a five digit zip code.");
//         history.back();
//       </script>
// <?php
// } elseif (!is_numeric($_POST['ship_zip'])){
// ?>
//       <script>
//         alert("You entered a non-numeric zip code for your shipping address.\nPlease enter a five digit zip code.");
//         history.back();
//       </script>
// <?php
// } elseif (!is_numeric($_POST['pay_card_no'])) {
// ?>
//       <script>
//         alert("You entered a non-numeric credit card number.\nPlease enter your card number only with no dashes or other characters.");
//         history.back();
//       </script>
// <?php
// } elseif (strlen($_POST['pay_card_no']) != 16) {
// ?>
//       <script>
//         alert("You entered a credit card number with an incorrect number of digits.\nPlease enter your card number only with no dashes or other characters.");
//         history.back();
//       </script>
// <?php
// } elseif (!is_numeric($_POST['pay_cvc'])) {
// ?>
//       <script>
//         alert("You entered a non-numeric security code.\nPlease enter the security code found on the back of your card.");
//         history.back();
//       </script>
// <?php
// } elseif (strlen($_POST['pay_card_cvc']) != 3) {
// ?>
//       <script>
//         alert("You entered a CVC security code with an incorrect number of digits.\nPlease enter the security code found on the back of your card.");
//         history.back();
//       </script>
// <?php
// }
// ?>
// 
	alert("Checking");
}
