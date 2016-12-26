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
//对注册的用户名进行查询
function usernameCheck(obj){
    document.getElementById("ifr").src = "index.php?c=register&a=usernameCheck&username=" + obj.value;
}
//注册表单检查
function registerCheck(obj){
    var id = obj.id;
    var value = obj.value.replace(/^\s+/, "").replace(/\s+$/, "");
    if (id == "registerEmail"){
        if (value == "") {
            showMessage(id, "邮箱不能为空", "form-group row has-feedback has-error", "glyphicon glyphicon-remove form-control-feedback");
        }else if(!/[a-z0-9]+([._\\-]*[a-z0-9])*@([a-z0-9]+[-a-z0-9]*[a-z0-9]+.){1,63}[a-z0-9]+/.test(value)){
            showMessage(id, "无效邮箱", "form-group row has-feedback has-error", "glyphicon glyphicon-remove form-control-feedback");
        }else{
            showMessage(id, "", "form-group row has-feedback has-success", "glyphicon glyphicon-ok form-control-feedback");
        }
    } else if (id == "registerPassword"){
        if (value == "") {
            showMessage(id, "密码不能为空", "form-group row has-feedback has-error", "glyphicon glyphicon-remove form-control-feedback");
        }else{
            showMessage(id, "", "form-group row has-feedback has-success", "glyphicon glyphicon-ok form-control-feedback");
        }
    } else if (id == "registerRepassword"){
        if (value == "") {
            showMessage(id, "确认密码不能为空", "form-group row has-feedback has-error", "glyphicon glyphicon-remove form-control-feedback");
        }else if(document.getElementById("registerPassword").value != value){
            showMessage(id, "两次密码不一致", "form-group row has-feedback has-error", "glyphicon glyphicon-remove form-control-feedback");
        }else{
            showMessage(id, "", "form-group row has-feedback has-success", "glyphicon glyphicon-ok form-control-feedback");
        }
    }
}
/**
 * Login
 */
//登陆表单检查
function loginCheck(obj) {
    var id = obj.id;
    var value = obj.value.replace(/^\s+/, "").replace(/\s+$/, "");
    if (value == "") {
        if (id == "loginName")
            showMessage(id, "用户名不能为空", "form-group row has-feedback has-error", "glyphicon glyphicon-remove form-control-feedback");
        else if (id == "loginPassword")
            showMessage(id, "密码不能为空", "form-group row has-feedback has-error", "glyphicon glyphicon-remove form-control-feedback");
    } else {
        showMessage(id, "", "form-group row has-feedback has-success", "glyphicon glyphicon-ok form-control-feedback");
    }
}
//验证码检查
function captchaCheck(obj){
    var captcha = obj.value;
    document.getElementById("ifr").src = "index.php?c=login&a=captchaCheck&captcha=" + captcha;
}
//登出
function showLogoutDiglog(){
    showDialog("您确定要登出吗?","index.php?c=login&a=logout");
}
/**
 * 根据id找到表单中的标签并更新提示信息以及符号
 * @param id
 * @param message 提示信息
 * @param divClassName 红框绿框
 * @param spanClassName 勾或叉
 */
function showMessage(id, message, divClassName, spanClassName){
    document.getElementById(id + 'Div').className = divClassName;
    document.getElementById(id + 'Span').className = spanClassName;
    document.getElementById(id + 'Message').innerText = message;
}
//根据url进行局部刷新
function showPage(url){
    document.getElementById('ifr').src = url;
}
// 显示对话框
function showDialog(message, link) {
    //document.getElementById('dialogConfirm').dataDismiss="modal";
    document.getElementById('dialogContent').innerHTML = message;
    document.getElementById('dialogConfirmA').href = link;
    $('#myDialog').modal('toggle');
}

// 隐藏对话框
function hideDialog() {
    $('#myDialog').modal('hide');
}

//商品
//添加相册图片位
function addImg(){
    var mainAddGoods = document.getElementById('goodsImgs');
    var newNode = document.createElement('div');
    newNode.innerHTML = "<label for='img_url' class='col-sm-2 col-xs-5 control-label'><a href='javascript:;' onclick='subImg(this)'>[-]</a></label>"
        + "<div class='col-sm-9 col-xs-12'>"
        + "<input type='file' id='img_url' name='img_url[]' class='form-control'>"
        + "</div>"
        + "<div class='col-sm-1 col-xs-0'></div>";
    mainAddGoods.appendChild(newNode);
}

//删除相册图片位
function subImg(obj){
    parentNode = obj.parentNode.parentNode;
    parentNode.parentNode.removeChild(parentNode);
}

//增加商品属性位
function addAttr(){
    var attr_group = document.getElementById('attr_group');
    var newNode = document.createElement('div');
    newNode.innerHTML =
         "<!-- 属性名称 -->"
        + "<div class='form-group row'>"
        + "<label for='goodsAttrName' class='col-sm-3 col-xs-5 control-label'><a href='javascript:;' onclick='subAttr(this)'>[-]</a>属性名称:</label>"
        + "<div class='col-sm-9 col-xs-12'>"
        + "<input type='type' id='goodsAttrName' name='attr_name[]' class='form-control' placeholder='please input your attrName'>"
        + "<span class='' id='goodsNameSpan'></span>"
        + "<span id='goodsNameMessage' style='color: red'></span>"
        + "</div>"
        + "</div>"
        + "<!-- 属性值 -->"
        + "<div class='form-group row'>"
        + "<label for='goodsName' class='col-sm-3 col-xs-5 control-label'>属性值:</label>"
        + "<div class='col-sm-9 col-xs-12'>"
        + "<input type='type' id='goodsAttrName' name='attr_value[]' class='form-control' placeholder='值之间使用英文逗号隔开'>"
        + "<span class='' id='goodsNameSpan'></span>"
        + "<span id='goodsNameMessage' style='color: red'></span>"
        + "</div>"
        + "</div>";
    attr_group.appendChild(newNode);
}
function subAttr(obj){
    parentNode = obj.parentNode.parentNode.parentNode;
    parentNode.parentNode.removeChild(parentNode);
}
// changeBackground
function changeBackground(colorValue) {
    document.body.style.backgroundColor = colorValue;
}

function searchKeyword(){
    var keyword = document.getElementById('search').value;
    showPage('index.php?c=goods&a=index&keyword='+keyword);
}

// changeTime
function changeTime() {
    var nowTime = new Date()
    document.getElementById('time').innerHTML = nowTime.getFullYear() + "/" + (nowTime.getMonth() + 1) + "/" + nowTime.getDate() + " " + nowTime.getHours() + ":" + nowTime.getMinutes() + ":" + nowTime.getSeconds();
    setTimeout(changeTime, 1000);
}

function updateCart(obj){
    var goods_id = obj;
    var value = document.getElementById(obj).value;
    window.location.href = "index.php?c=cart&a=update&goods_id="+obj+"&goods_number="+value;
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
