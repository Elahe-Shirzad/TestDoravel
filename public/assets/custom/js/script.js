jQuery.loadScript = function (url, callback) {
    jQuery.ajax({
        url: url,
        dataType: 'script',
        success: callback,
        async: true
    });
}

$.loadScript('https://sentry.api.dornicafile.ir/js-sdk-loader/37409946195312b9a0b47fbf28aa172d.min.js');

// Sanitize text
function cleanText(text) {
    let a = text
        .replace(/<[^>]*>/g, '')         // Remove HTML tags
        .replace(/&nbsp;/g, ' ')         // Replace non-breaking space entity
        .replace(/&zwnj;/g, ' ')          // Remove half-space HTML entity
        .replace(/\u200c/g, '')          // Remove Unicode half-space (ZWNJ)
        .replace(/\s+/g, ' ')            // Replace multiple spaces with a single space
        .trim();                         // Trim leading and trailing spaces
    return a;
}

// Use mask for national code
function maskNationalCode(code) {
    if (!/^\d{10}$/.test(code)) return code; // fallback if not 10 digits
    return `${code.slice(0, 3)}-${code.slice(3, 9)}-${code.slice(9)}`;
}

// Use Separator for number
function numberFormatter(number, separator = ',') {
    let numStr = number.toString();
    return (numStr.replace(/\B(?=(\d{3})+(?!\d))/g, separator));
}

function setSelectedByValue(items, targetValue) {
    items.forEach(obj => {
        obj.selected = (obj.value === targetValue);
    });
    return items;
}
