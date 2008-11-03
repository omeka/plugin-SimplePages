if (0) {
$('simple-pages-title').observe('keyup', updateSlug);

String.prototype.trim = function() {
    return this.replace(/^\s\s*/, '').replace(/\s\s*$/, ''); 
}

function updateSlug() {
    var title = $('simple-pages-title').value;            
    title = title.replace(/[^a-z0-9\/]/gi,' ');            
    title = title.trim().toLowerCase();
    var slug_words = title.split(" ");
    var slug = slug_words.join("-");
    $('simple-pages-slug').value = slug + '/';
}
}