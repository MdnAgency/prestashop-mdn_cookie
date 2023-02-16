<style>
    :root {
        --mdn-background:  {$colors.bg};
        --cc-bg:  {$colors.bg};
        --mdn-text: {$colors.text};
        --cc-text: {$colors.text};
        --cc-btn-primary-bg: {$colors.text};
        --cc-cookie-category-block-bg: rgba(80, 80, 80, 0.08);
        --cc-cookie-category-block-bg-hover: rgba(80, 80, 80, 0.12);
        --cc-btn-secondary-bg: rgba(80, 80, 80, 0.08);
        --cc-btn-secondary-hover-bg: rgba(80, 80, 80, 0.12);
    }
</style>
{literal}<script type="application/json" id="mdn_cookie_consent_i18n">{/literal}
    {ldelim}
    "modal_trigger_title": "{l s='Cookie settings' mod='mdn_cookie'}",

    "consent_modal_title":  "{l s='We use cookies!' mod='mdn_cookie'}",
    "consent_modal_description":  "{l s='This website uses essential cookies to ensure its proper operation and tracking cookies to understand how you interact with it. The latter will be set only after consent.' mod='mdn_cookie'}",
    "consent_modal_primary_btn":  "{l s='I agree' mod='mdn_cookie'}",
    "consent_modal_secondary_btn_settings":  "{l s='Customize' mod='mdn_cookie'}",
    "consent_modal_secondary_btn_accept_necessary":  "{l s='Accept necessary' mod='mdn_cookie'}",

    "settings_modal_title":  "{l s='Cookie settings' mod='mdn_cookie'}",
    "settings_modal_save_settings_btn":  "{l s='Save settings' mod='mdn_cookie'}",
    "settings_modal_accept_all_btn":  "{l s='Accept all' mod='mdn_cookie'}",
    "settings_modal_reject_all_btn":  "{l s='Accept necessary' mod='mdn_cookie'}",
    "settings_modal_close_btn_label":  "{l s='Close' mod='mdn_cookie'}",

    "settings_modal_before_consent_title":  "{l s='Cookie usage' mod='mdn_cookie'}",
    "settings_modal_before_consent_description":  "{l s='We use cookies to ensure the basic functionalities of the website and to enhance your online experience. You can choose for each category to opt-in/out whenever you want.' mod='mdn_cookie'}",

    "settings_modal_after_consent_title":  "{l s='More information' mod='mdn_cookie'}",
    "settings_modal_after_consent_description":  "{l s='For any queries in relation to my policy on cookies and your choices, please contact us.' mod='mdn_cookie'}",

    "functionality_storage_title":  "{l s='Functionality cookies' mod='mdn_cookie'}",
    "functionality_storage_description":  "{l s='These cookies are necessary for the proper functioning of our website. Without these cookies, the website might not be working properly.' mod='mdn_cookie'}",

    "personalization_storage_title":  "{l s='Personalization cookies' mod='mdn_cookie'}",
    "personalization_storage_description":  "{l s='Personalisation cookies may use third party cookies to help them personalise content and track users across different websites and devices.' mod='mdn_cookie'}",

    "security_storage_title":  "{l s='Security cookies' mod='mdn_cookie'}",
    "security_storage_description":  "{l s='Security cookies allows storage of security-related information, such as authentication, fraud protection, and other means to protect the user.' mod='mdn_cookie'}",

    "ad_storage_title":  "{l s='Ad cookies' mod='mdn_cookie'}",
    "ad_storage_description":  "{l s='Advertising cookies are used by us or our partners to show you relevant content or advertisements both on our site and on third party sites. This enables us to create profiles based on your interests, so-called pseudonymised profiles. Based on this information, it is generally not possible to directly identify you as a person, as only pseudonymised data is used. Unless you express your consent, you will not receive content and advertisements tailored to your interests.' mod='mdn_cookie'}",

    "analytics_storage_title":  "{l s='Analytics cookies' mod='mdn_cookie'}",
    "analytics_storage_description":  "{l s='Analytics cookies allow us to measure the performance of our website and our advertising campaigns. We use them to determine the number of visits and sources of visits to our website. We process the data obtained through these cookies in aggregate, without using identifiers that point to specific users of our website. If you disable the use of analytics cookies in relation to your visit, we lose the ability to analyse performance and optimise our measures.' mod='mdn_cookie'}",

    "cookie_table_col_name":  "{l s='Name' mod='mdn_cookie'}",
    "cookie_table_col_purpose":  "{l s='Description' mod='mdn_cookie'}",
    "cookie_table_col_processing_time":  "{l s='Expiration' mod='mdn_cookie'}",
    "cookie_table_col_provider":  "{l s='Provider' mod='mdn_cookie'}",
    "cookie_table_col_type":  "{l s='Type' mod='mdn_cookie'}",
    "cookie_table_col_link":  "{l s='Link' mod='mdn_cookie'}",
    "cookie_table_col_link_find_out_more":  "{l s='Link' mod='mdn_cookie'}",
    "cookie_table_col_category":  "{l s='Category' mod='mdn_cookie'}",

    "processing_time_session":  "{l s='Session' mod='mdn_cookie'}",
    "processing_time_persistent":  "{l s='Persistent' mod='mdn_cookie'}",

    "cookie_type_1st_party":  "{l s='1st party' mod='mdn_cookie'}",
    "cookie_type_3rd_party":  "{l s='3rd party' mod='mdn_cookie'}",

    "find_out_more":  "{l s='find out more' mod='mdn_cookie'}"
    {rdelim}

    {literal}</script>{/literal}
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
            let ccw = CookieConsentWrapper;
            let locale = "default";
            let i18n = JSON.parse(document.getElementById("mdn_cookie_consent_i18n").textContent);
            ccw.addTranslations(locale, i18n);

            let gtag = function(){dataLayer.push(arguments);}
            let default_consent = {};
            cc_wrapper_config.storage_pool.forEach((s) => {
                default_consent[s.name] = s.enabled_by_default ? "granted" : "denied"
            });
            gtag('consent', 'default', default_consent);
        })();
</script>
{/literal}