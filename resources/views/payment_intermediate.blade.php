    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Обработка платежа...</title>
        <style>
            body {
                font-family: sans-serif;
                display: flex;
                justify-content: center;
                align-items: center;
                min-height: 100vh;
                background-color: #f0f0f0;
            }

            .loader {
                border: 5px solid #f3f3f3; /* Light grey */
                border-top: 5px solid #3498db; /* Blue */
                border-radius: 50%;
                width: 50px;
                height: 50px;
                animation: spin 2s linear infinite;
            }

            @keyframes spin {
                0% { transform: rotate(0deg); }
                100% { transform: rotate(360deg); }
            }
        </style>
        <meta http-equiv="refresh" content="5;url=/payment_success">
    </head>
    <body>

        <div>
            <h1>Обработка платежа, пожалуйста, подождите...</h1>
            <div class="loader"></div>
            <p>Через 5 секунд вы будете автоматически перенаправлены на страницу успеха.</p>
        </div>

    </body>
    </html>
    
