<style>
    :root {
        --mdn-background:  {$colors.bg} !important;
        --mdn-primary: {$colors.text} !important;
        --cc-bg:  {$colors.bg} !important;
        --cc-text: {$colors.text} !important;
        --cc-btn-primary-bg: {$colors.button} !important;
    }
</style>
{literal}
<script type="text/javascript">{/literal}
    window.cc_wrapper_config =  {$config nofilter};
{literal}</script>
<script src="/modules/mdn_cookie/views/js/cookie-consent.min.js"></script>
{/literal}

{literal}
<script type="text/javascript">
        // Define dataLayer and the gtag function.
        window.dataLayer = window.dataLayer || [];
        (function () {

            let gtag = function(){dataLayer.push(arguments);}
            let default_consent = {};
            cc_wrapper_config.storage_pool.forEach((s) => {
                default_consent[s.name] = s.enabled_by_default ? "granted" : "denied"
            });
            gtag('consent', 'default', default_consent);
        })();


        window.cookieConsentWrapperEvents.push(['consent:accepted', function (consent) {
            console.log(consent);
        }]);
</script>
{/literal}