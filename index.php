<head>
    <title>Currency Converter</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <!-- Custom CSS -->
    <link href="styling.css" rel="stylesheet">
    <!-- Javascript -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    <style>

        html {
            font-size: 20px;
        }

        .panel {
            background: #333333;
            border: solid white;
        }

        .results {
            font-size: 1em;
            color: #FFFFFF;
        }

        .dropdown {
            margin-bottom: 50px;
        }

        .inline-block {
            display: inline-block;
        }

        .center {
            width: 90%;
            margin: 0 auto 15px;
        }

        .form-group label {
            color: white;
        }

        .button {
            margin-top: 15px;
        }
        label.error {
            color: red;
        }
        input.error {
            border: 1px solid red;
        }

        .results {
            display: none;
        }

        #error-div {
            background: red;
            padding: 10px 15px;
            color: white;
            display: none;
        }

        #loading {
            position: fixed;
            top: 0;
            bottom: 0;
            right: 0;
            left: 0;
            background-color: #fff;
            opacity: .6;
            z-index: 140;
            display: none;
        }
    </style>

</head>

<body>
<div class="container">
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="panel panel-primary text-center">
                <div class="panel-heading">
                    <h4 class="panel-title">Currency Converter</h4>
                </div>
                <div id="error-div" class="error"></div>
                <div class="panel-body">
                    <form class="form-vertical center" id="convert-form">
                        <div class="form-group">
                            <label for="">Enter Value:</label>
                            <input type="number" class="amount form-control" name="amount" placeholder="Enter value" min="1">
                        </div>

                        <div class="row ">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="">From currency:</label>
                                    <select class="currency-list form-control" name="currency_from">
                                        <option value="USD">USD</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>To currency:</label>
                                    <select class="currency-list form-control" name="currency_to">
                                        <option value="EUR">Евро - EUR</option>
                                        <option value="GBP">Фунт стерлингов - GBP</option>
                                        <option value="CAD">Канадский доллар - CAD</option>
                                        <option value="AUD">Австралийский доллар - AUD</option>
                                        <option value="CNY">Китайский юань - CNY</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group button">
                                    <button type="submit" class="btn btn-primary btn-lg">Convert</button>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="results">
                        <p>
                            Currency rate <span class="rate"></span>
                        </p>
                        <p>
                            Total amount <span class="amount"></span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="loading"></div>
<script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.min.js'></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js'></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/jquery.validate.min.js"></script>
<script>
    $(function() {
        $(document).on('ajaxStart',
            function () {
                $('#loading').fadeIn(400);
            }
        ).on('ajaxStop',
            function () {
                $('#loading').fadeOut(400);
            }
        );

        $('#convert-form').validate({
            rules: {
                amount: {
                    required: true,
                }
            },
            messages: {
                amount: {
                    required: 'Amount should not be empty'
                }
            },
            submitHandler: function(form) {
                var $form = $(form);
                var results = $('.results');
                var error_div = $('#error-div');

                error_div.hide();
                results.hide();
                $.ajax({
                    url: 'convert.php',
                    data: $form.serialize(),
                    type: 'POST',
                    dataType: 'json',
                    success: function (res) {
                        $(".rate").html(res.rate);
                        $(".amount").html((Math.round(res.amount * 100)/100).toFixed(2));
                        results.show();
                    },
                    error: function (err) {
                        error_div.html(err.responseJSON.message).show();
                    }
                });
                return false;
            }
        })
    });
</script>
</body>
</html>