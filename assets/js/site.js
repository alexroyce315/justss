$(document).ready(function () {

    // Variables
    var $codeSnippets = $('.code-example-body'),
      $nav = $('.navbar'),
      $body = $('body'),
      $window = $(window),
      $popoverLink = $('[data-popover]'),
      navOffsetTop = $nav.offset().top,
      $document = $(document),
      entityMap = {
          "&": "&amp;",
          "<": "&lt;",
          ">": "&gt;",
          '"': '&quot;',
          "'": '&#39;',
          "/": '&#x2F;'
      }

    function init() {
        analitics();
        $window.on('scroll', onScroll)
        $window.on('resize', resize)
        $popoverLink.on('click', openPopover)
        $document.on('click', closePopover)
        $('a[href^="#"]').on('click', smoothScroll)
        buildSnippets();
    }

    function smoothScroll(e) {
        e.preventDefault();
        $(document).off("scroll");
        var target = this.hash,
        menu = target;
        $target = $(target);
        $('html, body').stop().animate({
            'scrollTop': $target.offset().top - 40
        }, 0, 'swing', function () {
            window.location.hash = target;
            $(document).on("scroll", onScroll);
        });
    }

    function openPopover(e) {
        e.preventDefault()
        closePopover();
        var popover = $($(this).data('popover'));
        popover.toggleClass('open')
        e.stopImmediatePropagation();
    }

    function closePopover(e) {
        if ($('.popover.open').length > 0) {
            $('.popover').removeClass('open')
        }
    }

    $("#button").click(function () {
        $('html, body').animate({
            scrollTop: $("#elementtoScrollToID").offset().top
        }, 2000);
    });

    function resize() {
        $body.removeClass('has-docked-nav')
        navOffsetTop = $nav.offset().top
        onScroll()
    }

    function onScroll() {
        if (navOffsetTop < $window.scrollTop() && !$body.hasClass('has-docked-nav')) {
            $body.addClass('has-docked-nav')
        }
        if (navOffsetTop > $window.scrollTop() && $body.hasClass('has-docked-nav')) {
            $body.removeClass('has-docked-nav')
        }
    }

    function escapeHtml(string) {
        return String(string).replace(/[&<>"'\/]/g, function (s) {
            return entityMap[s];
        });
    }

    function buildSnippets() {
        $codeSnippets.each(function () {
            var newContent = escapeHtml($(this).html())
            $(this).html(newContent)
        })
    }

    init();

    // pjax open a link
    if ($.support.pjax) {
        $.pjax.defaults.timeout = 1200;
        $(document).pjax('a[data-pjax]', '#pjax-container');
        $(document).on('pjax:send', function () {
            NProgress.start();
        });
        $(document).on('pjax:complete', function () {
            fixPageTitle();
            NProgress.done();
            analitics();
        });
    }

    $(document).on('click', 'ul#node > li.nodeItem', function () {
        nodeId = $(this).attr('data-node');
        var box = document.createElement("div");
        var colse = document.createElement("a");
        colse.style.cssText = "position:absolute;top:-20px;right:-20px;width:42px;height:42px;line-height:42px;text-align:center;box-shadow:rgba(0,0,0,.2) 2px 2px 2px;border-radius:50%;color:white;background:#333;";
        colse.innerHTML = "Close";
        colse.href = 'javascript:;';
        box.appendChild(colse);
        box.style.cssText = "position:fixed;top:50%;left:50%;transform:translate3d(-50%,-50%,0);box-shadow:rgba(0,0,0,.2) 2px 2px 2px;border:1px solid #ddd;background:white";
        var iframe = document.createElement("iframe");
        iframe.width = "800px";
        iframe.height = "500px";
        iframe.style.cssText = "border:0;";
        iframe.src = '/user/node/' + nodeId + '.html';
        box.appendChild(iframe);
        document.body.appendChild(box);

        colse.onclick = function () {
            document.body.removeChild(box);
        }
    });

    $(document).on('click', '#checkIn', function () {
        $btn = $(this);
        $.get('/user/checkIn.html', function (data) {
            data = JSON.parse(data);
            if (data.result) {
                showNotice('success', data.msg);
                //$('#pjax-container').load('/user.html #pjax-container');
                window.location.replace(window.location.href);
            } else {
                showNotice('info', data.msg);
            }
        });
    });
    
    $(document).on('click', '#editBtn', function () {
        $btn = $(this);
        $btn.attr('disabled', 'disabled').html('Submit...');
        $.post(window.location.href, $('#myform').serialize(), function (data) {
            data = JSON.parse(data);
            if (data.result) {
                showNotice('success', data.msg);
                $btn.html('Edit Successfully.');
            } else {
                showNotice('error', data.msg);
                $btn.attr('disabled', false).html('Edit It.');
                return false;
            }
        });
    });

    $(document).on('click', '#submitBtn', function () {
        $btn = $(this);
        $btn.attr('disabled', 'disabled').html('Submit...');
        if (passwordCheck() && passwordCompare()) {
            $.post(window.location.href, $('#myform').serialize(), function (data) {
                data = JSON.parse(data);
                if (data.result) {
                    showNotice('success', data.msg);
                    $btn.html('Submit Successfully.');
                } else {
                    showNotice('error', data.msg);
                    $btn.attr('disabled', false).html('Make The Chage.');
                    return false;
                }
            });
        } else {
            showNotice('error', 'Check Your Email And Password.');
            $btn.attr('disabled', false).html('Make The Chage.');
            return false;
        }
    });

    $(document).on('click', '#forgetBtn', function () {
        $btn = $(this);
        $btn.attr('disabled', 'disabled').html('Request A Reset...');
        if (emailCheck()) {
            $.post(window.location.href, $('#myform').serialize(), function (data) {
                data = JSON.parse(data);
                if (data.result) {
                    showNotice('success', data.msg);
                    $btn.html('Request Successfully.');
                } else {
                    showNotice('error', data.msg);
                    $btn.attr('disabled', false).html('Request A Reset.');
                    return false;
                }
            });
        } else {
            showNotice('error', 'Check Your Email.');
            $btn.attr('disabled', false).html('Request A Reset.');
            return false;
        }
    });
    
    $(document).on('click', '#resetBtn', function () {
        $btn = $(this);
        $btn.attr('disabled', 'disabled').html('Reset...');
        if (passwordCheck() && passwordCompare()) {
            $.post(window.location.href, $('#myform').serialize(), function (data) {
                data = JSON.parse(data);
                if (data.result) {
                    showNotice('success', data.msg);
                    $btn.html('Reset Successfully.');
                } else {
                    showNotice('error', data.msg);
                    $btn.attr('disabled', false).html('Reset Password.');
                    return false;
                }
            });
        } else {
            showNotice('error', 'Check Your Email.');
            $btn.attr('disabled', false).html('Reset Password.');
            return false;
        }
    });

    $(document).on('blur', '#emailInput', function () {
        return true;
    });

    $(document).on('click', '#portGenerate', function () {
        $btn = $(this);
        $.get('/user/portGenerate.html', function (data) {
            data = JSON.parse(data);
            if (data.result) {
                $('#portInput').val(data.port);
                showNotice('success', data.msg);
            }
        });
    });

    $(document).on('click', '#applyBtn', function () {
        $btn = $(this);
        $btn.attr('disabled', 'disabled').html('Apply...');
        if (emailCheck() && passwordCheck() && passwordCompare()) {
            $.post(window.location.href, $('#myform').serialize(), function (data) {
                data = JSON.parse(data);
                if (data.result) {
                    showNotice('success', data.msg);
                    $btn.html('Apply Successfully.');
                } else {
                    showNotice('info', data.msg);
                    $btn.attr('disabled', false).html('Apply An Account.');
                    return false;
                }
            });
        } else {
            showNotice('error', 'Check Email And Password.');
            $btn.attr('disabled', false).html('Apply An Account.');
            return false;
        }
    });

    $(document).on('click', '#loginBtn', function () {
        $btn = $(this);
        $btn.attr('disabled', 'disabled').html('Login In...');
        if (emailCheck() && passwordCheck()) {
            $.post(window.location.href, $('#myform').serialize(), function (data) {
                data = JSON.parse(data);
                if (data.result) {
                    showNotice('success', 'Login Successful.');
                    $btn.html('Login Successfully.');
                    window.parent.location.replace(data.msg.replace('\\', ''));
                } else {
                    showNotice('error', data.msg);
                    $btn.html('Let Me In.').attr('disabled', false);
                    return false;
                }
            });
        } else {
            showNotice('error', 'Check Your Email And Password.');
            $btn.html('Let Me In.').attr('disabled', false);
            return false;
        }
    });
});

function analitics(){
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
    ga('create', 'UA-8043473-5', 'auto');
    ga('send', 'pageview');
}

function fixPageTitle(){
    var pageTitle = $('h4.docs-header')[0].innerHTML.split(' &nbsp; ');
    pageTitle = pageTitle[0].split(',');
    var title = document.title.split('-');
    title[0] = 'Welcome' == pageTitle[0] ? 'Home ' : pageTitle[0] + ' ';
    document.title = title.join('-');
}

function passwordCompare(){
    var $passwordInput = $('#newPasswordInput');
    var $confirmPasswordInput = $('#confirmPasswordInput');
    if('' == $passwordInput.val() || $confirmPasswordInput.val() == $passwordInput.val()){
        if($confirmPasswordInput.hasClass('has-error')){
            $confirmPasswordInput.removeClass('has-error');
        }
        return true;
    } else{
        showNotice('warning', 'Confirm Password Must Be The Same As New Password.');
        if(!$confirmPasswordInput.hasClass('has-error')){
            $confirmPasswordInput.addClass('has-error').focus();
        }
        return false;
    }
}

function passwordCheck(){
    var $passwordInput = undefined == $('#passwordInput')[0] ? $('#newPasswordInput') : $('#passwordInput');
    var pass = $passwordInput.val();
    if('' != pass && pass.length > 5){
        if($passwordInput.hasClass('has-error')){
            $passwordInput.removeClass('has-error');
        }
        return true;
    } else{
        showNotice('warning', 'Password Is Not Correct.');
        if(!$passwordInput.hasClass('has-error')){
            $passwordInput.addClass('has-error').focus();
        }
        return false;
    }
}

function emailCheck(unic){
    var $emailInput = $('#emailInput');
    var email = $emailInput.val();
    var reg = /^([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+@([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+\.[a-zA-Z]{2,3}$/;

    if('' != email && reg.test(email)){
        if($emailInput.hasClass('has-error')){
            $emailInput.removeClass('has-error');
        }
        if(unic){
            $.get('/user/repeat.html?itemId='+email, function (data) {
                data = JSON.parse(data);
                if (data.result) {
                    showNotice('success', data.msg);
                } else{
                    showNotice('warning', data.msg);
                    return false;
                } 
            });
        }
        return true;
    } else {
        showNotice('warning', 'Email Is Not Correct.');
        if(!$emailInput.hasClass('has-error')){
            $emailInput.addClass('has-error').focus();
        }
        return false;
    }
}

function showNotice(type, msg){
    notif({
        msg: msg,
        type: type,
        position: "center"
    });
}

/**
 * 同步加载js脚本
 * @param id   需要设置的<script>标签的id
 * @param url   js文件的相对路径或绝对路径
 * @return {Boolean}   返回是否加载成功，true代表成功，false代表失败
 */
function loadJS(id,url){
    var  xmlHttp = null;
    if(window.ActiveXObject)//IE
    {
        try {
            //IE6以及以后版本中可以使用
            xmlHttp = new ActiveXObject("Msxml2.XMLHTTP");
        }
        catch (e) {
            //IE5.5以及以后版本可以使用
            xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
    }
    else if(window.XMLHttpRequest)//Firefox，Opera 8.0+，Safari，Chrome
    {
        xmlHttp = new XMLHttpRequest();
    }
    //采用同步加载
    xmlHttp.open("GET",url,false);
    //发送同步请求，如果浏览器为Chrome或Opera，必须发布后才能运行，不然会报错
    xmlHttp.send(null);
    //4代表数据发送完毕
    if ( xmlHttp.readyState == 4 )
    {
        //0为访问的本地，200到300代表访问服务器成功，304代表没做修改访问的是缓存
        if((xmlHttp.status >= 200 && xmlHttp.status <300) || xmlHttp.status == 0 || xmlHttp.status == 304)
        {
            var myHead = document.getElementsByTagName("HEAD").item(0);
            var myScript = document.createElement( "script" );
            myScript.language = "javascript";
            myScript.type = "text/javascript";
            myScript.id = id;
            try{
                //IE8以及以下不支持这种方式，需要通过text属性来设置
                myScript.appendChild(document.createTextNode(xmlHttp.responseText));
            }
            catch (ex){
                myScript.text = xmlHttp.responseText;
            }
            myHead.appendChild( myScript );
            return true;
        }
        else
        {
            return false;
        }
    }
    else
    {
        return false;
    }
}
