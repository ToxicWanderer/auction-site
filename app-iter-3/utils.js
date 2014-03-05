// make sure to chmod 644
"use strict";

// cross-browser compatibility functions
function getText(id) {
	var element = document.getElementById(id);
	
	text = element.innerText;
	text = (typeof text !== 'undefined') ? text : element.textContent;
	text = (typeof text !== 'undefined') ? text : "error in getText()";
	
	alert(text);

	return text;
}

function setText(id, text) {
	var element = document.getElementById(id);
	
	if(element.innerText) {
		element.innerText = text;
	} else {
		element.textContent = text;
	}
}

function isNumber(n) {
	return !isNaN(parseFloat(n)) && isFinite(n);
}

function toggleDesc(descId, toggleId) {
	var descElem = document.getElementById(descId);
	var toggleElem = document.getElementById(toggleId);

	if (window.getComputedStyle(descElem).getPropertyValue('display') == "none") {
		descElem.style.display = "block"; // set desc visible
		setText(toggleId, "Hide Description");
	} else {
		descElem.style.display = "none"; // set desc not visible
		setText(toggleId, "-- Show Full Description --");
	}
}


function toggleText(targetId, toggleId, text, moreText, lessText) {
	var targetElem = document.getElementById(targetId);
	var toggleElem = document.getElementById(toggleId);
	
	var toggleText = getText(toggleId);
	if (toggleText == moreText) {
		setText(targetElem, text);
		setText(toggleElem, lessText);
	} else if (toggleText == lessText) {
		setText(targetElem, "");
		setText(toggleElem, moreText);
	}
}
