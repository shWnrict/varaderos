<?php require_once('config.php'); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Varaderos | Confirmation</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script
        src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Libre+Franklin:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');

        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            border: 1px solid #fff;
            margin: 2%;
            background-color: black;
            color: #fff;
            overflow: height;
            font-family: 'Libre Franklin', sans-serif;
        }


        h1 {
            font-size: 40px;
            margin-bottom: 20px;
            padding: 20px;
            text-transform: uppercase;
            font-weight: 700;

        }

        p {
            margin-bottom: 10px;
        }

        form {
            margin-top: 30px;
        }

        .date-inputs {
            display: flex;
            justify-content: center;
        }

        .date-inputs input {
            width: 100px;
            padding: 10px;
            margin: 20px 5px;
            border: 1px solid #fff;
            border-radius: 5px;
            background-color: transparent;
            color: #fff;
        }

        .btn-primary {
            margin: 2%;
            background-color: #D72C01;
            border-color: #D72C01;
            width: 20%;
            padding: 10px;
        }

        .btn-primary:hover {
            background-color: #ffffff;
            color: #D72C01;
            border-color: #D72C01;
        }

        .btn-primary:focus {
            box-shadow: 0 0 0 0.2rem rgba(38, 143, 255, 0.5);
        }

        .warning {
            font-size: 40px;
            margin: 10%;
            font-weight: 600;
            text-transform: uppercase;
            text-align: center;
        }

        a {
            color: #fff;
            text-decoration: underline;
        }

        @media screen and (max-width: 768px) {
            h1 {
                font-size: 5vw;
            }

            .btn-primary {
                width: 100%;
                margin: 20px 0;
            }

            .warning {
                font-size: 5vw;
            }
        }
    </style>
</head>

<body>
    <div class="container text-center">
        <h1>AGE GATE <br>
            Please input your date of birth below:</h1>
        <form id="birthdateForm">
            <!-- HTML Code -->
            <div class="date-inputs">
                <input type="text" class="datepicker" data-date-format="mm" placeholder="MM">
                <input type="text" class="datepicker" data-date-format="dd" placeholder="DD">
                <input type="text" class="datepicker" data-date-format="yyyy" placeholder="YYYY">
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>

        </form>

        <p>This site uses cookies. <a href="https://legal.brown-forman.com/cookie-policy/english">Cookie Policy</a>. I
            agree to the <a href="https://legal.brown-forman.com/terms-of-use/english">terms of use</a> terms of use and
            the <a href="https://legal.brown-forman.com/privacy-policy/english">privacy policy.</a>
            This
            information will not be used for marketing purposes.</p>
        <p class="warning">drink responsibly</p>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script
        src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
    <script>
        $(document).ready(function () {
            const dateInputs = $('.datepicker');
            dateInputs.datepicker({
                autoclose: true
            });

            dateInputs.on('change', function () {
                const selectedDate = new Date(`${dateInputs[2].value}-${dateInputs[0].value}-${dateInputs[1].value}`);
                if (!isNaN(selectedDate.getTime())) {
                    dateInputs.each(function () {
                        const format = $(this).data('date-format');
                        const value = format === 'mm' ? selectedDate.getMonth() + 1 : format === 'dd' ? selectedDate.getDate() : selectedDate.getFullYear();
                        $(this).val(value.toString().padStart(2, '0'));
                    });
                }
            });

            const currentDate = new Date();
            const birthYear = 2005; // Assuming anyone born in or before 2005 can enter
            const form = document.getElementById('birthdateForm');

            form.addEventListener('submit', (e) => {
                e.preventDefault();
                const monthInput = document.querySelector('.datepicker[data-date-format="mm"]');
                const dayInput = document.querySelector('.datepicker[data-date-format="dd"]');
                const yearInput = document.querySelector('.datepicker[data-date-format="yyyy"]');

                const userDate = new Date(`${yearInput.value}-${monthInput.value}-${dayInput.value}`);
                const age = currentDate.getFullYear() - userDate.getFullYear();

                if (age >= 18) {
                    window.location.href = "HomePage.php";
                } else {
                    alert("You must be 18 years or older to enter this site.");
                }
            });
        });
    </script>
</body>

</html>