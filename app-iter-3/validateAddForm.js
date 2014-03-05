// make sure to chmod 644
"use strict";

var okay_msg = "";
var no_submit_msg = "* Please correct the above errors before continuing *";
var required_msg = "* This field is required";
var short_msg = "* This field requires a longer value";
var long_msg = "* This field requires a shorter value";
var nan_msg = "* This field requires a numeric value";
var min_money_msg = "* This value must be greater than $.01";
var neg_money_msg = "* This value must be greater than $0";
var min_reserve_msg = "* Reserve price must be greater than start bid";

function checkName() {
	var elem = document.getElementById("name");
	var err_field_id = "name_err";
	var value = elem.value;

	if(value == undefined || value == ""){
		setText(err_field_id, required_msg);
		return false;
	} else if (value.length < 6) {
		setText(err_field_id, short_msg);
		return false;
	} else if (value.length > 100) {
		setText(err_field_id, long_msg);
		return false;
	} else {
		setText(err_field_id, okay_msg);
		return true;
	}
}

function checkStartBid() {
	var elem = document.getElementById("start_bid");
	var err_field_id = "start_bid_err";
	var value = elem.value;

	if(value == undefined || value == ""){
		setText(err_field_id, required_msg);
		return false;
	} else if (isNaN(value)) {
		setText(err_field_id, nan_msg);
		return false;
	} else if (value < .01) {
		setText(err_field_id, min_money_msg);
		return false;
	} else {
		setText(err_field_id, okay_msg);
		return true;
	}
}

function checkReserve() {
	var elem = document.getElementById("reserve");
	var err_field_id = "reserve_err";
	var value = elem.value;

	if(value == undefined || value == ""){
		setText(err_field_id, okay_msg);
		return true;
	} else if (isNaN(value)) {
		setText(err_field_id, nan_msg);
		return false;
	} else if (value < 0) {
		setText(err_field_id, neg_money_msg);
		return false;
	} else if (document.getElementById("start_bid").value > value && value != 0) {
		setText(err_field_id, min_reserve_msg);
		return false;
	} else {
		setText(err_field_id, okay_msg);
		return true;
	}
}

function checkCategory() {
	var elem = document.getElementById("category");
	var err_field_id = "category_err";
	var value = elem.value;

	if(value == undefined || value == "" || value == 0){
		setText(err_field_id, required_msg);
		return false;
	} else {
		setText(err_field_id, okay_msg);
		return true;
	}
}

function checkDesc() {
	var elem = document.getElementById("desc");
	var err_field_id = "desc_err";
	var value = elem.value;

	if(value == undefined || value == ""){
		setText(err_field_id, required_msg);
		return false;
	} else if (value.length < 20) {
		setText(err_field_id, short_msg);
		return false;
	} else {
		setText(err_field_id, okay_msg);
		return true;
	}
}

function checkAddForm() {
	var err_field_id = "submit_err";

	var valid = checkName() && checkStartBid() && checkReserve() && checkCategory() && checkDesc();

	if(!valid) {
		setText(err_field_id, no_submit_msg);
	}

	return valid;
}
