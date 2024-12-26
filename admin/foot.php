</div>
</div>
</div>
</div>
<script src="//ss.bscstorage.com/wpteam/static/vue@2/vue.min.js?ver=<?php Tomori::GetVer(); ?>"></script>
<script src="//ss.bscstorage.com/wpteam/static/jquery@3/jquery.min.js?ver=<?php Tomori::GetVer(); ?>"></script>
<script src="//ss.bscstorage.com/wpteam/static/jquery-pjax@2/jquery.pjax.min.js?ver=<?php Tomori::GetVer(); ?>"></script>
<script src="//ss.bscstorage.com/wpteam/static/mdui@1/js/mdui.min.js?ver=<?php Tomori::GetVer(); ?>"></script>

<script>
let vueInstance = null;

function initVue() {
    if (vueInstance) {
        vueInstance.$destroy();
    }
    vueInstance = new Vue({
        el: '#app',
        data: {
            title: 'QQ登录中转API',
            subTitle: '<?php echo $title; ?>',
            url: {
                Home: '<?php Tomori::GetSiteHomeUrl(); ?>',
                Admin: '<?php Tomori::GetSiteAdminUrl(); ?>',
                Page: '<?php Tomori::GetSiteUrl(); ?>',
                Gitee: 'https://gitee.com/ShuShuicu/qloginAPI',
                Github: 'https://github.com/ShuShuicu/qloginAPI',
                Bilibili: 'https://space.bilibili.com/435502585',
            }
        }
    });
}

$(document).pjax('a[href^="' + window.location.origin + '"]:not(a[target="_blank"], a[no-pjax])', {
    container: '#app',
    fragment: '#app',
    timeout: 8000
});

$(document).on('pjax:end', function() {
    initVue();
    mdui.mutation();
});

// 初始化页面时也调用一次
initVue();
mdui.mutation();
</script>
</body>

</html>