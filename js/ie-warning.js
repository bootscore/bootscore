if(checkForIE()){renderIeBrowserCheck()};

function checkForIE() {
    if (window.document.documentMode) {
        return true;
    }
    return false;
}

function renderIeBrowserCheck () {
    let div = document.createElement('div');
    div.setAttribute('class' ,'position-fixed top-0 end-0 bottom-0 start-0 d-flex justify-content-center align-items-center');
    div.setAttribute('style' ,'background:white;z-index:1999');
    div.innerHTML =
        '<div style="max-width: 90vw;">' +
            '<h1>' + translation.ie_detected + '</h1>' +
            '<p className="lead">' + translation.limited_functionality + '</p>' +
            '<p className="lead">' + translation.different_browser +
                ' <a href="https://www.mozilla.org/firefox/" target="_blank">Mozilla Firefox</a>, ' +
                '<a href="https://www.google.com/chrome/" target="_blank">Google Chrome</a>, ' +
                '<a href="https://www.opera.com/" target="_blank">Opera</a> ' +
                translation.or + ' <a href="https://www.microsoft.com/edge" target="_blank">Microsoft Edge</a> ' +
                translation.to_display +
            '</p>' +
        '</div>';
    document.body.appendChild(div);
}