// make sure to chmod 644
"use strict";

var okay_msg = "";
var no_submit_msg = "* Please correct the above errors before continuing *";
var required_msg = "* This field is required";
var nan_msg = "* This field requires a numeric value";
var zip_len_msg = "* Zip codes must be five (5) digits";
var card_no_len_msg = "* Credit card numbers must be 15 or 16 digits";
var cvc_len_msg = "* Security (CVC) code must be 3 digits";

function checkBillName() {
	var elem = document.getElementById("bill_name");
	var err_field_id = "bill_name_err";
	var value = elem.value;

	if(value == undefined || value == ""){
		setText(err_field_id, required_msg);
		return false;
	} else {
		setText(err_field_id, okay_msg);
		return true;
	}
}

function checkBillAddr() {
	var elem = document.getElementById("bill_addr_1");
	var err_field_id = "bill_addr_err";
	var value = elem.value;

	if(value == undefined || value == ""){
		setText(err_field_id, required_msg);
		return false;
	} else {
		setText(err_field_id, okay_msg);
		return true;
	}
}

function checkBillCity() {
	var elem = document.getElementById("bill_city");
	var err_field_id = "bill_city_err";
	var value = elem.value;

	if(value == undefined || value == ""){
		setText(err_field_id, required_msg);
		return false;
	} else {
		setText(err_field_id, okay_msg);
		return true;
	}
}

function checkBillState() {
	var elem = document.getElementById("bill_state");
	var err_field_id = "bill_state_err";
	var value = elem.value;

	if(value == undefined || value == "" || value == "--"){
		setText(err_field_id, required_msg);
		return false;
	} else {
		setText(err_field_id, okay_msg);
		return true;
	}
}

function checkBillZip() {
	var elem = document.getElementById("bill_zip");
	var err_field_id = "bill_zip_err";
	var value = elem.value;

	if(value == undefined || value == ""){
		setText(err_field_id, required_msg);
		return false;
	} else if (isNaN(value)) {
		setText(err_field_id, nan_msg);
		return false;
	} else if (value.length != 5) {
		setText(err_field_id, zip_len_msg);
		return false;
	} else {
		setText(err_field_id, okay_msg);
		return true;
	}
}

function checkShipName() {
	var elem = document.getElementById("ship_name");
	var err_field_id = "ship_name_err";
	var value = elem.value;

	if(value == undefined || value == ""){
		setText(err_field_id, required_msg);
		return false;
	} else {
		setText(err_field_id, okay_msg);
		return true;
	}
}

function checkShipAddr() {
	var elem = document.getElementById("ship_addr_1");
	var err_field_id = "ship_addr_err";
	var value = elem.value;

	if(value == undefined || value == ""){
		setText(err_field_id, required_msg);
		return false;
	} else {
		setText(err_field_id, okay_msg);
		return true;
	}
}

function checkShipCity() {
	var elem = document.getElementById("ship_city");
	var err_field_id = "ship_city_err";
	var value = elem.value;

	if(value == undefined || value == ""){
		setText(err_field_id, required_msg);
		return false;
	} else {
		setText(err_field_id, okay_msg);
		return true;
	}
}

function checkShipState() {
	var elem = document.getElementById("ship_state");
	var err_field_id = "ship_state_err";
	var value = elem.value;

	if(value == undefined || value == "" || value == "--"){
		setText(err_field_id, required_msg);
		return false;
	} else {
		setText(err_field_id, okay_msg);
		return true;
	}
}

function checkShipZip() {
	var elem = document.getElementById("ship_zip");
	var err_field_id = "ship_zip_err";
	var value = elem.value;

	if(value == undefined || value == ""){
		setText(err_field_id, required_msg);
		return false;
	} else if (isNaN(value)) {
		setText(err_field_id, nan_msg);
		return false;
	} else if (value.length != 5) {
		setText(err_field_id, zip_len_msg);
		return false;
	} else {
		setText(err_field_id, okay_msg);
		return true;
	}
}

function checkPayFirstName() {
	var elem = document.getElementById("pay_first_name");
	var err_field_id = "pay_first_name_err";
	var value = elem.value;

	if(value == undefined || value == ""){
		setText(err_field_id, required_msg);
		return false;
	} else {
		setText(err_field_id, okay_msg);
		return true;
	}
}

function checkPayLastName() {
	var elem = document.getElementById("pay_last_name");
	var err_field_id = "pay_last_name_err";
	var value = elem.value;

	if(value == undefined || value == ""){
		setText(err_field_id, required_msg);
		return false;
	} else {
		setText(err_field_id, okay_msg);
		return true;
	}
}

function checkPayCardNo() {
	var elem = document.getElementById("pay_card_no");
	var err_field_id = "pay_card_no_err";
	var value = elem.value;

	if(value == undefined || value == ""){
		setText(err_field_id, required_msg);
		return false;
	} else if (isNaN(value)) {
		setText(err_field_id, nan_msg);
		return false;
	} else if (value.length != 15 && value.length != 16) {
		setText(err_field_id, card_no_len_msg);
		return false;
	} else {
		setText(err_field_id, okay_msg);
		return true;
	}
}

function checkPayCVC() {
	var elem = document.getElementById("pay_cvc");
	var err_field_id = "pay_cvc_err";
	var value = elem.value;

	if(value == undefined || value == ""){
		setText(err_field_id, required_msg);
		return false;
	} else if (isNaN(value)) {
		setText(err_field_id, nan_msg);
		return false;
	} else if (value.length != 3) {
		setText(err_field_id, cvc_len_msg);
		return false;
	} else {
		setText(err_field_id, okay_msg);
		return true;
	}
}

function checkPayForm() {
	var err_field_id = "submit_err";

	var billValid = checkBillName() && checkBillAddr() && checkBillCity() && checkBillState() && checkBillZip();
	var shipValid = checkShipName() && checkShipAddr() && checkShipCity() && checkShipState() && checkShipZip();
	var payValid = checkPayFirstName() && checkPayLastName() && checkPayCardNo() && checkPayCVC();

	var valid = billValid && shipValid && payValid;

	if(!valid) {
		setText(err_field_id, no_submit_msg);
	}

	return valid;
}
