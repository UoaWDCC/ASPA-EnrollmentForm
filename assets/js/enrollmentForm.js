// get the pages
let page1 = document.getElementsByClassName("page1")[0];
let page2 = document.getElementsByClassName("page2")[0];
let page3 = document.getElementsByClassName("page3")[0];
let page4 = document.getElementsByClassName("page4")[0];

// Enter key (keycode 13) goes to the next page.
window.addEventListener("keypress", function(e) {
	if (e.keyCode === 13) {
		nextPage();
	}
});

let buttonReg = document.getElementById("register"); // get register button from page 1
let ok2 = document.getElementById("ok2"); // get ok button from page 2
let ok3 = document.getElementById("ok3"); // get ok button from page 3
let buttonSubmit = document.getElementById("submit"); // get submit button from page 4

// initial state
page2.style.display = "none";
page3.style.display = "none";
page4.style.display = "none";

// each button switches to the next page
buttonReg.onClick = function() {
	page1.style.display = "none";
	page2.style.display = "flex";
	page3.style.display = "none";
	page4.style.display = "none";
};
ok2.onClick = function() {
	page1.style.display = "none";
	page2.style.display = "none";
	page3.style.display = "flex";
	page4.style.display = "none";
};
ok3.onClick = function() {
	page1.style.display = "none";
	page2.style.display = "none";
	page3.style.display = "none";
	page4.style.display = "flex";
};
buttonSubmit.onClick = function() {
	// redirect to Green/Grey tick page
};

// go to the next page of the membership system
function nextPage() {
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
let tick = document.getElementById("tick");
let exclamation = document.getElementById("exclamation");
let loading = document.getElementById("loading");
let errorMsgs = document.getElementsByClassName("div-errormsg");
console.log(errorMsgs);
tick.style.display = "none";
exclamation.style.display = "none";
loading.style.display = "none";
errorMsgs[0].style.display = "none";
