<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wyświetlanie PDF</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.6.347/pdf.min.js"></script>
    <style>
        body {
            background-image: url('../img/background3.png');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            overflow: hidden; 
            background-color: rgb(46, 46, 46);
            color: white;
            font-family: arial;
        }

        #pdf-container {
            width: 100%;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: auto; 
            border-bottom: 1px solid rgb(66, 66, 66);
        }

        #pdf-viewer {
            max-width: 100%;
            max-height: 100%;
        }

        .pdf-page {
            page-break-after: always;
        }

        .controls {
            text-align: center;
            margin: 10px 0;
        }

        .controls input[type="number"] {
            width: 60px;
            text-align: center;
            margin: 0 5px;
        }

        .controls button {
            padding: 5px 10px;
            cursor: pointer;
        }
        #zoom-in, #zoom-out{
            border-radius: 25px;
            height: 40px;
            min-width: 40px;
            border: 0px;
            background-color: rgb(235, 235, 235);
            color: rgb(66, 66, 66);
            font-size: 15px;
            cursor: pointer;
            font-family: arial;
            font-weight: 550;
            margin-right: 5px;
            margin-top: 5px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.7);
        }
        #prev-page, #next-page, #go-to-page {
            border-radius: 10px;
            height: 40px;
            min-width: 110px;
            border: 0px;
            background-color: rgb(235, 235, 235);
            color: rgb(66, 66, 66);
            font-size: 15px;
            cursor: pointer;
            font-family: arial;
            font-weight: 550;
            padding-left: 5px;
            margin-right: 5px;
            margin-top: 5px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.7);
        }
        #page-num {
            border-radius: 25px;
            height: 40px;
            width: 60px;
            border: 0px;
            padding: 0px;
            background-color: rgb(235, 235, 235);
            color: rgb(0, 0, 0);
            font-size: 15px;
            font-family: arial;
        }
        #prev-page:hover, #next-page:hover, #go-to-page:hover, #zoom-in:hover, #zoom-out:hover, #page-num:hover {
            background-color: rgb(199, 199, 199);
        }
        #main-page {
            position: absolute;
            border-radius: 50px;
            width: 50px;
            height: 50px;
            background-color: gray;
            top: 20px;
            left: 20px;
            opacity: 0.6;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.7);
            cursor: pointer;
        }
        #main-page:hover {
            background-color: rgb(88, 88, 88);
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.9);
        }
        
    </style>
</head>
<body>
    <a href="../strona.php">
        <div id="main-page">
            <svg width="50" height="50" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                <polyline points="50,30 30,50 50,70" fill="none" stroke="rgb(27, 27, 27)" stroke-width="10" stroke-linecap="round" stroke-linejoin="round"/>
                <line x1="30" y1="50" x2="70" y2="50" stroke="rgb(27, 27, 27)" stroke-width="10" stroke-linecap="round"/>
            </svg>
        </div>
    </a>

    <div id="pdf-container">
        <div id="pdf-viewer"></div>
    </div>
    <div class="controls">
        <button id="prev-page">Poprzednia</button>
        <input type="number" id="page-num" value="1" min="1">
        <span>/ <span id="page-count">...</span></span>
        <button id="next-page">Następna</button>
        <button id="go-to-page">Przejdź</button>
        <div style="display: inline;">
        <button id="zoom-in">+</button>
        <button id="zoom-out">-</button>
    </div></div>
    <script>
        document.addEventListener('contextmenu', event => event.preventDefault());

        const url = '../pdfs/matematyka 2 - zakres podstawowy nowa era.pdf';  // adres URL do pliku PDF
        const pdfViewer = document.getElementById('pdf-viewer');
        const prevPageButton = document.getElementById('prev-page');
        const nextPageButton = document.getElementById('next-page');
        const goToPageButton = document.getElementById('go-to-page');
        const pageNumInput = document.getElementById('page-num');
        const pageCountSpan = document.getElementById('page-count');
        let pdfDoc = null;
        let pageNum = 1;
        let pageRendering = false;
        let pageNumPending = null;
        let scale = 1.0;  // Początkowa skala

        pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.6.347/pdf.worker.min.js';

        function renderPage(num) {
            pageRendering = true;
            pdfDoc.getPage(num).then(function(page) {
                const viewport = page.getViewport({ scale: scale });
                const canvas = document.createElement('canvas');
                const context = canvas.getContext('2d');
                canvas.height = viewport.height;
                canvas.width = viewport.width;
                pdfViewer.innerHTML = '';
                pdfViewer.appendChild(canvas);

                const renderContext = {
                    canvasContext: context,
                    viewport: viewport
                };

                const renderTask = page.render(renderContext);
                renderTask.promise.then(function() {
                    pageRendering = false;
                    if (pageNumPending !== null) {
                        renderPage(pageNumPending);
                        pageNumPending = null;
                    }
                });
            });

            pageNumInput.value = num;
            pageCountSpan.textContent = pdfDoc.numPages;
        }

        function queueRenderPage(num) {
            if (pageRendering) {
                pageNumPending = num;
            } else {
                renderPage(num);
            }
        }

        prevPageButton.addEventListener('click', function() {
            if (pageNum <= 1) {
                return;
            }
            pageNum--;
            queueRenderPage(pageNum);
        });

        nextPageButton.addEventListener('click', function() {
            if (pageNum >= pdfDoc.numPages) {
                return;
            }
            pageNum++;
            queueRenderPage(pageNum);
        });

        goToPageButton.addEventListener('click', function() {
            let num = parseInt(pageNumInput.value, 10);
            if (num >= 1 && num <= pdfDoc.numPages) {
                pageNum = num;
                queueRenderPage(pageNum);
            }
        });

        document.getElementById('zoom-in').addEventListener('click', function() {
            scale += 0.1;
            queueRenderPage(pageNum);
        });

        document.getElementById('zoom-out').addEventListener('click', function() {
            if (scale > 0.1) {
                scale -= 0.1;
                queueRenderPage(pageNum);
            }
        });

        window.addEventListener('resize', function() {
            if (pdfDoc !== null) {
                queueRenderPage(pageNum);
            }
        });

        pdfjsLib.getDocument(url).promise.then(function(pdfDoc_) {
            pdfDoc = pdfDoc_;
            renderPage(pageNum);
        }).catch(function(error) {
            console.error('Error loading PDF:', error);
        });
    </script>
</body>
</html>
