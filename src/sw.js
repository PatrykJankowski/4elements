self.addEventListener("fetch", function(event) {
    //This service worker won't touch the admin area and preview pages
    if (event.request.url.match(/wp-admin/) || event.request.url.match(/preview=true/)) {
        return;
    }
});