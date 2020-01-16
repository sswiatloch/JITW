function getAltStyleList() {
    var links = document.getElementsByTagName("link");
    var altStyles = [];

    for (const link of links) {
        if (link.rel == "alternate stylesheet") {
            altStyles.push(link);
        }
    }

    return altStyles;
}

function setStyleCookie(name) {
    document.cookie = "style=" + name;
}

function checkStyleCookie() {
    var cookie = document.cookie;
    return cookie.split('=')[1];
}

function loadAltStyleList() {
    var altStyles = getAltStyleList();
    var menu = document.getElementById("menulist");

    for (const style of altStyles) {
        style.disabled = true;
        var li = document.createElement("li");
        var a = document.createElement("a");
        name = style.title;
        a.append(name);
        li.appendChild(a);
        li.onclick = function (e) {
            altStyles = getAltStyleList();
            name = e.target.innerHTML;

            for (const altStyle of altStyles) {


                if (altStyle.title == name) {
                    if (altStyle.disabled == true) {
                        altStyle.disabled = false;
                        setStyleCookie(altStyle.title);
                    }
                    else if (altStyle.disabled == false) {
                        altStyle.disabled = true;
                        setStyleCookie("");
                    }
                } else {
                    altStyle.disabled = true;
                }
            }
        }
        menu.appendChild(li);
    }

    var cookie = checkStyleCookie();

    if (cookie != "") {
        for (const altStyle of altStyles) {
            if (altStyle.title == cookie) {
                altStyle.disabled = false;
            }
        }
    }
}