<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>NYPL Refinery :: SolrEvent</title>

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">

    <!-- Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css">

    <!-- Latest compiled and minified JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
</head>
<body>
<div class="container">

    <h1>SolrEvent</h1>

    <p>The SolrEvent Provider is a RESTAPI Provider to retrieve raw data from search queries against NYPL' s website Solr core for event data.</p>

    <ul>
        <li><a href="#filter-q">Keyword field</a></li>
        <li><a href="#filter-fq">Field filter query</a></li>
        <li><a href="#filter-sort">Sorting</a></li>
        <li><a href="#filter-start">Start value</a></li>
        <li><a href="#filter-rows">Number of rows</a></li>
        <li><a href="#filter-facet-fields">Facet fields</a></li>
        <li><a href="#including-ndos">Including Events and Facets NDO within the response</a></li>
    </ul>

    <br />

    <h2><a name="filter-q"></a>Keyword field</h2>

    <p>Type: <b>String</b></p>
    <p>A search by query is performed using the <code>filter[q]</code> parameter. It searches title and body fields</p>

    <div class="panel panel-default">
        <div class="panel-heading">Example Requests</div>
        <div class="panel-body">
            <ul>
                <li><a href="/api/nypl/ndo/v0.1/solr-event/search/?filter[q]=*:*">/api/nypl/ndo/v0.1/solr-event/search/?filter[q]=*:*</a></li>
                <li><a href="/api/nypl/ndo/v0.1/solr-event/search/?filter[q]=library_name:*">/api/nypl/ndo/v0.1/solr-event/search/?filter[q]=library_name:*</a></li>
            </ul>
        </div>
    </div>

    <br />

    <h2><a name="filter-fq"></a>Field filter query</h2>

    <p>Type: <b>String</b></p>
    <p>Field query. Allows multiple. The parameter is <code>filter[fq]</code> parameter.</p>

    <div class="panel panel-default">
        <div class="panel-heading">Default value</div>
        <div class="panel-body">
            date_time_start:[NOW-1HOUR]
        </div>
    </div>

    <h4>Search filter fields</h4>

    <p>A search filter can be applied to these fields:</p>

    <ul>
        <li><code>(integer) event_id</code>: Event ID, i.e. <i>8036850</i>.</li>
        <li><code>(string) date_time_start</code>: Start datetime, i.e. <i>2016-07-13T21:30:00Z</i>.</li>
        <li><code>(string) date_time_end</code>: End datetime, i.e. <i>2016-07-12T21:30:00Z</i>.</li>
        <li><code>(string) title</code>: Event title.</li>
        <li><code>(string) body</code>: Body content.</li>
        <li><code>(integer) location_id</code>: Location ID, i.e. <i>56</i>.</li>
        <li><code>(string) zipcode</code> Zip code, i.e. <i>10022-1211</i>.</li>
        <li><code>(string) address</code>: Event address, i.e. <i>127 East 58th Street</i>.</li>
        <li><code>(string) city</code>: City name, i.e. <i>Manhattan</i>.</li>
        <li><code>(string) library_name</code>: Library name, i.e. <i>58th Street Library</i>.</li>
        <li><code>(string) xstreet</code>: Streets between the event address, i.e. <i>between Park &amp; Lexington Aves</i>.</li>
        <li><code>(number) latitude</code>: Location latitude, i.e. <i>40.7619</i>.</li>
        <li><code>(number) longitude</code>: Location longitude, i.e. <i>-73.9691</i>.</li>
        <li><code>(url) site</code>: Site URL, i.e. <i>https://www.nypl.org/</i></li>
        <li><code>(url) uri</code>: Event URI, i.e. <i>https://www.nypl.org/events/programs/2016/07/13/book-discussion-58th-street-library</i>.</li>
        <li><code>(url) registration_uri</code>: Registration URI, i.e. <i>https://dev-www.nypl.org/events/programs/2016/07/13/book-discussion-58th-street-library#register</i>.</li>
        <li><code>(string) event_type</code>: Event type, i.e. <i>Book Discussions/Literary Readings</i>.</li>
        <li><code>(integer) event_type_id</code>: Event type ID, i.e. <i>4313</i>.</li>
        <li><code>(string) event_topic</code>: Event topic, i.e. <i>History/Social Studies</i>.</li>
        <li><code>(integer) event_topic_id</code>: Event topic ID, i.e. <i>4268</i>.</li>
        <li><code>(string) audience</code>: Audience, i.e. <i>Adults</i>.</li>
        <li><code>(integer) audience_id</code>: Audience ID, i.e. <i>4332</i>.</li>
        <li><code>(string) target_audience</code>: Target audience, i.e. <i>Adult</i>.</li>
        <li><code>(0/1) registration_status</code>: Registration status, i.e. <i>0</i>.</li>
        <li><code>(integer) registration_capacity</code>: Registration capacity, i.e. <i>20</i>.</li>
        <li><code>(integer) registration_count</code>: Registration count, i.e. <i>0</i>.</li>
        <li><code>(string) age</code>: Target age, i.e. <i>19-</i>.</li>
    </ul>

    <br />

    <div class="panel panel-default">
        <div class="panel-heading">Example Requests</div>
        <div class="panel-body">
            <ul>
                <li>library_name:"New Amsterdam Library" : <a href='/api/nypl/ndo/v0.1/solr-event/search/?filter[q]=*:*&filter[fq]=library_name%3A"New+Amsterdam+Library"'>/api/nypl/ndo/v0.1/solr-event/search/?filter[q]=*:*&amp;filter[fq]=library_name%3A"New+Amsterdam+Library"</a></li>
                <li>audience:Children : <a href='/api/nypl/ndo/v0.1/solr-event/search/?filter[q]=*:*&filter[fq]=audience%3AChildren'>/api/nypl/ndo/v0.1/solr-event/search/?filter[q]=*:*&amp;filter[fq]=audience%3AChildren</a></li>
                <li>support_audience:"Young Lions" : <a href='/api/nypl/ndo/v0.1/solr-event/search/?filter[q]=*:*&filter[fq]=support_audience%3A%22Young+Lions%22'>/api/nypl/ndo/v0.1/solr-event/search/?filter[q]=*:*&amp;filter[fq]=support_audience%3A%22Young+Lions%22</a></li>
                <li>event_type:"Special Events" : <a href='/api/nypl/ndo/v0.1/solr-event/search/?filter[q]=*:*&filter[fq]=event_type%3A%22Special+Events%22'>/api/nypl/ndo/v0.1/solr-event/search/?filter[q]=*:*&amp;filter[fq]=event_type%3A%22Special+Events%22</a></li>
                <li>event_topic:"Arts & Crafts" : <a href='/api/nypl/ndo/v0.1/solr-event/search/?filter[q]=*:*&filter[fq]=event_topic%3A%22Arts+%26+Crafts%22'>/api/nypl/ndo/v0.1/solr-event/search/?filter[q]=*:*&amp;filter[fq]=event_topic%3A%22Arts+%26+Crafts%22</a></li>
                <li>series:"TechConnect" : <a href='/api/nypl/ndo/v0.1/solr-event/search/?filter[q]=*:*&filter[fq]=series%3ATechConnect'>/api/nypl/ndo/v0.1/solr-event/search/?filter[q]=*:*&amp;filter[fq]=series%3ATechConnect</a></li>
                <li>city:"Manhattan" : <a href='/api/nypl/ndo/v0.1/solr-event/search/?filter[q]=*:*&filter[fq]=city%3AManhattan'>/api/nypl/ndo/v0.1/solr-event/search/?filter[q]=*:*&amp;filter[fq]=city%3AManhattan</a></li>
            </ul>
        </div>
    </div>

    <p><span class="glyphicon glyphicon-info-sign"></span> For more information about <code>Solr queries</code>, consult this brief <a href="http://yonik.com/solr/query-syntax/" target="_blank">Solr query syntax reference</a>.</p>

    <br />

    <h2><a name="filter-sort"></a>Sorting</h2>

    <p>Type: <b>String</b></p>
    <p>Field for sorting. Use multiple times for additional sorting. The parameter is <code>filter[sort]</code>.</p>

    <div class="panel panel-default">
        <div class="panel-heading">Default value</div>
        <div class="panel-body">
            date_time_start asc, library_name asc
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">Example Requests</div>
        <div class="panel-body">
            <ul>
                <li><a href='/api/nypl/ndo/v0.1/solr-event/search/?filter[q]=*:*&filter[sort]=title asc,city desc'>/api/nypl/ndo/v0.1/solr-event/search/?filter[q]=*:*&amp;filter[sort]=title asc,city desc</a></li>
            </ul>
        </div>
    </div>

    <br />

    <h2><a name="filter-start"></a>Start value</h2>

    <p>Type: <b>Integer</b></p>
    <p>Row for beginning of result set. The parameter used to set the start is <code>filter[start]</code>.</p>

    <div class="panel panel-default">
        <div class="panel-heading">Default value</div>
        <div class="panel-body">
            0
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">Example Requests</div>
        <div class="panel-body">
            <ul>
                <li><a href='/api/nypl/ndo/v0.1/solr-event/search/?filter[q]=*:*&filter[start]=10'>/api/nypl/ndo/v0.1/solr-event/search/?filter[q]=*:*&amp;filter[start]=10</a></li>
                <li><a href='/api/nypl/ndo/v0.1/solr-event/search/?filter[q]=*:*&filter[start]=25'>/api/nypl/ndo/v0.1/solr-event/search/?filter[q]=*:*&amp;filter[start]=25</a></li>
            </ul>
        </div>
    </div>

    <br />

    <h2><a name="filter-rows"></a>Number of rows</h2>

    <p>Type: <b>Integer</b></p>
    <p>Number of results to return. Parameter: <code>filter[rows]</code>.</p>

    <div class="panel panel-default">
        <div class="panel-heading">Default value</div>
        <div class="panel-body">
            10
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">Example Requests</div>
        <div class="panel-body">
            <ul>
                <li><a href='/api/nypl/ndo/v0.1/solr-event/search/?filter[q]=*:*&filter[rows]=5'>/api/nypl/ndo/v0.1/solr-event/search/?filter[q]=*:*&amp;filter[rows]=5</a></li>
                <li><a href='/api/nypl/ndo/v0.1/solr-event/search/?filter[q]=*:*&filter[rows]=25'>/api/nypl/ndo/v0.1/solr-event/search/?filter[q]=*:*&amp;filter[rows]=25</a></li>
            </ul>
        </div>
    </div>

    <br />

    <h2><a name="filter-facet-fields"></a>Facet fields</h2>

    <p>Type: <b>String or multiple strings</b></p>

    <p>Field to include in facet response. Use multiple times for each field to return.</p>

    <h4>Facet fields list</h4>

    <ul>
        <li><code>library_name</code></li>
        <li><code>audience</code></li>
        <li><code>support_audience</code></li>
        <li><code>event_type</code></li>
        <li><code>event_topic</code></li>
        <li><code>series</code></li>
        <li><code>city</code></li>
    </ul>

    <div class="panel panel-default">
        <div class="panel-heading">Default value</div>
        <div class="panel-body">
            library_name, audience, support_audience, event_type, event_topic, series, city
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">Example Requests</div>
        <div class="panel-body">
            <ul>
                <li><a href='/api/nypl/ndo/v0.1/solr-event/search/?filter[q]=*:*&filter[facet.field]=library_name'>/api/nypl/ndo/v0.1/solr-event/search/?filter[q]=*:*&amp;filter[facet.field]=library_name</a></li>
                <li><a href='/api/nypl/ndo/v0.1/solr-event/search/?filter[q]=*:*&filter[facet.field]=support_audience,event_type'>/api/nypl/ndo/v0.1/solr-event/search/?filter[q]=*:*&amp;filter[facet.field]=support_audience,event_type</a></li>
            </ul>
        </div>
    </div>

    <br />

    <h2><a name="including-ndos"></a>Including related Events and Facets</h2>

    <p>To include the event and facet objects related to the primary data, you can specify an <code>include</code> parameter to customize which related objects should be returned.</p>
    <p>Multiple includes can be specified and separated with a <code>,</code> comma.</p>
    <p><span class="glyphicon glyphicon-info-sign"></span> For more information on including related resources, consult the JSON API documentation on <a href="http://jsonapi.org/format/#fetching-includes" target="_blank">Inclusion of Related Resources</a>.</p>

    <div class="panel panel-default">
        <div class="panel-heading">Example Requests</div>
        <div class="panel-body">
            <ul>
                <li>Including <code>events</code> only: <a href='/api/nypl/ndo/v0.1/solr-event/search/?filter[q]=library_name%3A"New+Amsterdam+Library"&include=events'>/api/nypl/ndo/v0.1/solr-event/search/?filter[q]=library_name%3A"New+Amsterdam+Library"&amp;include=events</a></li>
                <li>Including <code>facets</code> only: <a href='/api/nypl/ndo/v0.1/solr-event/search/?filter[q]=audience%3AChildren&include=facets'>/api/nypl/ndo/v0.1/solr-event/search/?filter[q]=audience%3AChildren&amp;include=facets</a></li>
                <li>Including <code>events</code> and <code>facets</code>: <a href='/api/nypl/ndo/v0.1/solr-event/search/?filter[q]=*:*&include=facets,events'>/api/nypl/ndo/v0.1/solr-event/search/?filter[q]=*:*&amp;include=facets,events</a></li>
            </ul>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">Example Response including the Event objects</div>
        <div class="panel-body">
            <pre>
data: {
    type: "search",
    id: "5e54f933b2401adce202f40acd731c8a",
    attributes: {},
    relationships: {}
},
meta: {},
jsonapi: {},
links: {},
included: [
    {
        type: "event",
        id: "8350401",
        attributes: {
            uuid: "2368b9f5-cae8-4f71-9ecd-9c06f21c5b5e",
            language: "en",
            title: "Get in the Game: Be a Library All-Star!",
            body-full: " Team up with the Library this summer to learn new skills and explore new ideas while...",
            body-short: null,
            program-image-url: null,
            uri: "https://dev-www.nypl.org/events/programs/2016/07/08/get-game-be-library-all-star-0",
            registration-type: null,
            registration-status: 0,
            registration-uri: "https://dev-www.nypl.org/events/programs/2016/07/08/get-game-be-library-all-star-0#register",
            registration-open: null,
            registration-close: null,
            registration-capacity: 20,
            registration-state: null,
            registration-count: 0,
            start-date: "2016-07-15T11:30:00-04:00",
            end-date: "2016-07-15T11:30:00-04:00",
            date-created: "2016-06-16T15:17:00-04:00",
            date-modified: "2016-06-16T15:22:33-04:00",
            location: {},
            audience: [],
            series: [],
            event-type: {},
            event-topic: {},
            sponsor: "Programs in a Box is generously supported by the Andreas C. Dracopoulos Family... ",
            funding: null,
            cost: null,
            ticket-required: null,
            ticket-url: null,
            ticket-details: null,
            age: [],
            prerequisite: null,
            format: null,
            listening-device: null
        },
        links: {}
    },
    ...
]
}
            </pre>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">Example Response including the Facet objects</div>
        <div class="panel-body">
            <pre>
{
    data: {},
    meta: {},
    jsonapi: {},
    links: {},
    included: [
        {
            type: "search-facet",
            id: "library_name",
            attributes: {
                items: [
                {
                    name: "115th Street Library",
                    count: 26,
                    filter: "library_name:115th Street Library"
                },
                {
                    name: "53rd Street Library",
                    count: 4,
                    filter: "library_name:53rd Street Library"
                },
                {
                    name: "58th Street Library",
                    count: 9,
                    filter: "library_name:58th Street Library"
                },
                ...
            }
        },
        ...
    ]
}
            </pre>
        </div>
    </div>

    <br /><br />

</div>

</body>
</html>
