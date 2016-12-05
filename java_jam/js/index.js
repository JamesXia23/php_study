// changePage
function changePage(obj) {
    if (obj.id == 'navHome') {
        document.getElementById('mainHome').style.display = 'inline';
        document.getElementById('mainMenu').style.display = 'none';
        document.getElementById('mainMusic').style.display = 'none';
        document.getElementById('mainCountry').style.display = 'none';
        document.getElementById('mainLogin').style.display = 'none';
        document.getElementById('mainRegister').style.display = 'none';
        document.getElementById('mainEmployee').style.display = 'none';
        document.getElementById('mainCalculator').style.display = 'none';
    } else if (obj.id == 'navMenu') {
        document.getElementById('mainHome').style.display = 'none';
        document.getElementById('mainMenu').style.display = 'inline';
        document.getElementById('mainMusic').style.display = 'none';
        document.getElementById('mainCountry').style.display = 'none';
        document.getElementById('mainLogin').style.display = 'none';
        document.getElementById('mainRegister').style.display = 'none';
        document.getElementById('mainEmployee').style.display = 'none';
        document.getElementById('mainCalculator').style.display = 'none';

    } else if (obj.id == 'navMusic') {
        document.getElementById('mainHome').style.display = 'none';
        document.getElementById('mainMenu').style.display = 'none';
        document.getElementById('mainMusic').style.display = 'inline';
        document.getElementById('mainCountry').style.display = 'none';
        document.getElementById('mainLogin').style.display = 'none';
        document.getElementById('mainRegister').style.display = 'none';
        document.getElementById('mainEmployee').style.display = 'none';
        document.getElementById('mainCalculator').style.display = 'none';
    } else if (obj.id == 'navLogin') {
        document.getElementById('mainHome').style.display = 'none';
        document.getElementById('mainMenu').style.display = 'none';
        document.getElementById('mainMusic').style.display = 'none';
        document.getElementById('mainCountry').style.display = 'none';
        document.getElementById('mainLogin').style.display = 'inline';
        document.getElementById('mainRegister').style.display = 'none';
        document.getElementById('mainEmployee').style.display = 'none';
        document.getElementById('mainCalculator').style.display = 'none';
    } else if (obj.id == 'navRegister') {
        document.getElementById('mainHome').style.display = 'none';
        document.getElementById('mainMenu').style.display = 'none';
        document.getElementById('mainMusic').style.display = 'none';
        document.getElementById('mainCountry').style.display = 'none';
        document.getElementById('mainLogin').style.display = 'none';
        document.getElementById('mainRegister').style.display = 'inline';
        document.getElementById('mainEmployee').style.display = 'none';
        document.getElementById('mainCalculator').style.display = 'none';
    } else if (obj.id == 'navCalculator') {
        document.getElementById('mainHome').style.display = 'none';
        document.getElementById('mainMenu').style.display = 'none';
        document.getElementById('mainMusic').style.display = 'none';
        document.getElementById('mainCountry').style.display = 'none';
        document.getElementById('mainLogin').style.display = 'none';
        document.getElementById('mainRegister').style.display = 'none';
        document.getElementById('mainEmployee').style.display = 'none';
        document.getElementById('mainCalculator').style.display = 'inline';
    } else if (obj.id == 'navCountry') {
        document.getElementById('mainHome').style.display = 'none';
        document.getElementById('mainMenu').style.display = 'none';
        document.getElementById('mainMusic').style.display = 'none';
        document.getElementById('mainCountry').style.display = 'inline';
        document.getElementById('mainLogin').style.display = 'none';
        document.getElementById('mainRegister').style.display = 'none';
        document.getElementById('mainEmployee').style.display = 'none';
        document.getElementById('mainCalculator').style.display = 'none';
    } else if (obj.id == 'navEmployee') {
        document.getElementById('mainHome').style.display = 'none';
        document.getElementById('mainMenu').style.display = 'none';
        document.getElementById('mainMusic').style.display = 'none';
        document.getElementById('mainCountry').style.display = 'none';
        document.getElementById('mainLogin').style.display = 'none';
        document.getElementById('mainRegister').style.display = 'none';
        document.getElementById('mainEmployee').style.display = 'inline';
        document.getElementById('mainCalculator').style.display = 'none';
    }

}
/**
 * Register
 */
function registerCheck() {
    var fisrtName = document.getElementById('registerFName');
    var lastName = document.getElementById('registerLName');
    var registerEmail = document.getElementById('registerEmail');
    var registerTel = document.getElementById('registerTel');
    var registerAddress = document.getElementById('registerAddress');
    var password = document.getElementById('registerPassword');
    var rePassword = document.getElementById('registerRepassword');
    if (fisrtName.value == '') {
        showDialog('Register', 'fisrtName can\'t be null');
        fisrtName.focus();
    } else if (registerEmail.value == '') {
        showDialog('Register', 'email can\'t be null');
        registerEmail.focus();
    } else if (password.value == '') {
        showDialog('Register', 'password can\'t be null');
        password.focus();
    } else if (rePassword.value == '') {
        showDialog('Register', 'rePassword can\'t be null');
        rePassword.focus();
    } else if (!/[A-z]+/.test(fisrtName.value)) {
        showDialog('Register', 'fisrtName must only have characters');
        fisrtName.value = '';
    } else if (!/[a-z0-9]+([._\\-]*[a-z0-9])*@([a-z0-9]+[-a-z0-9]*[a-z0-9]+.){1,63}[a-z0-9]+/.test(registerEmail.value)) {
        showDialog('Register', 'email is invalid');
        registerEmail.value = '';
    } else if (password.value != rePassword.value) {
        showDialog('Register', 'rePassword is not equal to password');
        rePassword.value = '';
    } else {
        // window.alert('welcome back, JamesXia');
        showDialog('Register', 'Register Success');
        fisrtName.value = '';
        lastName.value = '';
        registerEmail.value = '';
        registerTel.value = '';
        registerAddress.value = '';
        password.value = '';
        rePassword.value = '';
        changePage(document.getElementById('navHome'));
    }
}
/**
 * Login
 */
var errorCount = 0;

function loginCheck() {
    var email = document.getElementById('loginEmail');
    var password = document.getElementById('loginPassword');
    if (email.value == '') {
        // window.alert('email can\'t be null');
        showDialog('Login', 'email can\'t be null');
        email.focus();
    } else if (password.value == '') {
        // window.alert('password can\'t be null');
        showDialog('Login', 'password can\'t be null');
        password.focus();
    } else if (email.value != '2013150391@szu.edu.cn' || password.value != '123456') {
        errorCount++;
        if (errorCount < 3) {
            // window.alert('email or password is error');
            showDialog('Login', 'email or password is wrong');
            email.focus();
        } else {
            // window.alert('sorry, You\'ve made mistakes for three times');
            showDialog('Login', 'sorry, You\'ve made mistakes for three times');
            email.disabled = true;
            email.placeholder = 'you can\'t input anymore';
            password.disabled = true;
            password.placeholder = 'you can\'t input anymore';
        }
        email.value = '';
        password.value = '';
    } else {
        // window.alert('welcome back, JamesXia');
        showDialog('Login', 'welcome back, JamesXia');
        changePage(document.getElementById('navHome'));
        document.getElementById('registerList').removeChild(document.getElementById('navRegister'));
        document.getElementById('loginList').removeChild(document.getElementById('navLogin'));
        document.getElementById('more').innerHTML = 'JamesXia';
        errorCount = 0;
    }
}
// changeBackground
function changeBackground(colorValue) {
    document.body.style.backgroundColor = colorValue;
}

// View Offers
var offerCount = 0;
function checkOffer() {
    offerCount++;
    if (offerCount == 1) {
        showDialog('View Offers', 'Congratulation User');
    } else if (offerCount == 3) {
        showDialog('View Offers', 'Discount offer of 20% on all products');
    } else if (offerCount >= 5) {
        showDialog('View Offers', 'Hurray!!! Mega offer of 50% on all products');
    }
}

// changeTime
function changeTime() {
    var nowTime = new Date()
    document.getElementById('time').innerHTML = nowTime.getFullYear() + "/" + (nowTime.getMonth() + 1) + "/" + nowTime.getDate() + " " + nowTime.getHours() + ":" + nowTime.getMinutes() + ":" + nowTime.getSeconds();
    setTimeout(changeTime, 1000);
}

/**
 * Calculator
 */
// checkNumber
function checkNumber(op) {
    var opDiv = document.getElementById(op.id + 'Div');
    var opSpan = document.getElementById(op.id + 'Span');
    var value = op.value.replace(/^\s+/, "").replace(/\s+$/, "");
    if (value == "") {
        opDiv.className = 'form-group row';
        opSpan.className = '';
    } else if (isNaN(op.value)) {
        opDiv.className = 'form-group row has-feedback has-error';
        opSpan.className = 'glyphicon glyphicon-remove form-control-feedback';
    } else {
        opDiv.className = 'form-group row has-feedback has-success';
        opSpan.className = 'glyphicon glyphicon-ok form-control-feedback';
    }
}

// clearNumber
function clearNumber() {
    op1 = document.getElementById('operator1');
    op2 = document.getElementById('operator2');
    op1.value = '';
    op2.value = '';
    checkNumber(op1);
    checkNumber(op2);
    document.getElementById('result').value = '';
}
// calculate
function calculate(obj) {
    var op1 = document.getElementById('operator1').value * 1;
    var op2 = document.getElementById('operator2').value * 1;
    if (isNaN(op1) || isNaN(op2)) {
        return;
    }

    var result = document.getElementById('result');
    if (obj.id == 'btnAdd') {
        result.value = op1 + op2;
    } else if (obj.id == 'btnSub') {
        result.value = op1 - op2;
    } else if (obj.id == 'btnProduct') {
        result.value = op1 * op2;
    } else if (obj.id == 'btnDivision') {
    	if (op2 == 0) {
    		showDialog('Calculator', "the op2 can be zero in division");
    		return;
    	}
        result.value = op1 / op2;
    }

}

// employees
var employees = {
    id: Array(0, 1, 2, 3, 4, 5, 6, 7, 8),
    name: Array('', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight'),
    address: Array('', 'Shenzhen', 'Shanghai', 'Beijing', 'Guangzhou', 'Chaozhou', 'Hangzhou', 'Fujian', 'Tianjin'),
    tel: Array('', '131-1111-1111', '132-2222-2222', '133-3333-3333', '134-4444-4444', '135-5555-5555', '136-6666-6666', '137-7777-7777', '138-8888-8888')
};
var curPage = 1;
var pages = Array('zero', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight');
function changeEmployee(obj) {
	
	if (obj.parentNode.className != 'disabled') {
		document.getElementById(pages[curPage]).parentNode.className = '';
		if (obj.id == 'previous') {
			curPage--;
			document.getElementById('nextLi').className = '';
			if (curPage == 1) {
				obj.parentNode.className = 'disabled';
			}

		} else if (obj.id == 'next') {
			curPage++;
			document.getElementById('previousLi').className = '';
			if (curPage == 8) {
				obj.parentNode.className = 'disabled';
			}
		} else {
			curPage = parseInt(obj.innerHTML);
			if (curPage == 1) {
				document.getElementById('previousLi').className = 'disabled';
			} else if (curPage == 8) {
				document.getElementById('nextLi').className = 'disabled';
			}
		}
		document.getElementById(pages[curPage]).parentNode.className = 'active';
		showEmployee(curPage);
	}		
	
}
function showEmployee(num) {
	document.getElementById('idTable').innerHTML = employees.id[num];
	document.getElementById('nameTable').innerHTML = employees.name[num];
	document.getElementById('addressTable').innerHTML = employees.address[num];
	document.getElementById('telTable').innerHTML = employees.tel[num];
}

// show the Dialog
function showDialog(title, message) {
    document.getElementById('dialogTitle').innerHTML = title;
    document.getElementById('dialogContent').innerHTML = message;
    $('#myModal').modal('toggle');
}

// hide the Dialog
function hideDialog() {
    $('#myModal').modal('hide');
}

// show the tips
function showTips(obj) {
    var input = document.createElement("div");
    if (obj.id == 'chinaImg') {
        input.id = "chinaDiv";
        input.className = "text-center alert alert-danger";
        input.innerHTML = "the People's Republic of China";
        document.getElementById("chinaTip").appendChild(input);
    } else if (obj.id == 'usImg') {
        input.id = "usDiv";
        input.className = "text-center alert alert-info";
        input.innerHTML = "the United States";
        document.getElementById("usTip").appendChild(input);
    } else {
        input.id = "ukDiv";
        input.className = "text-center alert alert-warning";
        input.innerHTML = "the United Kingdom";
        document.getElementById("ukTip").appendChild(input);
    }
}

// hide the tips
function hideTips(obj) {
    if (obj.id == 'chinaImg') {
        document.getElementById('chinaTip').removeChild(document.getElementById('chinaDiv'));
    } else if (obj.id == 'usImg') {
        document.getElementById('usTip').removeChild(document.getElementById('usDiv'));
    } else {
        document.getElementById('ukTip').removeChild(document.getElementById('ukDiv'));
    }
}
