import $ from 'jquery';
window.jQuery = $;
window.$ = $;

$('#article_thumbnail').on('change', function () {
    var fileName = $(this).val();
    $(this).next('.custom-file-label').html(fileName);
});
