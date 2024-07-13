function toggleFavorite(icon) {
    var dataname = icon.getAttribute('data-name');
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
            if (icon.querySelector('path').style.fill === 'red') {
                icon.querySelector('path').style.fill = 'none';
            } else {
                icon.querySelector('path').style.fill = 'red';
            }
        }
    };
    xhr.send("dataname=" + dataname);
}

document.getElementById('favorites-box').addEventListener('click', function() {
    document.getElementById('favorites-form').submit();
});


