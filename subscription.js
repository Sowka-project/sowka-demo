document.addEventListener("DOMContentLoaded", function() {
    var subscription = document.getElementById("subscription");
    var subscriptionBox = document.getElementById("subscription-box");
    var cencle = document.getElementById("cencle");
    
    // Funkcja, która pokazuje subscriptionBox
    subscription.addEventListener("click", function(event) {
    subscriptionBox.style.display = "flex";
    setTimeout(function() {
        subscriptionBox.classList.add("show");
    }, 10); // Krótkie opóźnienie, aby animacja zadziałała
    event.stopPropagation(); // Zapobiega propagacji zdarzenia kliknięcia do dokumentu
    });
    
    // Funkcja, która ukrywa subscriptionBox
    function hideSubscriptionBox() {
    subscriptionBox.classList.remove("show");
    setTimeout(function() {
        subscriptionBox.style.display = "none";
    }, 500); // Czas trwania animacji (500ms)
    }
    
    // Ukrywanie subscriptionBox po kliknięciu w cencle
    cencle.addEventListener("click", function(event) {
    hideSubscriptionBox();
    event.stopPropagation(); // Zapobiega propagacji zdarzenia kliknięcia do dokumentu
    });
    
    // Ukrywanie subscriptionBox po kliknięciu poza nim
    document.addEventListener("click", function(event) {
    if (!subscriptionBox.contains(event.target) && !subscription.contains(event.target)) {
        hideSubscriptionBox();
    }
    });
    });
    