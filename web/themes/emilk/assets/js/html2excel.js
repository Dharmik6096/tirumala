var exportThisWithParameter = (function () {
    var uri = 'data:application/vnd.ms-excel;base64,',
            template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel"  xmlns="http://www.w3.org/TR/REC-html40"><head> <!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets> <x:ExcelWorksheet><x:Name>{worksheet}</x:Name> <x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions> </x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook> </xml><![endif]--></head><body> <table>{table}</table></body></html>',
            base64 = function (s) {
                return window.btoa(unescape(encodeURIComponent(s)))
            },
            format = function (s, c) {
                return s.replace(/{(\w+)}/g, function (m, p) {
                    return c[p];
                })
            }
    return function (tableID, excelName, is_remove = false) {
        var table_id = tableID;
        $('#' + table_id + ' tr ').css('height', 'auto');
        var htmlData = '';
        var headerHtml = '';
        var $reportHeader = $('.report-header-info:visible').first();
        if ($reportHeader.length > 0) {
            var colspan = $('#' + table_id + ' thead tr:last th').length || $('#' + table_id + ' thead tr:last td').length || 10;
            var cName = $reportHeader.find('.label_heading').text().trim();
            var title = $reportHeader.find('.text-info').text().trim();
            var params = $reportHeader.find('.search-params').text().trim();
            var expUser = $reportHeader.find('.export-username').text().trim();
            var expPrint = $reportHeader.find('.export-printed').text().trim();
            var col1 = colspan > 1 ? colspan - 1 : 1;
            var col2 = 1;
            if (cName || expUser || expPrint) {
                headerHtml += '<tr>';
                headerHtml += '<th colspan="' + col1 + '" rowspan="2" style="text-align:center; vertical-align:middle; font-size:19px; font-weight:bold;">' + (cName ? cName : '') + '</th>';
                headerHtml += '<td colspan="' + col2 + '"><b>' + (expUser ? expUser : '') + '</b></td>';
                headerHtml += '</tr>';
                headerHtml += '<tr>';
                headerHtml += '<td colspan="' + col2 + '"><b>' + (expPrint ? expPrint : '') + '</b></td>';
                headerHtml += '</tr>';
            }
            if (title) {
                headerHtml += '<tr><th colspan="' + colspan + '" style="text-align:center; font-size:16px; font-weight:bold;">' + title + '</th></tr>';
            }
            if (params) {
                headerHtml += '<tr><th colspan="' + colspan + '" style="text-align:center; font-size:14px; font-weight:bold;">' + params + '</th></tr>';
            }
        }
        var theadRows = $('#' + table_id + ' thead ').eq(0).html() || $('.fht-thead table thead').html() || '';
        htmlData = (headerHtml + theadRows + $('#' + table_id + ' tbody ').html()).replace(/ style="[^"]*"/gi, '');
        setTimeout(() => {
            tableID = document.getElementById(tableID)
            var ctx = {worksheet: excelName || 'Worksheet', table: htmlData}
            var link = document.createElement("a");
            link.download = excelName + ".xls";
            link.href = uri + base64(format(template, ctx));
            link.click();
            link.remove();
        }, 200);
    }
})()