// get the pages
let page1 = document.getElementById("div-page1");
let page2 = document.getElementById("div-page2");
let page3 = document.getElementById("div-page3");
let page4 = document.getElementById("div-page4");

let buttonReg = document.getElementById("register"); // get register button from page 1
let ok2 = document.getElementById("ok2"); // get ok button from page 2
let ok3 = document.getElementById("ok3"); // get ok button from page 3
let buttonSubmit = document.getElementById("submit"); // get submit button from page 4

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
				buttonReg.click();
				break;
			case 2:
				ok2.click();
				break;
			case 3:
				ok3.click();
				break;
		}
	}
});

// initial state
page2.style.display = "none";
page3.style.display = "none";
page4.style.display = "none";

// name/email page (page 3) OK onclick function
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

// back buttons onclick functionality
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

function validateEmail(email) {
	var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
	return re.test(String(email).toLowerCase());
}

// // get the button elements (they are divs)
// let payCash = document.getElementById("w-node-2922c9864b4c-724d7631");
// let payTransfer = document.getElementById("w-node-b3c547ea4b4f-724d7631");
// let payWeChat = document.getElementById("w-node-79804cf717f6-724d7631");
// let payAli = document.getElementById("w-node-b10b15011cf4-724d7631");
// let payCard = document.getElementById("w-node-ccd45ade939b-724d7631");
// let payPoli = document.getElementById("w-node-1287f971e519-724d7631");

// // find the button being used for payment
// let buttonInUse;
// [payCash, payTransfer, payWeChat, payAli, payCard, payPoli].forEach(button, index => {
// 	button.addEventListener("click", function() {
// 		console.log(button);
// 		button.classList.add()
// 		buttonInUse = index;
// 	});
// });
