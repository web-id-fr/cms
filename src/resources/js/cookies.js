//COOKIE IN JS
import Cookies from 'js-cookie';

$(document).ready(function () {
    //RGPD
    let writeAcceptanceCookie = () => {
        Cookies.set('rgpd_validate', 'true', { expires: 365 });
        Cookies.remove('rgpd_first_url');
    };
    let fadeToggleBanner = () => {
        $('.warning_cookies').fadeToggle(200);
    };

    // Keep first visited URL
    if( !Cookies.get('rgpd_first_url') ) {
        Cookies.set('rgpd_first_url', location.href, { expires: 365 });
    }

    // Open banner if user has not validated AND we are on the same page
    if(Cookies.get('rgpd_validate') != 'true' && Cookies.get('rgpd_first_url') == location.href) {
        fadeToggleBanner();
    }
    else {
        writeAcceptanceCookie();
    }

    $('#accepte_cookies').on('click', () => {
        fadeToggleBanner();
        writeAcceptanceCookie();
    });
});
