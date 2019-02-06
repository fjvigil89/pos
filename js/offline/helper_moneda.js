let config_currency_symbol =null
let congig_thousand_separator =null;
let congig_remove_decimals = null;
let congig_decimal_separator = null;

function to_currency_no_money(number, decimals = 2) {
    let ret = accounting.formatNumber(number, decimals, "");

    return ret;
}
async function to_currency(number, decimals = 2) {
    let ret = 0;
    if(config_currency_symbol==null){
    
     config_currency_symbol = await objAppconfig.item('currency_symbol');
     congig_thousand_separator = await objAppconfig.item('thousand_separator');
     congig_remove_decimals = await objAppconfig.item('remove_decimals ');
        congig_decimal_separator = await objAppconfig.item('decimal_separator');
    }
    let currency_symbol = config_currency_symbol == 1 ? config_currency_symbol : '$';
    let thousand_separator = congig_thousand_separator == "," ? congig_thousand_separator : '.';
    let decimal_separator = congig_decimal_separator == "." ? congig_decimal_separator : ',';
    let remove_decimals = congig_remove_decimals==1  ? congig_remove_decimals : 0;
    if (remove_decimals == '1') {
        if (parseFloat(number) >= 0) {
            ret = accounting.formatMoney(number, currency_symbol, 0, thousand_separator, decimal_separator);
        }
        else {
            ret = accounting.formatMoney(Math.abs(number), currency_symbol, 0, thousand_separator, decimal_separator);
        }

        return ret;
    } else {
        if (parseFloat(number) >= 0) {
            ret = accounting.formatMoney(number, currency_symbol, decimals, thousand_separator, decimal_separator);
        }
        else {
            ret = '&#8209;' + accounting.formatMoney(Math.abs(number), currency_symbol, decimals, thousand_separator, decimal_separator);
        }
        return ret;
    }
}
function to_quantity(val, show_not_set = true) {
    if (val != null) {
        return val == parseInt(val) ? parseInt(val) : val.trimEnd('0');
    }

    if (show_not_set) {
        return "No establecido";
    }

    return '';

}
function round_to_nearest_05(numero) {
    let factor = 0.05;
    var numero_r = Math.round(numero / factor) * factor;
    return Number(Math.round(numero_r + 'e' + 2) + 'e-' + 2);

}
function array_sum(array) {
    var key
    var sum = 0
    if (typeof array !== 'object') {
        return null
    }
    for (key in array) {
        if (!isNaN(parseFloat(array[key]))) {
            sum += parseFloat(array[key])
        }
    }
    return sum
}