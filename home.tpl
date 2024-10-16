<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>[@title]</title>

    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <style>
        @import url("https://fonts.googleapis.com/css?family=Open+Sans:400,700");

        html {
            font-size: 10px;
            -webkit-tap-highlight-color: rgba(0, 0, 0, 0)
        }

        body {
            font-family: "Open Sans", "Helvetica Neue", Helvetica, Arial, sans-serif;
            font-size: 13px;
            line-height: 1.42857143;
            color: #777777;
            background-color: #fcfcfc
        }

        input, button, select, textarea {
            font-family: inherit;
            font-size: inherit;
            line-height: inherit
        }

        a {
            color: #d9230f;
            text-decoration: none
        }

        a:hover, a:focus {
            color: #91170a;
            text-decoration: underline
        }

        a:focus {
            outline: thin dotted;
            outline: 5px auto -webkit-focus-ring-color;
            outline-offset: -2px
        }

        figure {
                     margin: 0
                 }

        img {
            vertical-align: middle
        }

        .panel-heading{
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

    </style>
</head>
<body>

<div class="container">

    <div class="bs-docs-section">
        <div class="row">
            <div class="col-lg-12">
                <div class="page-header">
                    <h1 id="forms">AWOK</h1>
                    <p class="lead">Quick Search</p>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="well bs-component">
                    <form class="form-horizontal">
                        <fieldset>
                            <legend>Data</legend>
                            <div class="form-group">
                                <label for="query" class="col-lg-2 control-label">Query</label>
                                <div class="col-lg-10">
                                    <input type="text" class="form-control" name="query" id="query" placeholder="Search by name, brand and description">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="select" class="col-lg-2 control-label">Sort By</label>
                                <div class="col-lg-10">
                                    <select class="form-control" name="sort" id="sort">
                                        <option value="0">--</option>
                                        <option value="price">Price</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group order_dropdown hidden">
                                <label class="col-lg-2 control-label">Order By</label>
                                <div class="col-lg-10">
                                    <div class="radio">
                                        <label>
                                            <input type="radio" name="order" id="order" value="1">
                                            ASC
                                        </label>
                                        <label>
                                            <input type="radio" name="order" id="order" value="2">
                                            DESC
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-lg-10 col-lg-offset-2">
                                    <button type="button" class="btn btn-primary fetch">Submit</button>
                                </div>
                            </div>
                        </fieldset>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <div class="row results">

    </div>

    <footer>
        <div class="row">
            <div class="col-lg-12">
                <p>AWOK © Copyright 2015 Awok.com, All Rights Reserved</p>
            </div>
        </div>

    </footer>


</div>

<script type="text/javascript">
    $(function() {

        $(".fetch").click(function(){
            var fetch = $(this);
            fetch.text('Loading ..');
            fetch.prop('disabled', true);
            $.getJSON( "index.php", { query: $("#query").val() , sort: $("#sort").val(),order: $("#order:checked").val()} )
                    .done(function( data ) {
                        $(".results").empty();
                        $.each( data, function( key, json ) {
                            $(".results").append('<div class="col-lg-4"> ' +
                            '<div class="panel panel-default"> ' +
                            '<div class="panel-heading">'+json.name+'</div> ' +
                            '<div class="panel-body text-center">' +
                            '<img src="'+json.image_url+'">' +
                            '<p><strong>AED</strong> '+json.price+'</p>' +
                            '</div> ' +
                            '</div></div>').fadeIn();
                        });
                        fetch.prop('disabled', false);
                        fetch.prop('disabled', false);
                        fetch.text('Submit');
                    });
        });

        $("#sort").change(function () {
            if(this.value != 0){
                $(".order_dropdown").removeClass('hidden');
            }else{
                $(".order_dropdown").addClass('hidden');
            }
        });

    });
</script>
</body>
</html>