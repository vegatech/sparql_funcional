/**@license
This file uses Sparql Suggest for jQuery plugin (licensed under GPLv3) by Haochi Chen (ROMMEL VEGA )
 */
 /*To get list of Companies across the World and autopopulate using SPARQL with DBPedia.*/
var companyListSPARQL = "PREFIX db: <http://dbpedia.org/ontology/> PREFIX property: <http://dbpedia.org/property/> PREFIX position:<http://www.w3.org/2003/01/geo/wgs84_pos#> SELECT DISTINCT ?Company_Name WHERE { ?Company_URI a db:Company .?Company_URI property:name ?Company_Name .}";
var progress=10;
//Preparing SPARQL query against DBPedia
var companyNameQuery = "http://dbpedia.org/sparql?default-graph-uri=http%3A%2F%2Fdbpedia.org&query=" + escape(companyListSPARQL) + "&format=json";
var progress=20;
$.ajax({
    url: companyNameQuery,
    dataType: 'jsonp',
    jsonp: 'callback',
    success: function(data) {
        var companyArr = [];
        for (var i = 0; i < data.results.bindings.length; i++) {
            companyArr.push(data.results.bindings[i].Company_Name.value);
            
        }
        var progress=30;
        $('#tags').autocomplete({
            source: companyArr,
            delay: 30
        });
    },
    error: function(e) {
        alert(e);
    }
});