'use strict';
jQuery(function($) {

    const dir_name = ppom_file_vars.file_upload_path;

    const wc_product_qty = jQuery('form.cart').find('input[name="quantity"]');

    const enable_pages_qty = 'yes';

    $(document).on('ppom_image_ready', function(e) {

        var image_url = e.image.name;
        var image_id = e.image.id;
        var data_name = e.data_name;
        var input_type = e.input_type;

        const pdf_file_url = dir_name + image_id + '.pdf';

        renderPDF(pdf_file_url, document.getElementById('ppom-pdf-preview-wrapper'));
    });
});



function renderPDF(url, canvasContainer, options) {

    options = options || { scale: 0.2 };

    function renderPage(page) {

        var viewport = page.getViewport(options.scale);
        var wrapper = document.createElement("div");
        wrapper.className = "ppom-pdf-preview-canvas";
        var canvas = document.createElement('canvas');
        var ctx = canvas.getContext('2d');
        var renderContext = {
            canvasContext: ctx,
            viewport: viewport
        };

        canvas.height = viewport.height;
        canvas.width = viewport.width;
        wrapper.appendChild(canvas)
        canvasContainer.appendChild(wrapper);

        page.render(renderContext);
    }

    function renderPages(pdfDoc) {

        const total_pages = pdfDoc.numPages | 0;

        wc_product_qty.val(total_pages);
        ppom_update_option_prices();

        for (var num = 1; num <= pdfDoc.numPages; num++)
            pdfDoc.getPage(num).then(renderPage);
    }

    PDFJS.disableWorker = true;
    PDFJS.getDocument(url).then(renderPages);
}
