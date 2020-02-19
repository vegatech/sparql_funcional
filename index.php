<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//ES" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="es" lang="es" class="snorql">
      <head>
      <meta charset="utf-8">
      <style>
    .hidden{
      display:none;
    }
    </style>
    <script>
      window.console = window.console || function(t) {};
      var idioma='hello';
    </script>
    
    <?php
        header('Set-Cookie: cross-site-cookie=name; SameSite=None; Secure');
    ?>
    <script>
      if (document.location.search.match(/type=embed/gi)) {
        window.parent.postMessage("resize", "*");
      }
    </script>
    <title>..::BuscaPedia::.. </title>
    <link rel="icon" type="image/ico" href="images/buscapedia.ico" />
    <link rel="stylesheet" href="css/bootstrap.css" >
    <link rel="stylesheet" type="text/css" href="css/style.css" />
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <!--<script src="js/jquery-3.4.1.slim.min.js"></script>-->
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="js/sparql.js"></script>
    <script src="js/jquery.googleSuggest.js" type="text/javascript"></script>

    <script>
  
//var idioma;
var colscounter =0;
var endpointURL = 'http://dbpedia.org/sparql';
var defaultGraphURI = 'http://dbpedia.org';
var namespaces = {
    'http://www.w3.org/2002/07/owl#': 'owl',
    'http://www.w3.org/2001/XMLSchema#': 'xsd',
    'http://www.w3.org/2000/01/rdf-schema#': 'rdfs',
    'http://www.w3.org/1999/02/22-rdf-syntax-ns#': 'rdf',
    'http://xmlns.com/foaf/0.1/': 'foaf',
    'http://purl.org/dc/elements/1.1/': 'dc',
    'http://dbpedia.org/resource/': '',
    'http://dbpedia.org/property/': 'dbpedia2',
    'http://dbpedia.org/': 'dbpedia',
    'http://www.w3.org/2004/02/skos/core#': 'skos',
    'http://es-la.dbpedia.org/resource/': ''
};

function toPrefixes(namespaces) {
    var result = '';
    for (var uri in namespaces) {
        result += 'PREFIX ' + namespaces[uri] + ': <' + uri + '>\n';
    }
    return result;
}

function betterUnescape(s) {
    // R.V. 10/02/2020 unescape es una funcion deprecadapor lo tanto se usa decode URI component
    //return unescape(s.replace(/\+/g, ' '));

    return decodeURIComponent(s.replace(/\+/g, ' '));
}
function capitalize(text) {
    var i, words, w, result = '';

    words = text.split(' ');

    for (i = 0; i < words.length; i += 1) {
        w = words[i];
        result += w.substr(0,1).toUpperCase() + w.substr(1);
        if (i < words.length - 1) {
            result += ' ';    // Add the spaces back in after splitting
        }
    }

    return result;
}
    
function getComando(consultastr){
        //obtiene la equivalencia del comando asociado  a la palabra
    var comando;
    var result;
    var posicioni = consultastr.indexOf("/")+1;
    var comandosMap = new Map();
        comandosMap.set("albunes", "dbo:artist");
        comandosMap.set("colaboraciones", "dbo:associatedMusicalArtist");
        comandosMap.set("canciones", "dbo:musicalArtist");
        comandosMap.set("produciones", "dbo:producer");
        comandosMap.set("clubes", "dbo:team");
        comandosMap.set("premios", "tres");
        comandosMap.set("premios", "tres");
        comandosMap.set("premios", "tres");
    
        comando = consultastr.substr(posicioni,consultastr.length);
        comando = comando.toLowerCase();
        for (var [clave, valor] of comandosMap) {
            if (clave==comando){
                result = valor;
            }//if
        }//for
    return result;
}

function getvalidaComando(consultastr){
        // solo obtiene si en el campo general de la consultaobtiene un comando escrito
        var posicionf = null;
        if (consultastr.length != -1 ){  
                if (consultastr.indexOf("/")!= -1 ){
                return true;
                }else{
                return false;
                }
        }
}

function getConsulta(consultastr){
        // solo extrae la consulta antes del valor / o comando para enviarlo a la primera consulta
        var cadena;
        if (consultastr.length != -1 ){
		if (consultastr.indexOf("/") != -1 ){
                var posicionf = consultastr.indexOf("/");
               cadena = consultastr.substr(0,posicionf-1);
		}else{
        cadena=consultastr; 
		}
        return cadena;
		}
    }
function getComandoName(strcomando) {
        // solo obtiene el nombre del comando
        var result;
        var comando;
        var comandosMap = new Map();
        var posicioni = strcomando.indexOf("/")+1;
        comandosMap.set("albunes", "albunes");
        comandosMap.set("colaboraciones", "colaboraciones");
        comandosMap.set("canciones", "canciones");
        comandosMap.set("clubes", "clubes");
        comandosMap.set("cargos", "dos");
        comandosMap.set("premios", "tres");
        comando = strcomando.substr(posicioni,strcomando.length);
        comando = comando.toLowerCase();
        
            for (var [clave, valor] of comandosMap) {
            if (clave==comando){
                result = valor;
                }//if
            }//for
    return result;
    }
function start() {


    setEndpointURL(endpointURL, defaultGraphURI);
    //setPrefixesText(toPrefixes(namespaces));
    outputChanged('browse');
    var match = document.location.href.match(/\?(.*)/);
    //console.log("match: "+match);
    if (match) {
        var queryString = match[1];
        //console.log("queryString: "+queryString);
        //var queryString2 =queryString;
        //console.log("queryString2: "+queryString2);
    } else {
        var queryString = '';
        var queryString2 = '';
    }
    if (!queryString) {
        //document.getElementById('querytext').value = 'SELECT * WHERE {\n ?Resultado rdf:type <http://dbpedia.org/ontology/Ship> \n} LIMIT 400';
        document.getElementById('querytext').value = '';
        return;
    }
    var querytext = null;
    if (queryString == 'classes') {
        var resultTitle = 'List of all classes:';
        var query = 'SELECT DISTINCT ?class\n' +
                'WHERE { [] a ?class }\n' +
                'ORDER BY ?class';
    }
    if (queryString == 'properties') {
        var resultTitle = 'List of all properties:';
        var query = 'SELECT DISTINCT ?property\n' +
                'WHERE { [] ?property [] }\n' +
                'ORDER BY ?property';
    }
    var match = queryString.match(/property=([^&]*)/);
    if (match) {
        var resultTitle = 'All uses of property ' + betterUnescape(match[1]) + ':';
        var query = 'SELECT ?resource ?value\n' +
                'WHERE { ?resource <' + betterUnescape(match[1]) + '> ?value }\n' +
                'ORDER BY ?resource ?value';
    }
    var match = queryString.match(/class=([^&]*)/);
    if (match) {
        var resultTitle = 'All instances of class ' + betterUnescape(match[1]) + ':';
        var query = 'SELECT ?instance\n' +
                'WHERE { ?instance a <' + betterUnescape(match[1]) + '> }\n' +
                'ORDER BY ?instance';
    }
    var match = queryString.match(/describe=([^&]*)/);
    if (match) {
        var resultTitle = 'Description of ' + betterUnescape(match[1]) + ':';
        var query = 'SELECT ?property ?hasValue ?isValueOf\n' +
                'WHERE {\n' +
                '  { <' + betterUnescape(match[1]) + '> ?property ?hasValue }\n' +
                '  UNION\n' +
                '  { ?isValueOf ?property <' + betterUnescape(match[1]) + '> }\n' +
                '}';
//                '}\n' +
//                'ORDER BY ?isValueOf ?hasValue ?property';
    }
    if (queryString.match(/query=/)) {
        var resultTitle = 'Datos Generales :';
        querytext = betterUnescape(queryString.match(/query=([^&]*)/)[1]);
        var query = toPrefixes(namespaces) + querytext;
        
    }
    if (queryString.match(/queryb=/)) {
        console.log("entro queryb: ");
        var resultTitle2 = 'Parametros Buscados :';
        querytext2 = betterUnescape(queryString.match(/queryb=([^&]*)/)[1]);
        var query2 = toPrefixes(namespaces) + querytext2;
        var mostrar="activado";
    }else{
        var mostrar=null;
    }
    if (!querytext) {
        console.log("enquery text"+query);
        querytext = query;
        querytext2 =query2;
    }
    document.getElementById('querytext').value = querytext;
    //console.log("este es query2: "+querytext2);
    //document.getElementById('querytext2').value = txtconsulta2.value;//querytext2;
    doQuery(query, function(json) { displayResult(json, resultTitle); });

       // if (codigo.value.indexOf("/") != -1){
      doQuery2(query2, function(json) { displayResult2(json, resultTitle2); });  
    //}

}

var service;
function setEndpointURL(url, defaultGraphURI) {
    //document.title = document.title + ' for ' + url;
    //document.getElementById('title').appendChild(document.createTextNode(' for ' + url));
    document.getElementById('queryform').action = url;
    document.getElementById('defaultgraph').value = defaultGraphURI;
    service = new SPARQL.Service(url);
    service.setMethod('GET');
    if (defaultGraphURI) {
        service.addDefaultGraph(defaultGraphURI);
    }
}

function setPrefixesText(prefixes) {
    document.getElementById('prefixestext').appendChild(
            document.createTextNode(prefixes));
}

function doQuery(sparql, callback) {
    busy = document.createElement('p');
    busy.className = 'busy';
    busy.className ='alert alert-warning';
    busy.appendChild(document.createTextNode('Ejecucando consulta espere por favor ...'));
    setResult(busy);
    service.query(sparql, {
            success: callback,
            failure: onFailure
    });
}
function doQuery2(sparql, callback) {
    busy = document.createElement('p');
    busy.className = 'busy';
    busy.className ='alert alert-warning';
    busy.appendChild(document.createTextNode('Ejecucando consulta espere por favor ...'));
    setResult2(busy);
    service.query(sparql, {
            success: callback,
            failure: onFailure
    });
}

function displayResult(json, resultTitle) {
    var div = document.createElement('div');
    var title = document.createElement('h2');
    title.appendChild(document.createTextNode(resultTitle));
    div.appendChild(title);
    if (json.results.bindings.length == 0) {
        var p = document.createElement('p');
        //var button = document.createElement('button');
        p.className = 'empty';
        p.className ='alert alert-danger';             
        button.className='close';
        p.appendChild(document.createTextNode('[No se encontraron resultados con los valores indicados, por favor corrija los patrones de Busqueda]'));
        div.appendChild(p);
    } else {
        div.appendChild(jsonToHTML(json));
    }
    setResult(div);
}
function displayResult2(json, resultTitle) {
    var div = document.createElement('div');
    var title = document.createElement('h2');
    title.appendChild(document.createTextNode(resultTitle));
    div.appendChild(title);
    if (json.results.bindings.length == 0) {
        var p = document.createElement('p');
        //var button = document.createElement('button');
        p.className = 'empty';
        p.className ='alert alert-danger';             
        button.className='close';
        p.appendChild(document.createTextNode('[No se encontraron resultados con los valores indicados, por favor corrija los patrones de Busqueda]'));
        div.appendChild(p);
    } else {
        div.appendChild(jsonToHTML(json));
    }
    setResult2(div);
}
var xsltInput = null;
function outputChanged(newValue) {
    if (xsltInput == null) {
        xsltInput = document.getElementById('xsltinput');
    }
    var el = document.getElementById('xsltcontainer');
    while (el.childNodes.length > 0) {
        el.removeChild(el.firstChild);
    }
    if (newValue == 'xslt') {
        el.appendChild(xsltInput);
    }
}

function browserBaseURL() {
    return document.location.href.replace(/\?.*/, '');
}

function submitQuery() {
    var output = document.getElementById('selectoutput').value;
    if (output == 'browse') {
        document.getElementById('queryform').action = browserBaseURL();
        document.getElementById('query').value = document.getElementById('querytext').value;
    } else {
        document.getElementById('query').value = toPrefixes(namespaces) + document.getElementById('querytext').value;
    }
    document.getElementById('defaultgraph').disabled = (output == 'browse');
    document.getElementById('jsonoutput').disabled = (output != 'json');
    document.getElementById('stylesheet').disabled = (output != 'xslt' || !document.getElementById('xsltstylesheet').value);
    if (output == 'xslt') {
        document.getElementById('stylesheet').value = document.getElementById('xsltstylesheet').value;
    }
    document.getElementById('queryform').submit();
}

function resetQuery() {
    document.location = browserBaseURL();
}

function onFailure(report) {
    var message = report.responseText.match(/<pre>([\s\S]*)<\/pre>/);
    if (message) {
        var pre = document.createElement('pre');
        pre.innerHTML = message[1];
        setResult(pre);
    } else {
        var div = document.createElement('div');
        div.innerHTML = report.responseText;
        setResult(div);
    }
}

function setResult(node) {
    display(node, 'result');
}
function setResult2(node) {
    display(node, 'result2');
}

function display(node, whereID) {
    var where = document.getElementById(whereID);
    if (!where) {
        alert('ID not found: ' + whereID);
        return;
    }
    while (where.firstChild) {
        where.removeChild(where.firstChild);
    }
    if (node == null) return;
    where.appendChild(node);
}
function jsonToHTML(json) {
    //console.log(json);
    var table = document.createElement('table');
    //table.className = 'queryresults';
    table.className = 'table table-bordered';
    var tr = document.createElement('tr');
    for (var i in json.head.vars) {
        var th = document.createElement('th');
        th.setAttribute('scope','col');
        th.appendChild(document.createTextNode(json.head.vars[i]));
        tr.appendChild(th);
    }
    table.appendChild(tr);
    for (var i in json.results.bindings) {
        var binding = json.results.bindings[i];
        var tr = document.createElement('tr');
        if (i % 2) {
            tr.className = 'odd';
        } else {
            tr.className = 'even';
        }
        for (var v in json.head.vars) {
            td = document.createElement('td');
            //console.log('Entra al td Iterando columnas'+colscounter);
            colscounter++;
            var varName = json.head.vars[v];
            var node = binding[varName];
            if (varName == 'property') {
                td.appendChild(nodeToHTML(node, function(uri) { return '?property=' + escape(uri); }));
            } else if (varName == 'class') {
                td.appendChild(nodeToHTML(node, function(uri) { return '?class=' + escape(uri); }));
            } else {
                td.appendChild(nodeToHTML(node, function(uri) { return '?describe=' + escape(uri); }));
            }
            tr.appendChild(td);
        }
        table.appendChild(tr);
    }
    return table;
}
function jsonToHTML2(json) {
    //console.log(json);
    var div = document.createElement('div');
    //table.className = 'queryresults';
    //style="display:none;"
    div.className = "card";
    div.setAttribute('style','width: 18rem;');
    var tr = document.createElement('div');
    for (var i in json.head.vars) {
        var th = document.createElement('div');
        th.setAttribute("style","display:none;");
        th.appendChild(document.createTextNode(json.head.vars[i]));
        tr.appendChild(th);
    }
    div.appendChild(tr);
    for (var i in json.results.bindings) {
        var binding = json.results.bindings[i];
        var divbd = document.createElement('div');
        divbd.className = "card-body";
        //div.setAttribute('style','width: 18rem;');
        for (var v in json.head.vars) {
            td = document.createElement('td');
            //console.log('Entra al td Iterando columnas'+colscounter);
            colscounter++;
            var varName = json.head.vars[v];
            var node = binding[varName];
            if (varName == 'property') {
                td.appendChild(nodeToHTML(node, function(uri) { return '?property=' + escape(uri); }));
            } else if (varName == 'class') {
                td.appendChild(nodeToHTML(node, function(uri) { return '?class=' + escape(uri); }));
            } else {
                td.appendChild(nodeToHTML(node, function(uri) { return '?describe=' + escape(uri); }));
            }
            divbd.appendChild(td);
        }
        div.appendChild(tr);
    }
    return div;
}

function toQName(uri) {
    for (nsURI in namespaces) {
        if (uri.indexOf(nsURI) == 0) {
            return namespaces[nsURI] + ':' + uri.substring(nsURI.length);
        }
    }
    return null;
}

function toQNameOrURI(uri) {
    for (nsURI in namespaces) {
        if (uri.indexOf(nsURI) == 0) {
            return namespaces[nsURI] + ':' + uri.substring(nsURI.length);
        }
    }
    return '<' + uri + '>';
}

var xsdNamespace = 'http://www.w3.org/2001/XMLSchema#';
var numericXSDTypes = ['long', 'decimal', 'float', 'double', 'int', 'short', 'byte', 'integer',
        'nonPositiveInteger', 'negativeInteger', 'nonNegativeInteger', 'positiveInteger',
        'unsignedLong', 'unsignedInt', 'unsignedShort', 'unsignedByte'];
for (i in numericXSDTypes) {
    numericXSDTypes[i] =  xsdNamespace + numericXSDTypes[i];
}
function nodeToHTML(node, linkMaker) {
    if (!node) {
        var span = document.createElement('span');
        span.className = 'unbound';
        span.title = 'Unbound'
        span.appendChild(document.createTextNode('-'));
        return span;
    }
    if (node.type == 'uri') {
         ext= node.value.indexOf("jpg");
        var span = document.createElement('span');
        span.className = 'uri';
        if (!ext) {
            console.log('ext');
        var qname = toQName(node.value);
        var a = document.createElement('a');
        a.href = linkMaker(node.value);
        a.title = '<' + node.value + '>';
        if (qname) {
            a.appendChild(document.createTextNode(qname));
            span.appendChild(a);
        } else {
            a.appendChild(document.createTextNode(node.value));
            span.appendChild(document.createTextNode('<'));
            span.appendChild(a);
            span.appendChild(document.createTextNode('>'));
        }
        }
        match = node.value.match(/^(https?|ftp|mailto|irc|gopher|news):/);
       //ext= node.value.match(/^(.jpg|.png|.bmp):/);
       
         //var ext = node.split('.').pop();
        if (match) {
            if (ext) {
                ////console.log('ext');
                //console.log(node.value);
                span.appendChild(document.createTextNode(' '));
            var externalLink = document.createElement('a');
            externalLink.href = node.value;
            img = document.createElement('img');
            if (colscounter==1){
               //  console.log('verdadero:'+colscounter);
                img.src = 'link.png';
                colscounter=colscounter+1;
               
            }else{
            img.src = node.value;
               // console.log('verdadero:'+colscounter);
                colscounter=colscounter+1;
                
            }
            img.alt = '[' + match[1] + ']';
            img.title = 'Go to Web page';
            externalLink.appendChild(img);
            span.appendChild(externalLink);
            }else{
           
          
            span.appendChild(document.createTextNode(' '));
            var externalLink = document.createElement('a');
            externalLink.href = node.value;
            img = document.createElement('img');
            img.src = 'link.png';
            img.alt = '[' + match[1] + ']';
            img.title = 'Go to Web page';
            externalLink.appendChild(img);
            span.appendChild(externalLink);
            }
        }
        return span;
    }
    if (node.type == 'bnode') {
        return document.createTextNode('_:' + node.value);
    }
    if (node.type == 'literal') {
        var text = '"' + node.value + '"';
        if (node['xml:lang']) {
            text += '@' + node['xml:lang'];
        }
        return document.createTextNode(text);
    }
    if (node.type == 'typed-literal') {
        var text = '"' + node.value + '"';
        if (node.datatype) {
            text += '^^' + toQNameOrURI(node.datatype);
        }
        for (i in numericXSDTypes) {
            if (numericXSDTypes[i] == node.datatype) {
                var span = document.createElement('span');
                span.title = text;
                span.appendChild(document.createTextNode(node.value));
                return span;
            }
        }
        return document.createTextNode(text);
    }
    return document.createTextNode('???');
}

function addEvent(obj, evType, fn){
    if (obj.addEventListener){
        obj.addEventListener(evType, fn, true);
        return true;
    } else if (obj.attachEvent){
        var r = obj.attachEvent("on"+evType, fn);
        return r;
    } else {
        alert("Handler could not be attached");
    }
}
	function ShowSelected()
    {
    /* Para obtener el valor */
    idioma= document.getElementById("producto").value;
    //alert(cod);
     
    /* Para obtener el texto */
   //// var combo = document.getElementById("producto");
    //var selected = combo.options[combo.selectedIndex].text;
    //alert(selected);
    }
    </script>
</head>

<body   onLoad="start()">
<div class="container-xl">
    <div id="header">
    <h1 id="title"> </h1>
    </div>
                    <!-- Modal Vista -->
                    <div class="modal" id="myModal">
                      <div class="modal-dialog">
                        <div class="modal-content">

                          <!-- Modal Header -->
                              <div class="modal-header">
                                <h4 class="modal-title">Tips de uso...</h4>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                              </div>

                              <!-- Modal body -->
                              <div class="modal-body">
                                <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                                      <ol class="carousel-indicators">
                                        <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
                                        <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
                                        <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
                                      </ol>
                                      <div class="carousel-inner">
                                        <div class="carousel-item active">
                                          <img class="d-block w-100" src="images/logohome.jpg" alt="First slide">
                                        </div>
                                        <div class="carousel-item">
                                          <img class="d-block w-100" src="images/paso1.png" alt="Second slide">
                                        </div>
                                        <div class="carousel-item">
                                          <img class="d-block w-100" src="images/paso2.png" alt="Third slide">
                                        </div>
                                      </div>
                                      <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                        <span class="sr-only">Previous</span>
                                      </a>
                                      <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                        <span class="sr-only">Next</span>
                                      </a>
                                    </div>
                                
                              </div>

                              <!-- Modal footer -->
                              <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-dismiss="modal">Entendido</button>
                              </div>

                            </div>
                          </div>
                    </div>

    <div class="section1">
    

    <pre id="prefixestext"></pre>
        <!-- Imagen de titulo  -->
     
  <a href="http://localhost/sparql_funcional/index.php"> <img  src="images/logoindex1.jpg" ></a>
   
    <nav class="navbar navbar-light" style="background-color: #e3f2fd;">
  <!-- Navbar content -->
    <form id="queryform" action="#" method="get" enctype="multipart/form-data" class="form-inline" accept-charset="UTF-8">
    <fieldset>
    <div id="aqui"></div>
    
    <table style="width:100%">
    <tr>
        <td>
        <a href="http://localhost/sparql_funcional/index.php"><img src="images/buscapedia_32x32_search.png" alt="" width="32" height="32" title="Ir al Home"></a>
        </td> 
        <td>
        <select  id="producto" class="selectpicker" data-width="fit" onchange="ShowSelected();">
            <option  span class="flag-icon flag-icon-es" value="es"></span> Español</option>
            <option span class="flag-icon flag-icon-us" value="en"></span>  English</option>
            <option span class="flag-icon flag-icon-fr" value="fr"></span>  Frances</option>
            <option span class="flag-icon flag-icon-fr" value="cmn"></span>  chino</option>
            
        </select>
        </td> 
        <th><a href="http://localhost/sparql_funcional/index.php"><img src="icons/icons/search.svg" alt="" width="32" height="32" title="Ir al Home"></a></th>
        <th><input  id="codigo"  name="codigo" class="form-control mr-sm-2" type="search" placeholder="Ingrese criterio de busqueda" aria-label="Search" size="100" /> </th>
        <th><button class="btn btn-outline-info my-2 my-sm-0" type="submit" value="Buscar!" onClick="submitQuery()">Buscar</button></th>
    </tr>
  </table>
        <input type="hidden" name="default-graph-uri" value="" id="defaultgraph" disabled="disabled" />
        <input type="hidden" name="output" value="json" id="jsonoutput" disabled="disabled" />
        <input type="hidden" name="stylesheet" value="" id="stylesheet" disabled="disabled" />
        <textarea  name="query" rows="9" id="querytext" style="display:none;" ></textarea>
        <textarea  name="queryb" rows="9" id="querybind" style="display:none;"></textarea>
        </fieldset>
      </form>
    </nav>
      <div>
        <select id="selectoutput" onChange="outputChanged(this.value)" >
          <option selected="selected" value="browse">en Browser</option>
          <option value="json">en JSON</option>
          <option value="xml">an XML</option>
          <option value="xslt">en XML+XSLT</option>
        </select>
        <span id="xsltcontainer"><span id="xsltinput">
          XSLT stylesheet URL:
          <input id="xsltstylesheet" type="text" value="xml-to-html.xsl" size="20" />
        </span></span>
        <input type="button" value="Buscar!" onClick="submitQuery()" style="display:none; />
        <input type="button" value="Limpiar" onClick="resetQuery()" style="display:none; />
      </div>
    </div>
   

    <div class="section">
    <div class="list-group">
      <div id="result" class="table table-striped"><span></span></div>
      <div id="result2" class="table table-striped"><span></span></div>
      </div>
    </div>

    <div id="footer">Empoderado por <a href="http://www.capcenter.com/">CAPCENTER</a> y <a href="http://dbpedia.org/">dbpedia</a></div>
    
    <script id="rendered-js">
$(document).ready(function () {



var myselect = document.getElementById('perfil');
	function ShowSelected()
    {
    /* Para obtener el valor */
    idioma= document.getElementById("producto").value;
    console.log('idioma cuando ingreso a ShowSelected():'+idioma);
    }

  $("#producto").change(function () {
    idioma= $(this).val();
    //console.log('idioma cuando ingreso a procuto# on change:'+idioma);
    //cadena_en_campo();
  });
  //var asesor_de_felicidad = [
  //{ display: "Asesores de felicidad", value: "Asesores de felicidad" }];

  // Aqui creamos verificamos cual opciones apareceran dependiendo de la seleccion@superservicios
  $("#codigo").change(function () {
    var parent = $(this).val();
    var campo =capitalize(decodeURIComponent(codigo.value)); 
    idioma= document.getElementById("producto").value;
    var consulta1;
    var consulta2;
   
   /*//esta busqueda trae valoresparecidos
      //ya no funciona +'\nFilter (REGEX(lcase(?Nombre),"'+campo+'.*'+'")) '
    */
   
   
   if (getvalidaComando(campo)===true){
         console.log("El criterio de busqueda si tiene comando");
    var campo1 =   getConsulta(campo);
    var comando =  getComandoName(campo.toLowerCase());
     var parameter1="\n";
     var criterio="\n";
                                        
    switch (comando) {
  case 'albunes':
    parameter1="?albunes ";
    criterio ='\n OPTIONAL { ?name dct:artist ?albunes } .';
    break;
  case 'colaboraciones':
     parameter1="?colaboraciones ";
    break;
  case 'canciones':
     parameter1="?canciones ";
     criterio ='\n OPTIONAL { ?name dbo:musicalArtist ?canciones } .'
       
    break;
  case 'clubes':
    parameter1="?clubes ";
    criterio ='\n OPTIONAL { ?name dbo:team ?clubes } .'
    break;
  case 'Mango':
    parameter1="?albunes ";
    break;
  case 'Papaya':
    console.log('El kilogramo de Mangos y Papayas cuesta $2.79.');
    break;
  default:
    parameter1=" ";
    console.log('Lo lamentamos, por el momento no disponemos de ' + expr + '.');
}
    console.log("campo1: "+campo1);
    console.log("comando: "+comando);
   }else{
    var campo1 =   getConsulta(campo);
    var parameter1="";
     var criterio="\n";
   console.log("el criterio debusqueda no tiene comando");
    console.log("campo1: "+campo1);
   }
   
    consulta1 ='SELECT DISTINCT ?name ?comentario ?imagen ?nombre '
                                        +'\n WHERE {'
                                        +'\n?name rdfs:label ?nombre .'
                                        +'\n?name rdfs:label "'+campo1+'"@'+idioma+' .'
                                        +'\n?name dbo:thumbnail ?imagen . '
                                        +'\n?name rdfs:comment ?comentario .'
                                        +'\nFILTER (lang(?comentario) = "'+idioma+'")'
                                        +'\nFILTER (lang(?nombre) = "'+idioma+'")'
                                        +'\n} limit 10 ';

     consulta2 ='SELECT DISTINCT '
                                        +'?name '
                                        +'?nombre '
                                        + parameter1
                                        +'\n WHERE {'
                                        +'\n?name rdfs:label ?nombre .'
                                        +'\n?name rdfs:label "'+campo1+'"@'+idioma+' .'
                                      // +'\n OPTIONAL { ?name dct:subject ?subject } .'
                                        +criterio
                                        +'\nFILTER (lang(?nombre) = "'+idioma+'")'
                                        +'\n} limit 101 ';           
                                        
                                        
                                        
            querytext.value=consulta1;
            querybind.value=consulta2;
	
  
  
  });
   $("#txtconsulta2").change(function () {
          querybind.value=txtconsulta2.value;
   });
});
//# sourceURL=pen.js
    </script>
    </div>
<script>
//R.V. 10/02/2020 se agrega esta funcion para crear cookie que solo muestre modal una unica vez
        $(window).on('load',function(){
            console.log('idioma es:'+idioma);
            doOnce();
            function doOnce() {
              if (document.cookie.replace(/(?:(?:^|.*;\s*)doSomethingOnlyOnce\s*\=\s*([^;]*).*$)|^.*$/, "$1") !== "true") {
                //alert("Do something here!");
                document.cookie = "doSomethingOnlyOnce=true; expires=Fri, 31 Dec 9999 23:59:59 GMT";
                $('#myModal').modal('show');
              }
            }

        });           
</script>

<script>
// R.V. esta libreria se crea para sugerir en el campo principal posibles terminos de busqueda
$("#codigo").googleSuggest(); 
</script>
</body>
</html>