self.addEventListener("fetch",function(e){!e.request.url.match(/wp-admin/)&&e.request.url.match(/preview=true/)});