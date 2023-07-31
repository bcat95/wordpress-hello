<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script type="text/javascript">
function setRefCookieFromURL() {
    var urlParams = new URLSearchParams(window.location.search);
    var ref = urlParams.get('ref');
    if (ref) {
        document.cookie = "ref=" + ref + "; expires=Fri, 31 Dec 9999 23:59:59 GMT; path=/";
    }
}
setRefCookieFromURL();
</script>

<script type="text/javascript">
function ads_chatgpt_mentor_0615() {
    Swal.fire({
        title: 'Mẹo tạo ảnh AI',
        html: 'Sử dụng cú pháp /img YÊU CẦU để tạo ảnh AI',
        imageUrl: 'https://img.vietnamadvertisement.com/images/2023/06/15/image0f157ad500e4759a.png',
        imageWidth: 400,
        imageAlt: 'Dall-e',
        showCloseButton: true,
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: '<i class="fas fa-store-alt"></i> Thử Ngay',
        cancelButtonText: '<i class="fas fa-time-alt"></i> Đóng',
    }).then((result) => {
        localStorage.setItem('ads_chatgpt_mentor_0615', '1');
        if (result.value) {
            window.open('https://goink.me/vBJ0', '_blank').focus();
        }
    });
}
function ads_chatgpt_mentor() {
    Swal.fire({
        title: 'Trải nghiệm ngay ChatGPT Vietnamese không cần tài khoản',
        html: 'Gặp gỡ, chatvn',
        imageUrl: 'https://img.vietnamadvertisement.com/images/2023/03/16/ScreenShot_20230317094039.png',
        imageWidth: 400,
        imageAlt: 'Custom image',
        showCloseButton: true,
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: '<i class="fas fa-store-alt"></i> Chat Ngay',
        cancelButtonText: '<i class="fas fa-time-alt"></i> Đóng',
    }).then((result) => {
        localStorage.setItem('ads_chatgpt', '1');
        if (result.value) {
            window.open('https://rgl.ink/dgms', '_blank').focus();
        }
    });
}

setTimeout(function() {
    // if (localStorage.getItem('ads_chatgpt_mentor') !== '1') ads_chatgpt_mentor();
    if (localStorage.getItem('ads_chatgpt_mentor_0615') !== '1') ads_chatgpt_mentor_0615();
}, 5000);
</script>

<script type="text/javascript">
if ('serviceWorker' in navigator) {
    navigator.serviceWorker.register('/service-worker.js').then(function(registration) {
        console.log('ServiceWorker registration successful with scope: ', registration.scope);
    }).catch(function(error) {
        console.log('ServiceWorker registration failed: ', error);
    });
} else {
    var aElement = document.createElement('a');
    aElement.href = 'http://www.chromium.org/blink/serviceworker/service-worker-faq';
    aElement.textContent = 'unavailable';
    console.log(' ', aElement);
}
window.addEventListener('beforeinstallprompt', function(e) {
    e.userChoice.then(function(choiceResult) {
        console.log(choiceResult.outcome);
        if(choiceResult.outcome == 'dismissed') {
            console.log('User cancelled home screen install');
        }
        else {
            console.log('User added to home screen');
        }
    });
});
</script>

<script type="text/javascript">
    function bc_event_tracking() {
        $('.btn-send-chat').click(function() {
            gtag('event', 'conversion', {'send_to': 'AW-807155625/p7S7CMLaxpEYEKnv8IAD'});
            gtag('event', 'vn_mentor_chat', { 'event_category': 'Action' });
        });
        $('a.translate-button-header-cta').click(function() {
            gtag('event', 'vn_mentor_header_button', { 'event_category': 'Action' });
        });
        $('.start-chat').click(function() {
            gtag('event', 'vn_mentor_start_chat', { 'event_category': 'Action' });
        });
        $('#microphone-button').click(function() {
            gtag('event', 'vn_mentor_microphone', { 'event_category': 'Action' });
        });
        $('#download-chat,#download-chat-pdf').click(function() {
            gtag('event', 'vn_mentor_download_chat', { 'event_category': 'Action' });
        });
        $('#clear-chat').click(function() {
            gtag('event', 'vn_mentor_clear_chat', { 'event_category': 'Action' });
        });
        $('#close-chat').click(function() {
            gtag('event', 'vn_mentor_close_chat', { 'event_category': 'Action' });
        });
    }
    bc_event_tracking();
</script>
<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-Y2BPK6JX4E"></script>
<script>
window.dataLayer = window.dataLayer || [];
function gtag(){dataLayer.push(arguments);}
gtag('js', new Date());

gtag('config', 'G-Y2BPK6JX4E');
</script>

<script async defer crossorigin="anonymous" src="https://connect.facebook.net/vi_VN/sdk.js#xfbml=1&version=v16.0&appId=143893371929977&autoLogAppEvents=1" nonce="StKVTDEr"></script>
<!-- <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-3682138305573283" crossorigin="anonymous"></script> -->