function reloadchatsEnv() {
    var req = new XMLHttpRequest();

    req.onreadystatechange = function () {
        if (req.readyState === 4 && req.status === 200) {
            document.getElementById("chatsEnv").innerHTML = req.responseText;
        }
    }
    req.open('GET', 'showchatsEnv.php', true);
    req.send();
}

function reloadchatsReq() {
    var req = new XMLHttpRequest();

    req.onreadystatechange = function () {
        if (req.readyState === 4 && req.status === 200) {
            document.getElementById("chatsRec").innerHTML = req.responseText;
        }
    }
    req.open('GET', 'showchatsRec.php', true);
    req.send();
}

setInterval(function() {
    reloadchatsEnv();
    reloadchatsReq();
}, 1000);