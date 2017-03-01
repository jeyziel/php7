<!DOCTYPE html>
<html lang="en">
<head>
    <title>PHPOO</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <script type="text/javascript" src="{URL_PATH}/App/Templates/js/jquery.min.js"></script>
    <script type="text/javascript" src="{URL_PATH}/App/Templates/js/bootstrap.min.js"></script>
    <link href="{URL_PATH}/App/Templates/css/bootstrap.min.css" rel="stylesheet">
    <link href="{URL_PATH}/App/Templates/css/styles.css" rel="stylesheet">
</head>

<body>
    <!-- Header -->
    <div id="top-nav" class="navbar navbar-inverse navbar-static-top">
        <div class="container">
            <div class="navbar-header">
                <a class="navbar-brand" target="newwindow" href="http://pablo.blog.br/book">PHP Programando com Orientação a Objetos</a>
            </div>
        </div>
        <!-- /container -->
    </div>
    <!-- /Header -->

    <!-- Main -->
    <div class="container">
        <!-- upper section -->
        <div class="row">
            <div class="col-sm-3">
                <!-- left -->
                <h3><i class="glyphicon glyphicon-briefcase"></i> Menu </h3>
                <hr>

                <ul class="nav nav-stacked">
                    <li><a href="/CidadeList"><i class="glyphicon glyphicon-road"></i> Cidades</a></li>
                    <li><a href="/FabricantesList"><i class="glyphicon glyphicon-folder-close"></i> Fabricantes</a></li>
                    <li><a href="/ProdutosList"><i class="glyphicon glyphicon-tag"></i> Produtos</a></li>
                    <li><a href="/PessoasList"><i class="glyphicon glyphicon-user"></i> Pessoas</a></li>
                    <li><a href="/VendasForm"><i class="glyphicon glyphicon-shopping-cart"></i> Vendas</a></li>
                    <li><a href="/ContasAbertas"><i class="glyphicon glyphicon-book"></i> Pague suas contas</a></li>
                    <li><a href="/VendasReport"><i class="glyphicon glyphicon-book"></i> Rel. Vendas</a></li>
                    <li><a href="/ContasReport"><i class="glyphicon glyphicon-book"></i> Rel. Contas</a></li>
                    <li><a href="/LoginForm/onLogout"><i class="glyphicon glyphicon-log-out"></i> Logout</a></li>
                </ul>
            </div>
            <!-- /span-3 -->
            <div class="col-sm-9">

                <!-- column 2 -->
                <h3><i class="glyphicon glyphicon-edit"></i> {class} <a href="?class=ViewSource&method=onView&source={class}">(Ver código-fonte)</a></h3>

                <hr>

                <div class="row">
                    <!-- center left-->
                    <div class="col-sm-9">
                       {content}
                    </div>
                    <!--/col-->
                </div>
                <!--/row-->
            </div>
            <!--/col-span-9-->

        </div>
        <!--/row-->
        <!-- /upper section -->

    </div>
    <!--/container-->
    <!-- /Main -->
</body>

<script type="text/javascript">
$(function() {
    $(document.body).tooltip({
        selector: "[title]",
        placement: 'right',
        trigger: 'hover',
        cssClass: 'tooltip',
        container: 'body',
        content: function () {
            return $(this).attr("title");
        },
        html: true
    });
});
</script>

<br>
</html>