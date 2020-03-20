// get the pages
let page1 = document.getElementById("div-page1");
let page2 = document.getElementById("div-page2");
let page3 = document.getElementById("div-page3");
let page4 = document.getElementById("div-page4");

let buttonReg = document.getElementById("register"); // get register button from page 1
let ok2 = document.getElementById("ok2"); // get ok button from page 2
let ok3 = document.getElementById("ok3"); // get ok button from page 3
// get the submit / proceed payment buttons from page 4
let submit = document.getElementById("div-submit");
let proceedPayment = document.getElementById("div-proceedpayment");

// get the back buttons from page 3 and page 4
let back3 = document.getElementById("div-back-page3");
let back4 = document.getElementById("div-back-page4");

// get name and email inputs
let inputName = document.getElementById("field");
let inputEmail = document.getElementById("field-2");

// Enter key (keycode 13) triggers the click event of the appropriate buttons to go to next page.
window.addEventListener("keypress", function(e) {
	if (e.keyCode === 13) {
		console.log("Enter is pressed");
		switch (findActivePage()) {
			case 1:
				buttonReg.click(); //buttonReg does not need an onclick function declaration because it is handled by Webflow
				break;
			case 2:
				ok2.click(); //ok2 does not need an onclick function declaration because it is handled by Webflow
				break;
			case 3:
				ok3.click();
				break;
			case 4:
				if (isActive(submit)) submit.click();
				else if (isActive(proceedPayment)) proceedPayment.click();
				break;
		}
	}
});

// initial state
page2.style.display = "none";
page3.style.display = "none";
page4.style.display = "none";

// name/email page (page 3) OK button onclick name and email validation
ok3.onclick = function() {
	if (inputName.value.length === 0) {
		console.log("Name has no value", inputName);
		return;
	}
	if (inputEmail.value.length === 0) {
		console.log("Email has no value", inputEmail);
		tickEmail.style.display = "none";
		exclamationEmail.style.display = "block";
		errorMsgs[0].style.display = "block";
		return;
	}
	if (!validateEmail(inputEmail.value)) {
		console.log("Email is not valid!", inputEmail);
		tickEmail.style.display = "none";
		exclamationEmail.style.display = "block";
		errorMsgs[0].style.display = "block";
		return;
	}
	tickEmail.style.display = "block";
	exclamationEmail.style.display = "none";
	loading.style.display = "none";
	errorMsgs[0].style.display = "none";
	nextPage();
};

// TODO: these two buttons must connect to the next step of the enrollment form
submit.onclick = function() {
	alert("You have submitted the payment!");
};
proceedPayment.onclick = function() {
	alert("Taking you to proceed payment!");
};

// back buttons onclick function goes to the previous page
back3.onclick = function() {
	previousPage();
};
back4.onclick = function() {
	previousPage();
};

// go to the next page of the membership system
function nextPage() {
	console.log("Active page", findActivePage());
	switch (findActivePage()) {
		case 1:
			page1.style.display = "none";
			page2.style.display = "flex";
			page3.style.display = "none";
			page4.style.display = "none";
			break;
		case 2:
			page1.style.display = "none";
			page2.style.display = "none";
			page3.style.display = "flex";
			page4.style.display = "none";
			break;
		case 3:
			page1.style.display = "none";
			page2.style.display = "none";
			page3.style.display = "none";
			page4.style.display = "flex";
			break;
	}
}

// go to the previous page of the membership system
function previousPage() {
	console.log("Active page", findActivePage());
	switch (findActivePage()) {
		case 2:
			page1.style.display = "flex";
			page2.style.display = "none";
			page3.style.display = "none";
			page4.style.display = "none";
			break;
		case 3:
			page2.style.opacity = "1";
			page2.style.transform = "";
			page1.style.display = "none";
			page2.style.display = "flex";
			page3.style.display = "none";
			page4.style.display = "none";
			break;
		case 4:
			page1.style.display = "none";
			page2.style.display = "none";
			page3.style.display = "flex";
			page4.style.display = "none";
			break;
	}
}

// finds the page that is currently active
function findActivePage() {
	if (isActive(page1)) return 1;
	else if (isActive(page2)) return 2;
	else if (isActive(page3)) return 3;
	else if (isActive(page4)) return 4;
}

// Checks if an element is active (i.e. on the page)
function isActive(el) {
	return !(el.offsetParent === null);
}

// email validation and error message icons initially are invisible
let tickEmail = document.getElementById("tick-email");
let exclamationEmail = document.getElementById("exclamation-email");
let loading = document.getElementById("loading");
let errorMsgs = document.getElementsByClassName("div-errormsg");
console.log(errorMsgs);
tickEmail.style.display = "none";
exclamationEmail.style.display = "none";
loading.style.display = "none";
errorMsgs[0].style.display = "none";

// validates the email by checking if the input is in the right format (e.g. johnsmith@example.com)
function validateEmail(email) {
	var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
	return re.test(String(email).toLowerCase());
}

// get the button elements
let payCash = document.getElementById("btn-cash");
let payTransfer = document.getElementById("btn-banktransfer");
let payWeChat = document.getElementById("btn-wechat");
let payAli = document.getElementById("btn-alipay");
let payCard = document.getElementById("btn-creditcard");
let payPoli = document.getElementById("btn-poliay");

// initially hide Submit and Proceed Payment buttons
submit.style.display = "none";
proceedPayment.style.display = "none";

// sets up event listener for button click
[payCash, payTransfer, payWeChat, payAli, payCard, payPoli].forEach(
	(item, index) => {
		item.addEventListener("click", function(e) {
			toggleButton(item);
			showButton(index);
		});
	}
);

// make the buttons look like they are toggled
function toggleButton(buttonInUse) {
	[payCash, payTransfer, payWeChat, payAli, payCard, payPoli].forEach(
		button => {
			if (buttonInUse === button) button.classList.add("toggled");
			else button.classList.remove("toggled");
		}
	);
}

// show Submit button if user pays using Cash/Bank transfer
// show proceed payment if user pays using Wechat/AliPay/Credit/Debit/PoLi Pay
function showButton(index) {
	if (index === 0 || index === 1) {
		submit.style.display = "flex";
		proceedPayment.style.display = "none";
	} else {
		submit.style.display = "none";
		proceedPayment.style.display = "flex";
	}
}
