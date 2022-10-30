$(document).ready(function() {
    $('#pagination-tabla').pageMe({
        pagerSelector: '#pagination',
        showPrevNext: true,
        hidePageNumbers: false,
        perPage: 3
    });
});