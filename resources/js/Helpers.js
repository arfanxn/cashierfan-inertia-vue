export function tap (variable , callback = null) {
    if (typeof variable == 'function' && callback == null) {
        return variable();
    } else if (typeof variable != 'function' && typeof callback != 'function')  {
        throw new Error('callback must be a function');
    }    

    return callback(variable) ;
}

export function randInt(min = 1 ,max = 9999999) {
     return Math.floor(Math.random() * (max - min + 1) + min)
}

export function randChars(length = 16 ,chars ='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789') {

    let randChars = `` ;

    for ( var i = 0; i < length; i++ ) {
        randChars  += chars.charAt(Math.floor(Math.random() * 
        chars.length));
    }

    return randChars  ;
}


export function imgValidation(filenameOrFilepath){
    var allowedExtensions = /(\.jpg|\.jpeg|\.png|\.gif|\.jfif)$/i;
    if(!allowedExtensions.exec(filenameOrFilepath)){
        console.warn('Please upload file having extensions .jpeg/.jpg/.png/.gif/.jfif only.');
        return false;
    }
    return true ; 
}

export function isset(accessor , fallback = null , report = false){
    try {
        return accessor()
    } catch (err) {
        if (report) console.error(err);

        if (typeof fallback === 'function')  return fallback(accessor) ; 

        return fallback ? fallback : null; 
    }
}

export function setURIQueryString(uri, key, value) {
    var re = new RegExp("([?&])" + key + "=.*?(&|$)", "i");
    var separator = uri.indexOf('?') !== -1 ? "&" : "?";
    if (uri.match(re)) {
        return uri.replace(re, '$1' + key + "=" + value + '$2');
    }
    else {
        return uri + separator + key + "=" + value;
    }
}

export function toIDR (amount) {
    // console.log(amount);
    // amount = amount.replace(/[A-Za-z,.]+/ig , "") ;
    // console.log(amount);

    if (parseFloat(amount) === NaN) 
        amount = 0.00  ;

    return new Intl.NumberFormat('id-ID', {
                        style: 'currency', currency: 'IDR'
                    }).format(amount)
}