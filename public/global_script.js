const  requestJs = async (url, method='', formData=null) => {
    const response = await fetch(url, {
    method: method,
    cache:'no-cache',
    body: formData,
    headers: {
        "X-CSRF-TOKEN": "csrf_token()",
    }
   })

   if(response.ok){
    return response.json();
   } else {
    return Promise.reject({
        status:response.status,
        statusText:response.statusText,
        responseText:response.text()
    })
   }
}

const swalNotif = (title, text, type, confirmButtonText = '', cancelButtonText = '') => {
    return Swal.fire({
    title: title,
    text: text,
    icon: type,
    showCancelButton: cancelButtonText !== '',
    confirmButtonText: confirmButtonText,
    cancelButtonText: cancelButtonText,
    })
}

const toastrNotif = (title='', text, type) => {
    switch (type) {
        case 'success':
            return toastr.success(title, text, {timeOut: 2000, progressBar: true, closeButton: true});
            break;
        case 'error':
            return toastr.error(title, text, {timeOut: 2000, progressBar: true, closeButton: true});
            break;
        case 'warning':
            return toastr.warning(title, text, {timeOut: 2000, progressBar: true, closeButton: true});
            break;
        default:
            return toastr.info(title, text, {timeOut: 2000, progressBar: true, closeButton: true});
            break;
    }
}

const rupiahFormat = (angka) => {
    var number_string = angka.toString(),
    sisa  = number_string.length % 3,
    rupiah  = number_string.substr(0, sisa),
    ribuan  = number_string.substr(sisa).match(/\d{3}/g);
        
    if (ribuan) {
        separator = sisa ? '.' : '';
        rupiah += separator + ribuan.join('.');
    }

    return rupiah;
}

function debounce(func, wait, immediate) {
    var timeout;
    return function() {
        var context = this, args = arguments;
        var later = function() {
            timeout = null;
            if (!immediate) func.apply(context, args);
        };
        var callNow = immediate && !timeout;
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
        if (callNow) func.apply(context, args);
    };
};

// const dateFormat = (date = null) => {
//     let d = new Date(date);
//     let month = "" + (d.getMonth() + 1);
//     let day = "" + d.getDate();
//     let year = d.getFullYear();

//     if (month.length < 2) month = "0" + month;
//     if (day.length < 2) day = "0" + day;

//     return [day, month, year].join("/");
// }