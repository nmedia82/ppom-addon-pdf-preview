'use strict';
jQuery(function($) {
    console.log('text');
    const dir_name = ppom_file_vars.file_upload_path;

    console.log(ppom_pdfpreview_vars.dir_path);

    // Loaded via <script> tag, create shortcut to access PDF.js exports.
    var pdfjsLib = window['pdfjs-dist/build/pdf'];
    // The workerSrc property shall be specified.
    // pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://mozilla.github.io/pdf.js/build/pdf.worker.js';

    $(document).on('ppom_image_ready', function(e) {

        var image_url = e.image.name;
        var image_id = e.image.id;
        var data_name = e.data_name;
        var input_type = e.input_type;

        const pdf_file_url = dir_name + image_id + '.pdf';

        //
        // If absolute URL from the remote server is provided, configure the CORS
        // header on that server.
        //
        // var url = "choosing-a-pdf-viewer.pdf";
        // var url = "https://nmdevteam.com/pdf-view/choosing-a-pdf-viewer.pdf";

        //
        // The workerSrc property shall be specified.
        //
        // pdfjsLib.GlobalWorkerOptions.workerSrc =
        //   'build/pdf.worker.js';

        //
        // Asynchronous download PDF
        //
        // var loadingTask = pdfjsLib.getDocument(pdf_file_url);
        // loadingTask.promise.then(function(pdf) {

        //     // if (pdf) {
        //     //     pdf.destroy();
        //     // }

        //     var viewer = document.getElementById('pdf-viewer');

        //     for (var counter = 1; counter <= pdf.numPages; counter++) {

        //         // var canvas = document.createElement("canvas");
        //         // // canvas.className = 'pdf-page-canvas';
        //         // canvas.style.display = "block";
        //         // canvas.ID = 'pdf-page-' + counter;
        //         // viewer.appendChild(canvas);

        //         var canvas = document.getElementById('the-canvas-' + counter);

        //         // renderPage(page, canvas);

        //         pdf.getPage(counter).then(function(page) {
        //             var scale = 0.3;
        //             var viewport = page.getViewport({ scale: scale, });

        //             //
        //             // Prepare canvas using PDF page dimensions
        //             //
        //             // var canvas = document.getElementById('the-canvas');
        //             var context = canvas.getContext('2d');


        //             canvas.height = viewport.height;
        //             canvas.width = viewport.width;

        //             //
        //             // Render PDF page into canvas context
        //             //
        //             var renderContext = {
        //                 canvasContext: context,
        //                 viewport: viewport,
        //             };
        //             page.render(renderContext);
        //             // if (context) {
        //             //     context.clearRect(0, 0, canvas.width, canvas.height);
        //             //     context.beginPath();
        //             // }
        //         });

        //     }

        // });

        renderPDF(pdf_file_url, document.getElementById('holder'));

        console.log(pdf_file_url);

    });

});



function renderPDF(url, canvasContainer, options) {

    options = options || { scale: 0.2 };

    function renderPage(page) {
        var viewport = page.getViewport(options.scale);
        var wrapper = document.createElement("div");
        wrapper.className = "canvas-wrapper";
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
        for (var num = 1; num <= pdfDoc.numPages; num++)
            pdfDoc.getPage(num).then(renderPage);
    }

    PDFJS.disableWorker = true;
    PDFJS.getDocument(url).then(renderPages);

    // var loadingTask = pdfjsLib.getDocument(url);
    // loadingTask.promise.then(renderPages)

}
