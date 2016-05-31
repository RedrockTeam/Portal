
/*
*兼容ie8placehodler
* 视口过小的话隐藏aside导航
* 登陆框框控件
* top控件
* 登录表单验证
*/
(function($){
    $(function(){
        //获取视口宽度
        var widthViewPort = screen.width < window.innerWidth ? screen.width : window.innerWidth;
        //如果视口不够宽的话就将侧边栏隐藏了,没有做......

        //dom====bind
        var $showLoginW   = $("#show-loginW"),
            $loginWapper  = $("#control-login"),
            $loginBar     = $loginWapper.find(".m-login-bar"),
            $loginForm    = $loginWapper.find(".login-form");

        var userId  = $loginWapper.find(".user-id"),
            userPwd = $loginWapper.find(".user-pwd");

        //检测 ie8-
        var isIEE = /MSIE (\d+)\.0/i.exec(window.navigator.userAgent);

        //如果为ie8(不支持placehodolder)则脚本添加
        if(isIEE && isIEE[1] <= 9){
            userPwd.remove();

            userId.after( $('<input type="text" class="user-pwd f-db f-fs2">') );

            userId.val("昵称、学号、教师编号、E-mail地址");
            userPwd.val("通行证密码(6-16)位");

            userId.focus(function(){
                $(this).val("");
            });
            $loginWapper.find(".user-pwd").focus(function(){
                $(this).val("");
                $(this).attr("type","password");
            });
        }

        //打开登录弹出框
       $showLoginW.on('click',function(){
            $loginWapper.css({display:"block"});
            $loginBar.animate(
                {
                    width   : '419px',
                    height  : '207px',
                    opacity : 1
                },
                {
                    callback: function(){
                        alert(436578);
                    }
                }
           );
        });

        //关闭登陆框
       $("#control-login .u-exit, #control-login .s-bac").on('click',function(){
           $loginBar.animate(
               {
                   width   : '0px',
                   height  : '0px',
                   opacity : 0
               },
               function(){
                   $loginWapper.css({display:"none"});
               }
           );
       });

        //验证登录表单
        // var regUserId  = /^[\s\S]{6,16}$/,
        //     regUserPwd = /^[\s\S]{6,16}$/;

        // $loginForm.submit(function(){
        //     var valueUserId  = $loginWapper.find(".user-id").val(),
        //         valueUserPwd = $loginWapper.find(".user-pwd").val();

        //     if( !regUserId.exec(valueUserId)){
        //         alert("用户名输入有误");
        //         return false;
        //     }
        //     if( !regUserPwd.exec(valueUserPwd)){
        //         alert("密码输入有误");
        //         return false;
        //     }
        //     checkLogin(valueUserId,valueUserPwd);

        //     return false;
        // });

        // function checkLogin(userId,userPwd){
        //     $.post("backend/test.php",{userId: userId,userPwd: userPwd},function(res){

        //         if( typeof res != "object"){
        //             try{
        //                 res = JSON.parse(res);
        //             }catch(err){
        //                 alert("未知错误!!!!"+err);
        //                 return "";
        //             }
        //         }

        //         if(res.status == 200){
        //             $showLoginW.css("display","none");
        //             $("#show-user").css("display","block");
        //             $loginWapper.css("display","none");

        //             $("#show-user").find(".user-name").text(res.data.name);
        //         }else{
        //             alert(res.data.reason);
        //         }
        //     });
        // }

        //回到顶部
        $("#control-top").bind({

            mouseover: function(){
                $(this).removeClass("s-top-bac").html("返回顶部");
            },

            mouseout: function(){
                $(this).addClass("s-top-bac").html("");
            },

            click: function(){
                $("html,body").animate({ "scrollTop" : 0 });
            }
        });

        //侧边栏disply:block效果
        $(".u-tab").bind({
            'mouseenter' : function(ev){
                $(ev.target).find('.sub').animate({'opacity' : 1});
            },

            'mouseleave' : function(ev){
                $(ev.target).find('.sub').animate({'opacity' : 0});
            }
        });

    });
})(jQuery);