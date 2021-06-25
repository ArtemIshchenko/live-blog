$(document).on('click','.popup_selector',function (event) {
    event.preventDefault();
    var updateID = $(this).attr('data-inputid'); // Btn id clicked
    var elfinderUrl = '/live-blog/public/elfinder/popup/';

    // trigger the reveal modal with elfinder inside
    var triggerUrl = elfinderUrl + updateID;
    $.colorbox({
        href: triggerUrl,
        fastIframe: true,
        iframe: true,
        width: '70%',
        height: '80%'
    });

});
// function to update the file selected by elfinder
function processSelectedFile(filePath, requestingField) {
    var item = $('a[data-inputid=' + requestingField + ']').closest('.img-block');console.log(item.find('.feature_image'));
    item.find('.feature_image').attr('value', filePath).trigger('change');
    item.find('.img-uploaded').attr('src', '/live-blog/public/' + filePath).trigger('change');
}
