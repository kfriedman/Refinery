<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>NYPL Refinery</title>

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">

    <!-- Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css">

    <!-- Latest compiled and minified JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
</head>
<body>

<div class="container">
    <h1>NYPL Refinery</h1>

    <p>The Refinery is a data platform at NYPL.</p>
    <p>It reads data from Providers, translates and relates the data into standardized NYPL Data Objects (NDOs), and serves the NDOs via RESTful API endpoints.</p>
    <p>Endpoints utilize the <a href="http://jsonapi.org" target="_blank">JSON API v1.0</a> standard.</p>

    <ul>
        <li><a href="#paging">Paging and counting</a></li>
        <li><a href="#filtering">Filtering</a></li>
        <li><a href="#including">Including related objects</a></li>
        <li><a href="#sparse">Sparse fieldsets</a></li>
        <li><a href="#errors">Error reporting</a></li>
        <li><a href="#notes">General notes</a></li>
        <li><a href="#endpoints">Endpoints</a></li>
    </ul>




    <br><br>

    <h2><a name="paging"></a>Paging and counting</h2>

    <h4>Paging strategy</h4>

    <p>A paging-based strategy is used with <code>page[number]</code> and <code>page[size]</code> parameters.</p>

    <div class="panel panel-default">
        <div class="panel-heading">Example Requests</div>
        <div class="panel-body">
            <ul>
                <li><a href="/api/nypl/ndo/v0.1/book-lists/book-list-users?page[size]=2&page[number]=4">/api/nypl/ndo/v0.1/book-lists/book-list-users?page[size]=2&page[number]=4</a></li>
                <li><a href="/api/nypl/ndo/v0.1/book-lists?page[size]=25&page[number]=1">/api/nypl/ndo/v0.1/book-lists?page[size]=25&page[number]=1</a></li>
            </ul>
        </div>
    </div>

    <h4>Counts</h4>

    <p>A total count of primary resource objects (and a <code>page</code> count when paging is applied) is returned in the top-level <code>meta</code> response.</p>

    <div class="panel panel-default">
        <div class="panel-heading">Example Response</div>
        <div class="panel-body">
            <pre>meta: {
    ...
    count: 1415,
    page: {
        size: 25,
        number: 1,
        count: 57
    }
}</pre>
        </div>
    </div>

    <h4>Navigation</h4>

    <p>When paging is applied, links for navigating pages are returned in the top-level <code>links</code> response.</p>

    <div class="panel panel-default">
        <div class="panel-heading">Example Response</div>
        <div class="panel-body">
    <pre>links: {
    self: "https://refinery.nypl.org/api/nypl/ndo/v0.1/book-lists?page[size]=25",
    first: "https://refinery.nypl.org/api/nypl/ndo/v0.1/book-lists?page[size]=25",
    last: "https://refinery.nypl.org/api/nypl/ndo/v0.1/book-lists?page[size]=25&page[number]=57",
    next: "https://refinery.nypl.org/api/nypl/ndo/v0.1/book-lists?page[size]=25&page[number]=2"
}</pre>
        </div>
    </div>





    <br><br>

    <h2><a name="filtering"></a>Filtering</h2>

    <p>To filter a response, the <code>filter</code> parameter is used with the <code>field</code> name and value specified in the parameter.</p>

    <p>Attributes and relationships can be filtered.</p>

    <p><span class="glyphicon glyphicon-info-sign"></span> For more information about <code>fields</code>, consult the JSON API documentation on <a href="http://jsonapi.org/format/#document-resource-object-fields" target="_blank">Fields</a>.</p>

    <div class="panel panel-default">
        <div class="panel-heading">Example Requests</div>
        <div class="panel-body">
            <ul>
                <li><a href="/api/nypl/ndo/v0.1/site-data/collections?filter[name]=Homepage">/api/nypl/ndo/v0.1/site-data/collections?filter[name]=Homepage</a></li>
                <li><a href="/api/nypl/ndo/v0.1/content/alerts?filter[scope]=all">/api/nypl/ndo/v0.1/content/alerts?filter[scope]=all</a></li>
                <li><a href="/api/nypl/ndo/v0.1/site-data/containers?filter[name]=Of%20Note|Learn">/api/nypl/ndo/v0.1/site-data/containers?filter[name]=Of Note|Learn</a></li>
                <li><a href="/api/nypl/ndo/v0.1/site-data/header-items?filter[relationships][parent]=null">/api/nypl/ndo/v0.1/site-data/header-items?filter[relationships][parent]=null</a></li>
            </ul>
        </div>
    </div>

    <h4>Operators</h4>

    <p>Certain operators can be used when filtering a response:</p>

    <ul>
        <li><code>|</code> : To apply an "or" to a request</li>
        <li><code>,</code> : To apply an "and" to a request</li>
        <li><code>%</code> : For a wildcard request</li>
        <li><code>null</code> : For a null value</li>
    </ul>





    <br><br>

    <h2><a name="including"></a>Including related objects</h2>

    <p>To include objects related to the primary <code>data</code>, you can specify an <code>include</code> parameter to customize which related objects should be returned.</p>

    <p>Multiple includes can be specified and separated with a <code>,</code> comma. For a hierarchical include across relationships, a <code>.</code> period should be used to span a relationship.</p>

    <p><span class="glyphicon glyphicon-info-sign"></span> For more information on including related resources, consult the JSON API documentation on <a href="http://jsonapi.org/format/#fetching-includes" target="_blank">Inclusion of Related Resources</a>.</p>

    <div class="panel panel-default">
        <div class="panel-heading">Example Requests</div>
        <div class="panel-body">
            <ul>
                <li><a href="/api/nypl/ndo/v0.1/staff-profiles?include=person,headshot,division">/api/nypl/ndo/v0.1/staff-profiles?include=person,headshot,division</a></li>
                <li><a href="/api/nypl/ndo/v0.1/site-data/collections?include=top-level-containers.slots">/api/nypl/ndo/v0.1/site-data/collections?include=top-level-containers.slots</a></li>
                <li><a href="/api/nypl/ndo/v0.1/staff-picks/staff-pick-lists/monthly-2015-10-01?include=picks.item.tags,picks.age">/api/nypl/ndo/v0.1/staff-picks/staff-pick-lists/monthly-2015-10-01?include=picks.item.tags,picks.age</a></li>
            </ul>
        </div>
    </div>





    <br><br>

    <h2><a name="sparse"></a>Sparse fieldsets</h2>

    <p>To limit specific fields returned in the response, the <code>fields</code> response can be used by specifying the name of the <code>field</code> and it's keys.</p>

    <p>Multiple <code>fields</code> responses can be specified and separated with a <code>,</code> comma.</p>

    <p><span class="glyphicon glyphicon-info-sign"></span> For more information on using sparse fieldsets, consult the JSON API documentation on <a href="http://jsonapi.org/format/#fetching-sparse-fieldsets" target="_blank">Sparse Fieldsets</a>.</p>

    <div class="panel panel-default">
        <div class="panel-heading">Example Requests</div>
        <div class="panel-body">
            <ul>
                <li><a href="/api/nypl/ndo/v0.1/site-data/header-items?fields[header-item]=name">/api/nypl/ndo/v0.1/site-data/header-items?fields[header-item]=name</a></li>
                <li><a href="/api/nypl/ndo/v0.1/staff-picks/staff-pick-lists/monthly-2015-09-01?fields[staff-pick]=text,location&fields[staff-pick-list]=picks&include=picks">/api/nypl/ndo/v0.1/staff-picks/staff-pick-lists/monthly-2015-09-01?fields[staff-pick]=text,location&fields[staff-pick-list]=picks&include=picks</a></li>
            </ul>
        </div>
    </div>





    <br><br>

    <h2><a name="errors"></a>Error Reporting</h2>

    <p>Appropriate HTTP error codes are returned when an error occurs:</p>

    <ul>
        <li><code>400 Bad Request</code>: Indicates a request or parameter specified is not recognized and is generally caused by user-error</li>
        <li><code>404 Not Found</code>: Indicates an endpoint requested was not found and is generally caused by a misspelling or other user-error</li>
        <li><code>500 Internal Server Error</code>: Indicates an unexpected server error occurred and is generally not recoverable</li>
    </ul>

    <p>In addition to the HTTP error code, the error code and message(s) will be returned in the top-level <code>errors</code> response.</p>

    <p><span class="glyphicon glyphicon-info-sign"></span> For more information about error reporting, consult the JSON API documentation on <a href="http://jsonapi.org/format/#errors" target="_blank">Errors</a>.</p>

    <div class="panel panel-default">
        <div class="panel-heading">Example Response</div>
        <div class="panel-body">
            <pre>
errors: [
    {
        title: "Reading NDO (NYPL\Refinery\NDO\StaffPick) raw data failed: Pick requested (staff-pick-list) was not found",
        status: 404,
        detail: "..."
    }
]</pre>
        </div>
    </div>





    <br><br>

    <h2><a name="notes"></a>General notes</h2>

    <ul>
        <li><strong>Primary Keys:</strong> The <code>type</code> and <code>id</code> form a unique identifier throughout all responses.</li>
        <li><strong>Caching:</strong> To maximize performance, responses are cached for up to 10 minutes. Raw data from a Provider may be cached for longer periods.</li>
        <li><strong>Refreshing Data:</strong> To refresh data from a Provider on an endpoint, do a <strong>hard refresh</strong> in a web browser (commonly done by holding the <code>SHIFT</code> key during a browser refresh).</li>
    </ul>





    <br><br>

    <h2><a name="endpoints"></a>Endpoints</h2>

    <div class="alert alert-warning" role="alert">Please note that not all endpoints may be fully functional.</div>

    <table class="table table-striped">
        <?php foreach ($data['url'] as $url) { ?>
            <tr>
                <td><a href="<?php echo $url; ?>"><?php echo $url; ?></a></td>
            </tr>
        <?php } ?>
    </table>

</div>

</body>
</html>
