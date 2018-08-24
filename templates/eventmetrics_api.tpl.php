<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>NYPL Refinery :: EventMetrics</title>

        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">

        <!-- Optional theme -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css">

        <!-- Latest compiled and minified JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    </head>
    <body>
        <div class="container">

            <h1>EventMetrics</h1>

            <p>This refinery endpoint translates the raw node data into a CSV file, generating an Events metrics reporting for the Strategy Office. Reports take month, year and status parameters to generate monthly, statistical reports.</p>

            <ul>
                <li><a href="#filter-date">Filtering by year and month</a></li>
                <li><a href="#filter-output">Output type</a></li>
                <li><a href="#filter-status">Filtering by status</a></li>
                <li><a href="#filter-auth">Authentication by token</a></li>
                <li><a href="#filter-examples">Examples</a></li>
            </ul>

            <br />

            <h2><a name="filter-date"></a>Filtering by year and month</h2>

            <p>Type: <b>Number</b>.</p>

            <p>A search by month is performed using the <code>filter[month]</code> parameter along with the <code>filter[year]</code> parameter, filtering the event metrics regarding to one month only.</p>

            <p>The parameter <code>filter[month]</code> must be a two-digits number within the range <code>01-12</code>, and the parameter <code>filter[year]</code> must be a four-digits number.</p>

            <br />

            <h2><a name="filter-output"></a>Output type</h2>

            <p>Type: <b>String</b>.</p>

            <p>Field to define the output. The parameter is <code>filter[output]</code>, and it can take two values, <code>json</code> or <code>csv</code>.</p>

            <div class="panel panel-default">
                <div class="panel-heading">Default value</div>
                <div class="panel-body">json</div>
            </div>

            <br />

            <h2><a name="filter-status"></a>Filtering by status</h2>

            <p>Type: <b>Integer</b>. Values <code>0 | 1</code></p>

            <p>Field to filter by node status. The parameter is <code>filter[status]</code>.</p>

            <div class="panel panel-default">
                <div class="panel-heading">Default value</div>
                <div class="panel-body">1</div>
            </div>

            <br />

            <h2><a name="filter-auth"></a>Authentication by token</h2>

            <p>Type: <b>Hash</b>.</p>

            <p>Token to authenticate the request. The parameter is <code>filter[auth]</code>.</p>

            <p>This token must be previously defined in the environment configuration path <code>Server.DefaultProviders.EventMetrics.Auth</code>.</p>

            <div class="panel panel-default">
                <div class="panel-heading">Configuration example</div>
                <div class="panel-body">
                    <pre>
local:
  ...
  DefaultProviders:
    ...
    EventMetrics:
      Auth: f59dcf4e38a0ef12d9feb201568d5efb
                    </pre>
                </div>
            </div>

            <br />

            <h2><a name="filter-examples"></a>Examples</h2>

            <div class="panel panel-default">
                <div class="panel-heading">Request getting the report in json NDO format</div>
                <div class="panel-body">
                    <ul>
                        <li><a href="/api/nypl/ndo/v0.1/solr-event/metrics-search/?filter[year]=2016&filter[month]=07&filter[auth]=f59dcf4e38a0ef12d9feb201568d5efb">/api/nypl/ndo/v0.1/solr-event/metrics-search/?filter[year]=2016&filter[month]=07&filter[auth]=f59dcf4e38a0ef12d9feb201568d5efb</a></li>
                    </ul>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">Request getting the report in CSV format</div>
                <div class="panel-body">
                    <ul>
                        <li><code>Status 0</code>: <a href="/api/nypl/ndo/v0.1/solr-event/metrics-search/?filter[year]=2016&filter[output]=csv&filter[status]=0&filter[month]=07&filter[auth]=f59dcf4e38a0ef12d9feb201568d5efb">/api/nypl/ndo/v0.1/solr-event/metrics-search/?filter[year]=2016&filter[output]=csv&filter[status]=0&filter[month]=07&filter[auth]=f59dcf4e38a0ef12d9feb201568d5efb</a></li>
                        <li><code>Status 1</code>: <a href="/api/nypl/ndo/v0.1/solr-event/metrics-search/?filter[year]=2016&filter[output]=csv&filter[status]=1&filter[month]=07&filter[auth]=f59dcf4e38a0ef12d9feb201568d5efb">/api/nypl/ndo/v0.1/solr-event/metrics-search/?filter[year]=2016&filter[output]=csv&filter[status]=1&filter[month]=07&filter[auth]=f59dcf4e38a0ef12d9feb201568d5efb</a></li>
                    </ul>
                </div>
            </div>

        </div>
    </body>
</html>
