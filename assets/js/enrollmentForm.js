// get the pages
let page1 = document.getElementsByClassName("page1")[0];
let page2 = document.getElementsByClassName("page2")[0];
let page3 = document.getElementsByClassName("page3")[0];
let page4 = document.getElementsByClassName("page4")[0];

let buttonReg = document.getElementById("register"); // get register button from page 1
let ok2 = document.getElementById("ok2"); // get ok button from page 2
let ok3 = document.getElementById("ok3"); // get ok button from page 3
let buttonSubmit = document.getElementById("submit"); // get submit button from page 4

// Enter key (keycode 13) goes to the next page.
window.addEventListener("keypress", function(e) {
	if (e.keyCode === 13) {
		nextPage();
	}
});

// initial state
page2.style.display = "none";
page3.style.display = "none";
page4.style.display = "none";

// each button switches to the next page
[buttonReg, ok2, ok3].forEach(button => {
	button.onClick = nextPage;
});

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

// go to the previous page of the membership system
function previousPage() {
	switch (findActivePage()) {
		case 2:
			page1.style.display = "flex";
			page2.style.display = "none";
			page3.style.display = "none";
			page4.style.display = "none";
			break;
		case 3:
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
let tick = document.getElementById("tick");
let exclamation = document.getElementById("exclamation");
let loading = document.getElementById("loading");
let errorMsgs = document.getElementsByClassName("div-errormsg");
console.log(errorMsgs);
tick.style.display = "none";
exclamation.style.display = "none";
loading.style.display = "none";
errorMsgs[0].style.display = "none";
