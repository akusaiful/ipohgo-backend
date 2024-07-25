$(document).on('preview-updated', (e, data) => {
    if(window.lazyLoadInstance){
        lazyLoadInstance.update();
    }
    switch (data.type) {
        case 'list_news':
            const el = $('[id="block-' + data.id+'"]').find('.u-slick');
            window.travel_slick_carousel(el);
            break;
    }
});
