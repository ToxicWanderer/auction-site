// make sure to chmod 644
"use strict";

var okay_msg = "";
var no_submit_msg = "* Please correct the above errors before continuing *";
var required_msg = "* This field is required";
var invalid_email_msg = "* Invalid email address";
var email_match_msg = "* Email addresses do not match";
var pwd_match_msg = "* Passwords do not match";
var pwd_len_msg = "* Password must be 6+ characters";

function checkFirstName() {
	var elem = document.getElementById("first_name");
	var err_field_id = "first_name_err";
	var value = elem.value;

	if(value == undefined || value == ""){
		setText(err_field_id, required_msg);
		return false;
	} else {
		setText(err_field_id, okay_msg);
		return true;
	}
}

function checkLastName() {
	var elem = document.getElementById("last_name");
	var err_field_id = "last_name_err";
	var value = elem.value;

	if(value == undefined || value == ""){
		setText(err_field_id, required_msg);
		return false;
	} else {
		setText(err_field_id, okay_msg);
		return true;
	}
}

function checkEmail1() {
	var elem = document.getElementById("email_1");
	var err_field_id = "email_1_err";
	var value = elem.value;

	/*var atpos = value.indexOf("@");
	var dotpos = atpos + value.substring(atpos).indexOf(".");*/

	if(value == undefined || value == ""){
		setText(err_field_id, required_msg);
		return false;
	/*} else if(atpos < 1 || dotpos < atpos + 2 || dotpos + 2 >= x.length) {
		setText(err_field_id, invalid_email_msg);
		return false;*/
	} else {
		setText(err_field_id, okay_msg);
		return true;
	}
}

function checkEmail2() {
	var elem = document.getElementById("email_2");
	var err_field_id = "email_2_err";
	var value = elem.value;

	if(value == undefined || value == ""){
		setText(err_field_id, required_msg);
		return false;
	} else if (value != document.getElementById("email_1").value) {
		setText(err_field_id, email_match_msg);
		return false;
	} else {
		setText(err_field_id, okay_msg);
		return true;
	}
}

function checkUsername() {
	var elem = document.getElementById("username");
	var err_field_id = "username_err";
	var value = elem.value;

	if(value == undefined || value == ""){
		setText(err_field_id, required_msg);
		return false;
	} else {
		setText(err_field_id, okay_msg);
		return true;
	}
}

function checkPwd1() {
	var elem = document.getElementById("pwd_1");
	var err_field_id = "pwd_1_err";
	var value = elem.value;

	if(value == undefined || value == ""){
		setText(err_field_id, required_msg);
		return false;
	} else if (value.length < 6) {
		setText(err_field_id, pwd_len_msg);
		return false;
	} else {
		setText(err_field_id, okay_msg);
		return true;
	}
}

function checkPwd2() {
	var elem = document.getElementById("pwd_2");
	var err_field_id = "pwd_2_err";
	var value = elem.value;

	if(value == undefined || value == ""){
		setText(err_field_id, required_msg);
		return false;
	} else if (value.length < 6) {
		setText(err_field_id, pwd_len_msg);
		return false;
	} else if (value != document.getElementById("pwd_1").value) {
		setText(err_field_id, pwd_match_msg);
		return false;
	} else {
		setText(err_field_id, okay_msg);
		return true;
	}
}

function checkRegisterForm() {
	var err_field_id = "submit_err";

	var valid = checkFirstName() && checkLastName() && checkEmail1() && checkEmail2() && checkUsername() && checkPwd1() && checkPwd2();

	if(!valid) {
		setText(err_field_id, no_submit_msg);
	}

	return valid;
}
