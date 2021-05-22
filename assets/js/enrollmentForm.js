// ==========================================
//       Getting the document elements
// ==========================================

// get the pages
let page1 = document.getElementById("div-page1");
let page2 = document.getElementById("div-page2");
let page3 = document.getElementById("div-page3");
let page4 = document.getElementById("div-page4");

// get the buttons
let buttonReg = document.getElementById("register"); // register button from page 1
let ok2 = document.getElementById("ok2"); // ok button from page 2
let ok3 = document.getElementById("ok3"); // ok button from page 3
let back3 = document.getElementById("div-back-page3"); // back button from page 3
let submit = document.getElementById("div-submit"); // submit button from page 4
let proceedPayment = document.getElementById("div-proceedpayment"); // proceed payment button from page 4
let back4 = document.getElementById("div-back-page4"); // back button from page 4

// get name and email inputs on page 3
let inputEmail = document.getElementById("field-2");

// get email validation icons/messages on page 3
let tickEmail = document.getElementById("tick-email");
let exclamationEmail = document.getElementById("exclamation-email");
let loading = document.getElementById("loading");
let errorMsgs = document.getElementsByClassName("div-errormsg");
let errorMsgArray = document.getElementsByClassName("p-errormessage");
const notSignedUpUnpaidErr = [
    "Unrecognized email, please use the email you signed up to ASPA with.",
    "To sign up: <a href='https://bit.ly/3xl5bzx' target='_blank'> https://bit.ly/3xl5bzx </a>",
];
const signedUpUnpaidErr = [
    "You have signed up, but have not paid for membership fees.",
    "Please contact the ASPA Team to pay for membership.",
];
const alreadyPaidEventErr = [
    "Oops! This email has already paid for this event.",
    "Please use a different account or email.",
];

// get the payment button types on page 4
let payCash = document.getElementById("btn-cash");
let payTransfer = document.getElementById("btn-banktransfer");
let payWeChat = document.getElementById("btn-wechat");
let payAli = document.getElementById("btn-alipay");
let payCard = document.getElementById("btn-creditcard");
let payPoli = document.getElementById("btn-poliay");

// Email Address
let emailAddress = "";
// let name = "";
let paymentMethod = "";

// ==========================================
//        Getting the base_url
// ==========================================

let base_url = document.getElementById("base_url").innerHTML;

// ==========================================
//        Setup of Initial state
// ==========================================

page2.style.display = "none";
page3.style.display = "none";
page4.style.display = "none";

// validation icons/error message on page 3
loading.style.visibility = "hidden";
loading.style.opacity = "0";
loading.style.transition = "visibility 0s, opacity 0.2s linear";
exclamationEmail.style.visibility = "hidden";
exclamationEmail.style.opacity = "0";
exclamationEmail.style.transition = "visibility 0s, opacity 0.2s linear";
tickEmail.style.visibility = "hidden";
tickEmail.style.opacity = "0";
tickEmail.style.transition = "visibility 0s, opacity 0.2s linear";
errorMsgs[0].style.visibility = "hidden";
errorMsgs[0].style.opacity = "0";
errorMsgs[0].style.transition = "visibility 0s, opacity 0.2s linear";

// hide Submit and Proceed Payment buttons on page 4
submit.style.display = "none";
proceedPayment.style.display = "none";

// ==========================================
//             Page Navigation
// ==========================================

/**
 * go to the next page of the membership system
 */
function nextPage() {
    switch (findActivePage()) {
        case 1:
            page1.style.display = "none";
            page2.style.display = "flex";
            page3.style.display = "none";
            page4.style.display = "none";
            break;
        case 2:
            page2.classList.remove("page2-appear");
            page3.classList.add("page3-appear-only-fade");
            page1.style.display = "none";
            page2.style.display = "none";
            page3.style.display = "flex";
            page4.style.display = "none";

            setTimeout(() => inputEmail.focus(), 1000); // autofocus to name field
            break;
        case 3:
            page3.classList.remove("page3-appear");
            page4.classList.add("page4-appear-only-fade");
            page1.style.display = "none";
            page2.style.display = "none";
            page3.style.display = "none";
            page4.style.display = "flex";
            break;
    }
}


/**
 * go to the previous page of the membership system
 */
function previousPage() {
    switch (findActivePage()) {
        case 3:
            page2.classList.add("page2-appear");
            page1.style.display = "none";
            page2.style.display = "flex";
            page3.style.display = "none";
            page4.style.display = "none";
            break;
        case 4:
            page3.classList.add("page3-appear");
            page1.style.display = "none";
            page2.style.display = "none";
            page3.style.display = "flex";
            page4.style.display = "none";
            break;
    }
}

// Enter key (keycode 13) triggers the click event of the appropriate buttons to go to next page.
window.addEventListener("keydown", function(e) {
    if (e.keyCode === 13) {
        switch (findActivePage()) {
            case 1:
                buttonReg.click(); //buttonReg does not need an onclick function declaration because it is handled by Webflow
                break;
            case 2:
                ok2.click(); //ok2 does not need an onclick function declaration because it is handled by Webflow
                break;
            case 3:
                // Checking to see if the ok button is disabled. The enter key will only work if it is not disabled
                if ($("#div-ok3").css('pointer-events') != 'none') {
                    ok3.click();
                }
                break;
            case 4:
                if (isActive(submit)) {
                    submit.click();
                } else if (isActive(proceedPayment)) {
                    proceedPayment.click();
                }
                break;
        }
    }
});

ok2.onclick = function() {
    nextPage();
};

// back buttons onclick function goes to the previous page
back3.onclick = function() {
    previousPage();
};
back4.onclick = function() {
    previousPage();
};

// ==========================================
//    Name/Email Page (page 3) Functionality
// ==========================================

// name/email page (page 3) OK button onclick name and email validation
ok3.onclick = function() {

    hideAllWarnings();
    emailAddress = inputEmail.value.trim(); // collect email

    if (emailAddress.length === 0) {
        inputEmail.style.border = "1px solid red";
        return;
    } else {
        inputEmail.style.border = "1px solid #00A22C";
    }

    // Disabling the ok button to prevent user from clicking on it multiple times
    document.getElementById("ok3").classList.add("btn-disabled"); // stopping the ok button from increasing in size when hovered over
    $("#div-ok3").css("opacity", "0.2"); // reducing the opacity
    $("#div-ok3").css("pointer-events", "none"); // making the ok div unclickable

    showLoading();
    $.ajax({
        cache: false,
        url: base_url + "index.php/EnrollmentForm/validate",
        method: "POST",
        data: { emailAddress: emailAddress },
        // if the validate() url functions correctly (even if it returns True/False), then success function executes.
        success: function(data) {
            console.log(data);
            // data is a JSON object with the following properties:
            // is_success: True/False (if the email validation succeeeded)
            // message: any message
            // extra: any further information
            const signedUpUnpaid = "Error: signed up but not paid"; // edit these if the 'extra' message is modified
            const alreadyPaidForEvent = "Error: already paid for event";
            if (data.is_success === "True") {
                showSuccess();
                setTimeout(() => {
                    nextPage();
                    enableOkButton();
                }, 1000);

            } else if (
                data.is_success === "False" &&
                data.extra === signedUpUnpaid
            ) {
                showWarning();
                // change the error message to be "signed up but unpaid" warning
                errorMsgArray[0].innerHTML = signedUpUnpaidErr[0];
                errorMsgArray[1].innerHTML = signedUpUnpaidErr[1];
                enableOkButton();
                return;
            } else if (
                data.is_success === "False" &&
                data.extra === alreadyPaidForEvent
            ) {
                showWarning();
                // change the error message to be "already paid" warning
                errorMsgArray[0].innerHTML = alreadyPaidEventErr[0];
                errorMsgArray[1].innerHTML = alreadyPaidEventErr[1];
                enableOkButton();
                return;
            } else {
                showWarning();
                // change the error message to be "unrecognized email, please sign up" warning
                errorMsgArray[0].innerHTML = notSignedUpUnpaidErr[0];
                errorMsgArray[1].innerHTML = notSignedUpUnpaidErr[1];
                enableOkButton();
                return;
            }
        },
    });
};

/** 
 * Enable ok button
 */
function enableOkButton() {
    document.getElementById("ok3").classList.remove("btn-disabled"); // enabling the increasing in size when hovered over
    $("#div-ok3").css("opacity", "1"); // restoring the opacity
    $("#div-ok3").css("pointer-events", "auto"); // making the button clickable
}

/**
 * Hides all feedback elements (tick, errors, loading, resets the input fields)
 */
function hideAllWarnings() {
    tickEmail.style.visibility = "hidden";
    tickEmail.style.opacity = "1";
    exclamationEmail.style.visibility = "hidden";
    exclamationEmail.style.opacity = "0";
    errorMsgs[0].style.visibility = "hidden";
    errorMsgs[0].style.opacity = "0";
    loading.style.visibility = "hidden";
    loading.style.opacity = "0";
}

/**
 * shows the loading icon
 */
function showLoading() {
    loading.style.visibility = "visible";
    loading.style.opacity = "1";
}

/**
 * show the successful feedback
 */
function showSuccess() {
    loading.style.visibility = "hidden";
    loading.style.opacity = "0";
    tickEmail.style.visibility = "visible";
    tickEmail.style.opacity = "1";
    exclamationEmail.style.visibility = "hidden";
    exclamationEmail.style.opacity = "0";
    errorMsgs[0].style.visibility = "hidden";
    errorMsgs[0].style.opacity = "0";
    inputEmail.style.border = "1px solid #00A22C";
}

/**
 * show the error feedback
 */
function showWarning() {
    loading.style.visibility = "hidden";
    loading.style.opacity = "0";
    exclamationEmail.style.visibility = "visible";
    exclamationEmail.style.opacity = "1";
    errorMsgs[0].style.visibility = "visible";
    errorMsgs[0].style.opacity = "1";
    inputEmail.style.border = "1px solid red";
}

// ==========================================
//    Payment Page (page 4) Functionality
// ==========================================

// Buttons which are to be temporarily disabled
const disabledButtons = [payWeChat, payAli, payPoli];

/*
	sets up event listener for button click except for the buttons which are disabled 
	(the buttons to be disabled will not do anything when clicked on)
*/
[payCash, payTransfer, payWeChat, payAli, payCard, payPoli].forEach(
    (item, index) => {
        if (!disabledButtons.includes(item)) {
            item.addEventListener("click", function(e) {
                toggleButton(item);
                showButton(index);
            });
        }
    }
);

/*
	make the to-be-disabled-buttons look like they are disabled 
	(stopping them from increasing in size when hovered over, reducing their opacity, and making them unclickable)
 */

disabledButtons.forEach((buttonToDisable) => {
    buttonToDisable.classList.add("btn-disabled"); // stopping the disabled buttons from increasing in size when hovered over
    switch (buttonToDisable) {
        // More cases need to be added if any other buttons are to be disabled
        case payWeChat:
            $(".div-wechatpay").css("opacity", "0.2"); // reducing the opacity
            $(".btn-online.btn-wechatpay").css("pointer-events", "none"); // making the button unclickable
            break;
        case payAli:
            $(".div-alipay").css("opacity", "0.2");
            $(".btn-online.btn-alipay").css("pointer-events", "none");
            break;
        case payPoli:
            $(".div-polipay").css("opacity", "0.2");
            $(".btn-online.btn-polipay").css("pointer-events", "none");
            break;
    }
});

/**
 * make the buttons look like they are toggled
 * @param {Object} buttonInUse
 */
function toggleButton(buttonInUse) {
    [payCash, payTransfer, payWeChat, payAli, payCard, payPoli].forEach(
        (button) => {
            if (buttonInUse === button) button.classList.add("toggled");
            else button.classList.remove("toggled");
        }
    );
}

/**
 * show Submit button if user pays using Cash/Bank transfer
 * show proceed payment if user pays using Wechat/AliPay/Credit/Debit/PoLi Pay
 * @param {int} index
 */
function showButton(index) {
    if (index === 0 || index === 1) {
        submit.style.display = "flex";
        proceedPayment.style.display = "none";
        if (index === 0) {
            paymentMethod = "cash";
        } else {
            paymentMethod = "transfer";
        }
    } else {
        submit.style.display = "none";
        proceedPayment.style.display = "flex";
        paymentMethod = "online";
    }
}

// TODO: these two buttons must connect to the next step of the enrollment form
submit.onclick = function() {

    $("#enrollment-form").attr(
        "action",
        base_url + "EnrollmentForm/makeOfflinePayment"
    );
    $("#payment-method-field").attr("value", paymentMethod);

    document.getElementById("enrollment-form").submit();
};

proceedPayment.onclick = function() {
    // get the active button
    var toggled_index;
    [payCash, payTransfer, payWeChat, payAli, payCard, payPoli].forEach(
        (item, index) => {
            if (item.classList.contains("toggled")) toggled_index = index;
        }
    );

    if (toggled_index < 2) {
        alert("asdf");
    } else if (toggled_index == 4) {
        //Stripe Payment
        $("#enrollment-form").attr(
            "action",
            base_url + "EnrollmentForm/MakeStripePayment"
        );
        document.getElementById("enrollment-form").submit();
        //window.open('http://localhost/ASPA-EnrollmentForm/EnrollmentForm/MakeStripePayment?email=');
    } else {
        //IEpay
    }
    // alert("Taking you to proceed payment!");
};

// ==========================================
//             Helper Functions
// ==========================================

/**
 * finds the page that is currently active
 */
function findActivePage() {
    if (isActive(page1)) return 1;
    else if (isActive(page2)) return 2;
    else if (isActive(page3)) return 3;
    else if (isActive(page4)) return 4;
}

/**
 * Checks if an element is active (i.e. on the page)
 */
function isActive(el) {
    return !(el.offsetParent === null);
}