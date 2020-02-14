
function SetCookie() {
    var url = window.location.search;
    if(url.indexOf('?_vsrefdom=ppc') !== -1){
        document.cookie="hfref=googleAd";
}
}

SetCookie();
